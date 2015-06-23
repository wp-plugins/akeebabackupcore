<?php
/**
 * @package     Solo
 * @copyright   2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

use \Awf\Text\Text;

// Used for type hinting
/** @var \Solo\View\Manage\Html $this */

$router = $this->container->router;

$token = $this->container->session->getCsrfToken()->getValue();

$dateFormat = $this->getContainer()->appConfig->get('dateformat', '');
$dateFormat = trim($dateFormat);
$dateFormat = !empty($dateFormat) ? $dateFormat : Text::_('DATE_FORMAT_LC4');
$dateFormat = !empty($dateFormat) ? $dateFormat : Text::_('DATE_FORMAT_LC4');

?>

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
	<button class="close" data-dismiss="alert">×</button>
	<h4 class="alert-heading"><?php echo Text::_('BUADMIN_LABEL_HOWDOIRESTORE_LEGEND') ?></h4>

	<p><?php echo Text::sprintf('SOLO_MANAGE_LBL_HOWDOIRESTORE_PRO', 'https://www.akeebabackup.com/documentation/akeeba-solo/restoring-backups.html') ?></p>
</div>

<form action="<?php echo $router->route('index.php?view=manage')?>" method="post" name="adminForm" id="adminForm" role="form">
	<input type="hidden" name="boxchecked" id="boxchecked" value="0">
	<input type="hidden" name="task" id="task" value="default">
	<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists->order ?>">
	<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists->order_Dir ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<table class="table table-striped" id="itemsList">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="Solo.System.checkAll(this);"/>
				</th>
				<th width="20" class="hidden-phone">
					<?php echo \Awf\Html\Grid::sort('STATS_LABEL_ID', 'id', $this->lists->order_Dir, $this->lists->order, 'default'); ?>
				</th>
				<th width="240">
					<?php echo \Awf\Html\Grid::sort('STATS_LABEL_DESCRIPTION', 'description', $this->lists->order_Dir, $this->lists->order, 'default'); ?>
				</th>
				<th width="80">
					<?php echo \Awf\Html\Grid::sort('STATS_LABEL_START', 'backupstart', $this->lists->order_Dir, $this->lists->order, 'default'); ?>
				</th>
				<th width="80" class="hidden-xs">
					<?php echo Text::_('STATS_LABEL_DURATION'); ?>
				</th>
				<th width="80">
					<?php echo Text::_('STATS_LABEL_STATUS'); ?>
				</th>
				<th width="80" class="hidden-xs">
					<?php echo \Awf\Html\Grid::sort('STATS_LABEL_ORIGIN', 'origin', $this->lists->order_Dir, $this->lists->order, 'default'); ?>
				</th>
				<th width="80" class="hidden-xs">
					<?php echo \Awf\Html\Grid::sort('STATS_LABEL_TYPE', 'type', $this->lists->order_Dir, $this->lists->order, 'default'); ?>
				</th>
				<th width="20" class="hidden-xs">
					<?php echo \Awf\Html\Grid::sort('STATS_LABEL_PROFILEID', 'profile_id', $this->lists->order_Dir, $this->lists->order, 'default'); ?>
				</th>
				<th width="80" class="hidden-xs">
					<?php echo Text::_('STATS_LABEL_SIZE'); ?>
				</th>
				<th class="hidden-xs">
					<?php echo Text::_('STATS_LABEL_MANAGEANDDL'); ?>
				</th>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td class="form-inline">
					<div class="form-group">
						<label class="sr-only" for="description">
							<?php echo Text::_('SOLO_MANAGE_FIELD_DESCRIPTION') ?>
						</label>
						<input type="text" name="filter_description" id="description"
							   value="<?php echo $this->escape($this->lists->fltDescription) ?>"
							   class="form-control" onchange="document.adminForm.submit();"
							   placeholder="<?php echo Text::_('SOLO_MANAGE_FIELD_DESCRIPTION') ?>">
					</div>
					<div class="hidden-xs">
						<button class="btn btn-default btn-sm"
								onclick="this.form.submit(); return false;">
							<?php echo Text::_('SOLO_BTN_FILTER_SUBMIT'); ?>
						</button>
						<button class="btn btn-default btn-sm"
								onclick="document.adminForm.description.value='';this.form.submit(); return;">
							<?php echo Text::_('SOLO_BTN_FILTER_CLEAR'); ?>
						</button>
					</div>
				</td>
				<td colspan="2">
					<div class="hidden-xs">
						<?php echo \Awf\Html\Html::calendar($this->lists->fltFrom, 'filter_from', 'from', 'yyyy-mm-dd', array('class' => 'input-sm')) ?>
						&mdash;
						<?php echo \Awf\Html\Html::calendar($this->lists->fltTo, 'filter_to', 'to', 'yyyy-mm-dd', array('class' => 'input-sm')) ?>
						<button class="btn btn-default btn-sm"
								onclick="this.form.submit(); return false;">
							<?php echo Text::_('SOLO_BTN_FILTER_SUBMIT'); ?>
						</button>
					</div>
				</td>
				<td></td>
				<td></td>
				<td></td>
				<td>
					<?php echo \Awf\Html\Select::genericList($this->profileList, 'filter_profile', array('onchange' => "document.forms.adminForm.submit()", 'class' => 'form-control'), 'value', 'text', $this->lists->fltProfile); ?>
				</td>
				<td colspan="2"></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="11" class="center"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
		<?php if (!empty($this->list)): ?>
		<?php $i = 0;
			foreach ($this->list as $record):?>
			<?php
			$check = \Awf\Html\Grid::id(++$i, $record['id']);

			$backupId = isset($record['backupid']) ? $record['backupid'] : '';
			$origin_lbl = 'STATS_LABEL_ORIGIN_' . strtoupper($record['origin']);
			$origin = Text::_($origin_lbl);

			if (array_key_exists($record['type'], $this->backupTypes))
			{
				$type = $this->backupTypes[$record['type']];
			}
			else
			{
				$type = '&ndash;';
			}

			$startTime = new \Awf\Date\Date($record['backupstart']);
			$endTime = new \Awf\Date\Date($record['backupend']);

			$duration = $endTime->toUnix() - $startTime->toUnix();
			if ($duration > 0)
			{
				$seconds = $duration % 60;
				$duration = $duration - $seconds;

				$minutes = ($duration % 3600) / 60;
				$duration = $duration - $minutes * 60;

				$hours = $duration / 3600;
				$duration = sprintf('%02d', $hours) . ':' . sprintf('%02d', $minutes) . ':' . sprintf('%02d', $seconds);
			}
			else
			{
				$duration = '-';
			}

			$filename_col = '';

			if (!empty($record['remote_filename']))
			{
				// If we have a remote filename we allow for remote file management in the Pro release
				$remoteManagementLabel = Text::_('STATS_LABEL_REMOTEFILEMGMT');
				$cancelLabel = Text::_('SOLO_MANAGE_LBL_CANCELMODAL');
				$url = $router->route('index.php?view=remotefiles&tmpl=component&task=listActions&id=' . $record['id']);
				$filename_col = <<< HTML
<button class="akeeba_remote_management_link btn btn-default btn-sm"
	rel='{"href": "$url", "CancelLabel": "$cancelLabel", "OkLabel": "", "OkHandler": "function(){ window.location = window.location; }", "CancelHandler": "function(){ window.location = window.location; }" }' onclick="Solo.System.modal(this); return false;">
	$remoteManagementLabel
</button>
HTML;
				if ($record['meta'] != 'obsolete')
				{
					$filename_col .= '<hr/>' . Text::_('REMOTEFILES_LBL_LOCALFILEHEADER');
				}
			}
			elseif (@empty($record['remote_filename']) && ($this->enginesPerProfile[$record['profile_id']] != 'none') && ($record['meta'] != 'obsolete'))
			{
				$postProcEngine = $this->enginesPerProfile[$record['profile_id']];

				if (!empty($postProcEngine))
				{
					$url = $router->route('index.php?view=upload&tmpl=component&task=start&id=' . $record['id']);
					$title = Text::sprintf('AKEEBA_TRANSFER_DESC', Text::_("ENGINE_POSTPROC_{$postProcEngine}_TITLE"));
					$label = Text::_('AKEEBA_TRANSFER_TITLE') . ' (<em>' . $postProcEngine . '</em>)';
					$filename_col .= <<< HTML
<button class="btn btn-link"
	rel='{"href": "$url", "OkLabel": "", "CancelLabel": "", "showButtons": "0"}' onclick="Solo.System.modal(this); return false;">
	$label
</button>

HTML;
					$filename_col .= '<hr/>' . Text::_('REMOTEFILES_LBL_LOCALFILEHEADER');
				}
			}

			if ($record['meta'] == 'ok')
			{
				// Get the download links for downloads for completed, valid backups
				$thisPart = '';
				$thisID = urlencode($record['id']);
				$filename_col .= '<code>' . $record['archivename'] . "</code><br/>";

				if ($record['multipart'] == 0)
				{
					// Single part file -- Create a simple link
					$filename_col .= "<a class=\"btn btn-default btn-sm\" href=\"javascript:confirmDownload('$thisID', '$thisPart');\"><span class=\"fa fa-download\"></span>" . Text::_('STATS_LOG_DOWNLOAD') . "</a>";
				}
				else
				{
					for ($count = 0; $count < $record['multipart']; $count++)
					{
						$thisPart = urlencode($count);
						$label = Text::sprintf('STATS_LABEL_PART', $count);
						$filename_col .= ($count > 0) ? ' &bull; ' : '';
						$filename_col .= "<a class=\"btn btn-default btn-sm\" href=\"javascript:confirmDownload('$thisID', '$thisPart');\"><span class=\"fa fa-download\"></span>$label</a>";
					}
				}
			}
			else
			{
				// If the backup is not complete, just show dashes
				if (empty($filename_col))
				{
					$filename_col .= '&mdash;';
				}
			}

			// If there is a backup ID, show the view log button
			if (($record['meta'] == 'ok') && isset($record['backupid']) && !empty($record['backupid']))
			{
				$viewLogTag = $record['tag'] . '.' . $record['backupid'];
				$viewLogUrl = $router->route('index.php?view=log&tag=' . $viewLogTag . '&profileid=' . $record['profile_id']);
				$viewLogLabel = Text::_('VIEWLOG');
				$filename_col .= '<br><a class="btn btn-default btn-sm" href="' . $viewLogUrl . '">' .
					'<span class="glyphicon glyphicon-list-alt"></span>' . $viewLogLabel . '</a>';
			}

			// Label class based on status
			$status = Text::_('STATS_LABEL_STATUS_' . $record['meta']);
			$statusClass = '';
			switch ($record['meta'])
			{
				case 'ok':
					$statusClass = 'label-success';
					break;
				case 'pending':
					$statusClass = 'label-warning';
					break;
				case 'fail':
					$statusClass = 'label-danger';
					break;
				case 'remote':
					$statusClass = 'label-info';
					break;
				default:
					$statusClass = 'label-default';
					break;
			}

			$edit_link = $router->route('index.php?view=manage&task=showComment&id=' . $record['id'] . '&token=' . $token);

			if (empty($record['description']))
			{
				$record['description'] = Text::_('STATS_LABEL_NODESCRIPTION');
			}
			?>
		<tr>
			<td>
				<?php echo $check; ?>
			</td>
			<td class="hidden-xs">
				<?php echo $record['id']; ?>
			</td>
			<td>
				<?php if (!empty($record['comment'])): ?>
					<span class="glyphicon glyphicon-info-sign" rel="popover" data-content="<?php echo $this->escape($record['comment']) ?>"></span>
				<?php endif; ?>
				<a href="<?php echo $edit_link; ?>">
					<?php echo $this->escape($record['description']) ?>
				</a>
				<?php if ($backupId): ?>
					<br/>
					<small>
						<?php echo $backupId ?>
					</small>
				<?php endif; ?>
			</td>
			<td>
				<?php echo $startTime->format($dateFormat, true); ?>
			</td>
			<td class="hidden-xs">
				<?php echo $duration; ?>
			</td>
			<td>
				<span class="label <?php echo $statusClass; ?>">
					<?php echo $status ?>
				</span>
			</td>
			<td class="hidden-xs"><?php echo $origin ?></td>
			<td class="hidden-xs"><?php echo $type ?></td>
			<td class="hidden-xs">
				<?php
				$profileName = '&mdash;&mdash;&mdash;';
				if (isset($this->profiles[$record['profile_id']]))
				{
					$profile = $this->profiles[$record['profile_id']];
					$profileName = $this->profiles[$record['profile_id']]->description;
				}
				?>
				<span data-toggle="tooltip" data-placement="top" title="<?php echo $profileName ?>">
					<?php echo $record['profile_id'] ?>
				</span>
			</td>
			<td class="hidden-xs"><?php echo ($record['meta'] == 'ok') ? \Solo\Helper\Format::fileSize($record['size']) : ($record['total_size'] > 0 ? "(<i>" . \Solo\Helper\Format::fileSize($record['total_size']) . "</i>)" : '&mdash;') ?></td>
			<td class="hidden-xs"><?php echo $filename_col; ?></td>
		</tr>
		<?php endforeach; ?>
		<?php else: ?>
		<tr>
			<td colspan="11">
				<?php echo Text::_('SOLO_LBL_NO_RECORDS') ?>
			</td>
		</tr>
		<?php endif; ?>
		</tbody>
	</table>
</form>

<script type="application/javascript">
	(function($){
		$('[data-toggle="tooltip"]').tooltip();
	}(akeeba.jQuery));

	Solo.System.orderTable = function ()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $this->escape($this->lists->order); ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}

		Solo.System.tableOrdering(order, dirn, '');
	}

	function confirmDownloadButton()
	{
		var answer = confirm("<?php echo Text::_('STATS_LOG_DOWNLOAD_CONFIRM') ?>");

		if(answer)
		{
			Solo.System.submitForm('adminForm', 'download');
		}
	}

	function confirmDownload(id, part)
	{
		var answer = confirm('<?php echo \Solo\Helper\Escape::escapeJS(Text::_('STATS_LOG_DOWNLOAD_CONFIRM')) ?>');
		var newURL = '<?php echo \Solo\Helper\Escape::escapeJS($router->route('index.php?view=manage&task=download&format=raw')) ?>';

		if(answer) {
			var query = 'id=' + id;

			if( part != '' )
			{
				query += '&part=' + part
			}

			window.location = newURL + (newURL.indexOf('?') != -1 ? '&' : '?') + query;
		}
	}

	Solo.loadScripts[Solo.loadScripts.length] = function () {
		(function($){
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
		}(akeeba.jQuery));
	};
</script>