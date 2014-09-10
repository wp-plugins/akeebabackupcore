<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\View\Backup;

use Awf\Date\Date;
use Awf\Mvc\Model;
use Awf\Text\Text;
use Awf\Uri\Uri;
use Awf\Utils\Template;
use Solo\Helper\Escape;

/**
 * The view class for the Backup view
 */
class Html extends \Solo\View\Html
{
	public function onBeforeMain()
	{
		// Load the necessary Javascript
		Template::addJs('media://js/piecon.js', $this->container->application);
		Template::addJs('media://js/solo/backup.js', $this->container->application);

		/** @var \Solo\Model\Backup $model */
		$model = $this->getModel();

		// Get the backup description and comment
		$tz = $this->container->appConfig->get('timezone', 'UTC');
		$user = $this->container->userManager->getUser();
		$user_tz = $user->getParameters()->get('timezone', null);

		if (!empty($user_tz))
		{
			$tz = $user_tz;
		}

		$date = new Date('now', $tz);

		$default_description = Text::_('BACKUP_DEFAULT_DESCRIPTION') . ' ' . $date->format(Text::_('DATE_FORMAT_LC2'), true);

		$this->default_descr = Escape::escapeJS($default_description);
		$this->description   = Escape::escapeJS($model->getState('description', $default_description));
		$this->comment       = $model->getState('comment', '');

		// Push the return URL
		$returnURL = $model->getState('returnurl', '');
		$this->returnURL = empty($returnURL) ? '' : $returnURL;

		// Push the profile ID and name
		$this->profileId = \AEPlatform::getInstance()->get_active_profile();
		$this->profileName = \AEPlatform::getInstance()->get_profile_name($this->profileId);

		// If a return URL is set *and* the profile's name is "Site Transfer
		// Wizard", we are running the Site Transfer Wizard
		$this->isSTW = ($this->profileName == 'Site Transfer Wizard (do not rename)') && !empty($this->returnURL);

		// Get the domain details from scripting facility
		$config = \AEFactory::getConfiguration();
		$script = $config->get('akeeba.basic.backup_type', 'full');
		$scripting = \AEUtilScripting::loadScripting();
		$domains = array();

		if (!empty($scripting))
		{
			foreach ($scripting['scripts'][$script]['chain'] as $domain)
			{
				$description = Text::_($scripting['domains'][$domain]['text']);
				$domain_key = $scripting['domains'][$domain]['domain'];

				if ($this->isSTW && ($domain_key == 'Packing'))
				{
					$description = Text::_('BACKUP_LABEL_DOMAIN_PACKING_STW');
				}

				$domains[] = array($domain_key, $description);
			}
		}

		$this->domains = Escape::escapeJS(json_encode($domains), '"\\');

		// Push some engine parameters
		$this->maxexec = $config->get('akeeba.tuning.max_exec_time', 14) * 1000;
		$this->bias = $config->get('akeeba.tuning.run_time_bias', 75);
		$this->useIframe = $config->get('akeeba.basic.useiframe', 0) ? 'true' : 'false';

		if (AKEEBA_PRO && $config->get('akeeba.advanced.archiver_engine', 'jpa') == 'jps')
		{
			$this->showJPSKey = 1;
			$this->jpsKey = $config->get('engine.archiver.jps.key', '');
		}
		else
		{
			$this->showJPSKey = 0;
			$this->jpsKey = '';
		}

		if (AKEEBA_PRO)
		{
			$this->showANGIEKey = 1;
			$this->angieKey = $config->get('engine.installer.angie.key', '');
		}
		else
		{
			$this->showANGIEKey = 0;
			$this->angieKey = '';
		}

		$this->autoStart = $model->getState('autostart', 0);

		$this->srpInfo = $model->getState('srpinfo', array());

		// Check if the output directory is writable
		$quirks = \AEUtilQuirks::get_quirks(true);
		$this->unwritableOutput = array_key_exists('001', $quirks);
		$this->hasQuirks = !empty($quirks);
		$this->hasErrors = false;
		$this->hasCriticalErrors = false;
		$this->quirks = $quirks;

		if (!empty($quirks))
		{
			foreach ($quirks as $quirk)
			{
				if ($quirk['severity'] == 'high')
				{
					$this->hasErrors = true;
				}
				elseif ($quirk['severity'] == 'critical')
				{
					$this->hasErrors = true;
					$this->hasCriticalErrors = true;
				}
			}
		}

		// Set the toolbar title
		if (isset($this->srpInfo['tag']) && $this->srpInfo['tag'] == 'restorepoint')
		{
			$this->subtitle = Text::_('AKEEBASRP');
		}
		elseif ($this->isSTW)
		{
			$this->subtitle = Text::_('SITETRANSFERWIZARD');
		}
		else
		{
			$this->subtitle = Text::_('BACKUP');
		}

		// Push the list of profiles
		$cpanelModel = Model::getInstance($this->container->application_name, 'Main', $this->container);
		$this->profileList = $cpanelModel->getProfileList();

		if (!$this->hasCriticalErrors)
		{
			$this->container->application->getDocument()->getMenu()->disableMenu('main');
		}

		// All done, show the page!
		return true;
	}
}