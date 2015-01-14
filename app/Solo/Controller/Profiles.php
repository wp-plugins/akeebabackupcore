<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\Controller;

use Awf\Text\Text;
use Awf\Mvc\DataModel;
use Awf\Router\Router;

class Profiles extends DataControllerDefault
{
	/**
	 * Imports an exported profile .json file
	 *
	 * @return  void
	 */
	public function import()
	{
		// CSRF prevention
		$this->csrfProtection();

		// Get the reference to the uploaded file
		$file = $_FILES['importfile'];

		// Get a URL router
		$router = $this->container->router;

		if (isset($file['name']))
		{
			// Load the file data
			$data = file_get_contents($file['tmp_name']);
			@unlink($file['tmp_name']);

			// JSON decode
			$data = json_decode($data, true);

			// Check for data validity
			$isValid = is_array($data) && !empty($data);

			if ($isValid)
			{
				$isValid = $isValid && array_key_exists('description', $data);
			}

			if ($isValid)
			{
				$isValid = $isValid && array_key_exists('configuration', $data);
			}

			if ($isValid)
			{
				$isValid = $isValid && array_key_exists('filters', $data);
			}

			if (!$isValid)
			{
				$this->setRedirect($router->route('index.php?view=profiles'), Text::_('COM_AKEEBA_PROFILES_ERR_IMPORT_INVALID'), 'error');

				return;
			}

			// Unset the id, if it exists
			if (array_key_exists('id', $data))
			{
				unset($data['id']);
			}

			// Try saving the profile
			/** @var DataModel $model */
			$model = $this->getModel();
			$result = $model->create($data);

			if ($result)
			{
				$this->setRedirect($router->route('index.php?view=profiles'), Text::_('COM_AKEEBA_PROFILES_MSG_IMPORT_COMPLETE'));
			}
			else
			{
				$this->setRedirect($router->route('index.php?view=profiles'), Text::_('COM_AKEEBA_PROFILES_ERR_IMPORT_FAILED'), 'error');
			}
		}
		else
		{
			$this->setRedirect($router->route('index.php?view=profiles'), Text::_('MSG_UPLOAD_INVALID_REQUEST'), 'error');
		}

	}
}