<?php
/**
 * @package     Solo
 * @copyright   2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 *
 * @var \Solo\View\Backup\Html $this
 */

use Awf\Text\Text;
use Solo\Helper\Escape;

/** @var \Solo\View\Backup\Html $this */

$router = $this->container->router;
$config = \AEFactory::getConfiguration();

$quirks_style = $this->hasErrors ? 'alert-danger' : 'alert-warning';
$formstyle = $this->hasErrors ? 'style="display: none"' : '';

$configuration = AEFactory::getConfiguration();

?>
<div id="backup-setup">
	<h3>
		<?php echo Text::_('BACKUP_HEADER_STARTNEW') ?>
	</h3>

	<?php if ($this->hasQuirks && !$this->unwritableOutput): ?>
		<div id="quirks" class="alert <?php echo $quirks_style ?>">
			<h4 class="alert-heading">
				<?php if (!$this->hasCriticalErrors): ?>
					<?php echo Text::_('BACKUP_LABEL_DETECTEDQUIRKS') ?>
				<?php else: ?>
					<?php echo Text::_('STATUS_ERROR') ?>
				<?php endif; ?>
			</h4>
			<p><?php echo Text::_('BACKUP_LABEL_QUIRKSLIST') ?></p>
			<ul>
			<?php foreach ($this->quirks as $quirk):
				switch ($quirk['severity'])
				{
					case 'critical':
						$classSufix = 'danger';
						break;

					case 'high':
						$classSufix = 'warning';
						break;

					case 'medium':
						$classSufix = 'info';
						break;

					default:
						$classSufix = 'default';
						break;
				}
			?>
			<li>
				<a href="<?php echo $quirk['help_url']; ?>" target="_blank">
					<span class="label label-<?php echo $classSufix ?>">
						S<?php echo $quirk['code']; ?>
					</span>
					<?php echo $quirk['description']; ?>
				</a>
			</li>
			<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if($this->unwritableOutput): $formstyle="style=\"display: none;\"" ?>
		<div id="akeeba-fatal-outputdirectory" class="alert alert-danger">
			<?php if(isset($this->srpInfo['tag']) && ($this->srpInfo['tag'] == 'restorepoint')): ?>
				<p>
					<?php echo Text::_('BACKUP_ERROR_UNWRITABLEOUTPUT_SRP') ?>
				</p>
			<?php elseif($this->autoStart): ?>
				<p>
					<?php echo Text::_('BACKUP_ERROR_UNWRITABLEOUTPUT_AUTOBACKUP') ?>
				</p>
			<?php else: ?>
				<p>
					<?php echo Text::_('BACKUP_ERROR_UNWRITABLEOUTPUT_NORMALBACKUP') ?>
				</p>
			<?php endif; ?>
			<p>
				<?php echo Text::sprintf(
					'BACKUP_ERROR_UNWRITABLEOUTPUT_COMMON',
					$router->route('index.php?view=configuration'),
					'https://www.akeebabackup.com/warnings/q001.html'
				) ?>
			</p>
		</div>
	<?php endif; ?>

	<?php $row = 1 ?>

	<?php if(!$this->unwritableOutput && !$this->hasCriticalErrors):?>
	<div class="panel panel-default">
		<div class="panel-body">
			<form action="<?php echo $router->route('index.php?view=backup')?>" method="post" name="flipForm" id="flipForm" autocomplete="off">
				<input type="hidden" name="returnurl" value="<?php htmlentities($this->returnURL, ENT_COMPAT, 'UTF-8', false) ?>" />
				<input type="hidden" name="token" value="<?php echo $this->container->session->getCsrfToken()->getValue() ?>" />

				<div class="col-xs-12">
					<label>
						<?php echo Text ::_('CPANEL_PROFILE_TITLE'); ?>: #<?php echo $this->profileId; ?>
					</label>
				</div>
				<div class="col-md-8 col-sm-12">
					<?php echo \Awf\Html\Select::genericList($this->profileList, 'profile', array('onchange' => "document.forms.profileForm.submit()", 'class' => 'form-control'), 'value', 'text', $this->profileId); ?>
				</div>
				<div class="col-md-4 col-sm-12">
					<button class="btn btn-sm btn-default" onclick="this.form.submit(); return false;">
						<span class="glyphicon glyphicon-share-alt"></span>
						<?php echo Text::_('CPANEL_PROFILE_BUTTON'); ?>
					</button>
				</div>
			</form>
		</div>
	</div>
	<?php endif; ?>

	<form id="dummyForm" <?php echo $formstyle ?> class="form-horizontal" role="form">
		<div class="form-group">
			<label class="control-label col-sm-3" for="description">
				<?php echo Text::_('BACKUP_LABEL_DESCRIPTION'); ?>
			</label>
			<div class="col-sm-9">
				<input type="text" name="description" value="<?php echo $this->description; ?>"
					   maxlength="255" size="80" id="backup-description"
					   class="form-control" autocomplete="off" />
				<span class="help-block"><?php echo Text::_('BACKUP_LABEL_DESCRIPTION_HELP'); ?></span>
			</div>
		</div>

		<?php if($this->showJPSKey): ?>
			<div class="form-group">
				<label class="control-label col-sm-3" for="jpskey">
					<?php echo Text::_('CONFIG_JPS_KEY_TITLE'); ?>
				</label>
				<div class="col-sm-9">
					<input type="password" name="jpskey" value="<?php echo htmlentities($this->jpsKey, ENT_COMPAT, 'UTF-8', false) ?>"
						   size="50" id="jpskey" autocomplete="off" class="form-control" />
					<span class="help-block"><?php echo Text::_('CONFIG_JPS_KEY_DESCRIPTION'); ?></span>
				</div>
			</div>
		<?php endif; ?>
		<?php if(AKEEBA_PRO && $this->showANGIEKey): ?>
			<div class="form-group">
				<label class="control-label col-sm-3" for="angiekey">
					<?php echo Text::_('CONFIG_ANGIE_KEY_TITLE'); ?>
				</label>
				<div class="col-sm-9">
					<input type="password" name="angiekey" value="<?php echo htmlentities($this->angieKey, ENT_COMPAT, 'UTF-8', false) ?>"
						   size="50" id="angiekey" autocomplete="off" class="form-control" />
					<span class="help-block"><?php echo Text::_('CONFIG_ANGIE_KEY_DESCRIPTION'); ?></span>
				</div>
			</div>
		<?php endif; ?>
		<div class="form-group">
			<label class="control-label col-sm-3" for="comment">
				<?php echo Text::_('BACKUP_LABEL_COMMENT'); ?>
			</label>
			<div class="col-sm-9">
				<textarea name="comment" id="comment" rows="5" cols="73" class="form-control" autocomplete="off"><?php echo $this->comment ?></textarea>
				<span class="help-block"><?php echo Text::_('BACKUP_LABEL_COMMENT_HELP'); ?></span>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-9 col-lg-push-3">
				<button class="btn btn-primary" id="backup-start" onclick="return false;">
					<span class="glyphicon glyphicon-compressed"></span>
					<?php echo Text::_('BACKUP_LABEL_START') ?>
				</button>
				<span class="btn btn-warning" id="backup-default">
					<i class="glyphicon glyphicon-refresh"></i>
					<?php echo Text::_('BACKUP_LABEL_RESTORE_DEFAULT')?>
				</span>
			</div>
		</div>
	</form>
</div>

<div id="angie-password-warning" class="alert alert-danger alert-error" style="display: none">
	<h1><?php echo Text::_('BACKUP_ANGIE_PASSWORD_WARNING_HEADER')?></h1>

	<p><?php echo Text::_('BACKUP_ANGIE_PASSWORD_WARNING_1')?></p>
	<p><?php echo Text::_('BACKUP_ANGIE_PASSWORD_WARNING_2')?></p>
	<p><?php echo Text::_('BACKUP_ANGIE_PASSWORD_WARNING_3')?></p>
</div>

<div id="backup-progress-pane" style="display: none">
	<div class="alert alert-warning">
		<span class="glyphicon glyphicon-warning-sign"></span>
		<?php echo Text::_('BACKUP_TEXT_BACKINGUP'); ?>
	</div>
	<fieldset>
		<legend><?php echo Text::_('BACKUP_LABEL_PROGRESS') ?></legend>
		<div id="backup-progress-content">
			<div id="backup-steps">
			</div>
			<div id="backup-status" class="well">
				<div id="backup-step"></div>
				<div id="backup-substep"></div>
			</div>
			<div id="backup-percentage" class="progress">
				<div class="bar progress-bar" role="progressbar" style="width: 0%"></div>
			</div>
			<div id="response-timer">
				<div class="color-overlay"></div>
				<div class="text"></div>
			</div>
		</div>
		<span id="ajax-worker"></span>
	</fieldset>
</div>

<div id="backup-complete" style="display: none">
	<div class="alert alert-success alert-block">
		<h2 class="alert-heading">
			<?php echo Text::_(empty($this->returnURL) ? 'BACKUP_HEADER_BACKUPFINISHED' : 'BACKUP_HEADER_BACKUPWITHRETURNURLFINISHED'); ?>
		</h2>

		<div id="finishedframe">
			<p>
				<?php if(empty($this->returnURL)): ?>
					<?php echo Text::_('BACKUP_TEXT_CONGRATS') ?>
				<?php else: ?>
					<?php echo Text::_('BACKUP_TEXT_PLEASEWAITFORREDIRECTION') ?>
				<?php endif; ?>
			</p>

			<?php if(empty($this->returnURL)): ?>
				<a class="btn btn-primary btn-lg" href="<?php echo $router->route('index.php?view=manage') ?>">
					<span class="glyphicon glyphicon-list"></span>
					<?php echo Text::_('BUADMIN'); ?>
				</a>
				<a class="btn btn-default" id="ab-viewlog-success" href="<?php echo $router->route('index.php?view=log') ?>">
					<span class="fa fa-edit"></span>
					<?php echo Text::_('VIEWLOG'); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>

<div id="backup-warnings-panel" style="display:none">
	<div class="alert alert-warning">
		<h3 class="alert-heading"><?php echo Text::_('BACKUP_LABEL_WARNINGS') ?></h3>
		<div id="warnings-list">
		</div>
	</div>
</div>

<div id="error-panel" style="display: none">
	<div class="alert alert-danger">
		<h3 class="alert-heading">
			<?php echo Text::_('BACKUP_HEADER_BACKUPFAILED'); ?>
		</h3>
		<div id="errorframe">
			<p>
				<?php echo Text::_('BACKUP_TEXT_BACKUPFAILED') ?>
			</p>
			<p id="backup-error-message">
			</p>

			<?php if(AKEEBA_PRO):?>
				<p>
					<?php echo Text::_('BACKUP_TEXT_READLOGFAILPRO') ?>
				</p>
			<?php else: ?>
				<p>
					<?php echo Text::_('BACKUP_TEXT_READLOGFAIL') ?>
				</p>
			<?php endif; ?>

			<div class="alert alert-block alert-info">
				<p>
					<?php if(AKEEBA_PRO):?>
						<?php echo Text::_('BACKUP_TEXT_RTFMTOSOLVEPRO') ?>
					<?php endif; ?>
					<?php echo Text::sprintf('BACKUP_TEXT_RTFMTOSOLVE', 'https://www.akeebabackup.com/documentation/troubleshooter/abbackup.html?utm_source=akeeba_backup&utm_campaign=backuperrorlink') ?>
				</p>
				<p>
					<?php if(AKEEBA_PRO):?>
						<?php echo Text::sprintf('BACKUP_TEXT_SOLVEISSUE_PRO', 'https://www.akeebabackup.com/support.html?utm_source=akeeba_backup&utm_campaign=backuperrorpro') ?>
					<?php else: ?>
						<?php echo Text::sprintf('BACKUP_TEXT_SOLVEISSUE_CORE', 'https://www.akeebabackup.com/subscribe.html?utm_source=akeeba_backup&utm_campaign=backuperrorcore','https://www.akeebabackup.com/support.html?utm_source=akeeba_backup&utm_campaign=backuperrorcore') ?>
					<?php endif; ?>
					<?php echo Text::sprintf('BACKUP_TEXT_SOLVEISSUE_LOG', 'index.php?option=com_akeeba&view=log&tag=backend') ?>
				</p>
			</div>

			<?php if(AKEEBA_PRO):?>
				<button class="btn btn-lg btn-primary" onclick="window.location='<?php echo $router->route('index.php?view=alice') ?>'; return false;">
					<span class="fa fa-fire-extinguisher"></span>
					<?php echo Text::_('BACKUP_ANALYSELOG') ?>
				</button>
				<button class="btn btn-default btn-sm" onclick="window.location='https://www.akeebabackup.com/documentation/troubleshooter/abbackup.html?utm_source=akeeba_backup&utm_campaign=backuperrorbutton'; return false;">
					<span class="fa fa-share"></span>
					<?php echo Text::_('BACKUP_TROUBLESHOOTINGDOCS') ?>
				</button>
				<button class="btn btn-default btn-sm" onclick="window.location='<?php echo $router->route('index.php?view=log') ?>'; return false;">
					<span class="fa fa-edit"></span>
					<?php echo Text::_('VIEWLOG'); ?>
				</button>

			<?php else: ?>
				<a class="btn btn-lg btn-primary" href="https://www.akeebabackup.com/documentation/troubleshooter/abbackup.html?utm_source=akeeba_backup&utm_campaign=backuperrorbuttonsolo">
					<span class="fa fa-share"></span>
					<?php echo Text::_('BACKUP_TROUBLESHOOTINGDOCS') ?>
				</a>
				<a class="btn btn-default btn-sm" id="ab-viewlog-error" href="<?php echo $router->route('index.php?view=log') ?>">
					<span class="fa fa-edit"></span>
					<?php echo Text::_('VIEWLOG'); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>

<div id="retry-panel" style="display: none">
	<div class="alert alert-warning">
		<h3 class="alert-heading">
			<?php echo Text::_('BACKUP_HEADER_BACKUPRETRY'); ?>
		</h3>
		<div id="retryframe">
			<p><?php echo Text::_('BACKUP_TEXT_BACKUPFAILEDRETRY') ?></p>
			<p>
				<strong>
					<?php echo Text::_('BACKUP_TEXT_WILLRETRY') ?>
					<span id="akeeba-retry-timeout">0</span>
					<?php echo Text::_('BACKUP_TEXT_WILLRETRYSECONDS') ?>
				</strong>
				<br/>
				<button class="btn btn-danger btn-sm" onclick="Solo.Backup.cancelResume(); return false;">
					<span class="icon-cancel"></span>
					<?php echo Text::_('UI-MULTIDB-CANCEL'); ?>
				</button>
				<button class="btn btn-success btn-sm" onclick="Solo.Backup.resumeBackup(); return false;">
					<span class="icon-ok-circle"></span>
					<?php echo Text::_('BACKUP_TEXT_BTNRESUME'); ?>
				</button>
			</p>

			<p><?php echo Text::_('BACKUP_TEXT_LASTERRORMESSAGEWAS') ?></p>
			<p id="backup-error-message-retry">
			</p>
		</div>
	</div>
</div>

<script type="application/javascript">
Solo.loadScripts[Solo.loadScripts.length] = function () {
	(function($){
		Solo.Backup.returnUrl = '<?php echo Escape::escapeJS($this->returnURL) ?>';
		Solo.Backup.isSTW = <?php echo $this->isSTW ? 'true' : 'false' ?>;
		Solo.Backup.maxExecutionTime = '<?php echo $this->maxexec ?>';
		Solo.Backup.runtimeBias = '<?php echo $this->bias ?>';
		Solo.Backup.domains = JSON.parse("<?php echo $this->domains ?>");
		Solo.System.params.AjaxURL = '<?php echo $router->route('index.php?view=backup&task=ajax')?>';
		Solo.Backup.URLs.LogURL = '<?php echo $router->route('index.php?view=backup&task=log')?>';
		Solo.System.params.useIFrame = <?php echo $this->useIframe ?>;
		Solo.Backup.srpInfo = JSON.parse('<?php echo Escape::escapeJS(json_encode($this->srpInfo)) ?>');
		Solo.Backup.default_descr = '<?php echo $this->default_descr ?>';
		Solo.Backup.config_angiekey = '<?php echo $this->angieKey ?>';
		Solo.Backup.jpsKey = '<?php echo $this->jpsKey ?>';

		// Auto-resume setup
		Solo.Backup.resume.enabled = <?php echo (int)$configuration->get('akeeba.advanced.autoresume', 1); ?>;
		Solo.Backup.resume.timeout = <?php echo (int)$configuration->get('akeeba.advanced.autoresume_timeout', 10); ?>;
		Solo.Backup.resume.maxRetries = <?php echo (int)$configuration->get('akeeba.advanced.autoresume_maxretries', 3); ?>;
		Solo.Backup.resume.retry = 0;

		// Work around Safari which ignores autocomplete=off (FOR CRYING OUT LOUD!)
		setTimeout('Solo.Backup.restoreDefaultOptions();', 500);

		// Create a function for saving the editor's contents
		akeeba_comment_editor_save = function() {
		}

		// Push translations
		Solo.Backup.translations['UI-LASTRESPONSE']			= '<?php echo Escape::escapeJS(Text::_('BACKUP_TEXT_LASTRESPONSE')) ?>';
		Solo.Backup.translations['UI-STW-CONTINUE']			= '<?php echo Escape::escapeJS(Text::_('STW_MSG_CONTINUE')) ?>';

		<?php if (!$this->unwritableOutput && ($this->autoStart || (isset($this->srpInfo['tag']) && ($this->srpInfo['tag'] == 'restorepoint')))):?>
		Solo.Backup.start();
		<?php else: ?>
		$('#backup-start').bind("click", function(e){
			Solo.Backup.start();
		});

		$('#backup-default').click(Solo.Backup.restoreDefaultOptions);
		<?php endif; ?>

	}(akeeba.jQuery));
};
</script>