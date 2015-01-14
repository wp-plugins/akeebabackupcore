<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\View\Wizard;

use Awf\Utils\Template;
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
		Template::addJs('media://js/solo/wizard.js', $this->container->application);

		// Append buttons to the toolbar
		$buttons = array(
			array(
				'title' => 'SOLO_BTN_SUBMIT',
				'class' => 'btn-success',
				'onClick' => 'document.forms.adminForm.submit(); return false;',
				'icon' => 'glyphicon glyphicon-floppy-save'
			),
		);


		$toolbar = $document->getToolbar();
		foreach ($buttons as $button)
		{
			$toolbar->addButtonFromDefinition($button);
		}

		// Get the site URL and root directory
		$this->siteInfo = $this->getModel()->guessSiteParams();

		// All done, show the page!
		return true;
	}

	public function onBeforeWizard()
	{
		$document = $this->container->application->getDocument();

		// Load the necessary Javascript
		Template::addJs('media://js/piecon.js', $this->container->application);
		Template::addJs('media://js/solo/backup.js', $this->container->application);
		Template::addJs('media://js/solo/wizard.js', $this->container->application);

		// All done, show the page!
		return true;
	}
}