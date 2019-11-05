<?php

if (!defined('CMS_VERSION'))
    exit;

$GLOBALS['CMS_FORCE_MODULE_LOAD'] = 1;
$admintheme = cms_utils::get_theme_object();

#---------------------
# Submit
#---------------------
if (isset($params['submitfilter'])) {
    $errors = array();
    die('a');
    if (empty($errors)) {
//
//        $modules = $this->ListModules();
//        $oldmodname = $modules[$module_id]->module_name;
//        $cloner = new LISECloner($oldmodname, $module_name);
//        $module_fullname = $cloner->Run();
//        $modops = cmsms()->GetModuleOperations();
//        $mod = cmsms()->GetModuleInstance($module_fullname);
//        $mod->SetPreference('friendlyname', $module_friendlyname);
//        $mod->SetPreference('adminsection', $params['adminsection']);
//        $mod->SetPreference('moddescription', $params['moddescription']);
//        $params = array('message' => 'modulecopied', 'active_tab' => 'instancestab');
//        $this->Redirect($id, 'defaultadmin', '', $params);
    }
}

#---------------------
# Load modules
#---------------------

$modops = cmsms()->GetModuleOperations();
$allmodules = $modops->GetAllModuleNames();

if (count($allmodules) > 0) {
    $query = "SELECT * FROM " . cms_db_prefix() . "modules ORDER BY module_name";
    $result = $db->Execute($query);

    while ($result && $row = $result->FetchRow()) {
        $dbm[$row['module_name']]['Status'] = $row['status'];
        $dbm[$row['module_name']]['Version'] = $row['version'];
        $dbm[$row['module_name']]['Active'] = ($row['active'] == 1 ? true : false);
    }
}

$modules = $this->ListModules();
//add sync & sync_target into cms_modules

foreach ($modules as $module) {

    $mod = $modops->get_module_instance($module->module_name, '', true);
    $module->synclink = '';
    $module->clonelink = '';

    if (is_object($mod)) {
        $module->friendlyname = $mod->GetFriendlyName();
        $module->version = $dbm[$module->module_name]['Version'];

        $module->sel_domains = $mod->GetDomainSection();

        if (version_compare($mod->GetVersion(), $dbm[$module->module_name]['Version']) > 0) {
            $module->exportlink = '';
            $module->clonelink = '';
            $module->upgradelink = $this->CreateLink(
                    $id, 'admin_upgrademodule', $returnid, $themeObject->DisplayImage(
                            'icons/system/false.gif', $this->ModLang('instance_upgrade'), '', '', 'systemicon'
                    ), array('module_id' => $module->module_id)
            );

            $test = $themeObject->DisplayImage(
                    'icons/system/false.gif', $this->ModLang('instance_upgrade'), '', '', 'systemicon'
            );
        } else {
            if ($module->sync) {
                $module->synclink = $this->CreateLink($id, 'admin_sync', $returnid, $themeObject->DisplayImage('icons/system/true.gif', '', '', '', 'systemicon'), array(
                    'type' => $module->module_name
                ));
            } else {
                if ($module->module_name != 'LISESEO') {
                    $module->synclink = $this->CreateLink($id, 'admin_sync', $returnid, $themeObject->DisplayImage('icons/system/false.gif', '', '', '', 'systemicon'), array(
                        'type' => $module->module_name
                    ));
                }
            }
            if ($module->module_name != 'LISESEO') {
                $module->clonelink = $this->CreateLink(
                        $id, 'admin_clonemodule', $returnid, $themeObject->DisplayImage(
                                'icons/system/copy.gif', $this->ModLang('clone', $module->friendlyname), '', '', 'systemicon'
                        ), array('module_id' => $module->module_id)
                );
            }
            $module->exportlink = $this->CreateLink(
                    $id, 'admin_exportmodule', $returnid, lise_utils::DisplayImage(
                            lise_utils::UIgif('xml_rss'), $this->ModLang('export', $module->friendlyname), '', '', 'systemicon'
                    ), array('module_id' => $module->module_id)
            );

            $module->upgradelink = $themeObject->DisplayImage('icons/system/true.gif', $this->ModLang('instance_uptodate'), '', '', 'systemicon');
        }
    }
}

#---------------------
# Domain List
#---------------------
//$domains = CmsDomainCollection::get_all();
//if (count($domains) && cms_utils::module_available('MultiDomains')) {
//    for ($i = 0; $i < count($domains); $i++) {
//        $domain_sections[$domains[$i]->get_id()] = $domains[$i]->get_domain();
//    }
//    $smarty->assign('domain_sections', $domain_sections);
//}
#---------------------
# Smarty processing
#---------------------

$smarty->assign('modules', $modules);

$smarty->assign('startform', $this->CreateFormStart($id, 'admin_copymodule', $returnid));
$smarty->assign('endform', $this->CreateFormEnd());
$smarty->assign('duplicate', $this->CreateInputSubmit($id, 'duplicate', $this->ModLang('duplicate')));
$smarty->assign('startuploadform', $this->CreateFormStart($id, 'admin_uploadmodule', $returnid, 'post', 'multipart/form-data'));
$smarty->assign('enduploadform', $this->CreateFormEnd());
$smarty->assign('filenameinput', $this->CreateInputFile($id, 'filename', $this->ModLang('filename')));
$smarty->assign('upload', $this->CreateInputSubmit($id, 'upload', $this->ModLang('import_title')));

$smarty->assign('form_popup_start', $this->CreateFormStart($id, 'admin_instances', $returnid, 'post', 'multipart/form-data'));
$smarty->assign('form_popup_end', '</form>');

echo $this->ProcessTemplate('instancestab.tpl');