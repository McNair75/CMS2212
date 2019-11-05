<?php

if (!defined('CMS_VERSION'))
    exit;

if (isset($params['db_errors'])) {
    $this->ShowErrors($params['db_errors']);
}

#---------------------
# Smarty processing
#---------------------

$smarty->assign('startform', $this->CreateFormStart($id, 'admin_domaintenance', $returnid));
$smarty->assign('endform', $this->CreateFormEnd());

$smarty->assign('button_fix_fielddefs', $this->CreateInputSubmit($id, 'fix_fielddefs', $this->Lang('repair')));
$smarty->assign('button_import_from_LI2', $this->CreateInputSubmit($id, 'import_from_LI2', $this->Lang('button_import_from_LI2')));
$smarty->assign('button_fix_import_from_LI2', $this->CreateInputSubmit($id, 'fix_import_from_LI2', $this->Lang('button_fix_import_from_LI2')));

echo $this->ProcessTemplate('maintenancetab.tpl');