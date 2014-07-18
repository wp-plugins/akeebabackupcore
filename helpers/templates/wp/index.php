<?php include __DIR__ . '/php/menu.php';
$this->outputHTTPHeaders();

if (defined('AKEEBA_SOLOWP_OBFLAG'))
{
	include __DIR__ . '/php/head.php';
}
else
{
	include __DIR__ . '/php/head_wp.php';
}

?>
<div class="akeeba-bootstrap akeeba-wp">
<?php if (\Awf\Application\Application::getInstance()->getContainer()->input->getCmd('tmpl', '') != 'component'): ?>
	<div class="navbar navbar-default navbar-static-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only"><?php echo \Awf\Text\Text::_('SOLO_COMMON_TOGGLENAV') ?></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo $this->getContainer()->router->route('index.php') ?>">
					<img src="<?php echo \Awf\Uri\Uri::base() ?>media/logo/solo-256.png" class="img-responsive2">
					<?php echo \Awf\Text\Text::_('SOLO_APP_TITLE') ?>
					<small class="text-<?php echo AKEEBA_PRO ? 'danger' : 'muted' ?>"><?php echo AKEEBA_PRO ? 'Pro' : 'Core' ?></small>
					<?php if ((substr(AKEEBA_VERSION, 0, 3) == 'rev') || (strpos(AKEEBA_VERSION, '.a') !== false)): ?>
						<sup><small><span class="label label-danger">ALPHA</span></small></sup>
					<?php elseif (strpos(AKEEBA_VERSION, '.b') !== false): ?>
						<sup><small><span class="label label-primary">BETA</span></small></sup>
					<?php elseif (strpos(AKEEBA_VERSION, '.rc') !== false): ?>
						<sup><small><span class="label label-default">RC</span></small></sup>
					<?php endif; ?>
					<?php if ($title = $this->getToolbar()->getTitle()):?>
						<small>• <?php echo \Awf\Text\Text::_($title) ?></small>
					<?php endif; ?>
				</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-right">
					<?php _solo_template_renderSubmenu($this, $this->getMenu()->getMenuItems('main'), 'nav navbar-nav'); ?>
				</ul>
			</div>
		</div>
	</div>

<div id="wrap">
	<div class="container">
		<?php include __DIR__ . '/php/toolbar.php' ?>
	</div>
	<div class="container">
		<?php endif; ?>
		<?php include __DIR__ . '/php/messages.php' ?>
		<?php echo $this->getBuffer() ?>
		<?php if (\Awf\Application\Application::getInstance()->getContainer()->input->getCmd('tmpl', '') != 'component'): ?>
	</div>
</div>
	<div id="footer">
		<div class="container">
			<p class="muted credit">
				Copyright &copy;2013 &ndash; <?php echo date('Y') ?> Akeeba Ltd. All rights reserved.<br/>
				Akeeba Backup for WordPress is Free Software distributed under the
				<a href="http://www.gnu.org/licenses/gpl.html">GNU GPL version 3</a> or any later version published by the FSF.
				<?php if (defined('AKEEBADEBUG')): ?>
					<br>
					<small>
						Page creation <?php echo sprintf('%0.3f', \Awf\Application\Application::getInstance()->getTimeElapsed()) ?> sec
						&bull;
						Peak memory usage <?php echo sprintf('%0.1f', memory_get_peak_usage() / 1048576) ?> Mb
					</small>
				<?php endif; ?>
			</p>
		</div>
	</div>
<?php endif; ?>
<script type="text/javascript">
	akeeba.jQuery('.hasTooltip').tooltip();
</script>
</div>
<?php if (defined('AKEEBA_SOLOWP_OBFLAG')): ?>
</body>
</html>
<?php endif; ?>