<?php

if (!defined('CMS_VERSION'))
    exit;

lise_utils::log(print_r($params, 1));

$config = cmsms()->GetConfig();

$ignored = '27061987';


header("Content-type:application/json; charset=utf-8");
$handlers = ob_list_handlers();
for ($cnt = 0; $cnt < sizeof($handlers); $cnt++) {
    ob_end_clean();
}


if (trim($_REQUEST['token']) != $ignored) {
    $json['status'] = false;
    $json['message'] = "Invalid HTTP method or parameters";
    unset($_REQUEST['mact']);
    unset($_REQUEST['cntnt01returnid']);
//    $json['data'] = $_REQUEST;
    echo json_encode($json);
    exit();
}

#---------------------
# Init items
#---------------------

$res = [];
$row_icon = cms_join_path($config['themes_url'], 'home.png');

if (isset($_REQUEST['catid'])) {
    $catid = $_REQUEST['catid'];
    $query_object = "SELECT item_id FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_item_categories WHERE category_id = ?";
    $dbr = $db->Execute($query_object, array($catid));
    $select_icon = "SELECT icon FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_category Where category_id = ?";
    $row_icon = $db->GetOne($select_icon, array($catid));
    $row_icon = cms_join_path($config['uploads_url'], $row_icon);
} else {
    $query_object = "SELECT item_id FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_item";
    $dbr = $db->Execute($query_object);
}

while ($row = $dbr->FetchRow()) {
    $item_query = "SELECT title FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_item WHERE item_id = ?";
    $row_title = $db->GetOne($item_query, array($row['item_id']));

    $select_latitude = "SELECT value FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_fieldval Where item_id = ? and fielddef_id = ?";
    $row_latitude = $db->GetOne($select_latitude, array($row['item_id'], 2));

    $select_longitude = "SELECT value FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_fieldval Where item_id = ? and fielddef_id = ?";
    $row_longitude = $db->GetOne($select_longitude, array($row['item_id'], 3));

    $select_address = "SELECT value FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_fieldval Where item_id = ? and fielddef_id = ?";
    $row_address = $db->GetOne($select_address, array($row['item_id'], 4));

    $select_icon = "SELECT icon FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_category Where category_id = (SELECT category_id FROM cms_module_liselocations_item_categories WHERE item_id = ?)";
    $row_icon = $db->GetOne($select_icon, array($row['item_id']));
    $row_icon = cms_join_path($config['uploads_url'], $row_icon);

    /* Each row is added as a new array */
    $res['latlng'][] = array('title' => $row_title, 'latitude' => $row_latitude, 'longitude' => $row_longitude, 'address' => $row_address, 'z-index' => 1, 'icon' => $row_icon);
}

$res['count'] = count($res['latlng']);

echo json_encode($res);
exit();