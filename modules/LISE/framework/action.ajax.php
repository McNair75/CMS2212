<?php

if (!defined('CMS_VERSION'))
    exit;

lise_utils::log(print_r($params, 1));

if (isset($params['usr_function'])) {
    $usr_params = array();

    if (isset($params['params'])) {
        if (is_array($params['params'])) {
            $usr_params = array($params['params']);
        } else {
            $usr_params = explode(',', $params['params']);
        }
    }

    $handlers = ob_list_handlers();
    for ($cnt = 0; $cnt < sizeof($handlers); $cnt++) {
        ob_end_clean();
    }
    echo call_user_func_array(array($this, $params['usr_function']), $usr_params);
    exit;
}