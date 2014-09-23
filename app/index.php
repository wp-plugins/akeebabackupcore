<?php
/**
 * @package     Solo
 * @copyright   2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

use Awf\Application\Application;
use Awf\Autoloader\Autoloader;
use Awf\Session;

// Makes sure we have PHP 5.3.4 or later
if (version_compare(PHP_VERSION, '5.3.4', 'lt'))
{
	die(sprintf('Akeeba Solo requires PHP 5.3.4 or later but your server only has PHP %s.', PHP_VERSION));
}

// Include the autoloader
if (false == include __DIR__ . '/Awf/Autoloader/Autoloader.php')
{
	echo 'ERROR: Autoloader not found' . PHP_EOL;

	exit(1);
}

// Load the integration script
define('AKEEBASOLO', 1);
$dirParts = isset($_SERVER['SCRIPT_FILENAME']) ? explode(DIRECTORY_SEPARATOR, $_SERVER['SCRIPT_FILENAME']) : array();

if (count($dirParts) > 3)
{
	$dirParts = array_splice($dirParts, 0, -2);
	$myDir = implode(DIRECTORY_SEPARATOR, $dirParts);
}

if (@file_exists(__DIR__ . '/../../helpers/integration.php'))
{
	require_once __DIR__ . '/../../helpers/integration.php';
}
elseif (@file_exists('../../helpers/integration.php'))
{
	require_once '../../helpers/integration.php';
}
elseif (@file_exists($myDir . '/helpers/integration.php'))
{
	require_once $myDir . '/helpers/integration.php';
}

// Load the platform defines
if (!defined('APATH_BASE'))
{
	require_once __DIR__ . '/defines.php';
}

// Should I enable debug?
if (defined('AKEEBADEBUG'))
{
	error_reporting(E_ALL | E_NOTICE | E_DEPRECATED);
	ini_set('display_errors', 1);
}

// Add our app to the autoloader, if it's not already set
$prefixes = Autoloader::getInstance()->getPrefixes();
if (!array_key_exists('Solo\\', $prefixes))
{
	Autoloader::getInstance()->addMap('Solo\\', APATH_BASE . '/Solo');
}

// Include the Akeeba Engine factory
if (!defined('AKEEBAENGINE'))
{
	define('AKEEBAENGINE', 1);
	require_once __DIR__ . '/Solo/engine/factory.php';

	if(file_exists(__DIR__.'/Solo/alice/factory.php'))
	{
		require_once __DIR__ . '/Solo/alice/factory.php';
	}

	\AEPlatform::getInstance()->load_version_defines();
	\AEPlatform::getInstance()->apply_quirk_definitions();
}

try
{
	// Create the container if it doesn't already exist
	if (!isset($container))
	{
		$container = new \Solo\Container(array(
			'application_name'	=> 'Solo'
		));
	}

	// Create the application
	$application = $container->application;

	// Initialise the application
	$application->initialise();

	// Route the URL: parses the URL through routing rules, replacing the data in the app's input
	$application->route();

	// Dispatch the application
	$application->dispatch();

	// Render the output
	$application->render();

	// Clean-up and shut down
	$application->close();
}
catch (Exception $exc)
{
	$filename = null;

	if (isset($application))
	{
		if ($application instanceof \Awf\Application\Application)
		{
			$template = $application->getTemplate();

			if (file_exists(APATH_THEMES . '/' . $template . '/error.php'))
			{
				$filename = APATH_THEMES . '/' . $template . '/error.php';
			}
		}
	}

	if (is_null($filename))
	{
		die($exc->getMessage());
	}

	// An uncaught application error occurred
	/**
	echo "<h1>Application Error</h1>\n";
	echo "<p>Please submit the following error message and trace in its entirety when requesting support</p>\n";
	echo "<div class=\"alert alert-danger\">" . get_class($exc) . ' &mdash; ' . $exc->getMessage() . "</div>\n";
	echo "<pre class=\"well\">\n";
	echo $exc->getTraceAsString();
	echo "</pre>\n";
	/**/

	include $filename;
}