<?php

if (!defined('CMS_VERSION'))
    exit;
LISEFielddefOperations::ScanModules();
// all done
$this->Redirect($id, 'defaultadmin', $returnid, array('active_tab' => 'fielddefstab', 'message' => 'fielddef_scanned'));
