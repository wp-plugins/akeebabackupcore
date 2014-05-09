<?php
/**
 * @package     Solo
 * @copyright   2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 *
 * @var \Solo\View\Setup\Html $this
 */

use Awf\Text\Text;
use Solo\Helper\Escape;

/** @var \Solo\View\Wizard\Html $this */

$router = $this->container->router;
$config = \AEFactory::getConfiguration();

?>
<div id="akeeba-confwiz">
	<div id="backup-progress-pane" style="display: none">
		<div class="alert alert-info">
			<?php echo Text::_('AKEEBA_WIZARD_INTROTEXT'); ?>
		</div>

		<fieldset id="backup-progress-header">
			<legend><?php echo Text::_('AKEEEBA_WIZARD_PROGRESS') ?></legend>
			<div id="backup-progress-content">
				<div id="backup-steps">
					<div id="step-ajax" class="label label-default"><?php echo Text::_('AKEEBA_CONFWIZ_AJAX'); ?></div>
					<div id="step-minexec" class="label label-default"><?php echo Text::_('AKEEBA_CONFWIZ_MINEXEC'); ?></div>
					<div id="step-directory" class="label label-default"><?php echo Text::_('AKEEBA_CONFWIZ_DIRECTORY'); ?></div>
					<div id="step-dbopt" class="label label-default"><?php echo Text::_('AKEEBA_CONFWIZ_DBOPT'); ?></div>
					<div id="step-maxexec" class="label label-default"><?php echo Text::_('AKEEBA_CONFWIZ_MAXEXEC'); ?></div>
					<div id="step-splitsize" class="label label-default"><?php echo Text::_('AKEEBA_CONFWIZ_SPLITSIZE'); ?></div>
				</div>
				<div class="well">
					<div id="backup-substep">
					</div>
				</div>
			</div>
			<span id="ajax-worker"></span>
		</fieldset>

	</div>

	<div id="error-panel" class="alert alert-danger" style="display:none">
		<h2 class="alert-heading"><?php echo Text::_('AKEEBA_WIZARD_HEADER_FAILED'); ?></h2>
		<div id="errorframe">
			<p id="backup-error-message">
			</p>
		</div>
	</div>

	<div id="backup-complete" style="display: none">
		<div class="alert alert-success alert-block">
			<h2 class="alert-heading"><?php echo Text::_('AKEEBA_WIZARD_HEADER_FINISHED'); ?></h2>
			<div id="finishedframe">
				<p>
					<?php echo Text::_('AKEEBA_WIZARD_CONGRATS') ?>
				</p>
			</div>
			<button class="btn btn-primary btn-large" onclick="window.location='<?php echo $router->route('index.php?&view=backup') ?>'; return false;">
				<span class="glyphicon glyphicon-compressed"></span>
				<?php echo Text::_('BACKUP'); ?>
			</button>
			<button class="btn btn-default" onclick="window.location='<?php echo $router->route('index.php?&view=configuration') ?>'; return false;">
				<span class="icon-wrench"></span>
				<?php echo Text::_('CONFIGURATION'); ?>
			</button>
		</div>

	</div>
</div>

<script type="text/javascript">
Solo.loadScripts[Solo.loadScripts.length] = function () {
	(function($){
		Solo.System.params.AjaxURL = '<?php echo $router->route('index.php?view=wizard&task=ajax')?>';

		Solo.Wizard.translation['UI-TRYAJAX'] 				= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_TRYAJAX')) ?>';
		Solo.Wizard.translation['UI-TRYIFRAME'] 			= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_TRYIFRAME')) ?>';
		Solo.Wizard.translation['UI-CANTUSEAJAX'] 			= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_CANTUSEAJAX')) ?>';
		Solo.Wizard.translation['UI-MINEXECTRY'] 			= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_MINEXECTRY')) ?>';
		Solo.Wizard.translation['UI-CANTDETERMINEMINEXEC'] 	= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_CANTDETERMINEMINEXEC')) ?>';
		Solo.Wizard.translation['UI-SAVEMINEXEC'] 			= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_SAVEMINEXEC')) ?>';
		Solo.Wizard.translation['UI-CANTSAVEMINEXEC'] 		= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_CANTSAVEMINEXEC')) ?>';
		Solo.Wizard.translation['UI-CANTFIXDIRECTORIES'] 	= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_CANTFIXDIRECTORIES')) ?>';
		Solo.Wizard.translation['UI-CANTDBOPT'] 			= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_CANTDBOPT')) ?>';
		Solo.Wizard.translation['UI-EXECTOOLOW'] 			= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_EXECTOOLOW')) ?>';
		Solo.Wizard.translation['UI-MAXEXECTRY'] 			= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_MINEXECTRY')) ?>';
		Solo.Wizard.translation['UI-SAVINGMAXEXEC'] 		= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_SAVINGMAXEXEC')) ?>';
		Solo.Wizard.translation['UI-CANTSAVEMAXEXEC'] 		= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_CANTSAVEMAXEXEC')) ?>';
		Solo.Wizard.translation['UI-CANTDETERMINEPARTSIZE']	= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_CANTDETERMINEPARTSIZE')) ?>';
		Solo.Wizard.translation['UI-PARTSIZE'] 				= '<?php echo Escape::escapeJS(Text::_('AKEEBA_WIZARD_UI_PARTSIZE')) ?>';

		Solo.Backup.translations['UI-LASTRESPONSE']			= '<?php echo Escape::escapeJS(Text::_('BACKUP_TEXT_LASTRESPONSE')) ?>';

		Solo.Wizard.boot();
	}(akeeba.jQuery));
};
</script>