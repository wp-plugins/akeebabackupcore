<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\Model\Json\Task;

use Akeeba\Engine\Factory;
use Akeeba\Engine\Platform;
use Awf\Application\Application;
use Solo\Model\Json\TaskInterface;

/**
 * Step through a backup job
 */
class StepBackup implements TaskInterface
{
	/**
	 * Return the JSON API task's name ("method" name). Remote clients will use it to call us.
	 *
	 * @return  string
	 */
	public function getMethodName()
	{
		return 'stepBackup';
	}

	/**
	 * Execute the JSON API task
	 *
	 * @param   array $parameters The parameters to this task
	 *
	 * @return  mixed
	 *
	 * @throws  \RuntimeException  In case of an error
	 */
	public function execute(array $parameters = array())
	{
		// Get the passed configuration values
		$defConfig = array(
			'profile' => null,
			'tag'     => AKEEBA_BACKUP_ORIGIN,
			'backupid' => null,
		);

		$defConfig = array_merge($defConfig, $parameters);

		$profile = $defConfig['profile'];
		$tag = $defConfig['tag'];
		$backupid = $defConfig['backupid'];

		$session = Application::getInstance()->getContainer()->segment;

		// Try to set the profile from the setup parameters
		if (!empty($profile))
		{
			$session->set('profile', $profile);
		}

		Factory::loadState($tag, $backupid);
		$kettenrad = Factory::getKettenrad();
		$kettenrad->setBackupId($backupid);

		$registry = Factory::getConfiguration();
		$session->set('profile', $registry->activeProfile);

		$array = $kettenrad->tick();
		$ret_array = $kettenrad->getStatusArray();
		$array['Progress'] = $ret_array['Progress'];
		Factory::saveState($tag, $backupid);

		if ($array['Error'] != '')
		{
			throw new \RuntimeException('A backup error had occurred: ' . $array['Error'], 500);
		}

		if ($array['HasRun'] == false)
		{
			Factory::nuke();
			Factory::getFactoryStorage()->reset();
		}

		return $array;
	}
}