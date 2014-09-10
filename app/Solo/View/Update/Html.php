<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Solo\View\Update;

use Awf\Utils\Template;
use Solo\Model\Update;

class Html extends \Solo\View\Html
{
	public function display($tpl = null)
	{
		Template::addJs('media://js/piecon.js', $this->container->application);
		Template::addJs('media://js/solo/encryption.js', $this->container->application);
		Template::addJs('media://js/solo/update.js', $this->container->application);

		return parent::display($tpl);
	}

	public function onBeforeMain()
	{
		/** @var Update $model */
		$model = $this->getModel();

		$this->updateInfo = $model->getUpdateInformation();
		$this->needsDownloadId = $this->getModel('Main')->needsDownloadID();

		if ($this->updateInfo->get('stuck', 0))
		{
			$this->layout = 'stuck';
		}

		return true;
	}
}