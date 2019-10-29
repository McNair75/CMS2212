<?php

if (!defined('CMS_VERSION'))
    exit;

#Start MLE
global $hls, $hl, $mleblock;
#End MLE
#---------------------
# Init categories
#---------------------

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_color'))
    return;

$admintheme = cms_utils::get_theme_object();

$query_object = new LISEColorListQuery($this, $params);
$result = $query_object->Execute(true);

$items = array();
while ($result && $row = $result->FetchRow()) {

    $obj = $this->InitiateColorList();
    LISEColorListOperations::Load($this, $obj, $row, $mleblock);

    //$obj->depth = count(explode('.', $obj->hierarchy)) - 1;
    // approve
    if ($obj->active) {
        $obj->approve = $this->CreateLink($id, 'admin_approvecolor', $returnid, $admintheme->DisplayImage('icons/system/true.gif', $this->ModLang('revert'), '', '', 'systemicon'), array(
            'approve' => 0,
            'color_id' => $row['color_id'],
            'type' => 'Active'
        ));
    } else {
        $obj->approve = $this->CreateLink($id, 'admin_approvecolor', $returnid, $admintheme->DisplayImage('icons/system/false.gif', $this->ModLang('approve'), '', '', 'systemicon'), array(
            'approve' => 1,
            'color_id' => $row['color_id'],
            'type' => 'Active'
        ));
    }

    // popular
    if ($obj->popular) {
        $obj->popular = $this->CreateLink($id, 'admin_approvecolor', $returnid, $admintheme->DisplayImage('icons/system/true.gif', $this->ModLang('popular_off'), '', '', 'systemicon'), array(
            'popular' => 0,
            'color_id' => $row['color_id'],
            'type' => 'Popular'
        ));
    } else {
        $obj->popular = $this->CreateLink($id, 'admin_approvecolor', $returnid, $admintheme->DisplayImage('icons/system/false.gif', $this->ModLang('popular_on'), '', '', 'systemicon'), array(
            'popular' => 1,
            'color_id' => $row['color_id'],
            'type' => 'Popular'
        ));
    }

    // edit
    $obj->editlink = $this->CreateLink($id, 'admin_editcolor', $returnid, $admintheme->DisplayImage('icons/system/edit.gif', $this->ModLang('edit'), '', '', 'systemicon'), array(
        'color_id' => $row['color_id']
    ));

    $obj->name = $this->CreateLink($id, 'admin_editcolor', $returnid, $obj->name, array(
        'color_id' => $row['color_id']
    ));

    // delete
    $obj->delete = $this->CreateLink($id, 'admin_deletecolor', $returnid, $admintheme->DisplayImage('icons/system/delete.gif', $this->ModLang('delete'), '', '', 'systemicon'), array(
        'color_id' => $row['color_id']
    ));

    // select box
    $obj->select = $this->CreateInputCheckbox($id, 'categories[]', $row['color_id']);

    $items[] = $obj;
}

#---------------------
# Smarty processing
#---------------------

$smarty->assign('items', $items);
$smarty->assign('addlink', $this->CreateLink($id, 'admin_editcolor', $returnid, $admintheme->DisplayImage('icons/system/newobject.gif', $this->ModLang('add', $this->ModLang('color')), '', '', 'systemicon') . $this->ModLang('add', $this->ModLang('color'))));
$smarty->assign('reorderlink', $this->CreateLink($id, 'admin_reordercolor', $returnid, $admintheme->DisplayImage('icons/system/reorder.gif', $this->ModLang('reorder_colorlist'), '', '', 'systemicon') . $this->ModLang('reorder_colorlist')));

echo $this->ModProcessTemplate('colorlisttab.tpl');