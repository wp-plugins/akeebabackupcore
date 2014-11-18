<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license		GNU GPL version 3 or later
 */

namespace Solo\Pythia\Oracle;

use Solo\Pythia\OracleInterface;

class Magento implements OracleInterface
{
	protected $path = null;

	/**
	 * Creates a new oracle objects
	 *
	 * @param   string  $path  The directory path to scan
	 */
	public function __construct($path)
	{
		$this->path = $path;
	}

	/**
	 * Does this class recognises the site as a Moodle installation?
	 *
	 * @return  boolean
	 */
	public function isRecognised()
	{
		if (!@file_exists($this->path . '/api.php'))
		{
			return false;
		}

		if (!@file_exists($this->path . '/cron.php'))
		{
			return false;
		}

		if (!@is_dir($this->path . '/app'))
		{
			return false;
		}

		return true;
	}

	/**
	 * Return the name of the CMS / script (joomla)
	 *
	 * @return  string
	 */
	public function getName()
	{
		return 'magento';
	}

	/**
	 * Return the default installer name for this CMS / script (angie)
	 *
	 * @return  string
	 */
	public function getInstaller()
	{
		return 'angie-magento';
	}

	/**
	 * Return the database connection information for this CMS / script
	 *
	 * @return  array
	 */
	public function getDbInformation()
	{
		$ret = array(
			'driver'	=> 'mysqli',
			'host'		=> '',
			'port'		=> '',
			'username'	=> '',
			'password'	=> '',
			'name'		=> '',
			'prefix'	=> '',
		);

		$xml = new \SimpleXMLElement($this->path . '/app/etc/local.xml', 0, true);
		$resources = $xml->global->resources;

		$ret['host']     = (string) $resources->default_setup->connection->host;
		$ret['username'] = (string) $resources->default_setup->connection->username;
		$ret['password'] = (string) $resources->default_setup->connection->password;
		$ret['name']     = (string) $resources->default_setup->connection->dbname;
		$ret['prefix']   = (string) $resources->db->table_prefix;

		return $ret;
	}

	public function getExtradirs()
	{
		return array();
	}
}