<?php

if (!defined('CMS_VERSION'))
    exit;


#---------------------
# Init items
#---------------------

$allow_autoscan = $this->GetPreference('allow_autoscan', 0);
$allow_autoinstall = $this->GetPreference('allow_autoinstall', 0);
$allow_autoupdate = $this->GetPreference('allow_autoupdate', 0);

#---------------------
# Smarty processing
#---------------------

$smarty->assign('startform', $this->CreateFormStart($id, 'admin_updateoptions', $returnid));
$smarty->assign('endform', $this->CreateFormEnd());
$smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));

$smarty->assign('input_allow_autoscan', $this->CreateInputCheckbox($id, 'allow_autoscan', 1, $allow_autoscan, ($allow_autoscan ? 'checked="checked"' : '')));
$smarty->assign('input_allow_autoinstall', $this->CreateInputCheckbox($id, 'allow_autoinstall', 1, $allow_autoinstall, ($allow_autoinstall ? 'checked="checked"' : '')));
$smarty->assign('input_allow_autoupdate', $this->CreateInputCheckbox($id, 'allow_autoupdate', 1, $allow_autoupdate, ($allow_autoupdate ? 'checked="checked"' : '')));

echo $this->ProcessTemplate('optiontab.tpl');