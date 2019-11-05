<?php

if (!defined('CMS_VERSION'))
    exit;

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_option'))
    return;

if (!isset($params['module_id']))
    throw new \LISE\Exception('Missing parameter, this should not happen');

$GLOBALS['CMS_FORCE_MODULE_LOAD'] = 1;

$modops = cmsms()->GetModuleOperations();
$modules = $this->ListModules();
$modname = $modules[$params['module_id']]->module_name;

$exporter = new LISEInstanceExporter($modname);
$exporter->Run();

// all done
$this->Redirect($id, 'defaultadmin', $returnid, array('active_tab' => 'instancestab', 'message' => 'instance_moduleupgraded'));
