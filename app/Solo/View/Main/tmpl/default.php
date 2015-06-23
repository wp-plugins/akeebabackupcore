<?php
/**
 * @package     Solo
 * @copyright   2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

use \Awf\Text\Text;
use \Awf\Html;

// Used for type hinting
/** @var \Solo\View\Main\Html $this */

$router = $this->container->router;
$inCMS = $this->container->segment->get('insideCMS', false);

?>

<?php
// Obsolete PHP version check
if (version_compare(PHP_VERSION, '5.4.0', 'lt')):
	$akeebaCommonDatePHP = new \Awf\Date\Date('2014-08-14 00:00:00', 'GMT');
	$akeebaCommonDateObsolescence = new \Awf\Date\Date('2015-05-14 00:00:00', 'GMT');
	?>
	<div id="phpVersionCheck" class="alert alert-warning">
		<h3><?php echo Text::_('AKEEBA_COMMON_PHPVERSIONTOOOLD_WARNING_TITLE'); ?></h3>
		<p>
			<?php echo Text::sprintf(
				'AKEEBA_COMMON_PHPVERSIONTOOOLD_WARNING_BODY',
				PHP_VERSION,
				$akeebaCommonDatePHP->format(Text::_('DATE_FORMAT_LC1')),
				$akeebaCommonDateObsolescence->format(Text::_('DATE_FORMAT_LC1')),
				'5.5'
			);
			?>
		</p>
	</div>
<?php endif; ?>


<?php if (!empty($this->configUrl)): ?>
<div class="alert alert-danger" id="config-readable-error" style="display: none">
	<h4>
		<?php echo Text::_('SOLO_MAIN_ERR_CONFIGREADABLE_HEAD'); ?>
	</h4>
	<p>
		<?php echo Text::sprintf('SOLO_MAIN_ERR_CONFIGREADABLE_BODY', $this->configUrl); ?>
	</p>
</div>
<?php endif; ?>
<?php if (!empty($this->backupUrl)): ?>
<div class="alert alert-danger" id="output-readable-error" style="display: none">
	<h4>
		<?php echo Text::_('SOLO_MAIN_ERR_OUTPUTREADABLE_HEAD'); ?>
	</h4>
	<p>
		<?php echo Text::sprintf('SOLO_MAIN_ERR_OUTPUTREADABLE_BODY', $this->backupUrl); ?>
	</p>
</div>
<?php endif; ?>

<?php if ($this->needsDownloadId): ?>
	<div class="alert alert-warning">
		<?php if ($inCMS): ?>
		<?php echo Text::sprintf('COM_AKEEBA_LBL_CPANEL_NEEDSDLID','https://www.akeebabackup.com/instructions/1557-akeeba-solo-download-id-2.html'); ?>
		<?php else: ?>
			<?php echo Text::sprintf('COM_AKEEBA_LBL_CPANEL_NEEDSDLID','https://www.akeebabackup.com/instructions/1539-akeeba-solo-download-id.html'); ?>
		<?php endif; ?>
	</div>
<?php elseif ($this->warnCoreDownloadId): ?>
	<div class="alert alert-danger">
		<?php echo Text::_('SOLO_MAIN_LBL_NEEDSUPGRADE'); ?>
	</div>
<?php endif; ?>

<div id="soloUpdateNotification">

</div>

<div>
	<div class="col-md-8 col-sm-12 akeeba-cpanel">
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="<?php echo $router->route('index.php?view=main') ?>" method="post" name="profileForm">
					<input type="hidden" name="token" value="<?php echo $this->container->session->getCsrfToken()->getValue() ?>">
					<input type="hidden" name="task" value="switchProfile" />
					<div class="col-xs-12">
						<label>
							<?php echo Text ::_('CPANEL_PROFILE_TITLE'); ?>: #<?php echo $this->profile; ?>
						</label>
					</div>
					<div class="col-md-8 col-sm-12">
						<?php echo Html\Select::genericList($this->profileList, 'profile', array('onchange' => "document.forms.profileForm.submit()", 'class' => 'form-control'), 'value', 'text', $this->profile); ?>
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

		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-tasks"></span>
				<?php echo Text::_('SOLO_MAIN_LBL_HEAD_BACKUPOPS'); ?>
			</div>
			<div class="panel-body">
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=wizard') ?>">
					<span class="ak-icon ak-icon-wizard"></span>
					<span class="title"><?php echo Text::_('AKEEBA_CONFWIZ') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=configuration') ?>">
					<span class="ak-icon ak-icon-configuration"></span>
					<span class="title"><?php echo Text::_('CONFIGURATION') ?></span>
				</a>
				<a class="btn btn-primary cpanel-icon" href="<?php echo $router->route('index.php?view=backup') ?>">
					<span class="ak-icon ak-icon-backup"></span>
					<span class="title"><?php echo Text::_('BACKUP') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=manage') ?>">
					<span class="ak-icon ak-icon-manage"></span>
					<span class="title"><?php echo Text::_('BUADMIN') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=log') ?>">
					<span class="ak-icon ak-icon-viewlog"></span>
					<span class="title"><?php echo Text::_('VIEWLOG') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=alice')?>">
					<span class="ak-icon ak-icon-alice"></span>
					<span class="title"><?php echo Text::_('AKEEBA_ALICE') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=discover') ?>">
					<span class="ak-icon ak-icon-import"></span>
					<span class="title small-text"><?php echo Text::_('DISCOVER') ?></span>
				</a>
				<?php if (defined('AKEEBA_PRO') && AKEEBA_PRO): ?>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=s3import') ?>">
					<span class="ak-icon ak-icon-import-from-s3"></span>
					<span class="title small-text"><?php echo Text::_('S3IMPORT') ?></span>
				</a>
				<?php endif; ?>

				<?php if (!$inCMS || (defined('AKEEBA_PRO') && AKEEBA_PRO)): ?>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=update') ?>">
					<span class="ak-icon ak-icon-update" id="soloUpdateAvailableIcon" style="display: none"></span>
					<span class="ak-icon ak-icon-ok" id="soloUpdateUpToDateIcon" style="display: none"></span>
					<span class="title">
						<?php echo Text::_('SOLO_UPDATE_TITLE') ?>
						<span class="label label-danger" id="soloUpdateAvailable" style="display: none">
							<?php echo Text::_('SOLO_UPDATE_SUBTITLE_UPDATEAVAILABLE') ?>
						</span>
						<span class="label label-success" id="soloUpdateUpToDate" style="display: none">
							<?php echo Text::_('SOLO_UPDATE_SUBTITLE_UPTODATE') ?>
						</span>
					</span>
				</a>
				<?php endif; ?>
			</div>
		</div>

		<?php if (defined('AKEEBA_PRO') && AKEEBA_PRO): ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-plus-square"></span>
				<?php echo Text::_('CPANEL_HEADER_INCLUSION'); ?>
			</div>
			<div class="panel-body">
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=multidb') ?>">
					<span class="ak-icon ak-icon-multidb"></span>
					<span class="title small-text"><?php echo Text::_('MULTIDB') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=extradirs') ?>">
					<span class="ak-icon ak-icon-extradirs"></span>
					<span class="title small-text"><?php echo Text::_('EXTRADIRS') ?></span>
				</a>
			</div>
		</div>
		<?php endif; ?>

		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-minus-square"></span>
				<?php echo Text::_('CPANEL_HEADER_EXCLUSION'); ?>
			</div>
			<div class="panel-body">
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=fsfilters') ?>">
					<span class="ak-icon ak-icon-fsfilter"></span>
					<span class="title small-text"><?php echo Text::_('FSFILTERS') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=dbfilters') ?>">
					<span class="ak-icon ak-icon-dbfilter"></span>
					<span class="title small-text"><?php echo Text::_('DBEF') ?></span>
				</a>
				<?php if (defined('AKEEBA_PRO') && AKEEBA_PRO): ?>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=regexfsfilters')?>">
					<span class="ak-icon ak-icon-regexfiles"></span>
					<span class="title small-text"><?php echo Text::_('REGEXFSFILTERS') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=regexdbfilters')?>">
					<span class="ak-icon ak-icon-regexdb"></span>
					<span class="title small-text"><?php echo Text::_('REGEXDBFILTERS') ?></span>
				</a>
				<?php endif; ?>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-cogs"></span>
				<?php echo Text::_('SOLO_MAIN_LBL_SYSMANAGEMENT'); ?>
			</div>
			<div class="panel-body">
				<?php if (!$inCMS): ?>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=users') ?>">
					<span class="ak-icon ak-icon-users"></span>
					<span class="title"><?php echo Text::_('SOLO_MAIN_LBL_USERS') ?></span>
				</a>
				<?php endif; ?>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=profiles') ?>">
					<span class="ak-icon ak-icon-profiles"></span>
					<span class="title"><?php echo Text::_('PROFILES') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=schedule') ?>">
					<span class="ak-icon ak-icon-scheduling"></span>
					<span class="title"><?php echo Text::_('AKEEBA_SCHEDULE') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=sysconfig') ?>">
					<span class="ak-icon ak-icon-sysconfig"></span>
					<span class="title"><?php echo Text::_('SOLO_MAIN_LBL_SYSCONFIG') ?></span>
				</a>
			</div>
		</div>

	</div>

	<div class="col-md-4 col-sm-12">

		<div class="panel panel-default">
			<div class="panel-body">
				<p>
					<?php echo Text::_('SOLO_APP_TITLE'); ?>
					<?php echo AKEEBA_PRO ? 'Professional' : 'Core' ?>
					<span class="label label-primary"><?php echo AKEEBA_VERSION ?></span>

					<?php echo (strlen(Text::_('SOLO_APP_TITLE')) > 14) ? '<br/>' : '' ?>
					<button class="btn btn-xs btn-info <?php echo (strlen(Text::_('SOLO_APP_TITLE')) > 14) ? '' : 'pull-right' ?>" data-toggle="modal" data-target="#changelogModal">Changelog</button>
				</p>

				<?php if (!AKEEBA_PRO): ?>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="text-align: center; margin: 0px;">
					<input type="hidden" name="cmd" value="_s-xclick" />
					<input type="hidden" name="hosted_button_id" value="3NTKQ3M2DYPYW" />
					<button onclick="this.form.submit(); return false;" class="btn btn-success">
						<img src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" border="0">
						Donate via PayPal
					</button>
				</form>
				<?php endif; ?>
			</div>
		</div>

		<?php echo $this->loadAnyTemplate('Main/status') ?>

		<?php echo $this->loadAnyTemplate('Main/latest_backup') ?>
	</div>
</div>

<div class="modal fade" id="changelogModal" tabindex="-1" role="dialog" aria-labelledby="changelogModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="changelogModalLabel">Changelog</h4>
			</div>
			<div class="modal-body">
			<?php echo $this->loadAnyTemplate('Main/changelog') ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<?php
if($this->statsIframe)
{
    echo $this->statsIframe;
}
?>

<?php if (!empty($this->configUrl) || !empty($this->backupUrl)): ?>
<script type="text/javascript">
Solo.loadScripts[Solo.loadScripts.length] = function () {
	(function($){
		<?php if (!empty($this->configUrl)): ?>
			$.get('<?php echo $this->configUrl?>', function(data){
				if (data.length > 0)
				{
					$('#config-readable-error').css('display', 'block');
				}
			});
		<?php endif; ?>
		<?php if (!empty($this->backupUrl)): ?>
			$.get('<?php echo $this->backupUrl?>', function(data){
				$('#backup-readable-error').css('display', 'block');
			});
		<?php endif; ?>

	}(akeeba.jQuery));
};
</script>
<?php endif; ?>

<script type="text/javascript">
	Solo.loadScripts[Solo.loadScripts.length] = function () {
		(function($){
			<?php if (!$inCMS || (defined('AKEEBA_PRO') && AKEEBA_PRO)): ?>
			$.get('<?php echo $router->route('index.php?view=main&format=raw&task=getUpdateInformation&' . $this->getContainer()->session->getCsrfToken()->getValue() . '=1'); ?>', function(msg){
				// Initialize
				var junk = null;
				var message = "";

				// Get rid of junk before the data
				var valid_pos = msg.indexOf('###');

				if (valid_pos == -1)
				{
					return;
				}
				else if( valid_pos != 0 )
				{
					// Data is prefixed with junk
					junk = msg.substr(0, valid_pos);
					message = msg.substr(valid_pos);
				}
				else
				{
					message = msg;
				}

				message = message.substr(3); // Remove triple hash in the beginning

				// Get of rid of junk after the data
				valid_pos = message.lastIndexOf('###');
				message = message.substr(0, valid_pos); // Remove triple hash in the end

				try
				{
					var data = JSON.parse(message);
				}
				catch(err)
				{
					return;
				}

				if (data.hasUpdate)
				{
					$('#soloUpdateNotification').html(data.noticeHTML);
					$('#soloUpdateAvailable').show();
					$('#soloUpdateAvailableIcon').show();
				}
				else
				{
					$('#soloUpdateUpToDate').show();
					$('#soloUpdateUpToDateIcon').show();
				}
			})
			<?php endif; ?>
		}(akeeba.jQuery));

		function soloFeatureNotInCore()
		{
			alert('<?php echo Text::_('SOLO_MAIN_ERR_NOTINCORE')?>');
		}
	};

</script>
