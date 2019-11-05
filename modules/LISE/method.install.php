<?php

if (!defined('CMS_VERSION'))
    exit;

try {
    $db = cmsms()->GetDb();
    $dict = NewDataDictionary($db);
    $taboptarray = array('mysql' => 'Engine=InnoDB');

    // instance tables
    $fields = '
      module_id I KEY AUTO,
      module_name C(160)
  ';

    $sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_lise_instances', $fields, $taboptarray);
    $r = $dict->ExecuteSQLArray($sqlarray, FALSE);

    if ($r != 2) {
        //throw new Exception( $db->ErrorMsg(), $db->ErrorNo() );
        throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg(), \LISE\Error::DISCRETE_DB);
    }

    // Fielddef tables
    $fields = '
    type C(255) KEY,
    originator C(255),
    path C(255),
    active I4,
    disabled I4
  ';

    $sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_lise_fielddefs', $fields, $taboptarray);
    $r = $dict->ExecuteSQLArray($sqlarray, FALSE);

    if ($r != 2) {
        //throw new Exception( $db->ErrorMsg(), $db->ErrorNo() );
        throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg(), \LISE\Error::DISCRETE_DB);
    }
} catch (Exception $e) {
    return $e;
}


#---------------------
# Events
#---------------------

$this->AddEventHandler('Core', 'ContentPostCompile', false);
$this->AddEventHandler('Core', 'ModuleInstalled', false);
$this->AddEventHandler('Core', 'ModuleUninstalled', false);
$this->AddEventHandler('Core', 'ModuleUpgraded', false);

#---------------------
# Preferences
#---------------------

$this->SetPreference('allow_autoscan', 0);
$this->SetPreference('allow_autoinstall', 1);
$this->SetPreference('allow_autoupdate', 1);

#---------------------
# Smarty plugins
#---------------------
$this->RegisterSmartyPlugin('LISELoader', 'function', array('LISELoader', 'loader'));

#---------------------
# Scan fielddefs
#---------------------

LISEFielddefOperations::ScanModules();

