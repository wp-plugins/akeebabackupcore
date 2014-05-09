<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license		GNU GPL version 3 or later
 */

namespace Solo\Pythia\Oracle;

use Solo\Pythia\OracleInterface;

class Wordpress implements OracleInterface
{
	protected $path = null;

	/**
	 * Creates a new oracle objects
	 *
	 * @param   string  $path  The directory path to scan
	 */
	public function __construct($path)
	{
		$this->path = $path;
	}

	/**
	 * Does this class recognises the CMS type as Wordpress?
	 *
	 * @return  boolean
	 */
	public function isRecognised()
	{
		if (!@file_exists($this->path . '/wp-config.php') && !@file_exists($this->path . '/../wp-config.php'))
		{
			return false;
		}

		if (!@file_exists($this->path . '/wp-login.php'))
		{
			return false;
		}

		if (!@file_exists($this->path . '/xmlrpc.php'))
		{
			return false;
		}

		if (!@is_dir($this->path . '/wp-admin'))
		{
			return false;
		}

		return true;
	}

	/**
	 * Return the name of the CMS / script (joomla)
	 *
	 * @return  string
	 */
	public function getName()
	{
		return 'wordpress';
	}

	/**
	 * Return the default installer name for this CMS / script (angie)
	 *
	 * @return  string
	 */
	public function getInstaller()
	{
		return 'angie-wordpress';
	}

	/**
	 * Return the database connection information for this CMS / script
	 *
	 * @return  array
	 */
	public function getDbInformation()
	{
		$ret = array(
			'driver'	=> 'mysqli',
			'host'		=> '',
			'port'		=> '',
			'username'	=> '',
			'password'	=> '',
			'name'		=> '',
			'prefix'	=> '',
		);

		$filePath = $this->path . '/wp-config.php';

		if (!@file_exists($filePath))
		{
			$filePath = $this->path . '/../wp-config.php';
		}

		$fileContents = file($filePath);

		foreach ($fileContents as $line)
		{
			$line = trim($line);

			if (strpos($line, 'define') === 0)
			{
				$line = substr($line, 6);
				$line = trim($line);
				$line = rtrim($line, ';');
				$line = trim($line);
				$line = trim($line, '()');
				list($key, $value) = explode(',', $line);
				$key = trim($key);
				$key = trim($key, "'\"");
				$value = trim($value);
				$value = trim($value, "'\"");

				switch (strtoupper($key))
				{
					case 'DB_NAME':
						$ret['name'] = $value;
						break;

					case 'DB_USER':
						$ret['username'] = $value;
						break;

					case 'DB_PASSWORD':
						$ret['password'] = $value;
						break;

					case 'DB_HOST':
						$ret['host'] = $value;
						break;

				}
			}
			elseif (strpos($line, '$table_prefix') === 0)
			{
				$parts = explode('=', $line, 2);
				$prefixData = trim($parts[1]);
				$prefixData = rtrim($prefixData, ';');
				$prefixData = trim($prefixData, "'\"");

				$ret['prefix'] = $prefixData;
			}
		}

		return $ret;
	}
} 