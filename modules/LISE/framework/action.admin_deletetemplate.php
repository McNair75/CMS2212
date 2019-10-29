<?php

if (!defined('CMS_VERSION'))
    exit;

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_option'))
    return;

if (!isset($params['name'])) {
    die('missing parameter, this should not happen');
}

$this->DeleteTemplate($params['name']);

$handlers = ob_list_handlers();
for ($cnt = 0; $cnt < sizeof($handlers); $cnt++) {
    ob_end_clean();
}

echo $this->ModLang('deleted');
exit();