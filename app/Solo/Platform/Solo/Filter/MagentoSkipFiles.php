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
 * Magento specific Filter: Skip Directories
 *
 * Exclude subdirectories of special directories
 */
class MagentoSkipFiles extends FilterBase
{
	function __construct()
	{
		$this->object	= 'dir';
		$this->subtype	= 'content';
		$this->method	= 'direct';
		$this->filter_name = 'MagentoSkipFiles';

		$configuration = Factory::getConfiguration();

		if ($configuration->get('akeeba.platform.scripttype', 'generic') !== 'magento')
		{
			$this->enabled = false;

			return;
		}

		$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');

		// Exclude directories
		$this->filter_data[$root] = array (
			// catalog images cache
			'media/catalog/product/cache',
			// images ready to be imported
			'media/import',
			// exported images
			'media/export',
			// built-in magento backups
			'var/backups',
			// temp directory
			'var/tmp',
			// cache directory
			'var/cache',
			// session directory
			'var/session',
			// The logs directory
			'var/log',
			// Magento Connect tmp folder
			'var/package/tmp'
		);
	}
}