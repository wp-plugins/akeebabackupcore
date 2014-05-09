<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

use Awf\Text\Text;

/** @var   \Solo\View\Schedule\Html  $this */

?>
<div class="tab-pane fade" id="absTabRunBackups">
    <p>
        <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_HEADERINFO'); ?>
    </p>

    <fieldset>
        <legend><?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_CLICRON') ?></legend>

        <?php if(AKEEBA_PRO): ?>
            <p class="alert alert-info">
                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_CLICRON_INFO') ?>
                <br/>
                <a class="btn btn-mini btn-info" href="https://www.akeebabackup.com/documentation/akeeba-solo/native-cron-script.html" target="_blank">
                    <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_GENERICREADDOC') ?>
                </a>
            </p>
            <p>
                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_GENERICUSECLI') ?>
                <code>
                    <?php echo $this->croninfo->info->php_path ?>
                    <?php echo $this->croninfo->cli->path ?>
                </code>
            </p>
            <p>
                <span class="label label-danger"><?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_CLIGENERICIMPROTANTINFO'); ?></span>
                <?php echo Text::sprintf('COM_AKEEBA_SCHEDULE_LBL_CLIGENERICINFO', $this->croninfo->info->php_path); ?>
            </p>

        <?php else: ?>
            <div class="alert alert-warning">
                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_UPGRADETOPRO') ?>
                <br/>
                <a class="btn btn-primary" href="https://www.akeebabackup.com/subscribe.html" target="_blank">
                    <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_UPGRADENOW') ?>
                </a>
            </div>
        <?php endif; ?>
    </fieldset>

    <fieldset>
        <legend><?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_ALTCLICRON') ?></legend>

        <?php if(AKEEBA_PRO): ?>
            <p class="alert alert-info">
                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_ALTCLICRON_INFO') ?>
                <br/>
                <a class="btn btn-mini btn-info" href="https://www.akeebabackup.com/documentation/akeeba-solo/alternative-cron-script.html" target="_blank">
                    <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_GENERICREADDOC') ?>
                </a>
            </p>
            <?php if(!$this->croninfo->info->feenabled): ?>
                <p class="alert alert-error">
                    <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_DISABLED'); ?>
                </p>
            <?php elseif(!trim($this->croninfo->info->secret)): ?>
                <p class="alert alert-error">
                    <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_SECRET'); ?>
                </p>
            <?php else: ?>
                <p>
                    <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_GENERICUSECLI') ?>
                    <code>
                        <?php echo $this->croninfo->info->php_path ?>
                        <?php echo $this->croninfo->altcli->path ?>
                    </code>
                </p>
                <p>
                    <span class="label label-danger"><?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_CLIGENERICIMPROTANTINFO'); ?></span>
                    <?php echo Text::sprintf('COM_AKEEBA_SCHEDULE_LBL_CLIGENERICINFO', $this->croninfo->info->php_path); ?>
                </p>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-warning">
                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_UPGRADETOPRO') ?>
                <br/>
                <a class="btn btn-primary" href="https://www.akeebabackup.com/subscribe.html" target="_blank">
                    <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_UPGRADENOW') ?>
                </a>
            </div>
        <?php endif; ?>
    </fieldset>

    <fieldset>
        <legend><?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTENDBACKUP') ?></legend>

        <p class="alert alert-info">
            <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTENDBACKUP_INFO') ?>
            <br/>
            <a class="btn btn-mini btn-info" href="https://www.akeebabackup.com/documentation/akeeba-solo/automating-your-backup.html#frontend-backup" target="_blank">
                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_GENERICREADDOC') ?>
            </a>
        </p>
        <?php if(!$this->croninfo->info->feenabled): ?>
            <p class="alert alert-danger">
                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_DISABLED'); ?>
            </p>
        <?php elseif(!trim($this->croninfo->info->secret)): ?>
            <p class="alert alert-danger">
                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_SECRET'); ?>
            </p>
        <?php else: ?>
            <p>
                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTENDBACKUP_MANYMETHODS'); ?>
            </p>


            <ul id="abschedulingTabs" class="nav nav-tabs">
                <li>
                    <a href="#absTabWebcron" data-toggle="tab">
                        <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTENDBACKUP_TAB_WEBCRON'); ?>
                    </a>
                </li>
                <li>
                    <a href="#absTabWget" data-toggle="tab">
                        <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTENDBACKUP_TAB_WGET'); ?>
                    </a>
                </li>
                <li>
                    <a href="#absTabCurl" data-toggle="tab">
                        <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTENDBACKUP_TAB_CURL'); ?>
                    </a>
                </li>
                <li>
                    <a href="#absTabScript" data-toggle="tab">
                        <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTENDBACKUP_TAB_SCRIPT'); ?>
                    </a>
                </li>
                <li>
                    <a href="#absTabUrl" data-toggle="tab">
                        <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTENDBACKUP_TAB_URL'); ?>
                    </a>
                </li>
            </ul>

            <div id="absTabContent" class="tab-content">
                <div class="tab-pane fade" id="absTabWebcron">
                    <p>
                        <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON') ?>
                    <table class="table table-striped">
                        <tr>
                            <td></td>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_INFO') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_NAME') ?>
                            </td>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_NAME_INFO') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_TIMEOUT') ?>
                            </td>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_TIMEOUT_INFO') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_URL') ?>
                            </td>
                            <td>
                                <?php echo $this->croninfo->info->root_url.'/'.$this->croninfo->frontend->path ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_LOGIN') ?>
                            </td>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_LOGINPASSWORD_INFO') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_PASSWORD') ?>
                            </td>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_LOGINPASSWORD_INFO') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_EXECUTIONTIME') ?>
                            </td>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_EXECUTIONTIME_INFO') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_ALERTS') ?>
                            </td>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_ALERTS_INFO') ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WEBCRON_THENCLICKSUBMIT') ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade" id="absTabWget">
                    <p>
                        <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_WGET') ?>
                        <code>
                            wget --max-redirect=10000 "<?php echo $this->croninfo->info->root_url.'/'.$this->croninfo->frontend->path ?>" -O - 1>/dev/null 2>/dev/null
                        </code>
                    </p>
                </div>
                <div class="tab-pane fade" id="absTabCurl">
                    <p>
                        <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_CURL') ?>
                        <code>
                            curl -L --max-redirs 1000 -v "<?php echo $this->croninfo->info->root_url.'/'.$this->croninfo->frontend->path ?>" 1>/dev/null 2>/dev/null
                        </code>
                    </p>
                </div>
                <div class="tab-pane fade" id="absTabScript">
                    <p>
                        <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_CUSTOMSCRIPT') ?>
                    <pre>
    <?php echo '&lt;?php'; ?>

    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, '<?php echo $this->croninfo->info->root_url.'/'.$this->croninfo->frontend->path ?>');
    curl_setopt($curl_handle,CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($curl_handle,CURLOPT_MAXREDIRS, 10000);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, 1);
    $buffer = curl_exec($curl_handle);
    curl_close($curl_handle);
    if (empty($buffer))
        echo "Sorry, the backup didn't work.";
    else
        echo $buffer;
    <?php echo '?&gt;'; ?>
                    </pre>
                    </p>
                </div>
                <div class="tab-pane fade" id="absTabUrl">
                    <p>
                        <?php echo Text::_('COM_AKEEBA_SCHEDULE_LBL_FRONTEND_RAWURL') ?>
                        <code>
                            <?php echo $this->croninfo->info->root_url ?>/<?php echo $this->croninfo->frontend->path ?>
                        </code>
                    </p>
                </div>
            </div>

            <?php
            $this->container->application->getDocument()->addScriptDeclaration( <<<JS
Solo.loadScripts[Solo.loadScripts.length] = function () {
	(function($){
        $('#abschedulingTabs a:first').tab('show');
	}(akeeba.jQuery));
};
JS
            );
            ?>


        <?php endif; ?>
    </fieldset>
</div>