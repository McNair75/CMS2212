<?php

if (!defined('CMS_VERSION'))
    exit;


if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_option'))
    return;

$fielddefs = array();
if (isset($params['fielddef_id'])) {
    $fielddefs = array((int) $params['fielddef_id']);
}
if (isset($params['fielddefs']) && is_array($params['fielddefs'])) {
    $fielddefs = $params['fielddefs'];
}

foreach ($fielddefs as $fielddef_id) {

    LISEFielddefOperations::Delete($this, $fielddef_id);
}

$handlers = ob_list_handlers();
for ($cnt = 0; $cnt < sizeof($handlers); $cnt++) {
    ob_end_clean();
}

echo $this->ModLang('deleted');
exit();