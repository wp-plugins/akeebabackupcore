<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\View\Alice;

use Akeeba\Engine\Platform;
use Awf\Uri\Uri;
use Awf\Utils\Template;
use Solo\Model\Log;

class Html extends \Solo\View\Html
{
	/**
	 * Setup the main log page
	 *
	 * @return  boolean
	 */
	public function onBeforeMain()
	{
        // Load the necessary Javascript
		Template::addJs('media://js/piecon.js', $this->container->application);
		Template::addJs('media://js/solo/stepper.js', $this->container->application);
		Template::addJs('media://js/solo/alice.js', $this->container->application);

		$model = new Log();
		$this->logs = $model->getLogList();

		$tag = $model->getState('tag');

		if (empty($tag))
		{
			$tag = null;
		}

		$this->tag = $tag;

		// Get profile ID and name
		$this->profileid   = Platform::getInstance()->get_active_profile();;
		$this->profilename = $this->escape(Platform::getInstance()->get_profile_name($this->profileid));

		return true;
	}
}