<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\Model\Json\Task;

use Akeeba\Engine\Factory;
use Akeeba\Engine\Platform;
use Awf\Date\Date;
use Awf\Text\Text;
use Awf\Application\Application;
use Solo\Model\Json\TaskInterface;

/**
 * Start a backup job
 */
class StartBackup implements TaskInterface
{
	/**
	 * Return the JSON API task's name ("method" name). Remote clients will use it to call us.
	 *
	 * @return  string
	 */
	public function getMethodName()
	{
		return 'startBackup';
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
			'profile'     => 1,
			'description' => '',
			'comment'     => '',
			'backupid'    => null,
		);

		$defConfig = array_merge($defConfig, $parameters);

		$profile = $defConfig['profile'];
		$description = $defConfig['description'];
		$comment = $defConfig['comment'];
		$backupid = $defConfig['backupid'];

		// Nuke the factory
		Factory::nuke();

		// Set the profile. Note: $profile is set by extract($config) above.
		$profile = (int)$profile;

		if (!is_numeric($profile))
		{
			$profile = 1;
		}

		if (strtoupper($backupid) == '[DEFAULT]')
		{
			$db = Application::getInstance()->getContainer()->db;
			$query = $db->getQuery(true)
			            ->select('MAX(' . $db->qn('id') . ')')
			            ->from($db->qn('#__ak_stats'));

			try
			{
				$maxId = $db->setQuery($query)->loadResult();
			}
			catch (\Exception $e)
			{
				$maxId = 0;
			}

			$backupid = 'id' . ($maxId + 1);
		}
		elseif (empty($backupid))
		{
			$backupid = null;
		}

		$session = Application::getInstance()->getContainer()->segment;
		$session->set('profile', $profile);

		Platform::getInstance()->load_configuration($profile);

		// Use the default description if none specified
		if (empty($description))
		{
			$dateNow = new Date();
			$description = Text::_('BACKUP_DEFAULT_DESCRIPTION') . ' ' . $dateNow->format(Text::_('DATE_FORMAT_LC2'), true);
		}

		// Start the backup
		Factory::resetState(array(
			'maxrun' => 0
		));

		Factory::getTempFiles()->deleteTempFiles();

		$tempVarsTag = AKEEBA_BACKUP_ORIGIN;
		$tempVarsTag .= empty($backupid) ? '' : ('.' . $backupid);

		Factory::getFactoryStorage()->reset($tempVarsTag);

		Factory::loadState(AKEEBA_BACKUP_ORIGIN, $backupid);
		$kettenrad = Factory::getKettenrad();
		$kettenrad->setBackupId($backupid);

		$options = array(
			'description' => $description,
			'comment'     => $comment, // Note: $comment is set by extract() further above
			'tag'         => AKEEBA_BACKUP_ORIGIN
		);
		$kettenrad->setup($options); // Setting up the engine

		$kettenrad->tick(); // Initializes the init domain

		Factory::saveState(AKEEBA_BACKUP_ORIGIN, $backupid);

		$array = $kettenrad->getStatusArray();

		if ($array['Error'] != '')
		{
			throw new \RuntimeException('A backup error had occurred: ' . $array['Error'], 500);
		}

		$statistics = Factory::getStatistics();
		$array['BackupID'] = $statistics->getId();
		$array['HasRun'] = 1; // Force the backup to go on.

		return $array;
	}
}