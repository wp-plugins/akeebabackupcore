<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Solo\Pythia\Oracle;

use Solo\Pythia\OracleInterface;

class Prestashop implements OracleInterface
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
	 * Does this class recognises the CMS type as Prestashop?
	 *
	 * @return  boolean
	 */
	public function isRecognised()
	{
		if (!@file_exists($this->path . '/config/settings.inc.php'))
		{
			return false;
		}

		if (!@file_exists($this->path . '/config/smarty.config.inc.php'))
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
		return 'prestashop';
	}

	/**
	 * Return the default installer name for this CMS / script (angie)
	 *
	 * @return  string
	 */
	public function getInstaller()
	{
		return 'angie-prestashop';
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

		$fileContents = file($this->path . '/config/settings.inc.php');

		foreach ($fileContents as $line)
		{
            $line = trim($line);
            $matches = array();

            // Skip commented lines. However it will get the line between a multiline comment, but that's not a problem
            if(strpos($line, '#') === 0 || strpos($line, '//') === 0 || strpos($line, '/*') === 0)
            {
                // skip it
            }
            elseif(strpos($line, 'define(') !== false)
            {
                preg_match('#define\(["\'](.*?)["\']\,\s["\'](.*?)["\']#', $line, $matches);

                if(isset($matches[1]))
                {
                    $key = $matches[1];

                    switch(strtoupper($key))
                    {
                        case '_DB_SERVER_':
                            $ret['host'] = $matches[2];
                            break;
                        case '_DB_USER_':
                            $ret['username'] = $matches[2];
                            break;
                        case '_DB_PASSWD_':
                            $ret['password'] = $matches[2];
                            break;
                        case '_DB_NAME_' :
                            $ret['name'] = $matches[2];
                            break;
                        case '_DB_PREFIX_':
                            $ret['prefix'] = $matches[2];
                            break;
                        default:
                            // Do nothing, it's a variable we're not interested in
                            break;
                    }
                }
            }
		}

		return $ret;
	}
} 