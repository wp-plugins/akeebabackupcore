<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\Controller;

use Awf\Router\Router;
use Awf\Text\Text;

/**
 * The Controller for the Configuration view
 */
class Configuration extends ControllerDefault
{
	/**
	 * Handle the apply task which saves settings and shows the editor again
	 *
	 * @return  void
	 */
	public function apply()
	{
		// CSRF prevention
		$this->csrfProtection();

		// Get the var array from the request
		$data = $this->input->get('var', array(), 'array');

		/** @var \Solo\Model\Configuration $model */
		$model = $this->getModel();
		$model->setState('engineconfig', $data);
		$model->saveEngineConfig();

		$router = $this->container->router;

		$this->setRedirect($router->route('index.php?view=configuration'), Text::_('CONFIG_SAVE_OK'));
	}

	/**
	 * Handle the save task which saves settings and returns to the main page
	 *
	 * @return  void
	 */
	public function save()
	{
		$this->apply();

		$router = $this->container->router;

		$this->setRedirect($router->route('index.php?view=main'), Text::_('CONFIG_SAVE_OK'));
	}

	/**
	 * Handle the cancel task which doesn't save anything and returns to the cpanel
	 *
	 * @return  void
	 */
	public function cancel()
	{
		$this->csrfProtection();

		$router = $this->container->router;
		$this->setRedirect($router->route('index.php?view=main'));
	}

	/**
	 * Tests the validity of the FTP connection details
	 *
	 * @return  void
	 */
	public function testftp()
	{
		/** @var \Solo\Model\Configuration $model */
		$model = $this->getModel();

		$model->setState('host', $this->input->get('host', '', 'raw'));
		$model->setState('port', $this->input->get('port', 21, 'int'));
		$model->setState('user', $this->input->get('user', '', 'raw'));
		$model->setState('pass', $this->input->get('pass', '', 'raw'));
		$model->setState('initdir', $this->input->get('initdir', '', 'raw'));
		$model->setState('usessl', $this->input->get('usessl', '', 'raw') == 'true');
		$model->setState('passive', $this->input->get('passive', '', 'raw') == 'true');

		@ob_end_clean();

		echo '###' . json_encode($model->testFTP()) . '###';

		flush();
		$this->container->application->close();
	}

	/**
	 * Tests the validity of the SFTP connection details
	 *
	 * @return  void
	 */
	public function testsftp()
	{
		/** @var \Solo\Model\Configuration $model */
		$model = $this->getModel();

		$model->setState('host', $this->input->get('host', '', 'raw'));
		$model->setState('port', $this->input->get('port', 21, 'int'));
		$model->setState('user', $this->input->get('user', '', 'raw'));
		$model->setState('pass', $this->input->get('pass', '', 'raw'));
		$model->setState('privkey', $this->input->get('privkey', '', 'raw'));
		$model->setState('pubkey', $this->input->get('pubkey', '', 'raw'));
		$model->setState('initdir', $this->input->get('initdir', '', 'raw'));

		@ob_end_clean();

		echo '###' . json_encode($model->testSFTP()) . '###';

		flush();
		$this->container->application->close();
	}

	/**
	 * Opens an OAuth window for the selected data processing engine
	 *
	 * @return  void
	 */
	public function dpeoauthopen()
	{
		/** @var \Solo\Model\Configuration $model */
		$model = $this->getModel();

		$model->setState('engine', $this->input->get('engine', '', 'raw'));
		$model->setState('params', $this->input->get('params', array(), 'array'));

		@ob_end_clean();

		$model->dpeOuthOpen();

		flush();
		$this->container->application->close();
	}

	/**
	 * Runs a custom API call against the selected data processing engine
	 *
	 * @return  void
	 */
	public function dpecustomapi()
	{
		/** @var \Solo\Model\Configuration $model */
		$model = $this->getModel();

		$model->setState('engine', $this->input->get('engine', '', 'raw'));
		$model->setState('method', $this->input->getVar('method', '', 'raw'));
		$model->setState('params', $this->input->get('params', array(), 'array'));

		@ob_end_clean();

		echo '###' . json_encode($model->dpeCustomAPICall()) . '###';

		flush();

		$this->container->application->close();
	}
} 