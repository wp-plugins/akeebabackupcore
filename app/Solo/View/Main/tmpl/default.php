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
		<?php echo Text::sprintf('COM_AKEEBA_LBL_CPANEL_NEEDSDLID','https://www.akeebabackup.com/instructions/1539-akeeba-solo-download-id.html'); ?>
	</div>
<?php elseif ($this->warnCoreDownloadId): ?>
	<div class="alert alert-danger">
		<?php echo Text::_('SOLO_MAIN_LBL_NEEDSUPGRADE'); ?>
	</div>
<?php endif; ?>

<div id="soloUpdateNotification">

</div>

<div>
	<div class="col-md-8 col-sm-12">
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

		<?php if (!AKEEBA_PRO): ?>
		<div class="panel-group" id="coreaccordion">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#coreaccordion" href="#collapseOne">
							<span class="glyphicon glyphicon-info-sign" ></span>
							<?php echo Text::_('SOLO_MAIN_BTN_SUBSCRIBE'); ?>
						</a>
					</h4>
				</div>
				<div id="collapseOne" class="panel-collapse collapse">
					<div class="panel-body">
						<p class="text-muted">
							<?php echo Text::_('SOLO_MAIN_LBL_WHYSUBSCRIBE'); ?>
						</p>
						<p style="text-align: center;">
							<a class="btn btn-lg btn-danger" href="https://www.akeebabackup.com/subscribe.html">
								<span class="glyphicon glyphicon-shopping-cart"></span>
								<?php echo Text::_('SOLO_MAIN_BTN_SUBSCRIBE'); ?>
							</a>
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-tasks"></span>
				<?php echo Text::_('SOLO_MAIN_LBL_HEAD_BACKUPOPS'); ?>
			</div>
			<div class="panel-body">
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=wizard') ?>">
					<span class="icon fa fa-magic"></span>
					<span class="title"><?php echo Text::_('AKEEBA_CONFWIZ') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=configuration') ?>">
					<span class="icon fa fa-cog"></span>
					<span class="title"><?php echo Text::_('CONFIGURATION') ?></span>
				</a>
				<a class="btn btn-primary cpanel-icon" href="<?php echo $router->route('index.php?view=backup') ?>">
					<span class="icon glyphicon glyphicon-compressed"></span>
					<span class="title"><?php echo Text::_('BACKUP') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=manage') ?>">
					<span class="icon glyphicon glyphicon-list"></span>
					<span class="title"><?php echo Text::_('BUADMIN') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=log') ?>">
					<span class="icon fa fa-edit"></span>
					<span class="title"><?php echo Text::_('VIEWLOG') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo AKEEBA_PRO ? $router->route('index.php?view=alice') : 'javascript:soloFeatureNotInCore();' ?>">
					<span class="icon fa fa-fire-extinguisher <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"></span>
					<span class="title <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"><?php echo Text::_('AKEEBA_ALICE') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo AKEEBA_PRO ? $router->route('index.php?view=discover') : 'javascript:soloFeatureNotInCore();' ?>">
					<span class="icon glyphicon glyphicon-import <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"></span>
					<span class="title <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"><?php echo Text::_('DISCOVER') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo AKEEBA_PRO ? $router->route('index.php?view=s3import') : 'javascript:soloFeatureNotInCore();' ?>">
					<span class="icon glyphicon glyphicon-cloud-download" <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>></span>
					<span class="title <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"><?php echo Text::_('S3IMPORT') ?></span>
				</a>

				<?php if (!$inCMS || (defined('AKEEBA_PRO') && AKEEBA_PRO)): ?>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=update') ?>">
					<span class="icon glyphicon glyphicon-retweet"></span>
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
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-plus-square"></span>
				<?php echo Text::_('CPANEL_HEADER_INCLUSION'); ?>
			</div>
			<div class="panel-body">
				<a class="btn btn-default cpanel-icon" href="<?php echo AKEEBA_PRO ? $router->route('index.php?view=multidb') : 'javascript:soloFeatureNotInCore();' ?>">
					<span class="icon fa fa-th-large <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"></span>
					<span class="title <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"><?php echo Text::_('MULTIDB') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo AKEEBA_PRO ? $router->route('index.php?view=extradirs') : 'javascript:soloFeatureNotInCore();' ?>">
					<span class="icon fa fa-files-o <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"></span>
					<span class="title <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"><?php echo Text::_('EXTRADIRS') ?></span>
				</a>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-minus-square"></span>
				<?php echo Text::_('CPANEL_HEADER_EXCLUSION'); ?>
			</div>
			<div class="panel-body">
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=fsfilters') ?>">
					<span class="icon fa fa-folder"></span>
					<span class="title"><?php echo Text::_('FSFILTERS') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=dbfilters') ?>">
					<span class="icon glyphicon glyphicon-hdd"></span>
					<span class="title"><?php echo Text::_('DBEF') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo AKEEBA_PRO ? $router->route('index.php?view=regexfsfilters') : 'javascript:soloFeatureNotInCore();' ?>">
					<span class="icon fa fa-folder-o <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"></span>
					<span class="title small-text <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"><?php echo Text::_('REGEXFSFILTERS') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo AKEEBA_PRO ? $router->route('index.php?view=regexdbfilters') : 'javascript:soloFeatureNotInCore();' ?>">
					<span class="icon fa fa-hdd-o <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"></span>
					<span class="title small-text <?php echo AKEEBA_PRO ? '' : 'text-muted' ?>"><?php echo Text::_('REGEXDBFILTERS') ?></span>
				</a>
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
					<span class="icon fa fa-users"></span>
					<span class="title"><?php echo Text::_('SOLO_MAIN_LBL_USERS') ?></span>
				</a>
				<?php endif; ?>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=profiles') ?>">
					<span class="icon fa fa-briefcase"></span>
					<span class="title"><?php echo Text::_('PROFILES') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=schedule') ?>">
					<span class="icon fa fa-calendar"></span>
					<span class="title"><?php echo Text::_('AKEEBA_SCHEDULE') ?></span>
				</a>
				<a class="btn btn-default cpanel-icon" href="<?php echo $router->route('index.php?view=sysconfig') ?>">
					<span class="icon fa fa-cogs"></span>
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
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="text-align: center; margin-top: 40px;">
					<input type="hidden" name="cmd" value="_s-xclick" />
					<input type="hidden" name="hosted_button_id" value="10903325" />
					<button onclick="this.form.submit(); return false;" class="btn btn-default">
						<img src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" border="0">
						Donate via PayPal
					</button>
				</form>
				<br/>
				<p style="text-align: center">
					<a class="btn btn btn-danger" href="https://www.akeebabackup.com/subscribe.html">
						<span class="glyphicon glyphicon-shopping-cart"></span>
						<?php echo Text::_('SOLO_MAIN_BTN_SUBSCRIBE'); ?>
					</a>
				</p>
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
				}
				else
				{
					$('#soloUpdateUpToDate').show();
				}
			})
		<?php endif; ?>

	}(akeeba.jQuery));
};
	function soloFeatureNotInCore()
	{
		alert('<?php echo Text::_('SOLO_MAIN_ERR_NOTINCORE')?>');
	}

</script>
<?php endif; ?>
