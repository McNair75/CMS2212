<?php

if (!defined('CMS_VERSION'))
    exit;


#---------------------
# Database tables
#---------------------

$dict = NewDataDictionary($db);

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_item');
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fielddef');
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fielddef_opts');
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_category'); // the only good cat is a dead cat
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_item_categories');
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_color'); // the only good cat is a dead cat
//$dict->ExecuteSQLArray($sqlarray); TODO

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_item_colors');
//$dict->ExecuteSQLArray($sqlarray); TODO
// DEPRECATED
$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_template');
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fieldval');
$dict->ExecuteSQLArray($sqlarray);

#---------------------
# Templates
#---------------------	

$this->DeleteTemplate();

#---------------------
# Preferences
#---------------------

$this->RemovePreference();

#---------------------
# Permissions
#---------------------

$this->RemovePermission($this->_GetModuleAlias() . '_modify_item');
$this->RemovePermission($this->_GetModuleAlias() . '_modify_category');
//$this->RemovePermission($this->_GetModuleAlias() . '_modify_color'); TODO
$this->RemovePermission($this->_GetModuleAlias() . '_modify_option');
$this->RemovePermission($this->_GetModuleAlias() . '_remove_item');
$this->RemovePermission($this->_GetModuleAlias() . '_approve_item');
$this->RemovePermission($this->_GetModuleAlias() . '_modify_all_item');

#---------------------
# Events
#---------------------

$this->RemoveEvent('PreItemSave');
$this->RemoveEvent('PostItemSave');

$this->RemoveEvent('PreItemDelete');
$this->RemoveEvent('PostItemDelete');

$this->RemoveEvent('PreItemLoad');
$this->RemoveEvent('PostItemLoad');

$this->RemoveEvent('PreRenderAction');

