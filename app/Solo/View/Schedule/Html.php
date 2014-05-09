<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Solo\View\Schedule;

class Html extends \Solo\View\Html
{
	public function onBeforeMain()
	{
		// Get profile ID
		$this->profileid = \AEPlatform::getInstance()->get_active_profile();

		// Get profile name
		$profileName = \AEPlatform::getInstance()->get_profile_name($this->profileid);

		// Get the CRON paths
		$this->croninfo  = $this->getModel()->getPaths();
		$this->checkinfo = $this->getModel()->getCheckPaths();

		return true;
	}
} 