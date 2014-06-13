<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

use Awf\Text\Text;
use Solo\Helper\Escape;

/** @var   \Solo\View\Restore\Html $this */

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

<form action="<?php echo $router->route('index.php?view=restore&task=start&id=' . $this->id)?>" method="POST" name="adminForm" id="adminForm" class="form-horizontal" role="form">
	<fieldset>
		<legend><?php echo Text::_('RESTORE_LABEL_EXTRACTIONMETHOD'); ?></legend>

		<div class="form-group">
			<label class="control-label col-sm-3 col-xs-12" for="procengine">
				<?php echo Text::_('RESTORE_LABEL_EXTRACTIONMETHOD'); ?>
			</label>

			<div class="col-sm-9 col-xs-12">
				<?php echo \Awf\Html\Select::genericList($this->extractionmodes, 'procengine', array('class' => 'form-control'), 'value', 'text', $this->ftpparams['procengine']); ?>
				<p class="help-block">
					<?php echo Text::_('RESTORE_LABEL_REMOTETIP'); ?>
				</p>
			</div>
		</div>
	</fieldset>

	<fieldset <?php echo $this->getModel()->getState('extension', '') == 'jps' ? '' : 'style="display: none;"'?>>
		<legend><?php echo Text::_('RESTORE_LABEL_JPSOPTIONS'); ?></legend>

		<div class="form-group">
			<label class="control-label col-sm-3 col-xs-12">
				<?php echo Text::_('CONFIG_JPS_KEY_TITLE') ?>
			</label>

			<div class="col-sm-9 col-xs-12">
				<input value="" type="password" class="form-control" id="jps_key" name="jps_key" placeholder="<?php echo Text::_('CONFIG_JPS_KEY_TITLE') ?>" autocomplete="off">
			</div>
		</div>
	</fieldset>

	<fieldset id="ftpOptions">
		<legend><?php echo Text::_('RESTORE_LABEL_FTPOPTIONS'); ?></legend>

		<input id="var[ftp.passive_mode]" type="checkbox" checked autocomplete="off" style="display: none">
		<input id="var[ftp.ftps]" type="checkbox" autocomplete="off" style="display: none">

		<div class="form-group">
			<label class="control-label col-sm-3 col-xs-12" for="ftp_host">
				<?php echo Text::_('CONFIG_DIRECTFTP_HOST_TITLE') ?>
			</label>

			<div class="col-sm-9 col-xs-12">
				<input id="var[ftp.host]" name="ftp_host" value="<?php echo $this->ftpparams['ftp_host']; ?>" type="text" class="form-control">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-3 col-xs-12" for="ftp_port">
				<?php echo Text::_('CONFIG_DIRECTFTP_PORT_TITLE') ?>
			</label>

			<div class="col-sm-9 col-xs-12">
				<input id="var[ftp.port]" name="ftp_port" value="<?php echo $this->ftpparams['ftp_port']; ?>" type="text" class="form-control">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-3 col-xs-12" for="ftp_user">
				<?php echo Text::_('CONFIG_DIRECTFTP_USER_TITLE') ?>
			</label>

			<div class="col-sm-9 col-xs-12">
				<input id="var[ftp.user]" name="ftp_user" value="<?php echo $this->ftpparams['ftp_user']; ?>" type="text" class="form-control">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-3 col-xs-12" for="ftp_pass">
				<?php echo Text::_('CONFIG_DIRECTFTP_PASSWORD_TITLE') ?>
			</label>

			<div class="col-sm-9 col-xs-12">
				<input id="var[ftp.pass]" name="ftp_pass" value="<?php echo $this->ftpparams['ftp_pass']; ?>"
					   type="password" class="form-control">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-3 col-xs-12" for="ftp_root">
				<?php echo Text::_('CONFIG_DIRECTFTP_INITDIR_TITLE') ?>
			</label>

			<div class="col-sm-9 col-xs-12">
				<div class="input-group">
					<input id="var[ftp.initial_directory]" name="ftp_root" value="<?php echo $this->ftpparams['ftp_root']; ?>" type="text" class="form-control">
					<div class="input-group-btn">
						<button class="btn btn-default" id="ftp-browse" onclick="return false;">
							<span class="glyphicon glyphicon-folder-open"></span>
							<?php echo Text::_('CONFIG_UI_BROWSE') ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</fieldset>

	<div class="col-sm-9 col-sm-push-3 col-xs-12">
		<button class="btn btn-primary btn-lg" id="backup-start">
			<span class="glyphicon glyphicon-repeat"></span>
			<?php echo Text::_('RESTORE_LABEL_START') ?>
		</button>
		<button class="btn btn-default" id="var[ftp.test]" onclick="return false;">
			<?php echo Text::_('CONFIG_DIRECTFTP_TEST_TITLE') ?>
		</button>
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

		// Push some custom URLs
		Solo.Configuration.URLs['browser'] = '<?php echo Escape::escapeJS($router->route('index.php?view=browser&tmpl=component&processfolder=1&folder=')) ?>';
		Solo.Configuration.URLs['ftpBrowser'] = '<?php echo Escape::escapeJS($router->route('index.php?view=ftpbrowser')) ?>';
		Solo.Configuration.URLs['testFtp'] = '<?php echo Escape::escapeJS($router->route('index.php?view=configuration&task=testftp')) ?>';

		// Button hooks
		function onProcEngineChange(e)
		{
			if ($('#procengine').val() == 'direct')
			{
				document.getElementById('ftpOptions').style.display = 'none';
			}
			else
			{
				document.getElementById('ftpOptions').style.display = 'block';
			}
		}

		$('#ftp-browse').click(function(){
			Solo.Configuration.FtpBrowser.initialise('ftp.initial_directory', 'ftp')
		});

		$(document.getElementById('var[ftp.test]')).click(function(){
			Solo.Configuration.FtpTest.testConnection('ftp.test', 'ftp');
		});

		$('#procengine').change(onProcEngineChange);

		onProcEngineChange();
	}(akeeba.jQuery));
};
</script>