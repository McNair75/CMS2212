<?php

if (!defined('CMS_VERSION'))
    exit;

#---------------------
# Init items
#---------------------

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_option'))
    return;

$admintheme = cms_utils::get_theme_object();
$fielddefs = $this->GetFieldDefs();

foreach ($fielddefs as $fielddef) {

    $fielddef->SetName($this->CreateLink($id, 'admin_editfielddef', $returnid, $fielddef->GetName(), array('fielddef_id' => $fielddef->GetId())));

    if ($fielddef->IsRequired()) {
        $fielddef->SetRequired($this->CreateLink($id, 'admin_requirefielddef', $returnid, $admintheme->DisplayImage('icons/system/true.gif', $this->ModLang('status_optional'), '', '', 'systemicon'), array('require' => 0, 'fielddef_id' => $fielddef->GetId())));
    } else {
        $fielddef->SetRequired($this->CreateLink($id, 'admin_requirefielddef', $returnid, $admintheme->DisplayImage('icons/system/false.gif', $this->ModLang('status_required'), '', '', 'systemicon'), array('require' => 1, 'fielddef_id' => $fielddef->GetId())));
    }

    $fielddef->editlink = $this->CreateLink($id, 'admin_editfielddef', $returnid, $admintheme->DisplayImage('icons/system/edit.gif', $this->ModLang('edit'), '', '', 'systemicon'), array('fielddef_id' => $fielddef->GetId()));


    $fielddef->deletelink = $this->CreateLink($id, 'admin_deletefielddef', $returnid, $admintheme->DisplayImage('icons/system/delete.gif', $this->ModLang('delete'), '', '', 'systemicon'), array('fielddef_id' => $fielddef->GetId()));
    $fielddef->select = $this->CreateInputCheckbox($id, 'fielddefs[]', $fielddef->GetId());
}

#---------------------
# Smarty processing
#---------------------

$smarty->assign('startform', $this->CreateFormStart($id, 'admin_deletefielddef', $returnid));
$smarty->assign('endform', $this->CreateFormEnd());

$smarty->assign_by_ref('items', $fielddefs);
$smarty->assign('submitorder', $this->CreateInputSubmit($id, 'submit_fielddeforder', $this->ModLang('submit_order')));
$smarty->assign('addlink', $this->CreateLink($id, 'admin_editfielddef', $returnid, $admintheme->DisplayImage('icons/system/newobject.gif', $this->ModLang('add', $this->ModLang('fielddef')), '', '', 'systemicon') . $this->ModLang('add', $this->ModLang('fielddef'))));
$smarty->assign('submitmassdelete', $this->CreateInputSubmit($id, 'submitmassdelete', $this->ModLang('delete_selected', $this->ModLang('fielddefs')), '', '', $this->ModLang('areyousure_deletemultiple', $this->ModLang('fielddefs'))));

echo $this->ModProcessTemplate('fielddeftab.tpl');
