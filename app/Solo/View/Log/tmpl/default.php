<?php
/**
 * @package     Solo
 * @copyright   2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

use \Awf\Text\Text;
use \Awf\Html;

// Used for type hinting
/** @var  \Solo\View\Log\Html  $this */

$router = $this->container->router;

$inCMS = $this->container->segment->get('insideCMS', false);
$height = $inCMS ? '500px' : '70%';
?>
<?php if(count($this->logs)): ?>
<form name="adminForm" id="adminForm" action="<?php echo $router->route('index.php?view=log') ?>" method="POST" class="form-inline">
	<input type="hidden" name="token" value="<?php echo $this->container->session->getCsrfToken()->getValue() ?>" >
	<fieldset>
		<label for="tag">
			<?php echo Text::_('LOG_CHOOSE_FILE_TITLE'); ?>
		</label>
		<?php echo Html\Select::genericList($this->logs, 'tag', 'onchange=this.form.submit()', 'value', 'text', $this->tag, 'tag') ?>

		<?php if(!empty($this->tag)): ?>
		<button class="btn btn-primary" onclick="window.location='<?php echo $router->route('index.php?view=log&format=raw&task=download&tag=' . urlencode($this->tag)); ?>'; return false;">
			<span class="glyphicon glyphicon-download"></span>
			<?php echo Text::_('LOG_LABEL_DOWNLOAD'); ?>
		</button>

		<br/>
		<hr/>
		<iframe
			src="<?php echo $router->route('index.php?view=log&task=iframe&tmpl=component&layout=raw&tag=' . urlencode($this->tag)) ?>"
			width="99%" height="<?php echo $height ?>">
		</iframe>
		<?php endif; ?>

	</fieldset>
</form>
<?php else: ?>
<div class="alert alert-danger">
	<?php echo Text::_('LOG_NONE_FOUND') ?>
</div>
<?php endif; ?>