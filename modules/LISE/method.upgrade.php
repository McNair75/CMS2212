<?php

if (!defined('CMS_VERSION'))
    exit;

/* * **** */
# so far this is valid only for beta testing #
/* * *** */

switch ($oldversion) {
    case '0.1.b.2':
    case '0.1.b.3':

        # we now support InnoDB instead of MyISAM
        $prefix = cms_db_prefix() . 'module_' . $this->GetName() . '_';

        $table_list = array(
            'instances',
            'fielddefs'
        );

        foreach ($table_list as $table) {
            $tablename = $prefix . $table;
            $query = 'ALTER TABLE ' . $tablename . ' ENGINE=InnoDB;';
            cmsms()->GetDb()->Execute($query);
        }

        # the plugin moved to another class
        $this->RemoveSmartyPlugin('LISELoader'); #<- there was a typo here before?
        $this->RegisterSmartyPlugin('LISELoader', 'function', array('LISELoader', 'loader'));

        break;

    case '0.1.b.4':
        # the plugin moved to another class
        $this->RemoveSmartyPlugin('LISELoader');
        $this->RegisterSmartyPlugin('LISELoader', 'function', array('LISELoader', 'loader'));
        break;

    case '1.3.1':
    case '1.3.1.2':
        # add domain_id
        break;
}

if ($this->GetPreference('allow_autoupdate', 0)) {
    $modules = $this->ListModules();

    $modops = ModuleOperations::get_instance();
    $installed = $modops->GetInstalledModules();

    foreach ($modules as $module) {
        if (in_array($module->module_name, $installed)) {
            $instance = $modops->get_module_instance($module->module_name, '', TRUE);
            if ($instance->GetPreference('auto_upgrade')) {
                lise_utils::queue_for_upgrade($module->module_name);
            }
        }
    }
}