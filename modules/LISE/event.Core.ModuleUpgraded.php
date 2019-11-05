<?php

if (!defined('CMS_VERSION'))
    exit;

#---------------------
# Handle child cloning
#---------------------

$modules = $this->ListModules();
foreach ($modules as $module) {

    if ($module->module_name === $params['name']) {

        $duplicator = new LISEDuplicator();
        $duplicator->SetModule($module->module_name);
        $duplicator->SetDestination(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $module->module_name);
        $duplicator->Run();

        break;
    }
}

#---------------------
# Field scanner
#---------------------

if ($this->GetPreference('allow_autoscan'))
    LISEFielddefOperations::ScanModules();
