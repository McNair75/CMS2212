<?php

if (!defined('CMS_VERSION'))
    exit;

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_category'))
    return;

//
// functions
//
function lise_get_category_tree($module_alias, $parent_id = -1) {
    $db = cmsms()->GetDb();
    $thetable = cms_db_prefix() . 'module_' . $module_alias . '_category';

    $query = 'SELECT * FROM ' . $thetable . ' WHERE parent_id = ? ORDER BY hierarchy';
    $data = $db->GetArray($query, array($parent_id));

    if (is_array($data) && count($data)) {
        for ($i = 0; $i < count($data); $i++) {
            $tmp = lise_get_category_tree($module_alias, $data[$i]['category_id']);
            if (is_array($tmp) && count($tmp)) {
                $data[$i]['children'] = $tmp;
            }
        }

        return $data;
    }
}

function lise_create_category_flatlist($tree, $parent_id = -1) {
    $data = array();
    $order = 1;
    foreach ($tree as &$node) {
        if (is_array($node) && count($node) == 2) {
            $pid = (int) substr($node[0], strlen('cat_'));
            $data[] = array('id' => $pid, 'parent_id' => $parent_id, 'order' => $order);
            if (isset($node[1]) && is_array($node[1])) {
                $data = array_merge($data, lise_create_category_flatlist($node[1], $pid));
            }
        } else {
            $pid = (int) substr($node, strlen('cat_'));
            $data[] = array('id' => $pid, 'parent_id' => $parent_id, 'order' => $order);
        }
        $order++;
    }
    return $data;
}

//
// Get the data
//
$tree = lise_get_category_tree($this->_GetModuleAlias());

//print_r($tree);
//
// Handle form submission
//
if (isset($params['cancel'])) {
    $this->Redirect($id, 'defaultadmin', $returnid, array('active_tab' => 'categorytab'));
} else if (isset($params['submit'])) {

    $orderdata = json_decode($params['orderdata']);

    $flatlist = lise_create_category_flatlist($orderdata);
    $thetable = cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_category';

    // get the full category list.
    $categories = array(); {
        $query = 'SELECT * FROM ' . $thetable . ' ORDER BY hierarchy';
        $tmp = $db->GetArray($query);
        for ($i = 0; $i < count($tmp); $i++) {
            $rec = $tmp[$i];
            $categories[$rec['category_id']] = $tmp;
        }
    }

    // and update the database with our new order info.
    $query = 'UPDATE ' . $thetable . ' SET parent_id = ?, position = ?, hierarchy = ? WHERE category_id = ?';
    foreach ($flatlist as $rec) {
        $dbr = $db->Execute($query, array($rec['parent_id'], $rec['order'], '', $rec['id']));
    }
    LISECategoryOperations::UpdateHierarchyPositions($this);

    $this->Redirect($id, 'defaultadmin', $returnid, array('active_tab' => 'categorytab', 'message' => 'changessaved'));
}

//
// give data to smarty
//
$smarty->assign('formstart', $this->CreateFormStart($id, 'admin_reordercategory'));
$smarty->assign('formend', $this->CreateFormEnd());

$smarty->assign('tree', $tree);

$smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));
$smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', lang('cancel')));

echo $this->ModProcessTemplate('admin_reordercategory.tpl');