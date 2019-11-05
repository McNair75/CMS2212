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

if (isset($params['module_name'])) {
    $module_name = $params['module_name'];
}

if (isset($params['domain_id'])) {
    $domainsection = serialize($params['domain_id']);
}

$module_friendlyname = $module_name;

if (!empty($params['module_friendlyname'])) {
    $module_friendlyname = $params['module_friendlyname'];
}

$invalid_names = array('lise', 'original', 'xdefs', 'loader');
$modules = $this->ListModules();

// module admin section options
$admin_sections = array(
    lang('main') => 'main',
    lang('content') => 'content',
    lang('lise') => 'lise',
    lang('layout') => 'layout',
    lang('usersgroups') => 'usersgroups',
    lang('extensions') => 'extensions',
    lang('siteadmin') => 'siteadmin',
    lang('myprefs') => 'myprefs',
    lang('ecommerce') => 'ecommerce'
);

//$domains = CmsDomainCollection::get_all();
//if (count($domains) && cms_utils::module_available('MultiDomains')) {
//    for ($i = 0; $i < count($domains); $i++) {
//        $domain_sections[$domains[$i]->get_domain()] = $domains[$i]->get_id();
//    }
//}

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
        $duplicator = new LISEDuplicator($module_name);
        $module_fullname = $duplicator->Run();

        if ($autoinstall) {
            $modops = cmsms()->GetModuleOperations();
            $modops->InstallModule($module_fullname);
            $mod = cmsms()->GetModuleInstance($module_fullname);
            $mod->SetPreference('friendlyname', $module_friendlyname);
            $mod->SetPreference('adminsection', $params['adminsection']);
            $mod->SetPreference('domainsection', $domainsection);
            $mod->SetPreference('moddescription', $params['moddescription']);
        }

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
$smarty->assign('startform', $this->CreateFormStart($id, 'admin_copymodule', $returnid, 'post', 'multipart/form-data', false, '', $params));
$smarty->assign('endform', $this->CreateFormEnd());

$smarty->assign('input_module_name', $this->CreateInputText($id, 'module_name', $module_name, 40));
$smarty->assign('input_module_friendlyname', $this->CreateInputText($id, 'module_friendlyname', $module_friendlyname, 40));
$smarty->assign('input_moddescription', $this->CreateTextArea(false, $id, $this->GetPreference('moddescription', $this->ModLang('moddescription')), 'moddescription', 'pagesmalltextarea', '', '', '', '80', '3'));
$smarty->assign('input_adminsection', $this->CreateInputDropdown($id, 'adminsection', $admin_sections, -1, $this->GetPreference('adminsection', 'lise')));
$smarty->assign('input_domainsection', $this->CreateInputDropdown($id, 'domain_id[]', $domain_sections, -1, -1, 'size=' . (isset($domains) ? count($domains) : 'auto'), true));
$smarty->assign('autoinstall', $autoinstall);


$smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));
$smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', lang('cancel')));

echo $this->ProcessTemplate('copymodule.tpl');
?>