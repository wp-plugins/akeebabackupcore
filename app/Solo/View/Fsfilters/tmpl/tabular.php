<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

use Awf\Text\Text;
use Solo\Helper\Escape;

/** @var \Solo\View\Fsfilters\Html $this */

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


<div class="alert alert-info">
	<strong><?php echo Text::_('CPANEL_PROFILE_TITLE'); ?></strong>
	#<?php echo $this->profileid; ?> <?php echo $this->profilename; ?>
</div>

<div class="form-inline well">
	<div>
		<label><?php echo Text::_('FSFILTER_LABEL_ROOTDIR') ?></label>
		<span><?php echo $this->root_select; ?></span>
		<a class="btn btn-sm btn-default" href="<?php echo $router->route('index.php?view=fsfilters')?>">
			<span class="glyphicon glyphicon-list-alt"></span>
			<?php echo Text::_('FILTERS_LABEL_NORMALVIEW')?>
		</a>
	</div>
	<div id="addnewfilter">
		<?php echo Text::_('FSFILTER_LABEL_ADDNEWFILTER') ?>
		<button class="btn btn-default" onclick="Solo.Fsfilters.addNew('directories'); return false;">
			<span class="fa fa-ban"></span>
			<?php echo Text::_('FSFILTER_TYPE_DIRECTORIES') ?>
		</button>
		<button class="btn btn-default" onclick="Solo.Fsfilters.addNew('skipfiles'); return false;">
			<span class="fa fa-file"></span>
			<?php echo Text::_('FSFILTER_TYPE_SKIPFILES') ?>
		</button>
		<button class="btn btn-default" onclick="Solo.Fsfilters.addNew('skipdirs'); return false;">
			<span class="fa fa-folder"></span>
			<?php echo Text::_('FSFILTER_TYPE_SKIPDIRS') ?>
		</button>
		<button class="btn btn-default" onclick="Solo.Fsfilters.addNew('files'); return false;">
			<span class="fa fa-file-text"></span>
			<?php echo Text::_('FSFILTER_TYPE_FILES') ?>
		</button>
	</div>
</div>

<fieldset id="ak_roots_container_tab">
	<div id="ak_list_container">
		<table id="ak_list_table" class="table table-striped">
			<thead>
			<tr>
				<td width="250px"><?php echo Text::_('FILTERS_LABEL_TYPE') ?></td>
				<td><?php echo Text::_('FILTERS_LABEL_FILTERITEM') ?></td>
			</tr>
			</thead>
			<tbody id="ak_list_contents">
			</tbody>
		</table>
	</div>
</fieldset>

<script type="text/javascript">
Solo.loadScripts[Solo.loadScripts.length] = function () {
	(function($){
		Solo.System.params.AjaxURL = '<?php echo Escape::escapeJS($router->route('index.php?view=fsfilters&task=ajax&format=raw')) ?>';

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
		Solo.Fsfilters.translations['UI-ROOT'] = '<?php echo Escape::escapeJS(Text::_('FILTERS_LABEL_UIROOT')) ?>';
		Solo.Fsfilters.translations['UI-ERROR-FILTER'] = '<?php echo Escape::escapeJS(Text::_('FILTERS_LABEL_UIERRORFILTER')) ?>';
		<?php
			$filters = array('directories', 'skipfiles', 'skipdirs', 'files');
			foreach($filters as $type)
			{
				echo "\tSolo.Fsfilters.translations['UI-FILTERTYPE-" . strtoupper($type)."'] = '".
					Escape::escapeJS(Text::_('FSFILTER_TYPE_' . $type)) . "';\n";
			}
		?>

		// Push the location to the loading image file
		Solo.Fsfilters.loadingGif = '<?php echo \Awf\Uri\Uri::base(false, $this->container) . 'media/loading.gif' ?>';

		// Bootstrap the page display
		var data = eval(<?php echo Escape::escapeJS($this->json,"'"); ?>);

		Solo.Fsfilters.renderTab(data);
	}(akeeba.jQuery));
};
</script>