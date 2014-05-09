<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Solo\Pythia\Oracle;

use Solo\Pythia\OracleInterface;

class Joomla implements OracleInterface
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
	 * Does this class recognises the CMS type as Joomla?
	 *
	 * @return  boolean
	 */
	public function isRecognised()
	{
		if (!@file_exists($this->path . '/configuration.php'))
		{
			return false;
		}

		if (!@is_dir($this->path . '/administrator/components/com_admin'))
		{
			return false;
		}

		if (!@is_dir($this->path . '/libraries/joomla'))
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
		return 'joomla';
	}

	/**
	 * Return the default installer name for this CMS / script (angie)
	 *
	 * @return  string
	 */
	public function getInstaller()
	{
		return 'angie';
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

		$fileContents = file($this->path . '/configuration.php');

		foreach ($fileContents as $line)
		{
			$line = trim($line);

			if ((strpos($line, 'public') === 0) || (strpos($line, 'var') === 0))
			{
				if (strpos($line, 'public') === 0)
				{
					$line = substr($line, 6);
				}
				else
				{
					$line = substr($line, 3);
				}
				$line = trim($line);
				$line = rtrim($line, ';');
				$line = ltrim($line, '$');
				$line = trim($line);
				list($key, $value) = explode('=', $line);
				$key = trim($key);
				$value = trim($value);

				if ((strstr($value, '"') === false) && (strstr($value, "'") === false))
				{
					continue;
				}

				$value = trim($value, "'\"");

				switch (strtoupper($key))
				{
					case 'DB':
						$ret['name'] = $value;
						break;

					case 'DBPREFIX':
						$ret['prefix'] = $value;
						break;

					case 'DBTYPE':
						$ret['driver'] = $value;
						break;

					case 'USER':
						$ret['username'] = $value;
						break;

					case 'PASSWORD':
						$ret['password'] = $value;
						break;

					case 'HOST':
						$ret['host'] = $value;
						break;

				}
			}
		}

		return $ret;
	}
} 