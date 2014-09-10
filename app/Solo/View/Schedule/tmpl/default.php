<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license		GNU GPL version 3 or later
 */

use Awf\Text\Text;

/** @var   \Solo\View\Schedule\Html  $this */
?>
<ul id="runCheckTabs" class="nav nav-tabs">
        <li>
            <a href="#absTabRunBackups" data-toggle="tab">
                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_RUN_BACKUPS'); ?>
            </a>
        </li>
        <li>
            <a href="#absTabCheckBackups" data-toggle="tab">
                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_CHECK_BACKUPS'); ?>
            </a>
        </li>
    </ul>

    <div id="runCheckTabsContent" class="tab-content">
        <?php
        echo $this->loadTemplate('runbackups');
        echo $this->loadTemplate('checkbackups');
        ?>
    </div>
<?php
$this->container->application->getDocument()->addScriptDeclaration( <<<JS
Solo.loadScripts[Solo.loadScripts.length] = function () {
	(function($){
            $('#runCheckTabs a:first').tab('show');
	}(akeeba.jQuery));
};
JS
);
?>