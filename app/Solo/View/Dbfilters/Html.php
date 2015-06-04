<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Solo\View\Dbfilters;

use Akeeba\Engine\Platform;
use Awf\Html\Select;
use Awf\Router\Router;
use Awf\Text\Text;
use Awf\Uri\Uri;
use Awf\Utils\Template;

class Html extends \Solo\View\Html
{
	/**
	 * Prepare the view data for the main task
	 *
	 * @return  boolean
	 */
	public function onBeforeMain()
	{
		$model = $this->getModel();
		$task = $model->getState('browse_task', 'normal');

		$router = $this->container->router;

		// Add custom submenus
		$toolbar = $this->container->application->getDocument()->getToolbar();
		$toolbar->addSubmenuFromDefinition(array(
			'name'	=> 'normal',
			'title'	=> Text::_('FILTERS_LABEL_NORMALVIEW'),
			'url'	=> $router->route('index.php?view=dbfilters&task=normal'),
		));
		$toolbar->addSubmenuFromDefinition(array(
			'name'	=> 'tabular',
			'title'	=> Text::_('FILTERS_LABEL_TABULARVIEW'),
			'url'	=> $router->route('index.php?view=dbfilters&task=tabular'),
		));

		// Get a JSON representation of the available roots
		/** @var \Solo\Model\Dbfilters $model */
		$model = $this->getModel();
		$root_info = $model->get_roots();
		$roots = array();
		$options = array();

		if(!empty($root_info))
		{
			// Loop all db definitions
			foreach($root_info as $def)
			{
				$roots[] = $def->value;
				$options[] = Select::option($def->value, $def->text );
			}
		}

		$site_root = $roots[0];
		$attributes = 'onchange="Solo.Dbfilters.activeRootChanged();"';
		$this->root_select = Select::genericList($options, 'root', $attributes, 'value', 'text', $site_root, 'active_root');
		$this->roots = $roots;

		switch($task)
		{
			case 'normal':
			default:
				$this->setLayout('default');

				// Get a JSON representation of the directory data
				$model = $this->getModel();
				$json = json_encode($model->make_listing($site_root));
				$this->json = $json;
				break;

			case 'tabular':
				$this->setLayout('tabular');

				// Get a JSON representation of the tabular filter data
				$model = $this->getModel();
				$json = json_encode( $model->get_filters($site_root) );
				$this->json = $json;

				break;
		}

		// Get profile ID
		$profileid = Platform::getInstance()->get_active_profile();
		$this->profileid = $profileid;

		// Get profile name
		$this->profilename = $this->escape(Platform::getInstance()->get_profile_name($profileid));

		// Load additional Javascript
		Template::addJs('media://js/solo/fsfilters.js', $this->container->application);
		Template::addJs('media://js/solo/dbfilters.js', $this->container->application);

		return true;
	}

	/**
	 * The normal task simply calls the method for the main task
	 *
	 * @return  boolean
	 */
	public function onBeforeNormal()
	{
		return $this->onBeforeMain();
	}

	/**
	 * The tabular task simply calls the method for the main task
	 *
	 * @return  boolean
	 */
	public function onBeforeTabular()
	{
		return $this->onBeforeMain();
	}
}