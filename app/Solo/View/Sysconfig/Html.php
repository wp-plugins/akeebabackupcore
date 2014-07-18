<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Solo\View\Sysconfig;


use Awf\Router\Router;

class Html extends \Solo\View\Html
{
	public function onBeforeMain()
	{
		$document = $this->container->application->getDocument();

		$buttons = array(
			array(
				'title' 	=> 'SOLO_BTN_SAVECLOSE',
				'class' 	=> 'btn-success',
				'onClick'	=> 'Solo.System.submitForm(\'adminForm\', \'save\')',
				'icon' 		=> 'glyphicon glyphicon-ok'
			),
			array(
				'title' 	=> 'SOLO_BTN_SAVE',
				'class'		=> 'btn-default',
				'onClick' 	=> 'Solo.System.submitForm(\'adminForm\', \'apply\')',
				'icon' 		=> 'glyphicon glyphicon-ok'
			),
			array(
				'title' 	=> 'SOLO_BTN_CANCEL',
				'class' 	=> 'btn-warning',
				'url' 		=> $this->container->router->route('index.php'),
				'icon' 		=> 'glyphicon glyphicon-remove'
			),
		);

		$toolbar = $document->getToolbar();

		foreach ($buttons as $button)
		{
			$toolbar->addButtonFromDefinition($button);
		}

		return true;
	}
}