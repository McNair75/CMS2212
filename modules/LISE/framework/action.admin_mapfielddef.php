<?php

if (!defined('CMS_VERSION'))
    exit;
if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_options'))
    return;

if (!isset($params['map']) || !isset($params['fielddef_id'])) {
    die('missing parameter, this should not happen');
}

$fielddef_id = (int) $params['fielddef_id'];
$map = (bool) $params['map'];

$query = 'UPDATE ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fielddef SET map = ? WHERE fielddef_id = ?';
$db->Execute($query, array($map, $fielddef_id));

$admintheme = cms_utils::get_theme_object();
$json = new stdClass;

if ($map) {

    $json->image = $admintheme->DisplayImage('icons/system/true.gif', $this->ModLang('status_map_off'), '', '', 'systemicon');
    $json->href = $this->CreateLink($id, 'admin_mapfielddef', $returnid, '', array('map' => 0, 'fielddef_id' => $fielddef_id), '', true);
} else {

    $json->image = $admintheme->DisplayImage('icons/system/false.gif', $this->ModLang('status_map_off'), '', '', 'systemicon');
    $json->href = $this->CreateLink($id, 'admin_mapfielddef', $returnid, '', array('map' => 1, 'fielddef_id' => $fielddef_id), '', true);
}

// Fix URL for JSON output
$json->href = html_entity_decode($json->href);

header("Content-type:application/json; charset=utf-8");

$handlers = ob_list_handlers();
for ($cnt = 0; $cnt < sizeof($handlers); $cnt++) {
    ob_end_clean();
}

echo json_encode($json);
exit();