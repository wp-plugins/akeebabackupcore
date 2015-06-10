<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\Model;

use Awf\Mvc\Model;
use Akeeba\Engine\Factory;
use Akeeba\Engine\Platform;

class Backup extends Model
{
	/**
	 * Starts or step a backup process
	 *
	 * @return  array  An Akeeba Engine return array
	 */
	public function runBackup()
	{
		$ret_array = array();

		$ajaxTask = $this->getState('ajax');
		$tag = $this->getState('tag');
		$backupId = $this->getState('backupid');

		switch ($ajaxTask)
		{
			// Start a new backup
			case 'start':
				$description = $this->getState('description');
				$comment = $this->getState('comment');
				$jpskey = $this->getState('jpskey');
				$angiekey = $this->getState('angiekey');

				if (is_null($backupId))
				{
					$db = $this->container->db;
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

					$backupId = 'id' . ($maxId + 1);
				}

				// Try resetting the engine
				Factory::resetState(array(
											 'maxrun' => 0
										));

				// Remove any stale memory files left over from the previous step

				if (empty($tag))
				{
					$tag = Platform::getInstance()->get_backup_origin();
				}

				$tempVarsTag = $tag;
				$tempVarsTag .= empty($backupId) ? '' : ('.' . $backupId);

				Factory::getFactoryStorage()->reset($tempVarsTag);

				Factory::loadState($tag, $backupId);
				$kettenrad = Factory::getKettenrad();
				$kettenrad->setBackupId($backupId);

				// Take care of System Restore Point setup
				if ($tag == 'restorepoint')
				{
					// @todo This has to be implemented as some kind of plug-in functionality
				}

				$options = array(
					'description' => $description,
					'comment'     => $comment,
					'jpskey'      => $jpskey,
					'angiekey'    => $angiekey,
				);

				$kettenrad->setup($options);
				$kettenrad->tick();

				if (($kettenrad->getState() != 'running') && ($tag == 'restorepoint'))
				{
					Factory::saveState($tag, $backupId);
					Factory::loadState($tag, $backupId);
					$kettenrad = Factory::getKettenrad();
					$kettenrad->setBackupId($backupId);
					$kettenrad->tick();
				}

				$ret_array = $kettenrad->getStatusArray();
				$kettenrad->resetWarnings(); // So as not to have duplicate warnings reports

				Factory::saveState($tag, $backupId);
				break;

			// Step through a backup
			case 'step':
				Factory::loadState($tag, $backupId);
				$kettenrad = Factory::getKettenrad();
				$kettenrad->setBackupId($backupId);

				$kettenrad->tick();
				$ret_array = $kettenrad->getStatusArray();
				$kettenrad->resetWarnings(); // So as not to have duplicate warnings reports

				Factory::saveState($tag, $backupId);

				if ($ret_array['HasRun'] == 1)
				{
					// Clean up
					Factory::nuke();

					$tempVarsTag = $tag;
					$tempVarsTag .= empty($backupId) ? '' : ('.' . $backupId);

					Factory::getFactoryStorage()->reset($tempVarsTag);
				}
				break;

			// Send a push notification for backup failure
			case 'pushFail':
				Factory::loadState($tag, $backupId);
				$errorMessage = $this->getState('errorMessage');
				$platform    = Platform::getInstance();
				$pushSubject = sprintf($platform->translate('COM_AKEEBA_PUSH_ENDBACKUP_FAIL_SUBJECT'), $platform->get_site_name(), $platform->get_host());
				$key = empty($errorMessage) ? 'COM_AKEEBA_PUSH_ENDBACKUP_FAIL_BODY' : 'COM_AKEEBA_PUSH_ENDBACKUP_FAIL_BODY_WITH_MESSAGE';
				$pushDetails = sprintf($platform->translate($key), $platform->get_site_name(), $platform->get_host(), $errorMessage);
				Factory::getPush()->message($pushSubject, $pushDetails);
				break;

			default:
				break;
		}

		return $ret_array;
	}
} 