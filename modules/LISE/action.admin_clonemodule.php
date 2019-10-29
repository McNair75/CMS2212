<?php

if (!defined('CMS_VERSION'))
    exit;

#---------------------
# Check params
#---------------------
if (isset($params['cancel'])) {
    $params = array('active_tab' => 'instancestab');
    $this->Redirect($id, 'defaultadmin', $returnid, $params);
}

$autoinstall = $this->GetPreference('allow_autoinstall', 0);
$module_name = '';
$module_id = -1;

if (isset($params['module_name'])) {
    $module_name = $params['module_name'];
}

$module_friendlyname = $module_name;

if (isset($params['module_friendlyname'])) {
    $module_friendlyname = $params['module_friendlyname'];
}

if (isset($params['module_id'])) {
    $module_id = $params['module_id'];
}

$invalid_names = array('lise', 'original', 'xdefs', 'loader');
$modules = $this->ListModules();

// module admin section options
$admin_sections = array(
    lang('main') => 'main',
    lang('content') => 'content',
    lang('layout') => 'layout',
    lang('usersgroups') => 'usersgroups',
    lang('extensions') => 'extensions',
    lang('siteadmin') => 'siteadmin',
    lang('myprefs') => 'myprefs',
    lang('ecommerce') => 'ecommerce'
);

foreach ($modules as $mod) {
    $mod->module_name = substr($mod->module_name, strlen(LISEDuplicator::MOD_PREFIX));
    $invalid_names[] = strtolower($mod->module_name);
}

#---------------------
# Submit
#---------------------
if (isset($params['submit'])) {
    $errors = array();

    if (empty($module_name)) {
        $errors[] = $this->ModLang('module_name_empty');
    }

    if (preg_match('/[^0-9a-zA-Z]/', $module_name)) {
        $errors[] = $this->ModLang('module_name_invalid');
    }

    if (in_array(strtolower($module_name), $invalid_names)) {
        $errors[] = $this->ModLang('module_name_invalid');
    }

    if (empty($errors)) {

        $modules = $this->ListModules();
        $oldmodname = $modules[$module_id]->module_name;
        $cloner = new LISECloner($oldmodname, $module_name);
        $module_fullname = $cloner->Run();
        $modops = cmsms()->GetModuleOperations();
        $mod = cmsms()->GetModuleInstance($module_fullname);
        $mod->SetPreference('friendlyname', $module_friendlyname);
        $mod->SetPreference('adminsection', $params['adminsection']);
        $mod->SetPreference('moddescription', $params['moddescription']);
        $params = array('message' => 'modulecopied', 'active_tab' => 'instancestab');
        $this->Redirect($id, 'defaultadmin', '', $params);
    }
}

#---------------------
# Error handling
#---------------------
if (!empty($errors)) {

    echo $this->ShowErrors($errors);
}

#---------------------
# Smarty processing
#---------------------
$smarty->assign('startform', $this->CreateFormStart($id, 'admin_clonemodule', $returnid, 'post', 'multipart/form-data', false, '', $params));
$smarty->assign('endform', $this->CreateFormEnd());

$smarty->assign('input_module_name', $this->CreateInputText($id, 'module_name', $module_name, 40));
$smarty->assign('input_module_friendlyname', $this->CreateInputText($id, 'module_friendlyname', $module_friendlyname, 40));
$smarty->assign('input_moddescription', $this->CreateTextArea(false, $id, $this->GetPreference('moddescription', $this->ModLang('moddescription')), 'moddescription', 'pagesmalltextarea', '', '', '', '80', '3'));
$smarty->assign('input_adminsection', $this->CreateInputDropdown($id, 'adminsection', $admin_sections, -1, $this->GetPreference('adminsection', 'content')));
$smarty->assign('hidden', $this->CreateInputHidden($id, 'module_id', $module_id, 40));

$smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));
$smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', lang('cancel')));

echo $this->ProcessTemplate('clonemodule.tpl');
?>