<?php

namespace LISE;

class ConfigManager {

    private static $_instances = array();

    public static final function GetConfigInstance(\LISE $mod) {
        $mod_name = $mod->GetName();

        if (!isset(self::$_instances[$mod_name])) {
            self::$_instances[$mod_name] = new \LISEConfig($mod);
        }

        return self::$_instances[$mod_name];
    }

    public static final function GetConfigForInstanceName($name) {
        if (!isset(self::$_instances[$name])) {
            $mods = instances_operations::GetInstancesNamesListArray();
            if (!in_array($name, $mods))
                return FALSE;
            self::$_instances[$mod_name] = new \LISEConfig($mod);
        }

        return self::$_instances[$mod_name];
    }

}
