<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebabackupwp
 *
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * Directory exclusion filter
 */
class AEFilterPlatformDirectories extends AEAbstractFilter
{
	function __construct()
	{
		$this->object = 'dir';
		$this->subtype = 'all';
		$this->method = 'direct';
		$this->filter_name = 'PlatformDirectories';

		if (AEFactory::getKettenrad()->getTag() == 'restorepoint')
		{
			$this->enabled = false;
		}

		// We take advantage of the filter class magic to inject our custom filters
		$configuration = AEFactory::getConfiguration();

		// Get the site's root
		if ($configuration->get('akeeba.platform.override_root', 0))
		{
			$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');
		}
		else
		{
			$root = '[SITEROOT]';
		}

		$this->filter_data[$root] = array(
			// Output & temp directory of the component
			self::treatDirectory($configuration->get('akeeba.basic.output_directory')),
			// This folder would collide with the installer
			'installation',
			self::treatDirectory(ABSPATH . '/installation'),
			// Default backup output directory
			self::treatDirectory(APATH_BASE . '/backups'),
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