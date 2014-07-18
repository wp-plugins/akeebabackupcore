<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\Model;

use Awf\Mvc\Model;
use Awf\Text\Text;

/**
 * The Model for the Configuration view
 */
class Configuration extends Model
{
	/**
	 * Saves the backup engine configuration to non-volatile storage
	 *
	 * @return  void
	 */
	public function saveEngineConfig()
	{
		$data = $this->getState('engineconfig', array());

		// Forbid stupidly selecting the site's root as the output or temporary directory
		if (array_key_exists('akeeba.basic.output_directory', $data))
		{
			$folder = $data['akeeba.basic.output_directory'];
			$folder = \AEUtilFilesystem::translateStockDirs($folder, true, true);

			$check = \AEUtilFilesystem::translateStockDirs('[SITEROOT]', true, true);

			if ($check == $folder)
			{
				$this->container->application->enqueueMessage(Text::_('CONFIG_OUTDIR_ROOT'), 'warning');
				$data['akeeba.basic.output_directory'] = '[DEFAULT_OUTPUT]';
			}
		}

		// Merge it
		$config = \AEFactory::getConfiguration();
		$config->mergeArray($data, false, false);

		// Save configuration
		\AEPlatform::getInstance()->save_configuration();
	}

	/**
	 * Tests an FTP connection and makes sure that we can connect to the server and change to the initial directory
	 *
	 * @return  boolean|array  True on success, a list of errors on failure
	 */
	public function testFTP()
	{
		$config = array(
			'host'    => $this->getState('host'),
			'port'    => $this->getState('port'),
			'user'    => $this->getState('user'),
			'pass'    => $this->getState('pass'),
			'initdir' => $this->getState('initdir'),
			'usessl'  => $this->getState('usessl'),
			'passive' => $this->getState('passive'),
		);

		// Check for bad settings
		if (substr($config['host'], 0, 6) == 'ftp://')
		{
			return Text::_('CONFIG_FTPTEST_BADPREFIX');
		}

		// Perform the FTP connection test
		$test = new \AEArchiverDirectftp();
		$test->initialize('', $config);

		$errors = $test->getError();

		if (empty($errors) || $test->connect_ok)
		{
			$result = true;
		}
		else
		{
			$result = $errors;
		}

		return $result;
	}

	/**
	 * Tests an SFTP connection and makes sure that we can connect to the server and change to the initial directory
	 *
	 * @return  boolean|array  True on success, a list of errors on failure
	 */
	public function testSFTP()
	{
		$config = array(
			'host'    => $this->getState('host'),
			'port'    => $this->getState('port'),
			'user'    => $this->getState('user'),
			'pass'    => $this->getState('pass'),
			'privkey' => $this->getState('privkey'),
			'pubkey'  => $this->getState('pubkey'),
			'initdir' => $this->getState('initdir'),
		);

		// Check for bad settings
		if (substr($config['host'], 0, 7) == 'sftp://')
		{
			return Text::_('CONFIG_SFTPTEST_BADPREFIX');
		}

		// Perform the FTP connection test
		$test = new \AEArchiverDirectsftp();
		$test->initialize('', $config);
		$errors = $test->getWarnings();

		if (empty($errors) || $test->connect_ok)
		{
			$result = true;
		}
		else
		{
			$result = $errors;
		}

		return $result;
	}

	/**
	 * Opens an OAuth window for the selected post-processing engine
	 *
	 * @return  boolean|void  False on failure, no return on success
	 */
	public function dpeOuthOpen()
	{
		$engine = $this->getState('engine');
		$params = $this->getState('params', array());

		$engine = \AEFactory::getPostprocEngine($engine);

		if ($engine === false)
		{
			return false;
		}

		$engine->oauthOpen($params);
	}

	/**
	 * Runs a custom API call for the selected post-processing engine
	 *
	 * @return  boolean  True on success
	 */
	public function dpeCustomAPICall()
	{
		$engine = $this->getState('engine');
		$method = $this->getState('method');
		$params = $this->getState('params', array());

		$engine = \AEFactory::getPostprocEngine($engine);

		if ($engine === false)
		{
			return false;
		}

		return $engine->customApiCall($method, $params);
	}
} 