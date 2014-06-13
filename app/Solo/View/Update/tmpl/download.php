<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

use Awf\Text\Text;

/** @var   \Solo\View\Update\Html  $this */

$router = $this->container->router;
$token = $this->container->session->getCsrfToken()->getValue();

?>

<div id="downloadProgress">
	<div class="alert alert-warning">
		<span><?php echo Text::_('BACKUP_TEXT_BACKINGUP')?></span>
	</div>

	<div id="downloadProgressBarContainer" class="progress progress-striped active">
		<div id="downloadProgressBar" class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
			<span class="sr-only" id="downloadProgressBarInfo">0%</span>
		</div>
	</div>
	<div class="alert alert-info" id="downloadProgressInfo">
		<h4>
			<?php echo Text::_('SOLO_UPDATE_DOWNLOAD_LBL_DOWNLOADPROGRESS') ?>
		</h4>
		<div class="panel-body" id="downloadProgressBarText">

		</div>
	</div>
</div>

<div id="downloadError" style="display: none">
	<div class="alert alert-danger">
		<h4>
			<?php echo Text::_('SOLO_UPDATE_DOWNLOAD_ERR_DOWNLOADERROR_HEADER') ?>
		</h4>
		<div class="panel-body" id="downloadErrorText"></div>
	</div>
</div>

<script type="text/javascript">
Solo.loadScripts[Solo.loadScripts.length] = function () {
	(function($){
		Solo.System.errorCallback = Solo.Update.downloadErrorCallback;
		Solo.Update.errorCallback = Solo.Update.downloadErrorCallback;
		Solo.Update.translations['ERR_INVALIDDOWNLOADID'] = '<?php \Solo\Helper\Escape::escapeJS(Text::_('')) ?>';
		Solo.System.params.AjaxURL = '<?php echo $router->route('index.php?view=update&task=downloader&format=raw')?>';
		Solo.Update.nextStepUrl = '<?php echo $router->route('index.php?view=update&task=extract&token=' . $token); ?>';
		Solo.System.params.errorCallback = Solo.Update.downloadErrorCallback;
		Solo.Update.startDownload();
	}(akeeba.jQuery));
};
</script>