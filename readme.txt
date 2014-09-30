=== Akeeba Backup CORE for WordPress ===
Contributors: nikosdion
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=10903325
Tags: backup, restore, migrate, move
Requires at least: 3.8.0
Tested up to: 4.0
Stable tag: 1.1.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

Easily backup, restore and move your WordPress site with the fastest, most robust, native PHP backup plugin.

== Description ==

Akeeba Backup Core for WordPress is an open-source backup plugin for WordPress, quite a bit different than its
competition. Its mission is simple: create a site backup that can be restored on any WordPress-capable server. Its
possibilities: endless. It creates a full backup of your site in a single archive. The archive contains all the files,
a database snapshot and a web installer which is as easy to use as WordPress' famous five minute installation procedure.
The backup and restore process is AJAX powered to avoid server timeouts, even with huge sites. Our vast experience
(the backup engine is being continuously developed and perfected since 2006) guarantees that. Alternatively, you can
make a backup of only your database, or only your files. If you want a reliable, easy to use, open source backup
solution for your WordPress site, try it out.

Features:

* You own your data. Hosted services hold your data only as long as you pay them a monthly fee. With Akeeba Backup you have full control over the backup archives you generate.
* The fastest native PHP backup engine. You don't need to upload Linux executable files on your server!
* Works on any virtually any server environment: Apache, NginX, Lightspeed, Lighttpd, IIS and more on Windows, Linux, Mac OS X, Solaris and more.
* No more timeouts on large sites. Our renowned engine is designed for big sites in mind. Largest successfully backed up site reported so far: 110Gb (yes, Gigabytes).
* It configures itself for optimal operation with your site. Just click on Configuration Wizard.
* One click backup.
* AJAX-powered backup (site and database, database only, files only or incremental files only backup)
* Choose between standard ZIP format, the highly efficient JPA archive format or the encrypted JPS format.
* You can exclude specific files and folders
* You can exclude specific database tables or just their contents
* Unattended backup mode (CRON job scheduling), fully compatible with Webcron.org
* AJAX-powered site restoration script included in the backup
* "Kickstart" restore: restore without extracting the backup locally
* Archives can be restored on any host. Useful for transferring your site between subdomains/hosts or even to/from your local testing server (XAMPP, WAMPServer, MAMP, Zend Server, etc).

and much, much more!

Indicative uses:

* Security backups
* Creating dev sites to test new ideas, make site redesigns or troubleshoot issues
* Transfer a site you created locally to a live server
* Create "template" sites and clone them to fast-track the development of your clients' sites

Do not miss out the complimentary companion desktop software (Akeeba SiteDiff and Akeeba eXtract Wizard) which are
available free of charge from our site. They make working with Akeeba Backup a breeze.

If you want advanced features, such as uploading your backup archive to Amazon S3, Dropbox, Box.com and 40+ other cloud
storage providers, import backups from S3, exclude files, folders and database tables using regular expressions,
integrated restoration and much more you can subscribe to the commercial Akeeba Backup Professional for WordPress plugin
on our site.

Note: The plugin is free of charge, its support is not. You need a valid subscription on our site to request support.
However, its documentation, the troubleshooting wizard and searching the public tickets is free of charge.

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

Not very well. Even though you can backup and restore such sites, they cannot be automatically reconfigured if you move
them to a new server or a different location on the same server. For this reason we consider this to be an unsupported
environment.

= What are the requirements for your plugin? =

Akeeba Backup for WordPress requires PHP 5.3.4 or any later version. Older versions of PHP including PHP 4, 5.0, 5.1,
5.2 and 5.3.0 up to and including 5.3.3 are not supported. We recommend using PHP 5.4 or later for security and
performance reasons: PHP 5.4 is seven times faster than PHP 5.2 according to our benchmarks.

Akeeba Backup for WordPress is tested on WordPress 3.8 and later. It should work on earlier versions of WordPress but we
cannot guarantee this.

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

= I thought Akeeba Backup was a Joomla! extension? =

Almost. Akeeba Backup was originally only published for Joomla!, however the backup engine was CMS-agnostic. Both
the WordPress plugin and Joomla! extension by the Akeeba Backup are developed by us. The backup engine is shared among
them but everything else is optimised for the CMS each solution is designed to run in.

== Screenshots ==

1. A control panel interface puts everything you need under your fingertips.
2. Akeeba Backup automatically configures itself for optimal performance on your site.
3. Click on Backup Now, sit back and your backup is taken in a snap.
4. Managing backups is dead simple. And see just how fast backups are!
5. Advanced users can tweak Akeeba Backup to their liking
6. Excluding directories uses an intuitive file manager. No need to fiddle with unsightly directory names!
7. Want to automate your backups? Akeeba Backup will give you step by step instructions, specific to your site.

== Changelog ==


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

= 1.0.2 =
Javascript related issues affecting upgraders have been fixed.
A high priority issue regarding the permissions of restored off-site directories has been fixed.

= 1.0.1 =
Fixes a packaging issue which would cause version 1.0.0 to fail working at all

= 1.0.b4 =
First public beta made available on WordPress.org