<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\View\Discover;


use Akeeba\Engine\Factory;
use Awf\Uri\Uri;
use Awf\Utils\Template;

class Html extends \Solo\View\Html
{
	/**
	 * Push state variables before showing the main page
	 *
	 * @return  boolean
	 */
	public function onBeforeMain()
	{
		// Load the necessary Javascript
		Template::addJs('media://js/solo/configuration.js', $this->container->application);

		$model = $this->getModel();

		$directory = $model->getState('directory', '');

		if (empty($directory))
		{
			$config = Factory::getConfiguration();
			$this->directory = $config->get('akeeba.basic.output_directory', '[DEFAULT_OUTPUT]');
		}
		else
		{
			$this->directory = $directory;
		}

		return true;
	}

	/**
	 * Push state variables before showing the discovery page
	 *
	 * @return  boolean
	 */
	public function onBeforeDiscover()
	{
		$model = $this->getModel();

		$directory = $model->getState('directory','');
		$this->setLayout('discover');

		$files = $model->getFiles();

		$this->files = $files;
		$this->directory = $directory;

		return true;
	}
} 