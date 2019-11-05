<?php

if (!defined('CMS_VERSION'))
    exit;

#---------------------
# Database tables
#---------------------

$dict = NewDataDictionary($db);

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_lise_instances');
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_lise_fielddefs');
$dict->ExecuteSQLArray($sqlarray);

#---------------------
# Events
#---------------------

$this->RemoveEventHandler('Core', 'ContentPostCompile');
$this->RemoveEventHandler('Core', 'ModuleInstalled');
$this->RemoveEventHandler('Core', 'ModuleUninstalled');
$this->RemoveEventHandler('Core', 'ModuleUpgraded');

#---------------------
# Preferences
#---------------------

$this->RemovePreference();

#---------------------
# Smarty plugins
#---------------------

$this->RemoveSmartyPlugin();
