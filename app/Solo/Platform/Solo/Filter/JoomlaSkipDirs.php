<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU GPL version 3 or, at your option, any later version
 * @package akeebaengine
 *
 */

namespace Akeeba\Engine\Filter;

use Akeeba\Engine\Filter\Base as FilterBase;
use Akeeba\Engine\Factory;
use Akeeba\Engine\Platform;

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * Joomla!-specific Filter: Skip Directories
 *
 * Exclude subdirectories of special directories
 */
class JoomlaSkipDirs extends FilterBase
{	
	function __construct()
	{
		$this->object	= 'dir';
		$this->subtype	= 'children';
		$this->method	= 'direct';
		$this->filter_name = 'JoomlaSkipDirs';

		$configuration = Factory::getConfiguration();
		
		if ($configuration->get('akeeba.platform.scripttype', 'generic') !== 'joomla')
		{
			$this->enabled = false;
			return;
		}

		$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');

		$this->filter_data[$root] = array (
			// Output & temp directory of the application
			$this->treatDirectory($configuration->get('akeeba.basic.output_directory')),
			// default temp directory
			'tmp',
			// cache directories
			'cache',
			'administrator/cache',
			// This is not needed except on sites running SVN or beta releases
			'installation',
			// Default backup output for Akeeba Backup
			'administrator/components/com_akeeba/backup',
			// MyBlog's cache
			'components/libraries/cmslib/cache',
			// The logs directory
			'logs',
			'log'
		);
	}
}