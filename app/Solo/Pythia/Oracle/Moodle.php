<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license		GNU GPL version 3 or later
 */

namespace Solo\Pythia\Oracle;

use Solo\Pythia\OracleInterface;

class Moodle implements OracleInterface
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
	 * Does this class recognises the site as a Moodle installation?
	 *
	 * @return  boolean
	 */
	public function isRecognised()
	{
		if (!@file_exists($this->path . '/config.php'))
		{
			return false;
		}

		if (!@file_exists($this->path . '/version.php'))
		{
			return false;
		}

		if (!@is_dir($this->path . '/repository'))
		{
			return false;
		}

		if (!@is_dir($this->path . '/userpix'))
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
		return 'moodle';
	}

	/**
	 * Return the default installer name for this CMS / script (angie)
	 *
	 * @return  string
	 */
	public function getInstaller()
	{
		return 'angie-moodle';
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

		$filePath = $this->path . '/config.php';

		$fileContents = file($filePath);

		foreach ($fileContents as $line)
		{
			$line = trim($line);

			if (strpos($line, '$CFG->') === 0)
			{
				$line = trim(substr($line, 6));
				$line = trim(rtrim($line, ';'));
				list($key, $value) = explode('=', $line);
				$key = trim($key);
				$key = trim($key, "'\"");
				$value = trim($value);
				$value = trim($value, "'\"");

				switch (strtolower($key))
				{
					case 'dbname':
						$ret['name'] = $value;
						break;

					case 'dbuser':
						$ret['username'] = $value;
						break;

					case 'dbpass':
						$ret['password'] = $value;
						break;

					case 'dbhost':
						$ret['host'] = $value;
						break;

					case 'prefix':
						$ret['prefix'] = $value;
						break;

				}
			}
		}

		return $ret;
	}

	public function getExtradirs()
	{
		$ret      = array();
		$filePath = $this->path . '/config.php';

		$fileContents = file($filePath);

		foreach ($fileContents as $line)
		{
			$line = trim($line);

			if (strpos($line, '$CFG->') === 0)
			{
				$line = trim(substr($line, 6));
				$line = trim(rtrim($line, ';'));
				list($key, $value) = explode('=', $line);
				$key = trim($key);
				$key = trim($key, "'\"");
				$value = trim($value);
				$value = trim($value, "'\"");

				switch (strtolower($key))
				{
					case 'dataroot':
						$ret[] = $value;
						// I'm interested in the dataroot folder only
						break 2;
				}
			}
		}

		if(!$ret)
		{
			return $ret;
		}

		// Ok, I have the extra directory, now let's add it to the extra-site folders
		/** @var \Solo\Model\Extradirs $extradirs */
		$extradirs = \Awf\Mvc\Model::getTmpInstance(null, 'Extradirs');
		$extradirs->setFilter('moodledata', array($ret[0],'moodledata'));

		return $ret;
	}
}