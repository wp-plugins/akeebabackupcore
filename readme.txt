=== Akeeba Backup CORE for WordPress ===
Contributors: nikosdion
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=10903325
Tags: backup, restore, migrate, move
Requires at least: 3.8.0
Tested up to: 4.2
Stable tag: 1.3.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

Easily backup, restore and move your WordPress site with the fastest, most robust, native PHP backup plugin.

== Description ==

Akeeba Backup Core for WordPress is an open-source, free of charge backup plugin for WordPress, quite a bit different
than the others. Its mission is simple: create a full site backup (files and database) that can be restored on any
WordPress-capable server. Even without having WordPress already installed.

Akeeba Backup creates a full backup of your site in a single archive. The archive contains all the files,
a database snapshot and a web installer which is as easy to use as WordPress' famous five minute installation procedure.
The backup and restore process is AJAX powered to avoid server timeouts, even with huge sites. Serialised data is
handled automatically. Our long experience –the backup engine is being continuously developed and perfected since 2006–
guarantees that. You can also make a backup of only your database, or only your site's files.

If you want a reliable, easy to use, open source backup solution for your WordPress site, you've found it!

*Important note*: The software, its [documentation](https://www.akeebabackup.com/documentation/akeeba-solo.html)
 and [video tutorials](https://www.akeebabackup.com/videos/63-video-tutorials/1574-akeeba-backup-for-wordpress.html) are
 provided free of charge. Personalised support is not free; it requires paying for a support subscription. That's what
 pays the bills and lets us keep on writing good quality software full time.

Features:

* You own your data. Hosted services hold your data only as long as you pay them a monthly fee. With Akeeba Backup you have full control over the backup archives you generate.
* *NEW* Send your backups to another server by FTP or SFTP. (SFTP support requires the SSH2 PHP module to be installed on the server hosting your WordPress site).
* Serialised data are automatically adjusted on restoration WITHOUT third party tools and WITHOUT precarious regular expressesions which can break your site.
* WordPress Multisite supported out of the box, today.
* The fastest native PHP backup engine. You don't need to upload Linux executable files on your server!
* Works on any virtually any server environment: Apache, NginX, Lightspeed, Lighttpd, IIS and more on Windows, Linux, Mac OS X, Solaris and more.
* No more timeouts on large sites. Our renowned engine is designed for big sites in mind. Largest successfully backed up site reported so far: 110Gb (yes, Gigabytes).
* It configures itself for optimal operation with your site. Just click on Configuration Wizard.
* One click backup with desktop notifications when it's finished. No need to stare at the screen any more.
* AJAX-powered backup (site and database, database only, files only or incremental files only backup).
* Choose between standard ZIP format, the highly efficient JPA archive format or the encrypted JPS format (encrypted JPS format available in paid version only).
* You can exclude specific files and folders.
* You can exclude specific database tables or just their contents.
* Unattended backup mode (scheduled / automated backups), fully compatible with WebCRON.org.
* *NEW* Scheduled backups with CRON jobs running on your server.
* *NEW* Automatic log analyser to help you fix backup issues without having to pay for a support subscription.
* AJAX-powered site restoration script included in the backup.
* *NEW* Integrated restoration for restoring the backup on the same server you backed up from.
* Import backup archives after uploading them back to your server. Useful for restoring after reinstalling WordPress on the same or a new server.
* Archives can be restored on any host using Akeeba Kickstart (free of charge script to extract the backup archives on any server, *without* installing WordPress and Akeeba Backup). Useful for transferring your site between subdomains/hosts or even to/from your local testing server (XAMPP, WAMPServer, MAMP, Zend Server, etc).

and much, much more!

Indicative uses:

* Security backups.
* Creating development sites to test new ideas, make site redesigns or troubleshoot issues.
* Transfer a site you created locally to a live server.
* Create "template" sites and clone them to fast-track the development of your clients' sites.

Restoring your backups requires extracting them first. If you are restoring to a different server you need to download
our [free of charge Akeeba Kickstart script](https://www.akeebabackup.com/download/akeeba-kickstart.html) from our site.
If you are restoring on the same server you can simply use the integrated restoration feature in the plugin itself.

If you need to extract a backup archive on your Windows, Linux or Mac OS X computer you can use our free of charge
[Akeeba eXtract Wizard](https://www.akeebabackup.com/download/akeeba-extract-wizard/akeeba-extract-wizard-3-3.html)
desktop software.

[More features](https://www.akeebabackup.com/products/1610-akeeba-wp-core-vs-professional.html) are available in the
separate product called "Akeeba Backup Professional for WordPress" which you can only download after purchasing a
[support subscription](https://www.akeebabackup.com/subscribe/new/backupwp.html?layout=default) on our site. This
includes automatically transferring your backups to Amazon S3, Dropbox, OneDrive, Box.com and another 40+ storage
providers for safekeeping. Clarification: these features are NOT available in Akeeba Backup CORE for WordPress available
from WordPress.org. These premium features are only provided as a thank-you to people who choose to support us
financially by purchasing a support subscription on our site.

== Installation ==

1. Install Akeeba Backup for WordPress either via the WordPress.org plugin directory, or by uploading the files to your
   server. In the latter case we suggest you to upload the files into your site's `/wp-content/plugins/akeebabackupwp`
   directory.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. You will see the Akeeba Backup icon in your sidebar, below the Plugins section in the wp-admin area of your site.
   Click on it.
1. Click on the Configuration Wizard button and site back while the plugin configures itself *automatically*.
1. Every time you want to take a backup, click on the big blue Backup Now button in the plugin's interface.
1. That's it! Really, it is that simple!

== Frequently Asked Questions ==

= I have spotted a bug. Now what? =

Please use [our Contact Us page](https://www.akeebabackup.com/contact-us.html) to file a bug report. Make sure that you
indicate "Bug report" in the Category selection. We will review your bug report and work to fix it. We may contact you
for further information if necessary. If we don't contact you be assured that if you did report a bug we are already
working on fixing it.

= I am trying to install the plugin but the upload fails =

The plugin is quite big (around 4Mb). Most servers have an upload limit of 2Mb. You can either ask your host to increase
the file upload limit to 5Mb or you can install the plugin manually. Please see the Installation section for more
information.

= I have a problem using the plugin. What should I do? =

The first thing you should do is [read our extensive documentation](https://www.akeebabackup.com/documentation/akeeba-solo.html)
and our [troubleshooter](https://www.akeebabackup.com/documentation/troubleshooter.html). If you'd like to receive
personalised support from the developers of the plugin you can [subscribe](https://www.akeebabackup.com/subscribe/new/backupwp.html?layout=default)
to our services. Due to the very specialised nature of the software and our goal of providing exceptional support we do
not outsource our support. All support requests are answered by the developers who write the software. This is why we
require a subscription to provide support.

= Does your software support WordPress MU (multi-sites a.k.a. blog networks)? =

Yes. We have added full WordPress multi-sites support since late 2014. You can restore backups to different servers or
locations and things will still work.

= What about serialised data? =

Not a problem! You've probably used a lot of tools to try and manually replace serialised data after moving your site to
a different domain or directory and you were worried because they don't always work very well. We have implemented our
own tokeniser and assembler for serialised data which works the same way PHP works under the hood. Simply put, our
solution doesn't use precarious regular expressions and isn't even the least inclined on killing your serialised data.

Please note that for data replacement to work properly all of your plugins must be storing their data in UTF-8 encoding
in the database. Some themes use a double encoding which may result in invalid data. Unfortunately that's a problem with
these themes and we can't fix it. On the other hand these themes' developers seem to be aware of this issue and provide
their own settings export and import. If your theme provides such a feature please use it. We can't reliably work around
third party code not following the character encoding standards established well over twenty years ago...

= WordPress moved to UTF8MB4 (UTF-8 Multibyte). Do you support it? =

Yes, in full. Akeeba Backup will work no matter if your site uses UTF8MB4 or the old UTF-8 encoding. If you backup a
site with data encoded in UTF-8 the restoration will work on a server supporting UTF8MB4. Going the opposite way will
not work because of a MySQL restriction. If you end up with truncated text or MySQL errors on restoration that's the
reason. In this case you will have to ask your host to update their version of MySQL to 5.5 or later.

= What are the requirements for your plugin? =

Akeeba Backup for WordPress requires PHP 5.3.04 or any later version. Older versions of PHP including PHP 4, 5.0, 5.1,
5.2 and 5.3.0 up to and including 5.3.03 are not supported. We recommend using PHP 5.4 or later for security and
performance reasons: PHP 5.4 is seven times faster than PHP 5.2 according to our benchmarks.

Akeeba Backup for WordPress has been tested on WordPress 3.8 and later. It should work on earlier versions of WordPress
but we cannot guarantee this.

Akeeba Backup for WordPress requires at least 16Mb of PHP memory (memory_limit). We strongly suggest 64Mb or more for
optimal operation on large sites with hundreds of media files and hundreds of thousands of comments.

Some features may require the PHP cURL extension to be installed and activated on your server. If unsure ask your host.

Finally, you need adequate disk space to take a backup of your site. As a rule of thumb, that's about 80% the current
size of your site's public web directory (usually called public_html, htdocs, httpdocs, www or something in the like).

= Can I use this plugin on commercial sites / sites I am building for my clients? =

Yes, of course! Our plugin is licensed under the GNU General Public License version 3 or, at your option, any later
version of the license published by the Free Software Foundation. This license gives you the same Four Freedoms as
WordPress' license; in fact, GPLv3 is simply a newer version of the same GPLv2 license WordPress is using, one which
protects your interests even more.

= I have sites using other scripts / CMS. Can I use your software with them? =

Akeeba Backup is available in three different packages. Akeeba Backup for WordPress is designed to backup and restore
WordPress sites. Akeeba Backup for Joomla! does the same for Joomla! sites. Akeeba Solo is our standalone backup
software which support WordPress, Joomla!, Magento, PrestaShop, phpBB3 and many other CMS and scripts. Use the contact
link on our site to request more information for your specific needs.

== Screenshots ==

1. A control panel interface puts everything you need under your fingertips.
2. Akeeba Backup automatically configures itself for optimal performance on your site.
3. Click on Backup Now, sit back and your backup is taken in a snap.
4. Managing backups is dead simple. And see just how fast backups are!
5. Advanced users can tweak Akeeba Backup to their liking
6. Excluding directories uses an intuitive file manager. No need to fiddle with unsightly directory names!
7. Want to automate your backups? Akeeba Backup will give you step by step instructions, specific to your site.

== Changelog ==

= 1.3.4 =
* ANGIE: Improve memory efficiency of the database engine
* Switching the default log level to All Information and Debug
* CORE version; ALICE, automated backup issue resolution
* CORE version; CLI scripts to schedule backups and check for failed scheduled backups
* CORE version; Import backups already on the server
* CORE version; Additional archivers; Direct FTP, Direct SFTP, JPS, ZIP (via ZipArchive)
* CORE version; Additional fine-tuning options
* CORE version; Integrated restoration
* CORE version; Send archives by Email, FTP, SFTP
* Support utf8mb4 in CRON jobs
* [LOW] Desktop notifications for backup resume showed "%d" instead of the time to wait before resume
* [LOW] Push notifications should not be enabled by default
* [MEDIUM] Dropbox integration would not work under many PHP 5.5 and 5.6 servers due to a PHP issue. Workaround applied.
* [HIGH] Restoring on MySQL would be impossible unless you used the MySQL PDO driver

= 1.3.3 =
* Packaging error leading to immediate backup failure

= 1.3.2 =
* Push notifications with Pushbullet
* [HIGH] ANGIE: Restoration may fail or corrupt text on some servers due to UTF8MB4 support

= 1.3.1 =
* Updated Import from S3 to use the official Amazon AWS SDK for PHP
* ANGIE (restoration script): Reset OPcache after the restoration is complete (NB! Only if you use ANGIE's Remove Installation Directory feature)
* You can set the backup profile name directly from the Configuration page
* You can create new backup profiles from the Configuration page using the Save & New button
* Desktop notifications for backup start, finish, warning and error on compatible browsers (Chrome, Safari, Firefox)
* UTF8MB4 (UTF-8 multibyte) support in restoration scripts, allows you to correctly restore content using multibyte Unicode characters (Emoji, Traditional Chinese, etc)
* [HIGH] Restoration might be impossible if your database passwords contains a double quote character
* [MEDIUM] (Pro) Dropbox and iDriveSync: could not upload under PHP 5.6 and some versions of PHP 5.5
* [MEDIUM] Immediate crash when the legacy MySQL driver is not available
* [MEDIUM] (Pro) OneDrive upload could fail in CLI CRON jobs if the upload of all parts takes more than 3600 seconds
* [MEDIUM] (Pro) Reupload from Manage Backups failed when the post-processing engine is configured to use chunked uploads
* [MEDIUM] Sometimes, usually after updating WordPress, you'd get a 403 access denied until you cleared browser cookies
* [LOW] White page when the ak_stats database table is broken
* [LOW] (Pro) Wrong link to Download ID instructions page

= 1.3.0 =
* Warning with information and instructions when you have PHP 5.3.3 or earlier instead of crashing with a blank page
* Warning if you have an outdated PHP version which we'll stop supporting soon
* gh-28 Native Microsoft Live OneDrive support
* [MEDIUM] Cancelling the creation of a new backup profile could lead to server error
* [MEDIUM] Import from S3 didn't work correctly

= 1.2.2 =
* Added "Apply to all" button in Files and Directories Exclusion page
* [HIGH] Missing interface options on bad hosts which disable the innocent parse_ini_file PHP function
* [HIGH] ANGIE (restoration): Some bad hosts disable the innocent parse_ini_file PHP function resulting in translation and functional issues during the restoration
* [MEDIUM] ANGIE for Wordpress: Site url was not replaced when moving to a different server
* [MEDIUM] Update notification not displaying on some sites (Pro)
* [MEDIUM] Core: quote and time settings parameters are not visible in the Core release
* [MEDIUM] Warning thrown when connecting to the database - Issue Ref gh-23
* [LOW] Clicking the Configure button in the Profiles page can lead to error 500 on hosts with GET query parameter name length limits
* [LOW] ANGIE for Wordpress: fixed changing Admin access details while restoring
* [LOW] On some hosts you wouldn't get the correct installer included in the backup
* [LOW] Pro: Updater doesn't work properly on PHP 5.6 - Issue Ref gh-22

= 1.2.1.2 =
* [HIGH] Core: the front-end backup was broken

= 1.2.1.1 =
* Core: WordPress.org was sending out unnecessary files with the package

= 1.2.1 =
* [HIGH] Control Panel icons not shown on some extremely low quality hosts which disable the innocuous parse_ini_file function. If you were affected SWITCH HOSTS, IMMEDIATELY!
* [HIGH] Old PHP 5.3 versions have a bug regarding Interface implementation, causing a PHP fatal error

= 1.2.0 =
* New icon set in the main page
* Now supports WordPress Multisite for restoration on new servers (you have to keep the same subdomain or subdirectory layout for your multisites)
* [HIGH] Javascript conflict with some plugins
* [LOW] Upload to Dropbox may not work on servers without a global cacert.pem file

= 1.2.0.rc5 =
* DirectoryIterator::getExtension is not compatible with PHP 5.3.4 and 5.3.5
* Removed the (broken) multipart upload from the legacy S3 post-processing engine. Please use the new "Upload to Amazon S3" option for multipart uploads.
* [HIGH] Bug in third party Guzzle library causes Amazon S3 multipart uploads of archives larger than the remaining RAM size to fail due to memory exhaustion.
* [HIGH] ANGIE for WordPress: The .htaccess was broken on restoration due to two typos in the code
* [MEDIUM] Fatal error on sites with open_basedir restrictions on the site's root

= 1.2.0.rc4 =
* [LOW] 500 error on some sites after updating to version 1.2

= 1.2.0.rc3 =
* Core version on WordPress.org had filenames in lowercase instead of uppercase, leading to immediate error loading the plugin

= 1.2.0.rc2 =
* Wrongly tagged Core version on WordPress.org

= 1.2.0.rc1 =
* New and improved backup engine
* ANGIE for WordPress: Update serialised data on restoration
* ANGIE: Add warning about Live site URL on Windows
* You can now sort and search records in the Profile Management page
* Workaround for magic_quotes_gpc under PHP 5.3
* Changed the .htaccess files to be compatible with Apache 2.4
* Improved responsive display without cutting off the right side of the plugin's display
* Layout tweaks in the Configuration page
* [HIGH] Magic quotes on PHP 5.3 could cause problems in filter pages
* [HIGH] [PRO] Update information not fetched unless you manually retry through the Update page
* [HIGH] [PRO] Akeeba Backup for WP Professional doesn't update correctly
* [MEDIUM] ANGIE: The option "No auto value on zero" was not working
* [MEDIUM] The data file pointer can be null sometimes when using multipart archives causing backup failures
* [MEDIUM] Upload to remote storage from the Manage Backups page was broken for Amazon S3 multipart uploads
* [MEDIUM] Race condition could prevent the reliable creation of JPS (encrypted) archives
* [LOW] ANGIE: Fixed table name abstraction when no table prefix is given
* [LOW] ANGIE: Fixed loading of translations
* [LOW] ANGIE for WordPress: The blog name and tagline were empty when restoring to a new server (thanks Dimitris!)
* [LOW] Workaround for badly written Wordpress plugins that are killing the request
* [LOW] Javascript errors in WP 4.0 due to subtle changes in script load order
* [LOW] Huge logo appearing on the page when WordPress debug mode is enabled
* [LOW] SFTP post-processing engine did not mark successfully uploaded backup as Remote
* [LOW] SFTP post-processing engine could not fetch the archive back to the server
* [LOW] Tooltips not showing for engine parameters when selecting a different engine (e.g. changing the Archiver Engine from JPA to ZIP)

= 1.1.5 =
* [HIGH] The integrated restoration is broken after the last security update

= 1.1.4 =
* [SECURITY: Medium] Possibility of arbitrary file writing while a backup archive is being extracted by the integrated restoration feature

= 1.1.3 =
* White page under certain versions of PHP

= 1.1.2 =
* Backup failure on certain Windows hosts and PHP versions due to the way these versions handle file pointers
* Failure to post-process part files immediately on certain Windows hosts and PHP versions due to the way these versions handle file pointers
* [HIGH] Translations wouldn't load
* [LOW] Exclude non-core tables button not working in database table exclusion page
* [LOW] Possible white page if you have are hosting multiple Akeeba Backup installations on the same (sub)domain

= 1.1.1 =
* Dangling file pointer causing backup failure on certain Windows hosts
* CloudFiles implementation changed to authentication API version 2.0, eliminating the need to choose your location
* Old MySQL versions (5.1) would return randomly ordered rows when dumping MyISAM tables when the MySQL database is corrupt up to the kazoo and about to come crashing down in flames
* [LOW] Database table exclusion table blank and backup errors when your db user doesn't have adequate privileges to show procedures, triggers or stored procedures in MySQL
* [LOW] Could not back up triggers, procedures and functions

= 1.1.0 =
* Support for iDriveSync accounts created in 2014 or later
* A different log file is created per backup attempt (and automatically removed when the backup archives are deleted by quotas or by using Delete Files in the interface)
* You can now run several backups at the same time
* The minimum execution time can now be enforced in the client side for backend backups, leading to increased stability on certain hosts
* Back-end backups will resume after an AJAX error, allowing you to complete backups even on very hosts with very tight resource usage limits
* The Dropbox chunked upload can now work on files smaller than 150Mb and will work across backup steps, allowing you to upload large files to Dropbox without timeout errors
* Backups resulting in an AJAX error will be retried, in case backup failure was caused by a temporary server or network issue
* Workaround for missing jQuery (old versions of WordPress or plugins corrupting jQuery loading in wp-admin)
* Greatly improve the backup performance on massive tables as long as they have an auto_increment column
* Work around the issues caused by some servers' error pages which contain DOM-modifying JavaScript
* Work around for the overreaching password managers in so-called modern browsers which fill irrelevant passwords in the configuration page.
* [HIGH] Dropbox upload would enter an infinite loop when using chunked uploads
* [HIGH] Potential information leak through the JSON API using a Decryption Oracle attack
* [MEDIUM] Core version: missing remote backup feature
* [MEDIUM] Notice thrown from AppConfig.php
* [MEDIUM] ANGIE: Restoring off-site directories would lead to errors
* [LOW] ANGIE for WordPress, phpBB and PrestaShop: escape some special characters in passwords
* [LOW] Some language strings inherited from Akeeba Backup for Joomla! reference Joomla! instead of WordPress

= 1.0.6 =
* [HIGH] Information disclosure through the JSON API. This is a theoretical attack since we determined it is impractical to perform outside a controlled environment.
* [HIGH] Front-end backup wasn’t included in the Core version.

= 1.0.5 =
* [HIGH] Apparently the SVN issue causing the packaging problem with 1.0.2, 1.0.3 and 1.0.
* is still unresolved and we still get white pages for some Professional features. We now
* set the SVN repository, hoprefully fixing the issue.

= 1.0.4 =
* [HIGH] Packaging error leads to error pages when trying to access Professional features
* om the Core release.

= 1.0.3 =
* [HIGH] Packaging error leading to fatal error (white page)

= 1.0.2 =
* [HIGH] Leftover jQuery files from 1.0.b2 and earlier would be loaded in the stable release
* [HIGH] Missing Javascript file errors when WordPress' debug mode is enabled
* [HIGH] [PRO] ANGIE: restoring off-site directories leads to unworkable permissions (0341) in their subdirectories due to a typo

= 1.0.0 =
* [HIGH] WordPress Plugins page would report the Core version as an update to the Professional release, leading to loss of functionality

= 1.0.b4 =
* Reorganised JS and CSS loading to use WordPress' semantics
* [MEDIUM] Update page would always report that the PHP version is too old and refuse to update

= 1.0.b3 =
* Configuration file changed from config.json to config.php – expect some turbulence after update
* You can now change the date/time format for the Start column in the Manage Backups page
* Support restoration of "split URL" WordPress sites (site root and WordPress root being different directories)
* Basic support for WordPress Multisite: multisite installations can be backed up only by the network admin and can only be restored on the same server / URL as the original blog network
* Much improved database installer / updater
* Use WordPress' timezone instead of asking you to specify it manually
* Update notifications are performed using AJAX to prevent a connection timeout from making Akeeba Backup for WordPress inaccessible
* [HIGH] CLI scripts not working when the wp-config.php file is above the site's root
* [HIGH] Fatal error when the session path is not writeable
* [MEDIUM] The entire backup output folder was excluded from the backup, breaking Akeeba Backup when restored from a backup
* [MEDIUM] It wasn't possible to link to Dropbox
* [LOW] Sometimes the ANGIE password warning would appear without a password having been set
* [LOW] The profile selection box wouldn't show in the Backup Now page
* [LOW] The remote file management button in Manage Backups page doesn't open the interface in a pop-up as it should be

= 1.0.b2 =
* Wrong name of Awf/Adapters/Curl.php file led to fatal error on some hosts
* Enable debug mode when WordPress' debug mode (WP_DEBUG) is enabled
* [LOW] Download through browser warning showing \n instead of newlines
* [LOW] Beta version shown with an ALPHA badge

= 1.0.b1 =
* First public release


== Upgrade Notice ==

= 1.3.4 =
Many new features were added in the CORE version. There might be some discrepancies in our documentation about the
availability of certain features. If you spot one we appreciate your feedback.

= 1.0.2 =
Javascript related issues affecting upgraders have been fixed.
A high priority issue regarding the permissions of restored off-site directories has been fixed.

= 1.0.1 =
Fixes a packaging issue which would cause version 1.0.0 to fail working at all

= 1.0.b4 =
First public beta made available on WordPress.org