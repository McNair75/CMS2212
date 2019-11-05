<?php
die();
if (!isset($gCms))
    exit;
$db = cmsms()->GetDb();
$dict = NewDataDictionary($db);
$sqlarray = $dict->DropTableSQL(cms_db_prefix() . "module_mlecms_config");
$dict->ExecuteSQLArray($sqlarray);

// remove the permissions
$this->RemovePermission('manage mle_cms');
$this->RemovePermission('manage ' . MLE_SNIPPET . 'mle');
$this->RemovePermission('manage ' . MLE_BLOCK . 'mle');

// Remove all preferences for this module
$this->RemovePreference();

// And all Templates
$this->DeleteTemplate();

// Delete event handler
$this->RemoveEventHandler('Core', 'ContentPostRender');

// put mention into the admin log
$this->Audit(0, $this->Lang('friendlyname'), $this->Lang('uninstalled'));
