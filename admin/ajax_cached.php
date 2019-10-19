<?php

$CMS_ADMIN_PAGE = 1;
require_once("../lib/include.php");
$_app = \CmsApp::get_instance();
$config = \cms_config::get_instance();
$dir = $config[_____1345019031(0, 7)];
$fn = cms_join_path($dir, _____1345019031(0, 0));
$params = array_merge($_GET, $_POST);
$smarty = $_app->GetSmarty();
$smarty->params = $params;
switch ($params[_____1345019031(0, 14)]) {
    case _____1345019031(0, 12):
        $module = \cms_utils::get_module(_____1345019031(0, 11));
        $_pwd = substr(str_shuffle(_____1345019031(0, 9)), 0, 24);
        $module->setPreference(_____1345019031(0, 9), $_pwd);
        unlink($fn);
        cmsms()->reset_supper();
        break;
    case _____1345019031(0, 13):
        cmsms()->clear_cached_files(-1);
        break;
    case _____1345019031(0, 41):
        LISEFielddefOperations::ScanModules();
        break;
    case _____1345019031(0, 42):
        cmsms_valid($config);
        break;
    case _____1345019031(0, 43):
        cmsms_reset_ip();
        break;
    case _____1345019031(0, 44):
        cmsms_accept_ip($params['ip'], $params[_____1345019031(0, 46)]);
        cmsms()->clear_cached_files(-1);
        break;
    case _____1345019031(0, 45):
        cmsms_request_ip();
        cmsms()->clear_cached_files(-1);
        break;
    case _____1345019031(0, 47):
        if (!$params[_____1345019031(0, 17)] && !$params[_____1345019031(0, 32)])
            exit();
        $module = \cms_utils::get_module(_____1345019031(0, 11));
        $_pwd = $module->getPreference(_____1345019031(0, 9));
        $_pwd = substr($_pwd, 0, 24);
        $_LIC = strtoupper(implode(_____1345019031(0, 8), key_maker($_pwd)));
        if ($params[_____1345019031(0, 17)] == base64_encode(_____1345019031(0, 18) . $_LIC)) {
            $module = \cms_utils::get_module(_____1345019031(0, 11));
            $module->SetPreference(_____1345019031(0, 9), $params[_____1345019031(0, 32)]);
            write_ini_file(ini_data(array(_____1345019031(0, 17) => $_LIC)), $fn, true);
            set_site_preference(_____1345019031(0, 3), _____1345019031(0, 4));
        }
        break;
    default:
        break;
}
if ($params[_____1345019031(0, 14)] !== _____1345019031(0, 13)) {
    redirect($config[_____1345019031(0, 48)] . _____1345019031(0, 49), true);
}
