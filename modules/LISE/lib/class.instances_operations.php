<?php

namespace LISE;

class instances_operations {

    private static $_instances = array();
    private static $_instances_names = array();

    private static final function _load_instances() {
        if (empty(self::$_instances)) {
            $db = \cmsms()->GetDb();
            $query = "SELECT * FROM " . cms_db_prefix() . "module_lise_instances ORDER BY module_id";
            $dbr = $db->GetAll($query);

            foreach ($dbr as $one) {
                self::$_instances_names[$one['module_id']] = $one['module_name'];
                self::$_instances[$one['module_id']] = new \stdClass();
                self::$_instances[$one['module_id']]->module_id = $one['module_id'];
                self::$_instances[$one['module_id']]->module_name = $one['module_name'];
                self::$_instances[$one['module_id']]->domain_id = $one['domain_id'];
            }
        }
    }

    public static final function GetInstancesNamesListArray() {
        self::_load_instances();
        return self::$_instances_names;
    }

    public static final function ListModules() {
        self::_load_instances();
        return self::$_instances;
    }

}
