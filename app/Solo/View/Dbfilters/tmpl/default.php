<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

use Awf\Text\Text;
use Solo\Helper\Escape;

/** @var \Solo\View\Dbfilters\Html $this */

$router = $this->container->router;

?>
<div class="modal fade" id="dialog" tabindex="-1" role="dialog" aria-labelledby="dialogLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="dialogLabel">
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

<div class="alert alert-info">
	<strong><?php echo Text::_('CPANEL_PROFILE_TITLE'); ?></strong>
	#<?php echo $this->profileid; ?> <?php echo $this->profilename; ?>
</div>

<div class="form-inline well">
	<div>
		<label><?php echo Text::_('DBFILTER_LABEL_ROOTDIR') ?></label>
		<span><?php echo $this->root_select; ?></span>
		<button class="btn btn-danger" onclick="Solo.Dbfilters.nuke(); return false;">
			<span class="glyphicon glyphicon-trash"></span>
			<?php echo Text::_('DBFILTER_LABEL_NUKEFILTERS'); ?>
		</button>
		<button class="btn btn-warning btn-sm" onclick="Solo.Dbfilters.excludeNonCMS(); return false;">
			<span class="glyphicon glyphicon-fire"></span>
			<?php echo Text::_('DBFILTER_LABEL_EXCLUDENONCORE'); ?>
		</button>
		<a class="btn btn-sm btn-default" href="<?php echo $router->route('index.php?view=dbfilters&task=tabular')?>">
			<span class="glyphicon glyphicon-list-alt"></span>
			<?php echo Text::_('FILTERS_LABEL_VIEWALL')?>
		</a>
	</div>
</div>

<fieldset>
	<legend><?php echo Text::_('DBFILTER_LABEL_TABLES'); ?></legend>
	<div id="tables"></div>
</fieldset>

<script type="text/javascript">
Solo.loadScripts[Solo.loadScripts.length] = function () {
	(function($){

		Solo.System.params.AjaxURL = '<?php echo Escape::escapeJS($router->route('index.php?view=dbfilters&task=ajax&format=raw')) ?>';

		/**
		 * AJAX error callback
		 *
		 * @param   msg  The error message to show
		 */
		Solo.System.params.errorCallback = function(msg)
		{
			$('#dialogLabel').html('<?php echo Escape::escapeJS(Text::_('CONFIG_UI_AJAXERRORDLG_TITLE')) ?>');
			$('#dialogBody').html('');
			var alertBox = $(document.createElement('div')).addClass('alert alert-danger');
			alertBox.html('<?php echo Escape::escapeJS(Text::_('CONFIG_UI_AJAXERRORDLG_TEXT')) ?><br><pre>' + msg + '</pre>');
			alertBox.appendTo($('#dialogBody'));
			$('#dialog').modal({backdrop: 'static', keyboard: false});
		}

		// Push translations
		Solo.Dbfilters.translations['UI-ROOT'] = '<?php echo Escape::escapeJS(Text::_('FILTERS_LABEL_UIROOT')) ?>';
		Solo.Dbfilters.translations['UI-ERROR-FILTER'] = '<?php echo Escape::escapeJS(Text::_('FILTERS_LABEL_UIERRORFILTER')) ?>';
		<?php
			$filters = array('tables', 'tabledata');
			foreach($filters as $type)
			{
				echo "\tSolo.Dbfilters.translations['UI-FILTERTYPE-" . strtoupper($type)."'] = '".
					Escape::escapeJS(Text::_('DBFILTER_TYPE_' . $type)) . "';\n";
				echo "\tSolo.Fsfilters.translations['UI-FILTERTYPE-" . strtoupper($type)."'] = '".
					Escape::escapeJS(Text::_('DBFILTER_TYPE_' . $type)) . "';\n";
			}
		?>
		<?php
			$types = array('misc', 'table', 'view', 'procedure', 'function', 'trigger');
			foreach($types as $type)
			{
				echo "\tSolo.Dbfilters.translations['UI-TABLETYPE-" . strtoupper($type)."'] = '".
					Escape::escapeJS(Text::_('DBFILTER_TABLE_' . $type)) . "';\n";
			}
		?>

		// Push the location to the loading image file
		Solo.Dbfilters.loadingGif = '<?php echo \Awf\Uri\Uri::base(false, $this->container) . 'media/loading.gif' ?>';

		// Bootstrap the page display
		var data = eval(<?php echo Escape::escapeJS($this->json,"'"); ?>);

		Solo.Dbfilters.render(data);
	}(akeeba.jQuery));
};
</script>