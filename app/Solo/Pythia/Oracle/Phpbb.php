<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Solo\Pythia\Oracle;

use Solo\Pythia\OracleInterface;

class Phpbb implements OracleInterface
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
	 * Does this class recognises the site type as phpBB?
	 *
	 * @return  boolean
	 */
	public function isRecognised()
	{
		if (!@file_exists($this->path . '/config.php'))
		{
			return false;
		}

		if (!@file_exists($this->path . '/styles/subsilver2/style.cfg'))
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
		return 'phpbb';
	}

	/**
	 * Return the default installer name for this CMS / script (angie)
	 *
	 * @return  string
	 */
	public function getInstaller()
	{
		return 'angie-phpbb';
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

		$fileContents = file($this->path . '/config.php');

		foreach ($fileContents as $line)
		{
			$line = trim($line);

            $matches = array();

            // Skip commented lines. However it will get the line between a multiline comment, but that's not a problem
            if(strpos($line, '#') === 0 || strpos($line, '//') === 0 || strpos($line, '/*') === 0)
            {
                // simply do nothing, we will add the line later
            }
            else
            {
                preg_match('#\$(.*?)=\s?[\'"](.*?)[\'"]#', $line, $matches);

                if(isset($matches[1]))
                {
                    $key = trim($matches[1]);

                    switch(strtolower($key))
                    {
                        case 'dbms':
                            $ret['driver'] = $matches[2];
                            break;
                        case 'dbhost':
                            $ret['host'] = $matches[2];
                            break;
                        case 'dbport':
                            $ret['port'] = $matches[2];
                            break;
                        case 'dbuser':
                            $ret['username'] = $matches[2];
                            break;
                        case 'dbpasswd':
                            $ret['password'] = $matches[2];
                            break;
                        case 'dbname' :
                            $ret['name'] = $matches[2];
                            break;
                        case 'table_prefix' :
                            $ret['prefix'] = $matches[2];
                            break;
                        default:
                            // Do nothing, it's a variable we're not insterested in
                            break;
                    }
                }
            }
		}

		return $ret;
	}
} 