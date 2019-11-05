<?php

if (!defined('CMS_VERSION'))
    exit;

#Start MLE
global $hls, $hl, $mleblock;
#End MLE
#---------------------
# Init categories
#---------------------

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_category'))
    return;

$admintheme = cms_utils::get_theme_object();

$query_object = new LISECategoryQuery($this, $params);
$result = $query_object->Execute(true);

$items = array();
while ($result && $row = $result->FetchRow()) {

    $obj = $this->InitiateCategory();
    LISECategoryOperations::Load($this, $obj, $row, $mleblock);
    if ($obj->icon) {
        $obj->icon = '<img src="' . cms_join_path($config['uploads_url'], $obj->icon) . '" style="max-width:32px"/>';
    } else {
        $obj->icon = $admintheme->DisplayImage('icons/system/picture-icon-24.png', '', '', '', 'systemicon');
    }

    //$obj->depth = count(explode('.', $obj->hierarchy)) - 1;
    // approve
    if ($obj->active) {
        $obj->approve = $this->CreateLink($id, 'admin_approvecategory', $returnid, $admintheme->DisplayImage('icons/system/true.gif', $this->ModLang('revert'), '', '', 'systemicon'), array(
            'approve' => 0,
            'category_id' => $row['category_id'],
            'type' => 'Active'
        ));
    } else {
        $obj->approve = $this->CreateLink($id, 'admin_approvecategory', $returnid, $admintheme->DisplayImage('icons/system/false.gif', $this->ModLang('approve'), '', '', 'systemicon'), array(
            'approve' => 1,
            'category_id' => $row['category_id'],
            'type' => 'Active'
        ));
    }

    // popular
    if ($obj->popular) {
        $obj->popular = $this->CreateLink($id, 'admin_approvecategory', $returnid, $admintheme->DisplayImage('icons/system/true.gif', $this->ModLang('popular_off'), '', '', 'systemicon'), array(
            'popular' => 0,
            'category_id' => $row['category_id'],
            'type' => 'Popular'
        ));
    } else {
        $obj->popular = $this->CreateLink($id, 'admin_approvecategory', $returnid, $admintheme->DisplayImage('icons/system/false.gif', $this->ModLang('popular_on'), '', '', 'systemicon'), array(
            'popular' => 1,
            'category_id' => $row['category_id'],
            'type' => 'Popular'
        ));
    }

    // edit
    $obj->editlink = $this->CreateLink($id, 'admin_editcategory', $returnid, $admintheme->DisplayImage('icons/system/edit.gif', $this->ModLang('edit'), '', '', 'systemicon'), array(
        'category_id' => $row['category_id']
    ));

    $obj->name = $this->CreateLink($id, 'admin_editcategory', $returnid, $obj->name, array(
        'category_id' => $row['category_id']
    ));

    // delete
    $obj->delete = $this->CreateLink($id, 'admin_deletecategory', $returnid, $admintheme->DisplayImage('icons/system/delete.gif', $this->ModLang('delete'), '', '', 'systemicon'), array(
        'category_id' => $row['category_id']
    ));

    // select box
    $obj->select = $this->CreateInputCheckbox($id, 'categories[]', $row['category_id']);

    $items[] = $obj;
}

#---------------------
# Smarty processing
#---------------------

$smarty->assign('items', $items);
$smarty->assign('addlink', $this->CreateLink($id, 'admin_editcategory', $returnid, $admintheme->DisplayImage('icons/system/newobject.gif', $this->ModLang('add', $this->ModLang('category')), '', '', 'systemicon') . $this->ModLang('add', $this->ModLang('category'))));
$smarty->assign('reorderlink', $this->CreateLink($id, 'admin_reordercategory', $returnid, $admintheme->DisplayImage('icons/system/reorder.gif', $this->ModLang('reorder_categories'), '', '', 'systemicon') . $this->ModLang('reorder_categories')));

echo $this->ModProcessTemplate('categorytab.tpl');