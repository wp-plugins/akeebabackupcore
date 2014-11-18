<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 *
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 *
 */

namespace Akeeba\Engine\Postproc;

// Protection against direct access
defined('AKEEBAENGINE') or die();

use Psr\Log\LogLevel;
use Akeeba\Engine\Factory;
use Akeeba\Engine\Postproc\Connector\Amazons3 as ConnectorAmazons3;

class S3 extends Base
{
	public $cache = null;

	public function __construct()
	{
		$this->can_delete = true;
		$this->can_download_to_browser = true;
		$this->can_download_to_file = true;
	}

	public function processPart($absolute_filename, $upload_as = null)
	{
		// Retrieve engine configuration data
		$config = Factory::getConfiguration();

		$accesskey = trim($config->get('engine.postproc.s3.accesskey', ''));
		$secret = trim($config->get('engine.postproc.s3.secretkey', ''));
		$usessl = $config->get('engine.postproc.s3.usessl', 0) == 0 ? false : true;
		$bucket = $config->get('engine.postproc.s3.bucket', '');
		$legacy = $config->get('engine.postproc.s3.legacy', 0);
		$directory = $config->get('volatile.postproc.directory', null);
		$lowercase = $config->get('engine.postproc.s3.lowercase', 1);
		$rrs = $config->get('engine.postproc.s3.rrs', 1);
		$endpoint = $config->get('engine.postproc.s3.customendpoint', '');

		if (empty($directory))
		{
			$directory = $config->get('engine.postproc.s3.directory', 0);
		}

		if (!empty($directory))
		{
			$directory = str_replace('\\', '/', $directory);
			$directory = rtrim($directory, '/');
		}

		$endpoint = trim($endpoint);

		if (!empty($endpoint))
		{
			$protoPos = strpos($endpoint, ':\\');

			if ($protoPos !== false)
			{
				$endpoint = substr($endpoint, $protoPos + 3);
			}

			$slashPos = strpos($endpoint, '/');

			if ($slashPos !== false)
			{
				$endpoint = substr($endpoint, $slashPos + 1);
			}
		}

		// Sanity checks
		if (empty($accesskey))
		{
			$this->setError('You have not set up your Amazon S3 Access Key');

			return false;
		}

		if (empty($secret))
		{
			$this->setError('You have not set up your Amazon S3 Secret Key');

			return false;
		}

        if(!function_exists('curl_init'))
        {
            $this->setWarning('cURL is not enabled, please enable it in order to post-process your archives');

            return false;
        }

		if (empty($bucket))
		{
			$this->setError('You have not set up your Amazon S3 Bucket');

			return false;
		}
		else
		{
			// Remove any slashes from the bucket
			$bucket = str_replace('/', '', $bucket);
			if ($lowercase)
			{
				$bucket = strtolower($bucket);
			}
		}

		// Create an S3 instance with the required credentials
		$s3 = ConnectorAmazons3::getInstance($accesskey, $secret, $usessl);

		if (!empty($endpoint))
		{
			$s3->defaultHost = $endpoint;
		}

		// Do not use multipart uploads when in an immediate post-processing step,
		// i.e. we are uploading a part right after its creation
		$immediateEnabled = $config->get('engine.postproc.common.after_part', 0);
		if ($immediateEnabled)
		{
			$noMultipart = true;
		}
		else
		{
			$noMultipart = false;
		}

		// Disable multipart uploads if the user requested it
		if ($legacy)
		{
			$noMultipart = true;
		}

		// Are we already processing a multipart upload?
		if (!empty($this->cache))
		{
			// Continue processing an existing file and return
			$filename = $this->cache->filename;
			$absolute_filename = $this->cache->absolute_filename;
			$partNumber = $this->cache->partnumber;
			$uploadID = $this->cache->uploadid;
			$etags = $this->cache->etags;

			$partNumber++;

			Factory::getLog()->log(LogLevel::DEBUG, "S3 -- Uploading part $partNumber of $uploadID");

			// DEBUG
			/**
			 * $flatetags = implode(', ',$etags);
			 * Factory::getLog()->log(LogLevel::DEBUG,"S3 -- Absolute/relative filenames: $absolute_filename ### $filename");
			 * Factory::getLog()->log(LogLevel::DEBUG,"S3 -- Etags: ".$flatetags);
			 * Factory::getLog()->log(LogLevel::DEBUG,"S3 -- Serialized cache: ".serialize($this->cache));
			 * /**/

			$fileInfo = ConnectorAmazons3::inputFile($absolute_filename, false);
			$fileInfo['UploadID'] = $uploadID;
			$fileInfo['etags'] = $etags;
			$fileInfo['PartNumber'] = $partNumber;
			$input = $fileInfo; // So that it doesn't get overwritten
			$etag = ConnectorAmazons3::uploadMultipart($input, $bucket, $filename);

			if ($etag === 0)
			{
				// Done uploading, finalize
				$this->cache = null;
				Factory::getLog()->log(LogLevel::DEBUG, "S3 -- Finalizing multipart upload of " . basename($absolute_filename) . " (part #$partNumber)");
				$result = ConnectorAmazons3::finalizeMultipart($fileInfo, $bucket, $filename);
				$this->propagateFromObject($s3);

				if ($result === false)
				{
					// Finalization failed
					return false;
				}
				else
				{
					// Finalization successful
					return true;
				}
			}
			elseif ($etag === false)
			{
				// Upload failed
				$this->propagateFromObject($s3);

				return false;
			}
			else
			{
				// Successfully uploaded part
				$this->propagateFromObject($s3);
				$fileInfo['etags'][] = $etag;
				// Update stored values
				$this->cache->partnumber = $partNumber;
				$this->cache->etags = $fileInfo['etags'];
				$this->cache->uploadid = $fileInfo['UploadID'];

				// Return -1 so that we get called again
				return -1;
			}
		}

		// If we are here, we'll have to start uploading the file. Let's prepare ourselves for that.
		// Fix the directory name, if required
		if (!empty($directory))
		{
			$directory = trim($directory);
			$directory = ltrim(Factory::getFilesystemTools()->TranslateWinPath($directory), '/');
		}
		else
		{
			$directory = '';
		}

		// Parse tags
		$directory = Factory::getFilesystemTools()->replace_archive_name_variables($directory);
		$config->set('volatile.postproc.directory', $directory);

		// Calculate relative remote filename
		$filename = empty($upload_as) ? basename($absolute_filename) : $upload_as;
		if (!empty($directory) && ($directory != '/'))
		{
			$filename = $directory . '/' . $filename;
		}

		// Store the absolute remote path in the class property
		$this->remote_path = $filename;

		// Do we have to upload in one go or do a multipart upload instead?
		$filesize = @filesize($absolute_filename);
		if (($filesize > 5242880) && !$noMultipart)
		{
			Factory::getLog()->log(LogLevel::DEBUG, "S3 -- Starting multipart upload of " . basename($absolute_filename));
			// Start multipart processing for files over 5Mb
			$fileInfo = ConnectorAmazons3::inputFile($absolute_filename, false);
			$input = $fileInfo; // Required to avoid the original array being tampered with
			$uploadID = ConnectorAmazons3::startMultipart(
				$input,
				$bucket,
				$filename,
				ConnectorAmazons3::ACL_BUCKET_OWNER_FULL_CONTROL, // ACL (bucket owner has full control, file owner gets full control)
				array(), // Meta headers
				// Other request headers
				array(
					// Amazon storage class (support for RRS - Reduced Redundancy Storage)
					'x-amz-storage-class' => $rrs ? 'REDUCED_REDUNDANCY' : 'STANDARD'
				)
			);

			// Necessary warnings propagation, no matter the outcome
			$this->propagateFromObject($s3);

			if ($uploadID === false)
			{
				// We couldn't start the upload. Bail out at once!
				return false;
			}
			else
			{
				// Save the information we need for multipart uploading
				$fileInfo['UploadID'] = $uploadID;
				$fileInfo['etags'] = array();

				$cache = array(
					'absolute_filename' => $absolute_filename,
					'filename'          => $filename,
					'partnumber'        => 0,
					'uploadid'          => $uploadID,
					'etags'             => array()
				);

				$this->cache = (object)$cache;

				// Return -1 so that we get called again, even on the same step
				return -1;
			}
		}
		else
		{
			Factory::getLog()->log(LogLevel::DEBUG, "S3 -- Legacy (single part) upload of " . basename($absolute_filename));
			// Legacy single part uploads
			$result = $s3->putObject(
				ConnectorAmazons3::inputFile($absolute_filename, false), // File to read from
				$bucket, // Bucket name
				$filename, // Remote relative filename, including directory
				ConnectorAmazons3::ACL_BUCKET_OWNER_FULL_CONTROL, // ACL (bucket owner has full control, file owner gets full control)
				array(), // Meta headers
				// Other request headers
				array(
					// Amazon storage class (support for RRS - Reduced Redundancy Storage)
					'x-amz-storage-class' => $rrs ? 'REDUCED_REDUNDANCY' : 'STANDARD'
				)
			);

			// Return the result
			$this->propagateFromObject($s3);

			return $result;
		}
	}

	/**
	 * Implements object deletion
	 */
	public function delete($path)
	{
		// Retrieve engine configuration data
		$config = Factory::getConfiguration();

		$accesskey = trim($config->get('engine.postproc.s3.accesskey', ''));
		$secret = trim($config->get('engine.postproc.s3.secretkey', ''));
		$usessl = $config->get('engine.postproc.s3.usessl', 0) == 0 ? false : true;
		$bucket = $config->get('engine.postproc.s3.bucket', '');
		$lowercase = $config->get('engine.postproc.s3.lowercase', 1);
		$endpoint = $config->get('engine.postproc.s3.customendpoint', '');

		$endpoint = trim($endpoint);

		if (!empty($endpoint))
		{
			$protoPos = strpos($endpoint, ':\\');

			if ($protoPos !== false)
			{
				$endpoint = substr($endpoint, $protoPos + 3);
			}

			$slashPos = strpos($endpoint, '/');

			if ($slashPos !== false)
			{
				$endpoint = substr($endpoint, $slashPos + 1);
			}
		}

		// Sanity checks
		if (empty($accesskey))
		{
			$this->setError('You have not set up your Amazon S3 Access Key');

			return false;
		}

		if (empty($secret))
		{
			$this->setError('You have not set up your Amazon S3 Secret Key');

			return false;
		}

		if (empty($bucket))
		{
			$this->setError('You have not set up your Amazon S3 Bucket');

			return false;
		}
		else
		{
			// Remove any slashes from the bucket
			$bucket = str_replace('/', '', $bucket);
			if ($lowercase)
			{
				$bucket = strtolower($bucket);
			}
		}

		// Create an S3 instance with the required credentials
		$s3 = ConnectorAmazons3::getInstance($accesskey, $secret, $usessl);

		if (!empty($endpoint))
		{
			$s3->defaultHost = $endpoint;
		}

		// Delete the file
		$result = $s3->deleteObject($bucket, $path);

		// Return the result
		$this->propagateFromObject($s3);

		return $result;
	}

	public function downloadToFile($remotePath, $localFile, $fromOffset = null, $length = null)
	{
		// Retrieve engine configuration data
		$config = Factory::getConfiguration();

		$accesskey = trim($config->get('engine.postproc.s3.accesskey', ''));
		$secret = trim($config->get('engine.postproc.s3.secretkey', ''));
		$usessl = $config->get('engine.postproc.s3.usessl', 0) == 0 ? false : true;
		$bucket = $config->get('engine.postproc.s3.bucket', '');
		$lowercase = $config->get('engine.postproc.s3.lowercase', 1);
		$rrs = $config->get('engine.postproc.s3.rrs', 1);
		$endpoint = $config->get('engine.postproc.s3.customendpoint', '');

		$endpoint = trim($endpoint);

		if (!empty($endpoint))
		{
			$protoPos = strpos($endpoint, ':\\');

			if ($protoPos !== false)
			{
				$endpoint = substr($endpoint, $protoPos + 3);
			}

			$slashPos = strpos($endpoint, '/');

			if ($slashPos !== false)
			{
				$endpoint = substr($endpoint, $slashPos + 1);
			}
		}

		// Sanity checks
		if (empty($accesskey))
		{
			$this->setError('You have not set up your Amazon S3 Access Key');

			return false;
		}

		if (empty($secret))
		{
			$this->setError('You have not set up your Amazon S3 Secret Key');

			return false;
		}

		if (empty($bucket))
		{
			$this->setError('You have not set up your Amazon S3 Bucket');

			return false;
		}
		else
		{
			// Remove any slashes from the bucket
			$bucket = str_replace('/', '', $bucket);
			if ($lowercase)
			{
				$bucket = strtolower($bucket);
			}
		}

		// Create an S3 instance with the required credentials
		$s3 = ConnectorAmazons3::getInstance($accesskey, $secret, $usessl);

		if (!empty($endpoint))
		{
			$s3->defaultHost = $endpoint;
		}

		if ($fromOffset && $length)
		{
			$toOffset = $fromOffset + $length - 1;
		}
		else
		{
			$toOffset = null;
		}
		$result = $s3->getObject($bucket, $remotePath, $localFile, $fromOffset, $toOffset);

		// Return the result
		$this->propagateFromObject($s3);

		return $result;
	}

	public function downloadToBrowser($remotePath)
	{
		// Retrieve engine configuration data
		$config = Factory::getConfiguration();

		$accesskey = trim($config->get('engine.postproc.s3.accesskey', ''));
		$secret = trim($config->get('engine.postproc.s3.secretkey', ''));
		$usessl = $config->get('engine.postproc.s3.usessl', 0) == 0 ? false : true;
		$bucket = $config->get('engine.postproc.s3.bucket', '');
		$lowercase = $config->get('engine.postproc.s3.lowercase', 1);
		$rrs = $config->get('engine.postproc.s3.rrs', 1);
		$endpoint = $config->get('engine.postproc.s3.customendpoint', '');

		$endpoint = trim($endpoint);

		if (!empty($endpoint))
		{
			$protoPos = strpos($endpoint, ':\\');

			if ($protoPos !== false)
			{
				$endpoint = substr($endpoint, $protoPos + 3);
			}

			$slashPos = strpos($endpoint, '/');

			if ($slashPos !== false)
			{
				$endpoint = substr($endpoint, $slashPos + 1);
			}
		}

		// Sanity checks
		if (empty($accesskey))
		{
			$this->setError('You have not set up your Amazon S3 Access Key');

			return false;
		}

		if (empty($secret))
		{
			$this->setError('You have not set up your Amazon S3 Secret Key');

			return false;
		}

		if (empty($bucket))
		{
			$this->setError('You have not set up your Amazon S3 Bucket');

			return false;
		}
		else
		{
			// Remove any slashes from the bucket
			$bucket = str_replace('/', '', $bucket);
			if ($lowercase)
			{
				$bucket = strtolower($bucket);
			}
		}

		// Create an S3 instance with the required credentials
		$s3 = ConnectorAmazons3::getInstance($accesskey, $secret, $usessl);

		if (!empty($endpoint))
		{
			$s3->defaultHost = $endpoint;
		}

		$expires = time() + 10; // Should be plenty of time for a simple redirection!
		$stringToSign = "GET\n\n\n$expires\n/$bucket/$remotePath";
		$signature = ConnectorAmazons3::__getHash($stringToSign);

		$url = $usessl ? 'https://' : 'http://';
		$url .= "$bucket.s3.amazonaws.com/$remotePath?AWSAccessKeyId=" . urlencode($accesskey) . "&Expires=$expires&Signature=" . urlencode($signature);

		return $url;
	}
}