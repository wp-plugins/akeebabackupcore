<?php
/**
 * @package     Solo
 * @copyright   2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace Solo\Controller;

use Awf\Database\Installer;
use Awf\Mvc\Model;
use Awf\Router\Router;
use Awf\Text\Text;
use Solo\Model\Update;

class Main extends ControllerDefault
{
	public function onBeforeDefault()
	{
		// If we are running inside a CMS but there is no active user we have to throw a 403
		$inCMS = $this->container->segment->get('insideCMS', false);

		if ($inCMS && !$this->container->userManager->getUser()->getId())
		{
			return false;
		}

		// Update the database, if necessary
		$dbInstaller = new Installer($this->container);
		$dbInstaller->updateSchema();

		/** @var \Solo\Model\Main $model */
		$model = $this->getModel();

		// Run the update scripts, if necessary
		if ($model->postUpgradeActions())
		{
			$url = $this->container->router->route('index.php?view=main');
			$this->container->application->redirect($url);
		}

		// Apply settings encryption preferences
		$model->checkEngineSettingsEncryption();

		// Update magic configuration parameters
		$model->updateMagicParameters();

		// Flag stuck backups
		$model->flagStuckBackups();

		return true;
	}

	public function switchProfile()
	{
		$this->csrfProtection();

		// Switch the active profile
		$session = \Awf\Application\Application::getInstance()->getContainer()->segment;
		$session->profile = $this->input->getInt('profile', 1);

		// Redirect
		$url = $this->container->router->route('index.php?view=main');

		$returnURL = $this->input->get('returnurl', '', 'raw');
		if (!empty($returnURL))
		{
			$url = base64_decode($returnURL);
		}

		$this->setRedirect($url);

		return true;
	}

	public function getUpdateInformation()
	{
		// Protect against direct access
		$this->csrfProtection();

		// Initialise
		$ret = array(
			'hasUpdate'		=> false,
			'version'		=> '',
			'noticeHTML'	=> '',
		);

		// Am I running inside a CMS?
		$inCMS = $this->container->segment->get('insideCMS', false);

		if (!$inCMS || (defined('AKEEBA_PRO') && AKEEBA_PRO))
		{
			/** @var Update $updateModel */
			$updateModel = Model::getTmpInstance($this->container->application_name, 'Update', $this->container);
			$ret['hasUpdate'] = $updateModel->getUpdateInformation()->get('hasUpdate', false);
			$ret['version'] = $updateModel->getUpdateInformation()->get('version', 'dev');
		}

		if ($ret['hasUpdate'])
		{
			$router = $this->container->router;
			$updateHeader = Text::sprintf('SOLO_UPDATE_LBL_MAINNOTICE_TEXT', '<span class="label label-success">' . $ret['version'] . '</span>');
			$updateButton = Text::_('SOLO_UPDATE_BTN_UPDATE_NOW');
			$updateLink = $router->route('index.php?view=update');
			$ret['noticeHTML'] = <<< HTML
<div class="alert alert-dismissable alert-warning">
	<p>
		$updateHeader
	</p>
	<p style="text-align: center">
		<a href="$updateLink" class="btn btn-lg btn-primary">
			<span class="glyphicon glyphicon-retweet"></span>
			$updateButton
		</a>
	</p>
</div>
HTML;
		}

		echo '###' . json_encode($ret) . '###';
		$this->container->application->close();
	}
}