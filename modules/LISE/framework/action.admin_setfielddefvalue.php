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
    $value = $params['value'];
}

$query = 'DELETE FROM ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fieldval WHERE item_id = ? AND fielddef_id = ?';
$result = $db->Execute($query, array($item_id, $fid));

$query = 'INSERT INTO ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fieldval (item_id, fielddef_id, value) VALUES (?, ?, ?)';
$result = $db->Execute($query, array($item_id, $fid, $value));

$handlers = ob_list_handlers();
for ($cnt = 0; $cnt < sizeof($handlers); $cnt++) {
    ob_end_clean();
}

$json = new stdClass;

$json->value = $value;

// Fix URL for JSON output
$json->href = html_entity_decode($json->href);

header("Content-type:application/json; charset=utf-8");

$handlers = ob_list_handlers();
for ($cnt = 0; $cnt < sizeof($handlers); $cnt++) {
    ob_end_clean();
}

echo json_encode($json);
exit();