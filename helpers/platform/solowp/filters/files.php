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

/**
 * Subdirectories exclusion filter. Excludes temporary, cache and backup output
 * directories' contents from being backed up.
 */
class AEFilterPlatformFiles extends AEAbstractFilter
{
	public function __construct()
	{
		$this->object = 'file';
		$this->subtype = 'all';
		$this->method = 'direct';
		$this->filter_name = 'PlatformFiles';

		if (AEFactory::getKettenrad()->getTag() == 'restorepoint')
		{
			$this->enabled = false;
		}

		// Get the site's root
		$configuration = AEFactory::getConfiguration();

		if ($configuration->get('akeeba.platform.override_root', 0))
		{
			$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');
		}
		else
		{
			$root = '[SITEROOT]';
		}

		// We take advantage of the filter class magic to inject our custom filters
		$this->filter_data[$root] = array(
			'kickstart.php',
			'error_log',
			'wp-admin/error_log',
			self::treatDirectory(APATH_BASE) . '/restoration.php',
		);

		parent::__construct();
	}

	private static function treatDirectory($directory)
	{
		// Get the site's root
		$configuration = AEFactory::getConfiguration();

		if ($configuration->get('akeeba.platform.override_root', 0))
		{
			$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');
		}
		else
		{
			$root = '[SITEROOT]';
		}

		if (stristr($root, '['))
		{
			$root = AEUtilFilesystem::translateStockDirs($root);
		}
		$site_root = AEUtilFilesystem::TrimTrailingSlash(AEUtilFilesystem::TranslateWinPath($root));

		$directory = AEUtilFilesystem::TrimTrailingSlash(AEUtilFilesystem::TranslateWinPath($directory));

		// Trim site root from beginning of directory
		if (substr($directory, 0, strlen($site_root)) == $site_root)
		{
			$directory = substr($directory, strlen($site_root));
			if (substr($directory, 0, 1) == '/')
			{
				$directory = substr($directory, 1);
			}
		}

		return $directory;
	}
}