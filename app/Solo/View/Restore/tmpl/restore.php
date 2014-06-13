<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

use Awf\Text\Text;

/** @var   \Solo\View\Restore\Html $this */

$router = $this->container->router;

?>
<div class="alert alert-warning">
	<span class="glyphicon glyphicon-warning-sign"></span>
	<?php echo Text::_('RESTORE_LABEL_DONOTCLOSE'); ?>
</div>

<div id="restoration-progress">
	<h3><?php echo Text::_('RESTORE_LABEL_INPROGRESS') ?></h3>

	<table class="table table-striped">
		<tr>
			<td width="25%">
				<?php echo Text::_('RESTORE_LABEL_BYTESREAD'); ?>
			</td>
			<td>
				<span id="extbytesin"></span>
			</td>
		</tr>
		<tr>
			<td width="25%">
				<?php echo Text::_('RESTORE_LABEL_BYTESEXTRACTED'); ?>
			</td>
			<td>
				<span id="extbytesout"></span>
			</td>
		</tr>
		<tr>
			<td width="25%">
				<?php echo Text::_('RESTORE_LABEL_FILESEXTRACTED'); ?>
			</td>
			<td>
				<span id="extfiles"></span>
			</td>
		</tr>
	</table>

	<div id="response-timer">
		<div class="color-overlay"></div>
		<div class="text"></div>
	</div>
</div>

<div id="restoration-error" style="display:none">
	<div class="alert alert-danger">
		<h3 class="alert-heading"><?php echo Text::_('RESTORE_LABEL_FAILED'); ?></h3>
		<div id="errorframe">
			<p><?php echo Text::_('RESTORE_LABEL_FAILED_INFO'); ?></p>
			<p id="backup-error-message">
			</p>
		</div>
	</div>
</div>

<div id="restoration-extract-ok" style="display:none">
	<div class="alert alert-success">
		<h3 class="alert-heading"><?php echo Text::_('RESTORE_LABEL_SUCCESS'); ?></h3>
		<?php if (empty($this->siteURL)): ?>
		<p>
			<?php echo Text::_('SOLO_RESTORE_LABEL_SUCCESS_INFO'); ?>
		</p>
		<?php else: ?>
		<p>
			<?php echo Text::sprintf('SOLO_RESTORE_LABEL_SUCCESS_INFO_HASURL', $this->siteURL, $this->siteURL); ?>
		</p>
		<?php endif; ?>
	</div>

	<?php if (!empty($this->siteURL)): ?>
	<button class="btn btn-success" id="restoration-runinstaller" onclick="Solo.Restore.runInstaller('<?php echo $this->siteURL?>'); return false;">
		<span class="fa fa-rocket"></span>
		<?php echo Text::_('SOLO_RESTORE_BTN_INSTALLER'); ?>
	</button>
	<?php endif; ?>

	<button class="btn btn-danger" id="restoration-finalize" onclick="Solo.Restore.finalize(); return false;">
		<span class="fa fa-power-off"></span>
		<?php echo Text::_('RESTORE_LABEL_FINALIZE'); ?>
	</button>
</div>

<script type="text/javascript" language="javascript">
Solo.loadScripts[Solo.loadScripts.length] = function () {
	(function($){
		Solo.Restore.password = '<?php echo $this->password; ?>';
		Solo.Restore.ajaxURL = '<?php echo \Awf\Uri\Uri::base(false, $this->container) ?>restore.php';
		Solo.Restore.mainURL = '<?php echo $router->route('index.php')?>';

		Solo.Restore.translations['UI-LASTRESPONSE'] = '<?php echo \Solo\Helper\Escape::escapeJS(Text::_('BACKUP_TEXT_LASTRESPONSE')) ?>';

		Solo.Restore.errorCallback = Solo.Restore.errorCallbackDefault;

		Solo.Restore.pingRestoration();
	}(akeeba.jQuery));
};
</script>