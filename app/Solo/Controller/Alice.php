<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license		GNU GPL version 3 or later
 */

namespace Solo\Controller;


class Alice extends ControllerDefault
{
    public function ajax()
    {
        $model = $this->getModel();

        $model->setState('ajax', $this->input->get('ajax', '', 'cmd'));
        $model->setState('log', $this->input->get('log', '', 'cmd'));

        $ret_array = $model->runAnalysis();

        @ob_end_clean();
        header('Content-type: text/plain');
        echo '###' . json_encode($ret_array) . '###';
        flush();

        $this->container->application->close();
    }

    public function domains()
    {
        $return  = array();
        $domains = \AliceUtilScripting::getDomainChain();

        foreach($domains as $domain)
        {
            $return[] = array($domain['domain'], $domain['name']);
        }

        @ob_end_clean();
        header('Content-type: text/plain');
        echo '###'.json_encode($return).'###';
        flush();

        $this->container->application->close();
    }
} 