<?php

if (!defined('CMS_VERSION'))
    exit;

lise_utils::log(print_r($params, 1));

if (isset($params['catid']))
    $catid = $params['catid'];

$config = cmsms()->GetConfig();

#---------------------
# Init items
#---------------------
$query_object = "SELECT item_id FROM cms_module_liselocations_item_categories WHERE category_id = ?";
$dbr = $db->Execute($query_object, array($catid));
$res = [];
while ($row = $dbr->FetchRow()) {
    $item_query = "SELECT title FROM " . cms_db_prefix() . "module_liselocations_item WHERE item_id = ?";
    $row_title = $db->GetOne($item_query, array($row['item_id']));

    $select_latitude = "SELECT value FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_fieldval Where item_id = ? and fielddef_id = ?";
    $row_latitude = $db->GetOne($select_latitude, array($row['item_id'], 2));

    $select_longitude = "SELECT value FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_fieldval Where item_id = ? and fielddef_id = ?";
    $row_longitude = $db->GetOne($select_longitude, array($row['item_id'], 3));

    $select_address = "SELECT value FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_fieldval Where item_id = ? and fielddef_id = ?";
    $row_address = $db->GetOne($select_address, array($row['item_id'], 4));

    $title = $row_title;
    $latitude = $row_latitude;
    $longitude = $row_longitude;
    $desc = $row_address;
    $index = 1;
    /* Each row is added as a new array */
    $locations['latlng'][] = array($title, $latitude, $longitude, $desc, $index);
}

$select_icon = "SELECT icon FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_category Where category_id = ?";
$row_icon = $db->GetOne($select_icon, array($catid));

$locations['icon'] = $config['uploads_url'] . '/' . $row_icon;

$handlers = ob_list_handlers();
for ($cnt = 0; $cnt < sizeof($handlers); $cnt++) {
    ob_end_clean();
}

echo json_encode($locations);
exit();