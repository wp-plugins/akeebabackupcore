<?php die();?>

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