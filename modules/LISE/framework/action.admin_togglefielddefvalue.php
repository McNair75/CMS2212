<?php

if (!defined('CMS_VERSION'))
    exit;


if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_item'))
    return;

if (!isset($params['fielddef_id']) || !isset($params['item_id']))
    throw new \LISE\Exception('Missing parameter, this should not happen!');

$fid = (int) $params['fielddef_id'];
$item_id = (int) $params['item_id'];

$value = '';
if (isset($params['value'])) {

    $value = $params['value'] ? $params['value'] : false;
}

$query = 'DELETE FROM ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fieldval WHERE item_id = ? AND fielddef_id = ?';
$result = $db->Execute($query, array($item_id, $fid));

$query = 'INSERT INTO ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fieldval (item_id, fielddef_id, value) VALUES (?, ?, ?)';
$result = $db->Execute($query, array($item_id, $fid, $value));

$admintheme = cms_utils::get_theme_object();
$json = new stdClass;

if ($value) {

    $json->image = $admintheme->DisplayImage('icons/system/true.gif', $this->ModLang('revert'), '', '', 'systemicon');
    $json->href = $this->CreateLink($id, 'admin_togglefielddefvalue', $returnid, '', array('fielddef_id' => $fid, 'item_id' => $item_id, 'value' => 0), '', true);
} else {

    $json->image = $admintheme->DisplayImage('icons/system/false.gif', $this->ModLang('approve'), '', '', 'systemicon');
    $json->href = $this->CreateLink($id, 'admin_togglefielddefvalue', $returnid, '', array('fielddef_id' => $fid, 'item_id' => $item_id, 'value' => 1), '', true);
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