<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Solo\Pythia;


use Awf\Application\Application;
use Awf\Filesystem\File;

class Pythia
{
	protected $application = null;

	/**
	 * Public constructor
	 *
	 * @param   Application   $app  The application we are attached to
	 */
	function __construct($app = null)
	{
		if (is_null($app))
		{
			$app = Application::getInstance();
		}

		$this->application = $app;
	}

	/**
	 * Get information about the script installed under $path. Guessing classes ("oracles") try to figure out what
	 * kind of CMS/script is installed and its database settings.
	 *
	 * @param   string  $path  The path to scan
	 *
	 * @return  array
	 */
	public function getCmsInfo($path)
	{
		// Initialise
		$ret = array(
			'cms'		=> 'generic',
			'installer'	=> 'angie-generic',
			'database'	=> array(
				'driver'	=> 'mysqli',
				'host'		=> '',
				'port'		=> '',
				'username'	=> '',
				'password'	=> '',
				'name'		=> '',
				'prefix'	=> '',
			)
		);

		// Get a list of all the CMS guessing classes
		$dummy = array();
		$fs = new File($dummy);
		$files = $fs->directoryFiles(__DIR__ . '/Oracle', '.php');

		if (empty($files))
		{
			return $ret;
		}

		foreach ($files as $file)
		{
			$className = '\\Solo\\Pythia\\Oracle\\' . ucfirst(basename($file, '.php'));
			/** @var OracleInterface $o */
			$o = new $className($path);

			if ($o->isRecognised())
			{
				$ret['cms'] = $o->getName();
				$ret['installer'] = $o->getInstaller();
				$ret['database'] = $o->getDbInformation();

				return $ret;
			}
		}

		return $ret;
	}
} 