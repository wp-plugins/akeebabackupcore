<?php
/**
 * @package		akeebabackupwp
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Solo\Application;

use Awf\User\Privilege;

class WordpressUserPrivileges extends Privilege
{
	public function __construct()
	{
		$this->name = 'akeeba';
		// Set up the privilege names and their default values
		$this->privileges = array(
			'backup'	=> false,
			'configure'	=> false,
			'download'	=> false,
		);
	}

	/**
	 * It's called before the user record we are attached to is loaded.
	 *
	 * @param   object  $data  The raw data we are going to bind to the user object
	 *
	 * @return  void
	 */
	public function onBeforeLoad(&$data)
	{
		// CLI mode or access outside WP itself
		if (!defined('WPINC'))
		{
			return;
		}

		$myData = (array)$data;

		$isMultisite = is_multisite();

		$isSuperAdmin = is_super_admin();
		$isAdmin = isset($myData['wpAllCaps']) && isset($myData['wpAllCaps']['activate_plugins']) && ($myData['wpAllCaps']['activate_plugins']);

		if ($isMultisite && $isSuperAdmin)
		{
			// Give all privileges to Super Admins of multisite networks
			$this->privileges['backup'] = true;
			$this->privileges['configure'] = true;
			$this->privileges['download'] = true;
		}
		elseif ($isMultisite && $isAdmin)
		{
			// Only give backup privilege to Administrators of multisite networks
			$this->privileges['backup'] = true;
			$this->privileges['configure'] = false;
			$this->privileges['download'] = false;
		}
		elseif (!$isMultisite && ($isAdmin || $isSuperAdmin))
		{
			// Give all privileges to Administrators of single blogs
			$this->privileges['backup'] = true;
			$this->privileges['configure'] = true;
			$this->privileges['download'] = true;
		}
	}

	/**
	 * It's called after the user record we are attached to is loaded. We override it with a blank method to prevent
	 * the default privilege setup method from executing.
	 *
	 * @return  void
	 */
	public function onAfterLoad()
	{
		// Do nothing. DO NOT REMOVE THIS METHOD!!!
	}
}