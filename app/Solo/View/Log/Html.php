<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\View\Log;


use Akeeba\Engine\Platform;
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
		/** @var Log $model */
		$model = $this->getModel();
		$this->logs = $model->getLogList();

		$tag = $model->getState('tag');

		if (empty($tag))
		{
			$tag = null;
		}

		$this->tag = $tag;

		// Get profile ID and name
		$this->profileid = Platform::getInstance()->get_active_profile();;
		$this->profilename = $this->escape(Platform::getInstance()->get_profile_name($this->profileid));

		return true;
	}

	/**
	 * Setup the iframe display
	 *
	 * @return  boolean
	 */
	public function onBeforeIframe()
	{
		/** @var Log $model */
		$model = $this->getModel();
		$tag = $model->getState('tag');

		if (empty($tag))
		{
			$tag = null;
		}

		$this->tag = $tag;

		return true;
	}
} 