<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Solo;

class PostUpgradeScript
{
	/** @var \Awf\Container\Container|null The container of the application we are running in */
	protected $container = null;

	/**
	 * @var array Files to remove from all versions
	 */
	protected $removeFilesAllVersions = array(
		'media/css/bootstrap-namespaced.css',
		'media/css/bootstrap-switch.css',
		'media/css/datepicker.css',
		'media/css/theme.css',
		'media/js/bootstrap-switch.js',
		'media/js/piecon.js',
		'media/js/datepicker/bootstrap-datepicker.js',
		'media/js/solo/alice.js',
		'media/js/solo/backup.js',
		'media/js/solo/configuration.js',
		'media/js/solo/dbfilters.js',
		'media/js/solo/encryption.js',
		'media/js/solo/extradirs.js',
		'media/js/solo/fsfilters.js',
		'media/js/solo/gui-helpers.js',
		'media/js/solo/multidb.js',
		'media/js/solo/regexdbfilters.js',
		'media/js/solo/regexfsfilters.js',
		'media/js/solo/restore.js',
		'media/js/solo/setup.js',
		'media/js/solo/stepper.js',
		'media/js/solo/system.js',
		'media/js/solo/update.js',
		'media/js/solo/wizard.js',
	);

	/**
	 * @var array Files to remove from Core
	 */
	protected $removeFilesCore = array(
		'Solo/Controller/Alice.php',
		'Solo/Model/Alice.php',
		'Solo/Controller/Discover.php',
		'Solo/Model/Discover.php',
		'Solo/Controller/Extradirs.php',
		'Solo/Model/Extradirs.php',
		'Solo/Controller/Multidb.php',
		'Solo/Model/Multidb.php',
		'Solo/Controller/Regexdbfilters.php',
		'Solo/Model/Regexdbfilters.php',
		'Solo/Controller/Regexfsfilters.php',
		'Solo/Model/Regexfsfilters.php',
		'Solo/Controller/Remote.php',
		'Solo/Model/Remote.php',
		'Solo/Controller/Remotefiles.php',
		'Solo/Model/Remotefiles.php',
		'Solo/Controller/S3import.php',
		'Solo/Model/S3import.php',
		'Solo/Controller/Upload.php',
		'Solo/Model/Upload.php',
	);

	/**
	 * @var array Files to remove from Pro
	 */
	protected $removeFilesPro = array(

	);

	/**
	 * @var array Folders to remove from all versions
	 */
	protected $removeFoldersAllVersions = array(
	);

	/**
	 * @var array Folders to remove from Core
	 */
	protected $removeFoldersCore = array(
		'Solo/alice',
		'Solo/View/Alice',
		'Solo/View/Discover',
		'Solo/View/Extradirs',
		'Solo/View/Multidb',
		'Solo/View/Regexdbfilters',
		'Solo/View/Regexfsfilters',
		'Solo/View/Remote',
		'Solo/View/Remotefiles',
		'Solo/View/S3import',
		'Solo/View/Upload',
	);

	/**
	 * @var array Folders to remove from Pro
	 */
	protected $removeFoldersPro = array(

	);

	/**
	 * Class constructor
	 *
	 * @param \Awf\Container\Container $container The container of the application we are running in
	 */
	public function __construct(\Awf\Container\Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Execute the post-upgrade actions
	 */
	public function execute()
	{
		if ($this->container->segment->get('insideCMS', false))
		{
			if (defined('WPINC'))
			{
				$this->_WordPressActions();
			}

			if (defined('_JEXEC'))
			{
				$this->_JoomlaActions();
			}
		}

		// Remove obsolete files
		$this->processRemoveFiles();

		// Remove obsolete folders
		$this->processRemoveFolders();
	}

	/**
	 * Removes obsolete files, depending on the edition (core or pro)
	 */
	protected function processRemoveFiles()
	{
		$removeFiles = $this->removeFilesAllVersions;

		if (defined('AKEEBA_PRO') && AKEEBA_PRO)
		{
			$removeFiles = array_merge($removeFiles, $this->removeFilesPro);
		}
		else
		{
			$removeFiles = array_merge($removeFiles, $this->removeFilesCore);
		}

		$this->_removeFiles($removeFiles);
	}

	/**
	 * Removes obsolete folders, depending on the edition (core or pro)
	 */
	protected function processRemoveFolders()
	{
		$removeFolders = $this->removeFoldersAllVersions;

		if (defined('AKEEBA_PRO') && AKEEBA_PRO)
		{
			$removeFolders = array_merge($removeFolders, $this->removeFoldersPro);
		}
		else
		{
			$removeFolders = array_merge($removeFolders, $this->removeFoldersCore);
		}

		$this->_removeFolders($removeFolders);
	}

	/**
	 * Specific actions to execute when we are running inside WordPress
	 */
	private function _WordPressActions()
	{

	}

	/**
	 * Specific actions to execute when we are running inside Joomla
	 */
	private function _JoomlaActions()
	{

	}

	/**
	 * Removes obsolete files given on a list
	 *
	 * @param array $removeFiles List of files to remove
	 *
	 * @return void
	 */
	private function _removeFiles(array $removeFiles)
	{
		if (empty($removeFiles))
		{
			return;
		}

		$fsBase = rtrim($this->container->filesystemBase, '/' . DIRECTORY_SEPARATOR) . '/';
		$fs = $this->container->fileSystem;

		foreach($removeFiles as $file)
		{
			$fs->delete($fsBase . $file);
		}
	}

	/**
	 * Removes obsolete folders given on a list
	 *
	 * @param array $removeFolders List of folders to remove
	 *
	 * @return void
	 */
	private function _removeFolders(array $removeFolders)
	{
		if (empty($removeFolders))
		{
			return;
		}

		$fsBase = rtrim($this->container->filesystemBase, '/' . DIRECTORY_SEPARATOR) . '/';
		$fs = $this->container->fileSystem;

		foreach($removeFolders as $folder)
		{
			$fs->rmdir($fsBase . $folder, true);
		}
	}
}