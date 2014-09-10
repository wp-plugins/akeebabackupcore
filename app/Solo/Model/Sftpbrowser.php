<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Solo\Model;


use Awf\Filesystem\Sftp;
use Awf\Mvc\Model;

class Sftpbrowser extends Model
{
	public function doBrowse()
	{
		$dir = $this->getState('directory');

		// Parse directory to parts
		$parsed_dir = trim($dir,'/');
		$parts = empty($parsed_dir) ? array() : explode('/', $parsed_dir);

		// Find the path to the parent directory
		if (!empty($parts))
		{
			$copy_of_parts = $parts;
			array_pop($copy_of_parts);

			if (!empty($copy_of_parts))
			{
				$parent_directory = '/' . implode('/', $copy_of_parts);
			}
			else
			{
				$parent_directory = '/';
			}
		}
		else
		{
			$parent_directory = '';
		}


		$options = array(
			'host'		=> $this->getState('host'),
			'port'		=> $this->getState('port'),
			'username'	=> $this->getState('username'),
			'password'	=> $this->getState('password'),
			'directory'	=> $this->getState('directory'),
			'privKey'	=> $this->getState('privKey'),
			'pubKey'	=> $this->getState('pubKey'),
		);

		$list = false;
		$error = '';

		try
		{
			$sftp = new Sftp($options);
			$list = $sftp->listFolders();
		}
		catch (\RuntimeException $e)
		{
			$error = $e->getMessage();
		}

		$response_array = array(
			'error'			=> $error,
			'list'			=> $list,
			'breadcrumbs'	=> $parts,
			'directory'		=> $this->getState('directory'),
			'parent'		=> $parent_directory
		);

		return $response_array;
	}
} 