<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\View\Configuration;

use Awf\Utils\Template;
use Solo\Application;
use Solo\Helper\Escape;
use Awf\Uri\Uri;

/**
 * The view class for the Configuration view
 */
class Html extends \Solo\View\Html
{
	public function onBeforeMain()
	{
		$document = $this->container->application->getDocument();

		// Load the necessary Javascript
		Template::addJs('media://js/solo/configuration.js', $this->container->application);

		// Push configuration in JSON format
		$this->json = \Solo\Helper\Escape::escapeJS(\AEUtilInihelper::getJsonGuiDefinition(), '"\\');

		// Push the profile's numeric ID
		$this->profileId = \AEPlatform::getInstance()->get_active_profile();

		// Push the profile name
		$this->profileName = \AEPlatform::getInstance()->get_profile_name($this->profileId);

		// Are the settings secured?
		if (\AEPlatform::getInstance()->get_platform_configuration_option('useencryption', -1) == 0)
		{
			$this->secureSettings = -1;
		}
		elseif (!\AEUtilSecuresettings::supportsEncryption())
		{
			$this->secureSettings = 0;
		}
		else
		{
			$filename = $this->container->basePath . Application::secretKeyRelativePath;

			if (@file_exists($filename))
			{
				$this->secureSettings = 1;
			}
			else
			{
				$this->secureSettings = 0;
			}
		}

		// Push the media folder name @todo Do we really use it?
		$media_folder = URI::base(false, $this->container) . '/media/';
		$this->mediadir = Escape::escapeJS($media_folder . 'theme/');

		// Append buttons to the toolbar
		$buttons = array(
			array(
				'title' => 'SOLO_BTN_SAVECLOSE',
				'class' => 'btn-success',
				'onClick' => 'Solo.System.submitForm(\'adminForm\', \'save\')',
				'icon' => 'glyphicon glyphicon-floppy-save'
			),
			array(
				'title' => 'SOLO_BTN_SAVE',
				'class' => 'btn-default',
				'onClick' => 'Solo.System.submitForm(\'adminForm\', \'apply\')',
				'icon' => 'glyphicon glyphicon-ok'
			),
			array(
				'title' => 'SOLO_BTN_CANCEL',
				'class' => 'btn-warning',
				'onClick' => 'Solo.System.submitForm(\'adminForm\', \'cancel\')',
				'icon' => 'glyphicon glyphicon-remove'
			),
		);


		$toolbar = $document->getToolbar();
		foreach ($buttons as $button)
		{
			$toolbar->addButtonFromDefinition($button);
		}

		// All done, show the page!
		return true;
	}
}