<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

use Awf\Text\Text;
use Solo\Helper\Escape;

/** @var \Solo\View\Discover\Html $this */

$router = $this->container->router;

?>

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

<?php if (!AKEEBA_PRO && (rand(0, 9) == 0)): ?>
	<div style="border: thick solid green; border-radius: 10pt; padding: 1em; background-color: #f0f0ff; color: #333; font-weight: bold; text-align: center; margin: 1em 0">
		<p><?php echo Text::_('SOLO_MAIN_LBL_SUBSCRIBE_TEXT') ?></p>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="text-align: center; margin: 0px;">
			<input type="hidden" name="cmd" value="_s-xclick" />
			<input type="hidden" name="hosted_button_id" value="3NTKQ3M2DYPYW" />
			<button onclick="this.form.submit(); return false;" class="btn btn-success">
				<img src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" border="0">
				Donate via PayPal
			</button>
			<a class="small" style="font-weight: normal; color: #666" href="https://www.akeebabackup.com/subscribe/new/backupwp.html?layout=default">
				<?php echo Text::_('SOLO_MAIN_BTN_SUBSCRIBE_UNOBTRUSIVE'); ?>
			</a>
		</form>
	</div>
<?php endif; ?>

<?php if (AKEEBA_PRO): ?>
<div class="alert alert-info">
	<?php echo Text::sprintf('DISCOVER_LABEL_S3IMPORT', $router->route('index.php?view=s3import')) ?>
	<a class="btn btn-sm btn-default" href="<?php echo $router->route('index.php?view=s3import') ?>">
		<span class="glyphicon glyphicon-cloud-download"></span>
		<?php echo Text::_('S3IMPORT') ?>
	</a>
</div>
<?php endif; ?>

<form name="adminForm" id="adminForm" action="<?php echo $router->route('index.php?view=discover&task=discover') ?>" method="POST" class="form-horizontal" role="form">
	<input type="hidden" name="token" value="<?php echo $this->container->session->getCsrfToken()->getValue(); ?>" />
	
	<div class="form-group">
		<label class="control-label col-sm-2">
			<?php echo Text::_('DISCOVER_LABEL_DIRECTORY'); ?>
		</label>
		<div class="col-sm-10">
			<div class="input-group">
				<input type="text" name="directory" id="directory" value="<?php echo $this->directory ?>"
					   class="form-control">
				<span class="input-group-btn">
					<button title="<?php echo Text::_('CONFIG_UI_BROWSE')?>" class="btn btn-default" id="btnBrowse">
						<span class="glyphicon glyphicon-folder-open"></span>
					</button>
				</span>
			</div>
			<div class="help-block">
				<?php echo Text::_('DISCOVER_LABEL_SELECTDIR'); ?>
			</div>
		</div>
	</div>
	<div class="col-sm-10 col-sm-push-2">
		<button class="btn btn-primary" onclick="this.form.submit(); return false;">
			<span class="fa fa-search"></span>
			<?php echo Text::_('DISCOVER_LABEL_SCAN') ?>
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
		Solo.Configuration.translations['UI-ROOT'] = '<?php echo Escape::escapeJS(Text::_('SOLO_COMMON_LBL_ROOT')) ?>';

		// Push some custom URLs
		Solo.Configuration.URLs['browser'] = '<?php echo Escape::escapeJS($router->route('index.php?view=browser&tmpl=component&processfolder=1&folder=')) ?>';

		// Setup buttons
		$('#btnBrowse').click(function(e){
			var element = $(document.getElementById('directory'));
			var folder = element.val();
			Solo.Configuration.onBrowser(folder, element);
			e.preventDefault();
			return false;
		})
	}(akeeba.jQuery));
};
</script>