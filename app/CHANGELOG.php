<?php die();?>
Akeeba Solo 1.3.4
================================================================================
~ ANGIE: Improve memory efficiency of the database engine
~ Switching the default log level to All Information and Debug
+ Support utf8mb4 in CRON jobs
# [LOW] Desktop notifications for backup resume showed "%d" instead of the time to wait before resume
# [LOW] Push notifications should not be enabled by default
# [MEDIUM] Dropbox integration would not work under many PHP 5.5 and 5.6 servers due to a PHP issue. Workaround applied.
# [HIGH] Restoring on MySQL would be impossible unless you used the MySQL PDO driver

Akeeba Solo 1.3.3
================================================================================
! Packaging error leading to immediate backup failure

Akeeba Solo 1.3.2
================================================================================
+ Push notifications with Pushbullet
# [HIGH] ANGIE: Restoration may fail or corrupt text on some servers due to UTF8MB4 support

Akeeba Solo 1.3.1
================================================================================
~ Updated Import from S3 to use the official Amazon AWS SDK for PHP
~ ANGIE (restoration script): Reset OPcache after the restoration is complete (NB! Only if you use ANGIE's Remove Installation Directory feature)
+ You can set the backup profile name directly from the Configuration page
+ You can create new backup profiles from the Configuration page using the Save & New button
+ Desktop notifications for backup start, finish, warning and error on compatible browsers (Chrome, Safari, Firefox)
+ UTF8MB4 (UTF-8 multibyte) support in restoration scripts, allows you to correctly restore content using multibyte Unicode characters (Emoji, Traditional Chinese, etc)
# [HIGH] Restoration might be impossible if your database passwords contains a double quote character
# [MEDIUM] Dropbox and iDriveSync: could not upload under PHP 5.6 and some versions of PHP 5.5
# [MEDIUM] Immediate crash when the legacy MySQL driver is not available
# [MEDIUM] OneDrive upload could fail in CLI CRON jobs if the upload of all parts takes more than 3600 seconds
# [MEDIUM] Reupload from Manage Backups failed when the post-processing engine is configured to use chunked uploads
# [MEDIUM] Fixed update script
# [LOW] White page when the ak_stats database table is broken

Akeeba Solo 1.3.0
================================================================================
+ Warning with information and instructions when you have PHP 5.3.3 or earlier instead of a cryptic error message
+ Warning if you have an outdated PHP version which we'll stop supporting soon
+ Added support for uploading archives in subdirectories while using FTP post-processing engine
+ gh-28 Native Microsoft Live OneDrive support
+ gh-29 Profile description tooltip in Manage Backups page when hovering over the profile ID
+ gh-29 Profile filter in Manage Backups page
+ gh-30 Added option to enable/disable profile encryption
+ gh-31 Added page for displaying phpinfo() dump
# [HIGH] The ANGIE script selected in Configuration Wizard is not applied to the configuration
# [MEDIUM] Cancelling the creation of a new user account or backup profile could lead to server error
# [LOW] Fixed javascript error while using an ANGIE password containing single quotes
# [LOW] gh-26 Fixed white page when an error occurs and we're inside Wordpress
# [LOW] Prevent usage of negative profile ID in CLI backups
# [MEDIUM] Import from S3 didn't work correctly

Akeeba Solo 1.2.2
================================================================================
+ Added "Apply to all" button in Files and Directories Exclusion page
# [HIGH] Missing interface options on bad hosts which disable the innocent parse_ini_file PHP function
# [HIGH] ANGIE (restoration): Some bad hosts disable the innocent parse_ini_file PHP function resulting in translation and functional issues during the restoration
# [MEDIUM] ANGIE for Wordpress: Site url was not replaced when moving to a different server
# [MEDIUM] Update notification not displaying on some sites
# [MEDIUM] Frontend check for failed backups was not working
# [MEDIUM] Extradirs folders where not saved
# [LOW] Clicking the Configure button in the Profiles page can lead to error 500 on hosts with GET query parameter name length limits
# [LOW] ANGIE for Wordpress: fixed changing Admin access details while restoring
# [LOW] Updater doesn't work properly on PHP 5.6

Akeeba Solo 1.2.1
================================================================================
# [LOW] Username filtering doesn't work in the Users page
# [HIGH] Control Panel icons and translations not shown on some extremely low quality hosts which disable the innocuous parse_ini_file function. If you were affected SWITCH HOSTS, IMMEDIATELY!
# [HIGH] Old PHP 5.3 versions have a bug regarding Interface implementation, causing a PHP fatal error

Akeeba Solo 1.2.0
================================================================================
~ New icon set in the main page
+ Now supports WordPress Multisite for restoration on new servers (you have to keep the same subdomain or subdirectory layout for your multisites)
# [LOW] Upload to Dropbox may not work on servers without a global cacert.pem file

Akeeba Solo 1.2.0.rc5
================================================================================
! DirectoryIterator::getExtension is not compatible with PHP 5.3.4 and 5.3.5
- Removed the (broken) multipart upload from the legacy S3 post-processing engine. Please use the new "Upload to Amazon S3" option for multipart uploads.
# [HIGH] Bug in third party Guzzle library causes Amazon S3 multipart uploads of archives larger than the remaining RAM size to fail due to memory exhaustion.
# [HIGH] ANGIE for WordPress: The .htaccess was broken on restoration due to two typos in the code
# [MEDIUM] Fatal error on sites with open_basedir restrictions on the site's root

Akeeba Solo 1.2.0.rc4
================================================================================
# [LOW] 500 error on some sites after updating to version 1.2
~ There are no 1.2.0.rc3 and 1.2.0.rc4 versions; these version numbers were reserved for use by Akeeba Backup CORE for WordPress.

Akeeba Solo 1.2.0.rc1
================================================================================
+ New and improved backup engine
+ ANGIE for WordPress: Update serialised data
+ ANGIE: Add warning about Live site URL on Windows
+ You can now sort and search records in the Profile Management page
~ Workaround for magic_quotes_gpc under PHP 5.3
~ Changed the .htaccess files to be compatible with Apache 2.4
~ Improved responsive display
~ Layout tweaks in the Configuration page
# [HIGH] Update information not fetched unless you manually retry through the Update page
# [MEDIUM] ANGIE: The option "No auto value on zero" was not working
# [MEDIUM] The data file pointer can be null sometimes when using multipart archives causing backup failures
# [MEDIUM] Upload to remote storage from the Manage Backups page was broken for Amazon S3 multipart uploads
# [MEDIUM] Race condition could prevent the reliable creation of JPS (encrypted) archives
# [LOW] ANGIE: Fixed table name abstraction when no table prefix is given
# [LOW] ANGIE: Fixed loading of translations
# [LOW] ANGIE for WordPress: The blog name and tagline were empty when restoring to a new server (thanks Dimitris!)
# [LOW] SFTP post-processing engine did not mark successfully uploaded backup as Remote
# [LOW] SFTP post-processing engine could not fetch the archive back to the server
# [LOW] Tooltips not showing for engine parameters when selecting a different engine (e.g. changing the Archiver Engine from JPA to ZIP)

Akeeba Solo 1.1.5
================================================================================
# [HIGH] The integrated restoration is broken after the last security update

Akeeba Solo 1.1.4
================================================================================
! [SECURITY: Medium] Possibility of arbitrary file writing while a backup archive is being extracted by the integrated restoration feature

Akeeba Solo 1.1.3
================================================================================
! White page under certain versions of PHP

Akeeba Solo 1.1.2
================================================================================
! Backup failure on certain Windows hosts and PHP versions due to the way these versions handle file pointers
! Failure to post-process part files immediately on certain Windows hosts and PHP versions due to the way these versions handle file pointers
# [HIGH] Translations wouldn't load
# [LOW] Exclude non-core tables button not working in database table exclusion page
# [LOW] Possible white page if you have are hosting multiple Solo installations on the same (sub)domain

Akeeba Solo 1.1.1
================================================================================
! Dangling file pointer causing backup failure on certain Windows hosts
~ CloudFiles implementation changed to authentication API version 2.0, eliminating the need to choose your location
~ Old MySQL versions (5.1) would return randomly ordered rows when dumping MyISAM tables when the MySQL database is corrupt up to the kazoo and about to come crashing down in flames
# [LOW] Database table exclusion table blank and backup errors when your db user doesn't have adequate privileges to show procedures, triggers or stored procedures in MySQL
# [LOW] Could not back up triggers, procedures and functions

Akeeba Solo 1.1.0
================================================================================
+ Support for iDriveSync accounts created in 2014 or later
+ A different log file is created per backup attempt (and automatically removed when the backup archives are deleted by quotas or by using Delete Files in the interface)
+ You can now run several backups at the same time
+ The minimum execution time can now be enforced in the client side for backend backups, leading to increased stability on certain hosts
+ Back-end backups will resume after an AJAX error, allowing you to complete backups even on very hosts with very tight resource usage limits
+ The Dropbox chunked upload can now work on files smaller than 150Mb and will work across backup steps, allowing you to upload large files to Dropbox without timeout errors
+ Backups resulting in an AJAX error will be retried, in case backup failure was caused by a temporary server or network issue
+ Greatly improve the backup performance on massive tables as long as they have an auto_increment column
+ Work around the issues caused by some servers' error pages which contain DOM-modifying JavaScript
+ Work around for the overreaching password managers in so-called modern browsers which fill irrelevant passwords in the configuration page.
+ Improved Two Factor Authentication with one time emergency passwords
# [HIGH] Dropbox upload would enter an infinite loop when using chunked uploads
# [HIGH] Javascript not loaded in Setup page
# [HIGH] Potential information leak through the JSON API using a Decryption Oracle attack
# [MEDIUM] ANGIE for Joomla!, cannot detect Joomla! version, leading to Two Factor Auth data not being re-encoded with the new secret key
# [MEDIUM] ANGIE: Restoring off-site directories would lead to errors
# [MEDIUM] ANGIE (all flavours): cannot restart db restoration after a database error has occurred.
# [LOW] FTP port and directory not taken into account in the the setup page
# [LOW] ANGIE for WordPress, phpBB and PrestaShop: escape some special characters in passwords
# [LOW] Do not offer to install using the PDO or SQLite drivers as there's no schema file available for them
# [LOW] Wrong language strings (copied from the original Joomla! component's codebase)
# [LOW] ANGIE for Joomla!, array values in configuration.php were corrupted
# [LOW] Setup user interface issue when debug mode is enabled

Akeeba Solo 1.0.4
================================================================================
! [HIGH] Information disclosure through the JSON API. This is a theoretical attack since we determined it is impractical to perform outside a controlled environment.

Akeeba Solo 1.0.3
================================================================================
! [HIGH] Packaging error leading to fatal error (white page)

Akeeba Solo 1.0.2
================================================================================
# [HIGH] Sometimes no instructions shown in Setup when the config.php file cannot be saved
# [HIGH] Incorrect config.php file contents shown in Setup when the config.php file cannot be saved
# [HIGH] Fatal error under PHP 5.3.5
# [HIGH] Could not login under PHP 5.3.3 up to and including 5.3.5 due to database issue caused by unencoded binary salt
# [HIGH] [PRO] ANGIE: restoring off-site directories leads to unworkable permissions (0341) in their subdirectories due to a typo
# [MEDIUM] Error thrown when MB lang is not the default, preventing setup to continue
# [LOW] Do not complain if config.php is web accessible but it returns no contents (that's not a major security risk)
# [LOW] You were told that the config.php file wasn't written to disk even when it really was
# [LOW] You are given a blocking error when you have not defined db connection information for a files only backup and vice versa

Akeeba Solo 1.0.1
================================================================================
! Packaging error making it impossible to go past the database setup page

Akeeba Solo 1.0.0
================================================================================
~ Only users with all privileges can manage the users list (prevents users with the configuration privilege from escalating their privileges)
~ Only users with all privileges can access the system configuration
~ Only users with all privileges can access the updater
# [HIGH] Using a database password with special characters didn't allow you to proceed with the installation

Akeeba Solo 1.0.b5
================================================================================
! The CDN was serving the wrong package instead of 1.0.b4, one which didn't include the installation issue fix

Akeeba Solo 1.0.b4 - 2014/05/15
================================================================================
! Installation wouldn't get past the database page

Akeeba Solo 1.0.b3 - 2014/05/05
================================================================================
! Configuration file changed from config.json to config.php – expect some turbulence after update
+ You can now change the date/time format for the Start column in the Manage Backups page
+ Pre-fill Site's URL and Site Root if Akeeba Solo is installed in a subdirectory
+ Support restoration of "split URL" WordPress sites (site root and WordPress root being different directories)
+ Add an icon in the user manager to indicate which Two Factor Authentication method is enabled for each user account
~ Much improved database installer / updater
~ Update notifications are performed using AJAX to prevent a connection timeout from making Akeeba Solo inaccessible
# [HIGH] WordPress sites not detected when the wp-config.php file is above the site's root
# [HIGH] Fatal error when the session path is not writeable
# [HIGH] The "Include Akeeba Solo in the backup" feature was not working properly
# [MEDIUM] The entire backup output folder was excluded from the backup, breaking Solo when restored from a backup
# [MEDIUM] Configuration Wizard didn't warn you if your database settings were incorrect
# [MEDIUM] It wasn't possible to link to Dropbox
# [LOW] Sometimes the ANGIE password warning would appear without a password having been set
# [LOW] The profile selection box wouldn't show in the Backup Now page

Akeeba Solo 1.0.b2 - 2014/04/01
================================================================================
! Wrong name of Awf/Adapters/Curl.php file led to fatal error on some hosts
# [LOW] Download through browser warning showing \n instead of newlines
# [LOW] Beta version shown with an ALPHA badge

Akeeba Solo 1.0.b1 - 2014/04/01
================================================================================
! First release