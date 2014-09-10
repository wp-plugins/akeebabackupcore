<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 *
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebabackupwp
 *
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

if (!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR); // Sometimes required (legacy code)
}

/**
 * Akeeba Solo for WordPress platform class
 */
class AEPlatformSolowp extends AEPlatformAbstract
{
	/** @var   integer  Platform class priority */
	public $priority = 60;

	/** @var   string  The platform name */
	public $platformName = 'solowp';

	/**
	 * Performs heuristics to determine if this platform object is the ideal
	 * candidate for the environment Akeeba Engine is running in.
	 *
	 * @return  boolean  True if this platform applies to this environment
	 */
	public function isThisPlatform()
	{
		if (!defined('WPINC') && !defined('ABSPATH'))
		{
			return false;
		}

		return true;
	}

	/**
	 * Returns an associative array of stock platform directories
	 *
	 * @return  array  Platform directories
	 */
	public function get_stock_directories()
	{
		static $stock_directories = array();

		if (empty($stock_directories))
		{
			$stock_directories['[SITEROOT]'] = $this->get_site_root();
			$stock_directories['[ROOTPARENT]'] = @realpath($this->get_site_root() . '/..');
			$stock_directories['[SITETMP]'] = APATH_BASE . '/tmp';
			$stock_directories['[DEFAULT_OUTPUT]'] = APATH_BASE . '/backups';
		}

		return $stock_directories;
	}

	/**
	 * Returns the absolute path to the site's root
	 *
	 * @return  string  The absolute path to our own directory
	 */
	public function get_site_root()
	{
		static $root = null;

		if (empty($root) || is_null($root))
		{
			$root = ABSPATH;

			if (empty($root) || ($root == DIRECTORY_SEPARATOR) || ($root == '/'))
			{
				// Try to get the current root in a different way
				if (function_exists('getcwd'))
				{
					$root = getcwd();
				}

				if (empty($root))
				{
					$root = '../';
				}
				else
				{
					$adminPos = strpos($root, 'wp-admin');
					if ($adminPos !== false)
					{
						$root = substr($root, 0, $adminPos);
					}
					else
					{
						$root = '../';
					}
					// Degenerate case where $root = 'wp-admin'
					// without a leading slash before entering this
					// if-block
					if (empty($root))
					{
						$root = '../';
					}
				}
			}
		}

		return $root;
	}

	/**
	 * Returns the absolute path to the installer images directory
	 *
	 * @return  string  The absolute path to the installer images directory
	 */
	public function get_installer_images_path()
	{
		return \Awf\Application\Application::getInstance()->getContainer()->basePath . '/assets/installers';
	}

	/**
	 * Returns the active profile number
	 *
	 * @return  integer  The active profile number
	 */
	public function get_active_profile()
	{
		if (defined('AKEEBA_PROFILE'))
		{
			return AKEEBA_PROFILE;
		}
		else
		{
			$session = \Awf\Application\Application::getInstance()->getContainer()->segment;

			if (isset($session->profile))
			{
				$profile = (int)$session->profile;

				if (empty($profile) || ($profile < 1))
				{
					$profile = 1;
				}

				return $profile;
			}
			else
			{
				return 1;
			}
		}
	}

	/**
	 * Returns the selected profile's name. If no ID is specified, the current
	 * profile's name is returned.
	 *
	 * @param   integer $id The profile number for which to get the name
	 *
	 * @return  string  The profile's name
	 */
	public function get_profile_name($id = null)
	{
		if (empty($id))
		{
			$id = $this->get_active_profile();
		}

		$id = (int)$id;

		if (empty($id))
		{
			$id = 1;
		}

		$db = \Awf\Application\Application::getInstance()->getContainer()->db;
		$query = $db->getQuery(true)
			->select($db->qn('description'))
			->from($db->qn('#__ak_profiles'))
			->where($db->qn('id') . ' = ' . $db->q($id));
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Returns the backup origin as set in the AKEEBA_BACKUP_ORIGIN constant, otherwise "backend"
	 *
	 * @return   string  The backup origin
	 */
	public function get_backup_origin()
	{
		if (defined('AKEEBA_BACKUP_ORIGIN'))
		{
			return AKEEBA_BACKUP_ORIGIN;
		}

		return 'backend';
	}

	/**
	 * Returns a MySQL-formatted timestamp out of the current date
	 *
	 * @param   string $date [optional] The timestamp to use. Omit to use current timestamp.
	 *
	 * @return  string
	 */
	public function get_timestamp_database($date = 'now')
	{
		$date = new \Awf\Date\Date($date);

		return $date->toSql();
	}

	/**
	 * Returns the current timestamp, taking into account any TZ information,
	 * in the format specified by $format.
	 *
	 * @param   string $format Timestamp format string (standard PHP format string)
	 *
	 * @return  string
	 */
	public function get_local_timestamp($format = 'Y-m-d H:i:s')
	{
		$timezone = \Awf\Application\Application::getInstance()->getContainer()->appConfig->get('timezone', 'UTC');

		$dateNow = new \Awf\Date\Date('now', $timezone);

		return $dateNow->format($format, true);
	}

	/**
	 * Returns the current host name
	 *
	 * @return  string
	 */
	public function get_host()
	{
		$overrideURL = AEFactory::getConfiguration()->get('akeeba.platform.site_url', '');
		$overrideURL = trim($overrideURL);

		if (!empty($overrideURL))
		{
			// An override URL is already specified; use it
			$oURI = new \Awf\Uri\Uri($overrideURL);
		}
		elseif (!array_key_exists('REQUEST_METHOD', $_SERVER))
		{
			// Running under CLI or a broken server
			$url = AEPlatform::getInstance()->get_platform_configuration_option('siteurl', '');
			$oURI = new \Awf\Uri\Uri($url);
		}
		else
		{
			// Running under the web server
			$oURI = \Awf\Uri\Uri::getInstance();
		}

		return $oURI->getHost();
	}

	/**
	 * Returns the site's name. This doesn't apply for Akeeba Solo so we have to fake it.
	 *
	 * @return  string
	 */
	public function get_site_name()
	{
		if (function_exists('get_bloginfo'))
		{
			return get_bloginfo('name', 'raw');
		}
		else
		{
			return "Akeeba Backup";
		}
	}

	/**
	 * Gets the best matching database driver class. $use_platform is ignored in Akeeba Solo.
	 *
	 * @param   boolean $use_platform If set to false, it will forcibly try to assign one of the primitive types
	 *                                (AEDriverMySQL/AEDriverMySQLi) and NEVER tell you to use an AEPlatformDriver* class
	 *
	 * @return  string
	 */
	public function get_default_database_driver($use_platform = true)
	{
		if ($use_platform && defined('WPINC'))
		{
			return 'AEDriverPlatformSolowp';
		}

		return 'AEDriverMysql';
	}

	/**
	 * Returns a set of options to connect to the default database
	 *
	 * @return  array  Database connection options
	 */
	public function get_platform_database_options()
	{
		static $options;

		if (empty($options))
		{
			if (!defined('WPINC'))
			{
				global $table_prefix;
			}
			else
			{
				global $wpdb;
				$table_prefix = $wpdb->prefix;
			}

			$options = array(
				'host'		=> DB_HOST,
				'user'		=> DB_USER,
				'password'	=> DB_PASSWORD,
				'database'	=> DB_NAME,
				'prefix'	=> $table_prefix
			);
		}

		return $options;
	}

	/**
	 * Provides a platform-specific translation function
	 *
	 * @param   string $key The translation key
	 *
	 * @return  string  The translated string
	 */
	public function translate($key)
	{
		return \Awf\Text\Text::_($key);
	}

	/**
	 * Populates global constants holding the Akeeba application version
	 */
	public function load_version_defines()
	{
		$fileName = APATH_BASE . '/version.php';

		if (file_exists($fileName))
		{
			require_once $fileName;
		}

		if (!defined('AKEEBA_VERSION'))
		{
			define("AKEEBA_VERSION", "dev");
		}

		if (!defined('AKEEBA_PRO'))
		{
			define('AKEEBA_PRO', true);
		}

		if (!defined('AKEEBA_DATE'))
		{
			$date = new \Awf\Date\Date();
			define("AKEEBA_DATE", $date->format('Y-m-d'));
		}
	}

	/**
	 * Returns the platform name and version
	 *
	 * @return  array  An array containing the platform name and version, in this order
	 */
	public function getPlatformVersion()
	{
		return array(
			'name'    => 'Akeeba Backup for WordPress',
			'version' => AKEEBA_VERSION
		);
	}

	/**
	 * Logs platform-specific directories with _AE_LOG_INFO log level
	 *
	 * @return  array|null
	 */
	public function log_platform_special_directories()
	{
		$site_root = $this->get_site_root();

		AEUtilLogger::WriteLog(_AE_LOG_INFO, "APATH_BASE         :" . APATH_BASE);
		AEUtilLogger::WriteLog(_AE_LOG_INFO, "Application Path   :" . \Awf\Application\Application::getInstance()->getContainer()->basePath);
		AEUtilLogger::WriteLog(_AE_LOG_INFO, "Site root          :" . $this->get_site_root());

		// If the release is older than 3 months, issue a warning
		if (defined('AKEEBA_DATE'))
		{
			$releaseDate = new \Awf\Date\Date(AKEEBA_DATE);

			if (time() - $releaseDate->toUnix() > 7776000)
			{
				if (!isset($ret['warnings']))
				{
					$ret['warnings'] = array();
					$ret['warnings'] = array_merge($ret['warnings'], array(
						'Your version of Akeeba Backup is more than 90 days old and most likely already out of date. Please check if a newer version is published and install it.'
					));
				}
			}

		}

		// Detect UNC paths and warn the user
		if(DIRECTORY_SEPARATOR == '\\') {
			if( (substr(JPATH_ROOT, 0, 2) == '\\\\') || (substr(JPATH_ROOT, 0, 2) == '//') ) {
				if (!isset($ret['warnings']))
				{
					$ret['warnings'] = array();
				}

				$ret['warnings'] = array_merge($ret['warnings'], array(
					'Your site\'s root is using a UNC path (e.g. \\SERVER\path\to\root). PHP has known bugs which may',
					'prevent it from working properly on a site like this. Please take a look at',
					'https://bugs.php.net/bug.php?id=40163 and https://bugs.php.net/bug.php?id=52376. As a result your',
					'backup may fail.'
				));
			}
		}

		if (empty($ret))
		{
			$ret = null;
		}

		return $ret;
	}

	/**
	 * Loads a platform-specific software configuration option
	 *
	 * @param   string $key     The configuration option's key
	 * @param   mixed  $default The default value to use
	 *
	 * @return  mixed
	 */
	public function get_platform_configuration_option($key, $default)
	{
		$config = \Awf\Application\Application::getInstance()->getContainer()->appConfig;

		return $config->get('options.' . $key, $default);
	}

	/**
	 * Returns a list of emails to the Super Administrators
	 *
	 * @return  array
	 */
	public function get_administrator_emails()
	{
		$ret = array();

		$emails = $this->get_platform_configuration_option('frontend_email_address', '');

		if (!empty($emails))
		{
			$emails = explode(',', $emails);
			foreach ($emails as $email)
			{
				$ret[] = trim($email);
			}
		}

		return $ret;
	}

	/**
	 * Sends a very simple email using the platform's mailer facility
	 *
	 * @param   string $to         The recipient's email address
	 * @param   string $subject    The subject of the email
	 * @param   string $body       The body of the email
	 * @param   string $attachFile The file to attach (null to not attach any files)
	 *
	 * @return  boolean
	 */
	public function send_email($to, $subject, $body, $attachFile = null)
	{
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "-- Fetching mailer object");

		$app = \Awf\Application\Application::getInstance();
		$mailer = $app->getContainer()->mailer;

		if (!is_object($mailer))
		{
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, "Could not send email to $to - Reason: Mailer object is not an object; please check your system settings");

			return false;
		}

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "-- Creating email message");

		$recipient = array($to);
		$mailer->addRecipient($recipient);
		$mailer->setSubject($subject);
		$mailer->setBody($body);

		if (!empty($attachFile))
		{
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, "-- Attaching $attachFile");

			if (!file_exists($attachFile) || !(is_file($attachFile) || is_link($attachFile)))
			{
				AEUtilLogger::WriteLog(_AE_LOG_WARNING, "The file does not exist, or it's not a file; no email sent");

				return false;
			}

			if (!is_readable($attachFile))
			{
				AEUtilLogger::WriteLog(_AE_LOG_WARNING, "The file is not readable; no email sent");

				return false;
			}

			$fileSize = @filesize($attachFile);

			if ($fileSize)
			{
				// Check that we have AT LEAST 2.5 times free RAM as the filesize (that's how much we'll need)
				if (!function_exists('ini_get'))
				{
					// Assume 8Mb of PHP memory limit (worst case scenario)
					$totalRAM = 8388608;
				}
				else
				{
					$totalRAM = ini_get('memory_limit');
					if (strstr($totalRAM, 'M'))
					{
						$totalRAM = (int)$totalRAM * 1048576;
					}
					elseif (strstr($totalRAM, 'K'))
					{
						$totalRAM = (int)$totalRAM * 1024;
					}
					elseif (strstr($totalRAM, 'G'))
					{
						$totalRAM = (int)$totalRAM * 1073741824;
					}
					else
					{
						$totalRAM = (int)$totalRAM;
					}
					if ($totalRAM <= 0)
					{
						// No memory limit? Cool! Assume 1Gb of available RAM (which is absurdly abundant as of March 2011...)
						$totalRAM = 1086373952;
					}
				}
				if (!function_exists('memory_get_usage'))
				{
					$usedRAM = 8388608;
				}
				else
				{
					$usedRAM = memory_get_usage();
				}

				$availableRAM = $totalRAM - $usedRAM;

				if ($availableRAM < 2.5 * $fileSize)
				{
					AEUtilLogger::WriteLog(_AE_LOG_WARNING, "The file is too big to be sent by email. Please use a smaller Part Size for Split Archives setting.");
					AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Memory limit $totalRAM bytes -- Used memory $usedRAM bytes -- File size $fileSize -- Attachment requires approx. " . (2.5 * $fileSize) . " bytes");

					return false;
				}
			}
			else
			{
				AEUtilLogger::WriteLog(_AE_LOG_WARNING, "Your server fails to report the file size of $attachFile. If the backup crashes, please use a smaller Part Size for Split Archives setting");
			}

			$mailer->addAttachment($attachFile);
		}

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "-- Sending message");

		try
		{
			$result = $mailer->Send();
		}
		catch (Exception $e)
		{
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, "Could not email $to:");
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, $e->getMessage());
			$ret = $e->getMessage();
			unset($result);
			unset($mailer);

			return $ret;
		}

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "-- Email sent");

		return true;
	}

	/**
	 * Deletes a file from the local server using direct file access or FTP
	 *
	 * @param   string $file File path to delete
	 *
	 * @return  boolean  True on success
	 */
	public function unlink($file)
	{
		try
		{
			$fs = \Awf\Application\Application::getInstance()->getContainer()->fileSystem;
			$result = $fs->delete($file);
		}
		catch (RuntimeException $e)
		{
			$result = false;
		}

		if (is_null($result))
		{
			$result = true;
		}

		return $result;
	}

	/**
	 * Moves a file around within the local server using direct file access or FTP
	 *
	 * @param   string $from File path to copy from
	 * @param   string $to   File path to copy to
	 *
	 * @return  boolean  True on success
	 */
	public function move($from, $to)
	{
		try
		{
			$fs = \Awf\Application\Application::getInstance()->getContainer()->fileSystem;
			$result = $fs->move($from, $to);
		}
		catch (RuntimeException $e)
		{
			$result = false;
		}

		if (is_null($result))
		{
			$result = true;
		}

		return $result;
	}

	/**
	 * Stores a flash (temporary) variable in the session.
	 *
	 * @param   string $name  The name of the variable to store
	 * @param   string $value The value of the variable to store
	 *
	 * @return  void
	 */
	public function set_flash_variable($name, $value)
	{
		$session = \Awf\Application\Application::getInstance()->getContainer()->segment;

		$session->setFlash($name, $value);
	}

	/**
	 * Return the value of a flash (temporary) variable from the session and
	 * immediately removes it.
	 *
	 * @param   string $name    The name of the flash variable
	 * @param   mixed  $default Default value, if the variable is not defined
	 *
	 * @return  mixed  The value of the variable or $default if it's not set
	 */
	public function get_flash_variable($name, $default = null)
	{
		$session = \Awf\Application\Application::getInstance()->getContainer()->segment;

		$value = $session->getFlash($name);

		if (is_null($value))
		{
			$value = $default;
		}

		return $value;
	}

	/**
	 * Perform an immediate redirection to the defined URL
	 *
	 * @param   string $url The URL to redirect to
	 *
	 * @return  void
	 */
	public function redirect($url)
	{
		\Awf\Application\Application::getInstance()->redirect($url);
	}

	/**
	 * Applies the quirk definitions for Akeeba Solo
	 *
	 * @return  void
	 */
	public function apply_quirk_definitions()
	{
		\AEUtilQuirks::clearQuirkDefs();
		// Output directory unwritable
		\AEUtilQuirks::addQuirkDef('001', 'critical');
		// Free memory too low
		\AEUtilQuirks::addQuirkDef('004', 'critical');
		// open_basedir on output directory
		\AEUtilQuirks::addQuirkDef('101', 'high');
		// Less than 10" of max_execution_time with PHP Safe Mode enabled
		\AEUtilQuirks::addQuirkDef('103', 'high');
		// CRC problems with hash extension not present
		\AEUtilQuirks::addQuirkDef('202', 'medium');
		// Default output directory in use
		\AEUtilQuirks::addQuirkDef('203', 'low');
		// Disabled functions may affect operation
		\AEUtilQuirks::addQuirkDef('204', 'medium');
		// ZIP format selected
		\AEUtilQuirks::addQuirkDef('401', 'low');
	}

	public function getPlatformDirectories()
	{
		return array(__DIR__);
	}
}