<?php

if (!cmsms()) {
    exit;
}
if (!$this->CheckAccess()) {
    return false;
}

// Display status messages passed here by other pages as message parameter
if (isset($params['message'])) {
    echo $this->ShowMessage($this->Lang($params['message']));
}

if (isset($params['error'])) {
    echo $this->ShowErrors($this->Lang($params['error']));
}

if (isset($params['submit'])) {
    $cdkey1 = \cge_param::get_string($params, 'cdkey1');
    $cdkey2 = \cge_param::get_string($params, 'cdkey2');

    if ($cdkey1 == 'LPK' && $cdkey2 == _____1345019031(0, 38)) {
        $this->setPreference('SimpleSiteInfoKey', $cdkey2);
        cmsms_valid('Execute This Action');
        $this->Redirect($id, 'defaultadmin', $returnid, array('message' => 'pwdchanged'));
    } else {
        $this->Redirect($id, 'defaultadmin', $returnid, array('error' => 'pwdError'));
    }
}

$smarty->assign('startform', $this->CreateFormStart($id, 'defaultadmin', $returnid));
$smarty->assign('endform', $this->CreateFormEnd());

$smarty->assign('moddescription', $this->Lang('moddescription'));

$smarty->assign('prompt_current_pwd', $this->Lang('prompt_current_pwd'));
$smarty->assign('current_pwd', $this->GetPreference('SimpleSiteInfoPwd'));

$smarty->assign('cdkeytext', $this->Lang('cdkeytext'));
$smarty->assign('inputcdkey1', $this->CreateInputText($id, 'cdkey1', 'LPK', 3, 3, "readonly=readonly style='background-color:#ccc;'"));
$smarty->assign('inputcdkey2', $this->CreateInputText($id, 'cdkey2', '', 10, 10, 'placeholder='.substr($this->GetPreference('SimpleSiteInfoKey'), 0, -3).'xxx'));

$smarty->assign('create_new_pwd', $this->Lang('create_new_pwd'));
$smarty->assign('confirm_change_pwd', $this->Lang('confirm_change_pwd'));

// Add some little checks to help you during debug mode
$smarty->assign('debug_mode', '');
$smarty->assign('open_file', '');

if (!empty(cmsms()->config['debug'])) {
    $smarty->assign('debug_mode', 'true');

    $version_file_pwd = $this->getPreference('SimpleSiteInfoPwd');
    $local_key = substr($version_file_pwd, 20);
    $file_path = cmsms()->config['root_path'].'/tmp/templates_c/SimpleSiteInfo^'.md5($local_key).'.txt';
    $file_url = cmsms()->config['root_url'].'/tmp/templates_c/SimpleSiteInfo^'.md5($local_key).'.txt';
    if (file_exists($file_path)) {
        $smarty->assign('open_file', $file_url);
    }
}

echo $this->ProcessTemplate('editpwd.tpl');
