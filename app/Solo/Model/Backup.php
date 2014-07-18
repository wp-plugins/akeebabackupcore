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

		switch ($ajaxTask)
		{
			case 'start':
				$description = $this->getState('description');
				$comment = $this->getState('comment');
				$jpskey = $this->getState('jpskey');
				$angiekey = $this->getState('angiekey');

				$tag = $this->getState('tag');

				// Try resetting the engine
				\AECoreKettenrad::reset(array(
											 'maxrun' => 0
										));

				// Remove any stale memory files left over from the previous step

				if (empty($tag))
				{
					$tag = \AEPlatform::getInstance()->get_backup_origin();
				}

				\AEUtilTempvars::reset($tag);

				$kettenrad = \AECoreKettenrad::load($tag);

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
					$kettenrad->tick();
				}

				$ret_array = $kettenrad->getStatusArray();
				$kettenrad->resetWarnings(); // So as not to have duplicate warnings reports

				\AECoreKettenrad::save($tag);
				break;

			case 'step':
				$tag = $this->getState('tag');
				$kettenrad = \AECoreKettenrad::load($tag);

				$kettenrad->tick();
				$ret_array = $kettenrad->getStatusArray();
				$kettenrad->resetWarnings(); // So as not to have duplicate warnings reports

				\AECoreKettenrad::save($tag);

				if ($ret_array['HasRun'] == 1)
				{
					// Clean up
					\AEFactory::nuke();
					\AEUtilTempvars::reset($tag);
				}
				break;

			default:
				break;
		}

		return $ret_array;
	}
} 