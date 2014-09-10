<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\Model;

use Awf\Mvc\Model;

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
				\AECoreKettenrad::reset(array(
											 'maxrun' => 0
										));

				// Remove any stale memory files left over from the previous step

				if (empty($tag))
				{
					$tag = \AEPlatform::getInstance()->get_backup_origin();
				}

				$tempVarsTag = $tag;
				$tempVarsTag .= empty($backupId) ? '' : ('.' . $backupId);

				\AEUtilTempvars::reset($tempVarsTag);

				$kettenrad = \AECoreKettenrad::load($tag, $backupId);
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
					\AECoreKettenrad::save($tag, $backupId);
					$kettenrad = \AECoreKettenrad::load($tag, $backupId);
					$kettenrad->setBackupId($backupId);
					$kettenrad->tick();
				}

				$ret_array = $kettenrad->getStatusArray();
				$kettenrad->resetWarnings(); // So as not to have duplicate warnings reports

				\AECoreKettenrad::save($tag, $backupId);
				break;

			case 'step':
				$kettenrad = \AECoreKettenrad::load($tag, $backupId);
				$kettenrad->setBackupId($backupId);

				$kettenrad->tick();
				$ret_array = $kettenrad->getStatusArray();
				$kettenrad->resetWarnings(); // So as not to have duplicate warnings reports

				\AECoreKettenrad::save($tag, $backupId);

				if ($ret_array['HasRun'] == 1)
				{
					// Clean up
					\AEFactory::nuke();

					$tempVarsTag = $tag;
					$tempVarsTag .= empty($backupId) ? '' : ('.' . $backupId);

					\AEUtilTempvars::reset($tempVarsTag);
				}
				break;

			default:
				break;
		}

		return $ret_array;
	}
} 