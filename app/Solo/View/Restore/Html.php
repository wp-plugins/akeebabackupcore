<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\View\Restore;

use Akeeba\Engine\Factory;
use Awf\Mvc\View;
use Awf\Uri\Uri;
use Awf\Utils\Template;
use Solo\View\Html as BaseHtml;

class Html extends BaseHtml
{
	public function display($tpl = null)
	{
		Template::addJs('media://js/solo/encryption.js', $this->container->application);
		Template::addJs('media://js/solo/configuration.js', $this->container->application);
		Template::addJs('media://js/solo/restore.js', $this->container->application);

		return parent::display($tpl);
	}

	public function onBeforeMain()
	{
		/** @var \Solo\Model\Restore $model */
		$model = $this->getModel();

		$this->id				= $model->getState('id', 0);
		$this->ftpparams		= $model->getFTPParams();
		$this->extractionmodes	= $model->getExtractionModes();

		return true;
	}

	public function onBeforeStart()
	{
		/** @var \Solo\Model\Restore $model */
		$model = $this->getModel();

		$inCMS = $this->container->segment->get('insideCMS', false);

		$this->password = $model->getState('password');

		if ($inCMS)
		{
			$this->siteURL = $this->container->appConfig->get('cms_url', '');
		}
		else
		{
			$this->siteURL = Factory::getConfiguration()->get('akeeba.platform.site_url', '');
		}

		$this->siteURL = trim($this->siteURL);

		$this->setLayout('restore');

		return true;
	}

} 