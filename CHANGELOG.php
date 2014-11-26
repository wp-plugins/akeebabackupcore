<?php die(); ?>
Akeeba Backup for WordPress 1.2.0.rc5
================================================================================
! DirectoryIterator::getExtension is not compatible with PHP 5.3.4 and 5.3.5
- Removed the (broken) multipart upload from the legacy S3 post-processing engine. Please use the new "Upload to Amazon S3" option for multipart uploads.
# [HIGH] Bug in third party Guzzle library causes Amazon S3 multipart uploads of archives larger than the remaining RAM size to fail due to memory exhaustion.
# [HIGH] ANGIE for WordPress: The .htaccess was broken on restoration due to two typos in the code
# [MEDIUM] Fatal error on sites with open_basedir restrictions on the site's root

Akeeba Backup for WordPress 1.2.0.rc4
================================================================================
# [LOW] 500 error on some sites after updating to version 1.2

Akeeba Backup for WordPress 1.2.0.rc3
================================================================================
! Core version on WordPress.org had filenames in lowercase instead of uppercase, leading to immediate error loading the plugin

Akeeba Backup for WordPress 1.2.0.rc2
================================================================================
! Wrongly tagged Core version on WordPress.org

Akeeba Backup for WordPress 1.2.0.rc1
================================================================================
+ New and improved backup engine
+ ANGIE for WordPress: Update serialised data on restoration
+ ANGIE: Add warning about Live site URL on Windows
+ You can now sort and search records in the Profile Management page
~ Workaround for magic_quotes_gpc under PHP 5.3
~ Changed the .htaccess files to be compatible with Apache 2.4
~ Improved responsive display without cutting off the right side of the plugin's display
~ Layout tweaks in the Configuration page
# [HIGH] Magic quotes on PHP 5.3 could cause problems in filter pages
# [HIGH] [PRO] Update information not fetched unless you manually retry through the Update page
# [HIGH] [PRO] Akeeba Backup for WP Professional doesn't update correctly
# [MEDIUM] ANGIE: The option "No auto value on zero" was not working
# [MEDIUM] The data file pointer can be null sometimes when using multipart archives causing backup failures
# [MEDIUM] Upload to remote storage from the Manage Backups page was broken for Amazon S3 multipart uploads
# [MEDIUM] Race condition could prevent the reliable creation of JPS (encrypted) archives
# [LOW] ANGIE: Fixed table name abstraction when no table prefix is given
# [LOW] ANGIE: Fixed loading of translations
# [LOW] ANGIE for WordPress: The blog name and tagline were empty when restoring to a new server (thanks Dimitris!)
# [LOW] Workaround for badly written Wordpress plugins that are killing the request
# [LOW] Javascript errors in WP 4.0 due to subtle changes in script load order
# [LOW] Huge logo appearing on the page when WordPress debug mode is enabled
# [LOW] SFTP post-processing engine did not mark successfully uploaded backup as Remote
# [LOW] SFTP post-processing engine could not fetch the archive back to the server
# [LOW] Tooltips not showing for engine parameters when selecting a different engine (e.g. changing the Archiver Engine from JPA to ZIP)

Akeeba Backup for WordPress 1.1.5
================================================================================
# [HIGH] The integrated restoration is broken after the last security update

Akeeba Backup for WordPress 1.1.4
================================================================================
! [SECURITY: Medium] Possibility of arbitrary file writing while a backup archive is being extracted by the integrated restoration feature

Akeeba Backup for WordPress 1.1.3
================================================================================
! White page under certain versions of PHP

Akeeba Backup for WordPress 1.1.2
================================================================================
! Backup failure on certain Windows hosts and PHP versions due to the way these versions handle file pointers
! Failure to post-process part files immediately on certain Windows hosts and PHP versions due to the way these versions handle file pointers
# [HIGH] Translations wouldn't load
# [LOW] Exclude non-core tables button not working in database table exclusion page
# [LOW] Possible white page if you have are hosting multiple Akeeba Backup installations on the same (sub)domain

Akeeba Backup for WordPress 1.1.1
================================================================================
! Dangling file pointer causing backup failure on certain Windows hosts
~ CloudFiles implementation changed to authentication API version 2.0, eliminating the need to choose your location
~ Old MySQL versions (5.1) would return randomly ordered rows when dumping MyISAM tables when the MySQL database is corrupt up to the kazoo and about to come crashing down in flames
# [LOW] Database table exclusion table blank and backup errors when your db user doesn't have adequate privileges to show procedures, triggers or stored procedures in MySQL
# [LOW] Could not back up triggers, procedures and functions

Akeeba Backup for WordPress 1.1.0
================================================================================
+ Support for iDriveSync accounts created in 2014 or later
+ A different log file is created per backup attempt (and automatically removed when the backup archives are deleted by quotas or by using Delete Files in the interface)
+ You can now run several backups at the same time
+ The minimum execution time can now be enforced in the client side for backend backups, leading to increased stability on certain hosts
+ Back-end backups will resume after an AJAX error, allowing you to complete backups even on very hosts with very tight resource usage limits
+ The Dropbox chunked upload can now work on files smaller than 150Mb and will work across backup steps, allowing you to upload large files to Dropbox without timeout errors
+ Backups resulting in an AJAX error will be retried, in case backup failure was caused by a temporary server or network issue
+ Workaround for missing jQuery (old versions of WordPress or plugins corrupting jQuery loading in wp-admin)
+ Greatly improve the backup performance on massive tables as long as they have an auto_increment column
+ Work around the issues caused by some servers' error pages which contain DOM-modifying JavaScript
+ Work around for the overreaching password managers in so-called modern browsers which fill irrelevant passwords in the configuration page.
# [HIGH] Dropbox upload would enter an infinite loop when using chunked uploads
# [HIGH] Potential information leak through the JSON API using a Decryption Oracle attack
# [MEDIUM] Core version: missing remote backup feature
# [MEDIUM] Notice thrown from AppConfig.php
# [MEDIUM] ANGIE: Restoring off-site directories would lead to errors
# [LOW] ANGIE for WordPress, phpBB and PrestaShop: escape some special characters in passwords
# [LOW] Some language strings inherited from Akeeba Backup for Joomla! reference Joomla! instead of WordPress

Akeeba Backup for WordPress 1.0.6
================================================================================
! [HIGH] Information disclosure through the JSON API. This is a theoretical attack since we determined it is impractical to perform outside a controlled environment.
# [HIGH] Front-end backup wasn’t included in the Core version.

Akeeba Backup for WordPress 1.0.5
================================================================================
# [HIGH] Apparently the SVN issue causing the packaging problem with 1.0.2, 1.0.3 and 1.0.
4 is still unresolved and we still get white pages for some Professional features. We now
reset the SVN repository, hoprefully fixing the issue.

Akeeba Backup for WordPress 1.0.4
================================================================================
# [HIGH] Packaging error leads to error pages when trying to access Professional features
from the Core release.

Akeeba Backup for WordPress 1.0.3
================================================================================
! [HIGH] Packaging error leading to fatal error (white page)

Akeeba Backup for WordPress 1.0.2
================================================================================
# [HIGH] Leftover jQuery files from 1.0.b2 and earlier would be loaded in the stable release
# [HIGH] Missing Javascript file errors when WordPress' debug mode is enabled
# [HIGH] [PRO] ANGIE: restoring off-site directories leads to unworkable permissions (0341) in their subdirectories due to a typo

Akeeba Backup for WordPress 1.0.0
================================================================================
# [HIGH] WordPress Plugins page would report the Core version as an update to the Professional release, leading to loss of functionality

Akeeba Backup for WordPress 1.0.b4
================================================================================
~ Reorganised JS and CSS loading to use WordPress' semantics
# [MEDIUM] Update page would always report that the PHP version is too old and refuse to update

Akeeba Backup for WordPress 1.0.b3 - 2014/05/05
================================================================================
! Configuration file changed from config.json to config.php – expect some turbulence after update
+ You can now change the date/time format for the Start column in the Manage Backups page
+ Support restoration of "split URL" WordPress sites (site root and WordPress root being different directories)
+ Basic support for WordPress Multisite: multisite installations can be backed up only by the network admin and can only be restored on the same server / URL as the original blog network
~ Much improved database installer / updater
~ Use WordPress' timezone instead of asking you to specify it manually
~ Update notifications are performed using AJAX to prevent a connection timeout from making Akeeba Backup for WordPress inaccessible
# [HIGH] CLI scripts not working when the wp-config.php file is above the site's root
# [HIGH] Fatal error when the session path is not writeable
# [MEDIUM] The entire backup output folder was excluded from the backup, breaking Akeeba Backup when restored from a backup
# [MEDIUM] It wasn't possible to link to Dropbox
# [LOW] Sometimes the ANGIE password warning would appear without a password having been set
# [LOW] The profile selection box wouldn't show in the Backup Now page
# [LOW] The remote file management button in Manage Backups page doesn't open the interface in a pop-up as it should be

Akeeba Backup for WordPress 1.0.b2 - 2014/04/01
================================================================================
! Wrong name of Awf/Adapters/Curl.php file led to fatal error on some hosts
~ Enable debug mode when WordPress' debug mode (WP_DEBUG) is enabled
# [LOW] Download through browser warning showing \n instead of newlines
# [LOW] Beta version shown with an ALPHA badge

Akeeba Backup for WordPress 1.0.b1 - 2014/04/01
================================================================================
! First public release