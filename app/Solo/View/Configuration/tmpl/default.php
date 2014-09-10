<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

use Awf\Text\Text;
use Solo\Helper\Escape;

/** @var $this \Solo\View\Configuration\Html */

$router = $this->container->router;

?>

<div class="modal fade" id="ftpdialog" tabindex="-1" role="dialog" aria-labelledby="ftpdialogLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="ftpdialogLabel">
					<?php echo Text ::_('CONFIG_UI_FTPBROWSER_TITLE') ?>
				</h4>
			</div>
			<div class="modal-body">
				<p class="instructions alert alert-info hidden-xs">
					<button class="close" data-dismiss="alert">×</button>
					<?php echo Text::_('FTPBROWSER_LBL_INSTRUCTIONS'); ?>
				</p>
				<div class="error alert alert-danger" id="ftpBrowserErrorContainer">
					<button class="close" data-dismiss="alert">×</button>
					<h2><?php echo Text::_('FTPBROWSER_LBL_ERROR'); ?></h2>

					<p id="ftpBrowserError"></p>
				</div>
				<ol id="ak_crumbs" class="breadcrumb"></ol>
				<div class="folderBrowserWrapper">
					<table id="ftpBrowserFolderList" class="table table-striped">
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="ftpdialogCancelButton" class="btn btn-default" data-dismiss="modal">
					<?php echo Text::_('SOLO_BTN_CANCEL') ?>
				</button>
				<button type="button" id="ftpdialogOkButton" class="btn btn-primary">
					<?php echo Text::_('BROWSER_LBL_USE') ?>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="sftpdialog" tabindex="-1" role="dialog" aria-labelledby="sftpdialogLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="sftpdialogLabel">
					<?php echo Text ::_('CONFIG_UI_SFTPBROWSER_TITLE') ?>
				</h4>
			</div>
			<div class="modal-body">
				<p class="instructions alert alert-info">
					<button class="close" data-dismiss="alert">×</button>
					<?php echo Text::_('SFTPBROWSER_LBL_INSTRUCTIONS'); ?>
				</p>
				<div class="error alert alert-danger" id="sftpBrowserErrorContainer">
					<button class="close" data-dismiss="alert">×</button>
					<h2><?php echo Text::_('SFTPBROWSER_LBL_ERROR'); ?></h2>

					<p id="sftpBrowserError"></p>
				</div>
				<ol id="ak_scrumbs" class="breadcrumb"></ol>
				<div class="folderBrowserWrapper">
					<table id="sftpBrowserFolderList" class="table table-striped">
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="sftpdialogCancelButton" class="btn btn-default" data-dismiss="modal">
					<?php echo Text::_('SOLO_BTN_CANCEL') ?>
				</button>
				<button type="button" id="sftpdialogOkButton" class="btn btn-primary">
					<?php echo Text::_('BROWSER_LBL_USE') ?>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="dialog" tabindex="-1" role="dialog" aria-labelledby="dialogLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="dialogLabel">
					<?php echo Text::_('CONFIG_UI_BROWSER_TITLE'); ?>
				</h4>
			</div>
			<div class="modal-body" id="dialogBody">
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="testFtpDialog" tabindex="-1" role="dialog" aria-labelledby="testFtpDialogLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="testFtpDialogLabel">
				</h4>
			</div>
			<div class="modal-body" id="testFtpDialogBody">
				<div class="alert alert-success" id="testFtpDialogBodyOk"></div>
				<div class="alert alert-danger" id="testFtpDialogBodyFail"></div>
			</div>
		</div>
	</div>
</div>

<form name="adminForm" id="adminForm" method="post"
	  action="<?php echo $router->route('index.php?view=configuration') ?>" class="form-horizontal">

	<div>
		<?php if (!AKEEBA_PRO): ?>
			<div class="well">
				<p class="text-danger">
					<?php echo Text::_('SOLO_MAIN_LBL_WHYSUBSCRIBE'); ?>
				</p>
				<p style="text-align: center;">
					<a class="btn btn-lg btn-danger" href="https://www.akeebabackup.com/subscribe.html">
						<span class="glyphicon glyphicon-shopping-cart"></span>
						<?php echo Text::_('SOLO_MAIN_BTN_SUBSCRIBE'); ?>
					</a>
				</p>
			</div>
		<?php endif; ?>

		<?php if ($this->secureSettings): ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">×</button>
				<?php echo Text::_('CONFIG_UI_SETTINGS_SECURED'); ?>
			</div>
			<div class="ak_clr"></div>
		<?php elseif ($this->secureSettings == 0): ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">×</button>
				<?php echo Text::_('CONFIG_UI_SETTINGS_NOTSECURED'); ?>
			</div>
			<div class="ak_clr"></div>
		<?php endif; ?>

		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			<strong><?php echo Text::_('CPANEL_PROFILE_TITLE'); ?></strong>:
			#<?php echo $this->profileId; ?> <?php echo $this->profileName; ?>
		</div>

		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			<?php echo Text::_('CONFIG_WHERE_ARE_THE_FILTERS'); ?>
		</div>

	</div>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="token" value="<?php echo $this->container->session->getCsrfToken()->getValue() ?>"/>

	<!-- This div contains dynamically generated user interface elements -->
	<div id="akeebagui">
	</div>

</form>
<script type="text/javascript" language="javascript">
// Callback routine to close the browser dialog
var akeeba_browser_callback = null;

Solo.loadScripts[Solo.loadScripts.length] = function () {
	(function($){
	// Initialise the translations
	Solo.Configuration.translations['UI-BROWSE'] = '<?php echo Escape::escapeJS(Text::_('CONFIG_UI_BROWSE')) ?>';
	Solo.Configuration.translations['UI-CONFIG'] = '<?php echo Escape::escapeJS(Text::_('CONFIG_UI_CONFIG')) ?>';
	Solo.Configuration.translations['UI-REFRESH'] = '<?php echo Escape::escapeJS(Text::_('CONFIG_UI_REFRESH')) ?>';
	Solo.Configuration.translations['UI-FTPBROWSER-TITLE'] = '<?php echo Escape::escapeJS(Text::_('CONFIG_UI_FTPBROWSER_TITLE')) ?>';
	Solo.Configuration.translations['UI-ROOT'] = '<?php echo Escape::escapeJS(Text::_('SOLO_COMMON_LBL_ROOT')) ?>';
	Solo.Configuration.translations['UI-TESTFTP-OK'] = '<?php echo Escape::escapeJS(Text::_('CONFIG_DIRECTFTP_TEST_OK')) ?>';
	Solo.Configuration.translations['UI-TESTFTP-FAIL'] = '<?php echo Escape::escapeJS(Text::_('CONFIG_DIRECTFTP_TEST_FAIL')) ?>';
	Solo.Configuration.translations['UI-TESTSFTP-OK'] = '<?php echo Escape::escapeJS(Text::_('CONFIG_DIRECTSFTP_TEST_OK')) ?>';
	Solo.Configuration.translations['UI-TESTSFTP-FAIL'] = '<?php echo Escape::escapeJS(Text::_('CONFIG_DIRECTSFTP_TEST_FAIL')) ?>';

	// Push some custom URLs
	Solo.Configuration.URLs['browser'] = '<?php echo Escape::escapeJS($router->route('index.php?view=browser&tmpl=component&processfolder=1&folder=')) ?>';
	Solo.Configuration.URLs['ftpBrowser'] = '<?php echo Escape::escapeJS($router->route('index.php?view=ftpbrowser&tmpl=component')) ?>';
	Solo.Configuration.URLs['sftpBrowser'] = '<?php echo Escape::escapeJS($router->route('index.php?view=sftpbrowser&tmpl=component')) ?>';
	Solo.Configuration.URLs['testFtp'] = '<?php echo Escape::escapeJS($router->route('index.php?view=configuration&task=testftp&format=raw')) ?>';
	Solo.Configuration.URLs['testSftp'] = '<?php echo Escape::escapeJS($router->route('index.php?view=configuration&task=testsftp&format=raw')) ?>';
	Solo.Configuration.URLs['dpeauthopen'] = '<?php echo Escape::escapeJS($router->route('index.php?view=configuration&task=dpeoauthopen&format=raw')) ?>';
	Solo.Configuration.URLs['dpecustomapi'] = '<?php echo Escape::escapeJS($router->route('index.php?view=configuration&task=dpecustomapi&format=raw')) ?>';
	Solo.System.params.AjaxURL = Solo.Configuration.URLs['dpecustomapi'];

	// Load the configuration UI data
	akeeba_ui_theme_root = '<?php echo $this->mediadir ?>';
	var data = JSON.parse("<?php echo $this->json; ?>");

	// Work around Safari which ignores autocomplete=off. WTF!
	setTimeout(function(){
		Solo.Configuration.parseConfigData(data);

		// Work around Chrome, Firefox etc "modern" browsers which blatantly ignore autocomplete=off
		setTimeout('Solo.Configuration.restoreDefaultPasswords();', 1000);

		// Enable tootip popovers
		akeeba.jQuery('[rel="popover"]').popover({
			trigger: 'manual',
			animate: false,
			html: true,
			placement: 'bottom',
			template: '<div class="popover akeeba-bootstrap-popover" onmouseover="akeeba.jQuery(this).mouseleave(function() {akeeba.jQuery(this).hide(); });"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'
		})
			.click(function (e) {
				e.preventDefault();
			})
			.mouseenter(function (e) {
				akeeba.jQuery('div.popover').remove();
				akeeba.jQuery(this).popover('show');
			});
	}, 10);

	// Initialise hooks used by the definition INI files
	akeeba_directftp_init_browser = function()
	{
		Solo.Configuration.FtpBrowser.initialise('engine.archiver.directftp.initial_directory', 'engine.archiver.directftp')
	}

	akeeba_postprocftp_init_browser = function()
	{
		Solo.Configuration.FtpBrowser.initialise('engine.postproc.ftp.initial_directory', 'engine.postproc.ftp')
	}

	akeeba_directsftp_init_browser = function()
	{
		Solo.Configuration.SftpBrowser.initialise('engine.archiver.directsftp.initial_directory', 'engine.archiver.directsftp')
	}

	akeeba_postprocsftp_init_browser = function()
	{
		Solo.Configuration.FtpBrowser.initialise('engine.postproc.sftp.initial_directory', 'engine.postproc.sftp')
	}

	directftp_test_connection = function()
	{
		Solo.Configuration.FtpTest.testConnection('engine.archiver.directftp.ftp_test','engine.archiver.directftp');
	}

	postprocftp_test_connection = function()
	{
		Solo.Configuration.FtpTest.testConnection('engine.postproc.ftp.ftp_test','engine.postproc.ftp');
	}

	directsftp_test_connection = function()
	{
		Solo.Configuration.SftpTest.testConnection('engine.archiver.directsftp.sftp_test','engine.archiver.directsftp');
	}

	postprocsftp_test_connection = function()
	{
		Solo.Configuration.SftpTest.testConnection('engine.postproc.sftp.sftp_test','engine.postproc.sftp');
	}
	}(akeeba.jQuery));
};
</script>