<?php

if (!defined('CMS_VERSION'))
    exit;

$parms = array('active_tab' => 'maintenancetab');

#---------------------
# Fix fielddef tables
#---------------------
if (isset($params['fix_fielddefs'])) {
    $type_map = array(
        'textbox' => 'TextInput',
        'dropdown' => 'Dropdown',
        'hierarchy' => 'ContentPages',
        'checkbox' => 'Checkbox',
        'textarea' => 'TextArea',
        'gallery' => 'GalleryDropdown',
        'select_date' => 'SelectDate',
        'upload_file' => 'GBFilePicker',
        'select_file' => 'SelectFile'
    );

    $modules = $this->ListModules();

    foreach ($modules as $module) {

        $mod = cmsms()->GetModuleInstance($module->module_name);

        if (is_object($mod)) {

            foreach ($type_map as $old_type => $new_type) {

                $query = 'UPDATE ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_fielddef SET type = ? WHERE type = ?';
                $result = $db->Execute($query, array($new_type, $old_type));
            }
        }
    }

    $parms['message'] = 'message_fielddefs_fixed';
}

#---------------------
# import from LI2
#---------------------
if (isset($params['import_from_LI2'])) {
    $listit2 = cmsms()->GetModuleInstance('ListIt2');
    $modules = $listit2->ListModules();

    foreach ($modules as $module) {
        $curr_mod = cmsms()->GetModuleInstance($module->module_name);

        if (!is_object($curr_mod)) {
            throw new \LISE\Exception('Error in ' . __CLASS__ . ' invalid instance name!');
        }

        $duplicator = new LISE_LI2converter($module->module_name);
        $module_fullname = $duplicator->Run();
    }

    $parms['message'] = 'message_instances_copied';
}

#---------------------
# Fix import from LI2
#---------------------
if (isset($params['fix_import_from_LI2'])) {
    $modules = $this->ListModules();
    $error_messages = array();

    foreach ($modules as $module) {
        $mod = cmsms()->GetModuleInstance($module->module_name);

        if (!is_object($mod))
            continue;# module is not active or was just deleted, or something odd....

        $tableprefix = cms_db_prefix() . 'module_' . $mod->_GetModuleAlias();

        try {
            $tablename = $tableprefix . '_item';

            if (!lise_utils::db_column_exists($tablename, 'url')) {
                $q = 'ALTER TABLE ' . $tablename . ' ADD url VARCHAR(250)';
                $r = $db->Execute($q);
                if (!$r)
                    throw new \LISE\Exception($db->ErrorMsg(), \LISE\Error::DISCRETE_DB);
            }

            $tablename = $tableprefix . '_fielddef';

            if (!lise_utils::db_column_exists($tablename, 'template')) {
                $q = 'ALTER TABLE ' . $tablename . ' ADD template VARCHAR(250)';
                $r = $db->Execute($q);
                if (!$r)
                    throw new \LISE\Exception($db->ErrorMsg(), \LISE\Error::DISCRETE_DB);
            }
        } catch (Exception $e) {
            //$error_messages[] = 'Error: ' . $e->getMessage();  
            $error_messages[] = $e; // converts to string
        }
    }

    if (!empty($error_messages)) {
        $parms['db_errors'] = implode(', ', $error_messages);
    }
}


#---------------------
# Redirect
#---------------------

$this->Redirect($id, 'defaultadmin', $returnid, $parms);
?>