<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\Model;

use Awf\Date\Date;
use Awf\Mvc\Model;
use Awf\Text\Text;

// JSON API version number
define('AKEEBA_JSON_API_VERSION', '330');

/*
 * Short API version history:
 * 300	First draft. Basic backup working. Encryption semi-broken.
 * 316	Fixed download feature.
 * 320  Minor bug fixes
 * 330  Introduction of Akeeba Solo
 */

// Force load the AEUtilEncrypt class if it's Akeeba Backup Professional
if (AKEEBA_PRO == 1)
{
	$dummy = new \AEUtilEncrypt;
}

if (!defined('AKEEBA_BACKUP_ORIGIN'))
{
	define('AKEEBA_BACKUP_ORIGIN', 'json');
}

class Json extends Model
{
	const    STATUS_OK = 200; // Normal reply
	const    STATUS_NOT_AUTH = 401; // Invalid credentials
	const    STATUS_NOT_ALLOWED = 403; // Not enough privileges
	const    STATUS_NOT_FOUND = 404; // Requested resource not found
	const    STATUS_INVALID_METHOD = 405; // Unknown JSON method
	const    STATUS_ERROR = 500; // An error occurred
	const    STATUS_NOT_IMPLEMENTED = 501; // Not implemented feature
	const    STATUS_NOT_AVAILABLE = 503; // Remote service not activated

	const    ENCAPSULATION_RAW = 1; // Data in plain-text JSON
	const    ENCAPSULATION_AESCTR128 = 2; // Data in AES-128 stream (CTR) mode encrypted JSON
	const    ENCAPSULATION_AESCTR256 = 3; // Data in AES-256 stream (CTR) mode encrypted JSON
	const    ENCAPSULATION_AESCBC128 = 4; // Data in AES-128 standard (CBC) mode encrypted JSON
	const    ENCAPSULATION_AESCBC256 = 5; // Data in AES-256 standard (CBC) mode encrypted JSON

	private $json_errors = array(
		'JSON_ERROR_NONE'      => 'No error has occurred (probably empty data passed)',
		'JSON_ERROR_DEPTH'     => 'The maximum stack depth has been exceeded',
		'JSON_ERROR_CTRL_CHAR' => 'Control character error, possibly incorrectly encoded',
		'JSON_ERROR_SYNTAX'    => 'Syntax error'
	);

	/** @var int The status code */
	private $status = 200;

	/** @var int Data encapsulation format */
	private $encapsulation = 1;

	/** @var mixed Any data to be returned to the caller */
	private $data = '';

	/** @var string A password passed to us by the caller */
	private $password = null;

	/** @var string The method called by the client */
	private $method_name = null;

	public function execute($json)
	{
		// Check if we're activated
		$enabled = \AEPlatform::getInstance()->get_platform_configuration_option('frontend_enable', 0);

		if (!$enabled)
		{
			$this->data = 'Access denied';
			$this->status = self::STATUS_NOT_AVAILABLE;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return $this->getResponse();
		}

		// Try to JSON-decode the request's input first
		$request = @json_decode($json, false);

		if (is_null($request))
		{
			// Could not decode JSON
			$this->data = 'JSON decoding error';
			$this->status = self::STATUS_ERROR;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return $this->getResponse();
		}

		// Decode the request body
		// Request format: {encapsulation, body{ [key], [challenge], method, [data] }} or {[challenge], method, [data]}
		if (isset($request->encapsulation) && isset($request->body))
		{
			if (!class_exists('\\AEUtilEncrypt') && !($request->encapsulation == self::ENCAPSULATION_RAW))
			{
				// Encrypted request found, but there is no encryption class available!
				$this->data = 'This server does not support encrypted requests';
				$this->status = self::STATUS_NOT_AVAILABLE;
				$this->encapsulation = self::ENCAPSULATION_RAW;

				return $this->getResponse();
			}

			// Fully specified request
			switch ($request->encapsulation)
			{
				case self::ENCAPSULATION_AESCBC128:
					if (!isset($body))
					{
						$request->body = base64_decode($request->body);
						$body = \AEUtilEncrypt::AESDecryptCBC($request->body, $this->serverKey(), 128);
					}
					break;

				case self::ENCAPSULATION_AESCBC256:
					if (!isset($body))
					{
						$request->body = base64_decode($request->body);
						$body = \AEUtilEncrypt::AESDecryptCBC($request->body, $this->serverKey(), 256);
					}
					break;

				case self::ENCAPSULATION_AESCTR128:
					if (!isset($body))
					{
						$body = \AEUtilEncrypt::AESDecryptCtr($request->body, $this->serverKey(), 128);
					}
					break;

				case self::ENCAPSULATION_AESCTR256:
					if (!isset($body))
					{
						$body = \AEUtilEncrypt::AESDecryptCtr($request->body, $this->serverKey(), 256);
					}
					break;

				case self::ENCAPSULATION_RAW:
					$body = $request->body;
					break;
			}

			if (!empty($request->body))
			{
				if (!isset($body))
				{
					$body = $request->body;
				}

				$authorised = true;
				$body = rtrim($body, chr(0));

				// Make sure it looks like a valid JSON string and is at least 12 characters (minimum valid message length)
				if ((strlen($body) < 12) || (substr($body, 0, 1) != '{') || (substr($body, -1) != '}'))
				{
					$authorised = false;
				}

				// Try to JSON decode the body
				if ($authorised)
				{
					$request->body = $this->json_decode($body);

					if (is_null($request->body))
					{
						$authorised = false;
					}
					elseif (!is_object($request->body))
					{
						$authorised = false;
					}
				}

				// Make sure there is a requested method
				if ($authorised)
				{
					if (!isset($request->body->method) || empty($request->body->method))
					{
						$authorised = false;
					}
				}

				if (!$authorised)
				{
					// Decryption failed. The user is an impostor! Go away, hacker!
					$this->data = 'Authentication failed';
					$this->status = self::STATUS_NOT_AUTH;
					$this->encapsulation = self::ENCAPSULATION_RAW;

					return $this->getResponse();
				}
			}
		}
		elseif (isset($request->body))
		{
			// Partially specified request, assume RAW encapsulation
			$request->encapsulation = self::ENCAPSULATION_RAW;
			$request->body = json_decode($request->body);
		}
		else
		{
			// Legacy request
			$legacyRequest = clone $request;
			$request = (object)array('encapsulation' => self::ENCAPSULATION_RAW, 'body' => null);
			$request->body = json_decode($legacyRequest);
			unset($legacyRequest);
		}

		// Authenticate the user. Do note that if an encrypted request was made, we can safely assume that
		// the user is authenticated (he already knows the server key!)
		if ($request->encapsulation == self::ENCAPSULATION_RAW)
		{
			$authenticated = false;

			if (isset($request->body->challenge))
			{
				list($challenge, $check) = explode(':', $request->body->challenge);
				$crosscheck = strtolower(md5($challenge . $this->serverKey()));
				$authenticated = ($crosscheck == $check);
			}

			if (!$authenticated)
			{
				// If the challenge was missing or it was wrong, don't let him go any further
				$this->data = 'Invalid login credentials';
				$this->status = self::STATUS_NOT_AUTH;
				$this->encapsulation = self::ENCAPSULATION_RAW;

				return $this->getResponse();
			}
		}

		// Replicate the encapsulation preferences of the client for our own output
		$this->encapsulation = $request->encapsulation;

		// Store the client-specified key, or use the server key if none specified and the request
		// came encrypted.
		$this->password = isset($request->body->key) ? $request->body->key : null;
		$hasKey = (isset($request->body->key) || property_exists($request->body, 'key')) ? !is_null($request->body->key) : false;

		if (!$hasKey && ($request->encapsulation != self::ENCAPSULATION_RAW))
		{
			$this->password = $this->serverKey();
		}

		// Does the specified method exist?
		$method_exists = false;
		$method_name = '';

		if (isset($request->body->method))
		{
			$method_name = ucfirst($request->body->method);
			$this->method_name = $method_name;
			$method_exists = method_exists($this, '_api' . $method_name);
		}

		if (!$method_exists)
		{
			// The requested method doesn't exist. Oops!
			$this->data = "Invalid method $method_name";
			$this->status = self::STATUS_INVALID_METHOD;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return $this->getResponse();
		}

		// Run the method
		$params = array();

		if (isset($request->body->data))
		{
			$params = (array)$request->body->data;
		}

		$this->data = call_user_func(array($this, '_api' . $method_name), $params);

		return $this->getResponse();
	}

	/**
	 * Packages the response to a JSON-encoded object, optionally encrypting the
	 * data part with a caller-supplied password.
	 * @return string The JSON-encoded response
	 */
	private function getResponse()
	{
		// Initialize the response
		$response = array(
			'encapsulation' => $this->encapsulation,
			'body'          => array(
				'status' => $this->status,
				'data'   => null
			)
		);

		$data = json_encode($this->data);

		if (empty($this->password))
		{
			$this->encapsulation = self::ENCAPSULATION_RAW;
		}

		switch ($this->encapsulation)
		{
			case self::ENCAPSULATION_RAW:
				break;

			case self::ENCAPSULATION_AESCTR128:
				$data = \AEUtilEncrypt::AESEncryptCtr($data, $this->password, 128);
				break;

			case self::ENCAPSULATION_AESCTR256:
				$data = \AEUtilEncrypt::AESEncryptCtr($data, $this->password, 256);
				break;

			case self::ENCAPSULATION_AESCBC128:
				$data = base64_encode(\AEUtilEncrypt::AESEncryptCBC($data, $this->password, 128));
				break;

			case self::ENCAPSULATION_AESCBC256:
				$data = base64_encode(\AEUtilEncrypt::AESEncryptCBC($data, $this->password, 256));
				break;
		}

		$response['body']['data'] = $data;

		return '###' . json_encode($response) . '###';
	}

	private function serverKey()
	{
		static $key = null;

		if (is_null($key))
		{
			$key = \AEPlatform::getInstance()->get_platform_configuration_option('frontend_secret_word', '');
		}

		return $key;
	}

	private function _apiGetVersion()
	{
		// @todo Handle update information
		$update = new \Solo\Model\Update;
		$updateInformation = $update->getUpdateInformation();

		$edition = AKEEBA_PRO ? 'pro' : 'core';

		return (object)array(
			'api'        => AKEEBA_JSON_API_VERSION,
			'component'  => AKEEBA_VERSION,
			'date'       => AKEEBA_DATE,
			'edition'    => $edition,
			'updateinfo' => $updateInformation,
		);
	}

	private function _apiGetProfiles()
	{
		$model = new Profiles();
		$profiles = $model->get(true);
		$ret = array();

		if (count($profiles))
		{
			foreach ($profiles as $profile)
			{
				$temp = new \stdClass();
				$temp->id = $profile->id;
				$temp->name = $profile->description;
				$ret[] = $temp;
			}
		}

		return $ret;
	}

	private function _apiStartBackup($config)
	{
		// Get the passed configuration values
		$defConfig = array(
			'profile'     => 1,
			'description' => '',
			'comment'     => '',
			'backupid'    => null,
		);

		$config = array_merge($defConfig, $config);

		foreach ($config as $key => $value)
		{
			if (!array_key_exists($key, $defConfig)) unset($config[$key]);
		}
		extract($config);

		// Nuke the factory
		\AEFactory::nuke();

		// Set the profile. Note: $profile is set by extract($config) above.
		$profile = (int)$profile;

		if (!is_numeric($profile))
		{
			$profile = 1;
		}

		if (strtoupper($backupid) == '[DEFAULT]')
		{
			$db = $this->container->db;
			$query = $db->getQuery(true)
				->select('MAX(' . $db->qn('id') . ')')
				->from($db->qn('#__ak_stats'));

			try
			{
				$maxId = $db->setQuery($query)->loadResult();
			}
			catch (\Exception $e)
			{
				$maxId = 0;
			}

			$backupid = 'id' . ($maxId + 1);
		}
		elseif (empty($backupid))
		{
			$backupid = null;
		}

		$session = \Awf\Application\Application::getInstance()->getContainer()->segment;
		$session->profile = $profile;

		\AEPlatform::getInstance()->load_configuration($profile);

		// Use the default description if none specified
		if (empty($description))
		{
			$dateNow = new Date();
			$description = Text::_('BACKUP_DEFAULT_DESCRIPTION') . ' ' . $dateNow->format(Text::_('DATE_FORMAT_LC2'), true);
		}

		// Start the backup
		\AECoreKettenrad::reset(array(
			'maxrun' => 0
		));

		\AEUtilTempfiles::deleteTempFiles();

		$tempVarsTag = AKEEBA_BACKUP_ORIGIN;
		$tempVarsTag .= empty($backupid) ? '' : ('.' . $backupid);

		\AEUtilTempvars::reset($tempVarsTag);

		$kettenrad = \AECoreKettenrad::load(AKEEBA_BACKUP_ORIGIN, $backupid);
		$kettenrad->setBackupId($backupid);

		$options = array(
			'description' => $description,
			'comment'     => $comment, // Note: $comment is set by extract() further above
			'tag'         => AKEEBA_BACKUP_ORIGIN
		);
		$kettenrad->setup($options); // Setting up the engine
		$array = $kettenrad->tick(); // Initializes the init domain
		\AECoreKettenrad::save(AKEEBA_BACKUP_ORIGIN, $backupid);

		$array = $kettenrad->getStatusArray();
		if ($array['Error'] != '')
		{
			// A backup error had occurred. Why are we here?!
			$this->status = self::STATUS_ERROR;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return 'A backup error had occurred: ' . $array['Error'];
		}
		else
		{
			$statistics = \AEFactory::getStatistics();
			$array['BackupID'] = $statistics->getId();
			$array['HasRun'] = 1; // Force the backup to go on.
			return $array;
		}
	}

	private function _apiStepBackup($config)
	{
		$defConfig = array(
			'profile' => null,
			'tag'     => AKEEBA_BACKUP_ORIGIN,
			'backupid' => null,
		);
		$config = array_merge($defConfig, $config);
		extract($config);

		// Try to set the profile from the setup parameters
		if (!empty($profile))
		{
			$registry = \AEFactory::getConfiguration();
			$session = \Awf\Application\Application::getInstance()->getContainer()->segment;
			$session->profile = $profile;
		}

		$kettenrad = \AECoreKettenrad::load($tag, $backupid);
		$kettenrad->setBackupId($backupid);

		$registry = \AEFactory::getConfiguration();
		$session = \Awf\Application\Application::getInstance()->getContainer()->segment;
		$session->profile = $registry->activeProfile;

		$array = $kettenrad->tick();
		$ret_array = $kettenrad->getStatusArray();
		$array['Progress'] = $ret_array['Progress'];
		\AECoreKettenrad::save($tag, $backupid);

		if ($array['Error'] != '')
		{
			// A backup error had occurred. Why are we here?!
			$this->status = self::STATUS_ERROR;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return 'A backup error had occurred: ' . $array['Error'];
		}
		elseif ($array['HasRun'] == false)
		{
			\AEFactory::nuke();
			\AEUtilTempvars::reset();
		}

		return $array;
	}

	private function _apiListBackups($config)
	{
		$defConfig = array(
			'from'  => 0,
			'limit' => 50
		);
		$config = array_merge($defConfig, $config);
		extract($config);

		$model = new Manage();
		$model->setState('limitstart', $from);
		$model->setState('limit', $limit);

		return $model->getStatisticsListWithMeta(false);
	}

	private function _apiGetBackupInfo($config)
	{
		$defConfig = array(
			'backup_id' => '0'
		);
		$config = array_merge($defConfig, $config);
		extract($config);

		// Get the basic statistics
		$record = \AEPlatform::getInstance()->get_statistics($backup_id);

		// Get a list of filenames
		$backup_stats = \AEPlatform::getInstance()->get_statistics($backup_id);

		// Backup record doesn't exist
		if (empty($backup_stats))
		{
			$this->status = self::STATUS_NOT_FOUND;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return 'Invalid backup record identifier';
		}

		$filenames = \AEUtilStatistics::get_all_filenames($record);

		if (empty($filenames))
		{
			// Archives are not stored on the server or no files produced
			$record['filenames'] = array();
		}
		else
		{
			$filedata = array();
			$i = 0;

			// Get file sizes per part
			foreach ($filenames as $file)
			{
				$i++;
				$size = @filesize($file);
				$size = is_numeric($size) ? $size : 0;
				$filedata[] = array(
					'part' => $i,
					'name' => basename($file),
					'size' => $size
				);
			}

			// Add the file info to $record['filenames']
			$record['filenames'] = $filedata;
		}

		return $record;

	}

	private function _apiDownload($config)
	{
		$defConfig = array(
			'backup_id'  => 0,
			'part_id'    => 1,
			'segment'    => 1,
			'chunk_size' => 1
		);
		$config = array_merge($defConfig, $config);
		extract($config);

		$backup_stats = \AEPlatform::getInstance()->get_statistics($backup_id);
		if (empty($backup_stats))
		{
			// Backup record doesn't exist
			$this->status = self::STATUS_NOT_FOUND;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return 'Invalid backup record identifier';
		}
		$files = \AEUtilStatistics::get_all_filenames($backup_stats);

		if ((count($files) < $part_id) || ($part_id <= 0))
		{
			// Invalid part
			$this->status = self::STATUS_NOT_FOUND;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return 'Invalid backup part';
		}

		$file = $files[$part_id - 1];

		$filesize = @filesize($file);
		$seekPos = $chunk_size * 1048756 * ($segment - 1);

		if ($seekPos > $filesize)
		{
			// Trying to seek past end of file
			$this->status = self::STATUS_NOT_FOUND;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return 'Invalid segment';
		}

		$fp = fopen($file, 'rb');

		if ($fp === false)
		{
			// Could not read file
			$this->status = self::STATUS_ERROR;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return 'Error reading backup archive';
		}

		rewind($fp);
		if (fseek($fp, $seekPos, SEEK_SET) === -1)
		{
			// Could not seek to position
			$this->status = self::STATUS_ERROR;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return 'Error reading specified segment';
		}

		$buffer = fread($fp, 1048756);

		if ($buffer === false)
		{
			// Could not read
			$this->status = self::STATUS_ERROR;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return 'Error reading specified segment';
		}

		fclose($fp);

		switch ($this->encapsulation)
		{
			case self::ENCAPSULATION_RAW:
				return base64_encode($buffer);
				break;

			case self::ENCAPSULATION_AESCTR128:
				$this->encapsulation = self::ENCAPSULATION_AESCBC128;

				return $buffer;
				break;

			case self::ENCAPSULATION_AESCTR256:
				$this->encapsulation = self::ENCAPSULATION_AESCBC256;

				return $buffer;
				break;

			default:
				// On encrypted comms the encryption will take care of transport encoding
				return $buffer;
				break;
		}
	}

	private function _apiDelete($config)
	{
		$defConfig = array(
			'backup_id' => 0
		);
		$config = array_merge($defConfig, $config);
		extract($config);

		$model = new Manage();
		$model->setState('id', (int)$backup_id);

		try
		{
			$model->delete();
			$result = true;
		}
		catch (\Exception $e)
		{
			$result = false;
			$error = $e->getMessage();
		}

		if (!$result)
		{
			$this->status = self::STATUS_ERROR;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return $error;
		}
		else
		{
			return true;
		}
	}

	private function _apiDeleteFiles($config)
	{
		$defConfig = array(
			'backup_id' => 0
		);
		$config = array_merge($defConfig, $config);
		extract($config);

		$model = new Manage();
		$model->setState('id', (int)$backup_id);

		try
		{
			$model->deleteFile();
			$result = true;
		}
		catch (\Exception $e)
		{
			$result = false;
			$error = $e->getMessage();
		}

		if (!$result)
		{
			$this->status = self::STATUS_ERROR;
			$this->encapsulation = self::ENCAPSULATION_RAW;

			return $error;
		}
		else
		{
			return true;
		}
	}

	private function _apiGetLog($config)
	{
		$defConfig = array(
			'tag' => 'remote'
		);
		$config = array_merge($defConfig, $config);
		extract($config);

		$filename = \AEUtilLogger::logName($tag);
		$buffer = file_get_contents($filename);

		switch ($this->encapsulation)
		{
			case self::ENCAPSULATION_RAW:
				return base64_encode($buffer);
				break;

			case self::ENCAPSULATION_AESCTR128:
				$this->encapsulation = self::ENCAPSULATION_AESCBC128;

				return $buffer;
				break;

			case self::ENCAPSULATION_AESCTR256:
				$this->encapsulation = self::ENCAPSULATION_AESCBC256;

				return $buffer;
				break;

			default:
				// On encrypted comms the encryption will take care of transport encoding
				return $buffer;
				break;
		}

	}

	private function _apiDownloadDirect($config)
	{
		$defConfig = array(
			'backup_id' => 0,
			'part_id'   => 1
		);
		$config = array_merge($defConfig, $config);
		extract($config);

		$backup_stats = \AEPlatform::getInstance()->get_statistics($backup_id);
		if (empty($backup_stats))
		{
			// Backup record doesn't exist
			$this->status = self::STATUS_NOT_FOUND;
			$this->encapsulation = self::ENCAPSULATION_RAW;
			@ob_end_clean();
			header('HTTP/1.1 500 Invalid backup record identifier');
			flush();
			$this->container->application->close();
		}
		$files = \AEUtilStatistics::get_all_filenames($backup_stats);

		if ((count($files) < $part_id) || ($part_id <= 0))
		{
			// Invalid part
			$this->status = self::STATUS_NOT_FOUND;
			$this->encapsulation = self::ENCAPSULATION_RAW;
			@ob_end_clean();
			header('HTTP/1.1 500 Invalid backup part');
			flush();
			$this->container->application->close();
		}

		$filename = $files[$part_id - 1];
		@clearstatcache();

		// For a certain unmentionable browser
		if (function_exists('ini_get') && function_exists('ini_set'))
		{
			if (ini_get('zlib.output_compression'))
			{
				ini_set('zlib.output_compression', 'Off');
			}
		}

		// Remove php's time limit -- Thank you, Nooku, for the tip
		if (function_exists('ini_get') && function_exists('set_time_limit'))
		{
			if (!ini_get('safe_mode'))
			{
				@set_time_limit(0);
			}
		}

		$basename = @basename($filename);
		$filesize = @filesize($filename);
		$extension = strtolower(str_replace(".", "", strrchr($filename, ".")));

		while (@ob_end_clean()) ;
		@clearstatcache();
		// Send MIME headers
		header('MIME-Version: 1.0');
		header('Content-Disposition: attachment; filename="' . $basename . '"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');

		switch ($extension)
		{
			case 'zip':
				// ZIP MIME type
				header('Content-Type: application/zip');
				break;

			default:
				// Generic binary data MIME type
				header('Content-Type: application/octet-stream');
				break;
		}
		// Notify of filesize, if this info is available
		if ($filesize > 0) header('Content-Length: ' . @filesize($filename));
		// Disable caching
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Expires: 0");
		header('Pragma: no-cache');
		flush();
		if ($filesize > 0)
		{
			// If the filesize is reported, use 1M chunks for echoing the data to the browser
			$blocksize = 1048756; //1M chunks
			$handle = @fopen($filename, "r");
			// Now we need to loop through the file and echo out chunks of file data
			if ($handle !== false) while (!@feof($handle))
			{
				echo @fread($handle, $blocksize);
				@ob_flush();
				flush();
			}
			if ($handle !== false) @fclose($handle);
		}
		else
		{
			// If the filesize is not reported, hope that readfile works
			@readfile($filename);
		}
		flush();
		$this->container->application->close();
	}

	private function _apiUpdateGetInformation($config)
	{
		$defConfig = array(
			'force' => 0
		);
		$config = array_merge($defConfig, $config);
		extract($config);

		// @todo Handle update information
		$update = new \Solo\Model\Update;

		$updateInformation = $update->getUpdateInformation($force);

		return (object)$updateInformation;
	}

	private function _apiUpdateDownload($config)
	{
		// @todo Download the update
	}

	private function _apiUpdateExtract($config)
	{
		// @todo Extract the update
	}

	private function _apiUpdateInstall($config)
	{
		// @todo Run any post-update scripts
	}

	private function _apiUpdateCleanup($config)
	{
		// @todo Remove temporary update files
	}
} 