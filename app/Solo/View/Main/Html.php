<?php
/**
 * @package     Solo
 * @copyright   2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace Solo\View\Main;

use Awf\Mvc\Model;
use Awf\Mvc\View;
use Solo\Model\Main;
use Solo\Model\Update;

class Html extends \Solo\View\Html
{
	public function onBeforeMain()
	{
		/** @var Main $model */
		$model = $this->getModel();

		$this->profile = \AEPlatform::getInstance()->get_active_profile();
		$this->profileList = $model->getProfileList();
		$this->latestBackupDetails = $model->getLatestBackupDetails();

		if (!$this->container->segment->get('insideCMS', false))
		{
			$this->configUrl = $model->getConfigUrl();
		}
		$this->backupUrl = $model->getBackupUrl();

		$this->needsDownloadId = $model->needsDownloadID();
		$this->warnCoreDownloadId = $model->mustWarnAboutDownloadIdInCore();

        /** @var Stats $statsModel */
        $statsModel = Model::getTmpInstance($this->container->application_name, 'Stats', $this->container);
        $this->statsIframe = $statsModel->collectStatistics(true);

		return true;
	}
}