<?php die();?>
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