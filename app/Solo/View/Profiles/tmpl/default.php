<?php
/**
 * @package     Solo
 * @copyright   2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

use \Awf\Text\Text;

// Used for type hinting
/** @var \Solo\View\Profiles\Html $this */

$router = $this->container->router;
$configURL = base64_encode($router->route('index.php?view=configuration'));
$token = $this->container->session->getCsrfToken()->getValue();
?>

<div class="alert alert-info">
	<strong><?php echo Text::_('CPANEL_PROFILE_TITLE'); ?></strong>:
	#<?php echo $this->profileid; ?> <?php echo $this->profilename; ?>
</div>

<form action="<?php echo $router->route('index.php?view=profiles')?>" method="post" name="adminForm" id="adminForm" role="form">
	<input type="hidden" name="boxchecked" id="boxchecked" value="0">
	<input type="hidden" name="task" id="task" value="browse">
	<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists->order ?>">
	<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists->order_Dir ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<table class="table table-striped" id="adminList">
		<thead>
			<tr>
				<th width="20px">&nbsp;</th>
				<th width="20px">#</th>
				<th><?php Text::_('PROFILE_COLLABEL_DESCRIPTION'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="20" class="center"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
		<?php if (!empty($this->items)): ?>
		<?php $i = 0;
			foreach ($this->items as $profile):?>
			<?php
			$check = \Awf\Html\Grid::id(++$i, $profile->id);
			$link = $router->route('index.php?&view=profiles&task=edit&id=' . $profile->id);
			$exportBaseName = \Awf\Utils\String::toSlug($profile->description);
			?>
		<tr>
			<td>
				<?php echo $check; ?>
			</td>
			<td>
				<?php echo $profile->id; ?>
			</td>
			<td>
				<button class="btn btn-sm btn-primary" onclick="window.location='<?php echo $router->route('index.php?view=main&task=switchProfile&profile=' . $profile->id . '&returnurl=' . $configURL . '&' . $token . '=1')?>'; return false;">
					<span class="glyphicon glyphicon-cog"></span>
					<?php echo Text::_('CONFIG_UI_CONFIG'); ?>
				</button>
				&nbsp;
				<button class="btn btn-sm btn-default" onclick="window.location='<?php echo $router->route('index.php?view=profiles&task=read&id=' . $profile->id . '&basename=' . urlencode($exportBaseName) . '&format=json&' . $token . '=1')?>'; return false;">
					<span class="icon-download"></span>
					<?php echo Text::_('COM_AKEEBA_PROFILES_BTN_EXPORT'); ?>
				</button>
				&nbsp;
				<a href="<?php echo $link; ?>">
					<?php echo $profile->description; ?>
				</a>
			</td>
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

<form action="<?php echo $router->route('index.php?view=profiles&task=import')?>" method="POST" name="importForm" enctype="multipart/form-data" id="importForm" class="form form-inline well">
	<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
	<input type="hidden" name="task" id="task" value="import" />
	<input type="hidden" name="<?php echo $token ?>" value="1" />

	<input type="file" name="importfile" class="form-control" />

	<button class="btn btn-success">
		<span class="glyphicon glyphicon-upload"></span>
		<?php echo Text::_('COM_AKEEBA_PROFILES_HEADER_IMPORT');?>
	</button>
	<span class="help-inline">
		<?php echo Text::_('COM_AKEEBA_PROFILES_LBL_IMPORT_HELP');?>
	</span>
</form>

<script type="application/javascript">
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
</script>