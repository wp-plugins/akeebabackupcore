<?php die(); ?>

Akeeba Backup for WordPress 1.0.4
================================================================================
* [HIGH] Packaging error leads to error pages when trying to access Professional features from the Core release.

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