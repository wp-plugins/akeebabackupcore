<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\Controller;

use Awf\Application\Application;
use Awf\Date\Date;
use Awf\Router\Router;
use Awf\Text\Text;

class Remote extends ControllerDefault
{
	public function execute($task)
	{
		$this->checkPermissions();
		define('AKEEBA_BACKUP_ORIGIN', 'frontend');

		return parent::execute($task);
	}

	public function main()
	{
		// Set the profile
		$this->setProfile();

		// Get the backup ID
		$backupId = $this->input->get('backupid', null, 'raw', 2);

		if (strtoupper($backupId) == '[DEFAULT]')
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
		elseif (empty($backupId))
		{
			$backupId = null;
		}

		// Start the backup
		\AECoreKettenrad::reset(array(
			'maxrun' => 0
		));
		\AEUtilTempfiles::deleteTempFiles();
		$tempVarsTag = AKEEBA_BACKUP_ORIGIN;
		$tempVarsTag .= empty($backupId) ? '' : ('.' . $backupId);

		\AEUtilTempvars::reset($tempVarsTag);

		$kettenrad = \AECoreKettenrad::load(AKEEBA_BACKUP_ORIGIN, $backupId);
		$kettenrad->setBackupId($backupId);

		$dateNow = new Date();
		$description = Text::_('BACKUP_DEFAULT_DESCRIPTION') . ' ' . $dateNow->format(Text::_('DATE_FORMAT_LC2'), true);
		$options = array(
			'description' => $description,
			'comment'     => ''
		);
		$kettenrad->setup($options);
		$kettenrad->tick();
		$kettenrad->tick();
		$array = $kettenrad->getStatusArray();
		\AECoreKettenrad::save(AKEEBA_BACKUP_ORIGIN, $backupId);

		$noredirect = $this->input->get('noredirect', 0, 'int');

		if ($array['Error'] != '')
		{
			// An error occured
			if ($noredirect)
			{
				@ob_end_clean();
				echo '500 ERROR -- ' . $array['Error'];
				flush();
				$this->container->application->close();
			}
			else
			{
				throw new \RuntimeException($array['Error'], 500);
			}
		}
		elseif ($array['HasRun'] == 1)
		{
			// All done
			\AEFactory::nuke();
			\AEUtilTempvars::reset();

			@ob_end_clean();
			echo '200 OK';
			flush();
			$this->container->application->close();
		}
		else
		{
			if ($noredirect)
			{
				@ob_end_clean();
				echo "301 More work required";
				flush();
				$this->container->application->close();
			}
			else
			{
				$router = $this->container->router;
				$url = 'index.php?view=remote&task=step&key=' . $this->input->get('key', '', 'none', 2) . '&profile=' . $this->input->get('profile', 1, 'int');

				if (!empty($backupId))
				{
					$url .= '&backupid=' . $backupId;
				}

				$this->setRedirect($router->route($url));
			}
		}
	}

	public function step()
	{
		// Set the profile
		$this->setProfile();

		// Get the backup ID
		$backupId = $this->input->get('backupid', null, 'raw', 2);

		if (empty($backupId))
		{
			$backupId = null;
		}

		$kettenrad = \AECoreKettenrad::load(AKEEBA_BACKUP_ORIGIN, $backupId);
		$kettenrad->setBackupId($backupId);

		$kettenrad->tick();
		$array = $kettenrad->getStatusArray();
		$kettenrad->resetWarnings(); // So as not to have duplicate warnings reports
		\AECoreKettenrad::save(AKEEBA_BACKUP_ORIGIN, $backupId);

		$noredirect = $this->input->get('noredirect', 0, 'int');

		if ($array['Error'] != '')
		{
			// An error occured
			if ($noredirect)
			{
				@ob_end_clean();
				echo '500 ERROR -- ' . $array['Error'];
				flush();
				$this->container->application->close();
			}
			else
			{
				throw new \RuntimeException($array['Error'], 500);
			}
		}
		elseif ($array['HasRun'] == 1)
		{
			// All done
			\AEFactory::nuke();
			\AEUtilTempvars::reset();

			@ob_end_clean();
			echo '200 OK';
			flush();
			$this->container->application->close();
		}
		else
		{
			if ($noredirect)
			{
				@ob_end_clean();
				echo "301 More work required";
				flush();
				$this->container->application->close();
			}
			else
			{
				$router = $this->container->router;
				$url = 'index.php?view=remote&task=step&key=' . $this->input->get('key', '', 'none', 2) . '&profile=' . $this->input->get('profile', 1, 'int');

				if (!empty($backupId))
				{
					$url .= '&backupid=' . $backupId;
				}

				$this->setRedirect($router->route($url));
			}
		}
	}

	/**
	 * Check that the user has sufficient permissions, or die in error
	 *
	 */
	private function checkPermissions()
	{
		// Is frontend backup enabled?
		$febEnabled = \AEPlatform::getInstance()->get_platform_configuration_option('frontend_enable', 0);
		$febEnabled = in_array($febEnabled, array('on', 'checked', 'true', 1, 'yes'));

		$validKey = \AEPlatform::getInstance()->get_platform_configuration_option('frontend_secret_word', '');
		$validKeyTrim = trim($validKey);

		if (!$febEnabled || empty($validKey))
		{
			throw new \RuntimeException(Text::_('SOLO_REMOTE_ERROR_NOT_ENABLED'), 403);
		}

		// Is the key good?
		$key = $this->input->get('key', '', 'none', 2);

		if (($key != $validKey) || (empty($validKeyTrim)))
		{
			throw new \RuntimeException(Text::_('SOLO_REMOTE_ERROR_INVALID_KEY'), 403);
		}
	}

	private function setProfile()
	{
		// Set profile
		$profile = $this->input->get('profile', 1, 'int');

		if (empty($profile))
		{
			$profile = 1;
		}

		$session = Application::getInstance()->getContainer()->segment;
		$session->profile = $profile;

		\AEPlatform::getInstance()->load_configuration($profile);
	}
} 