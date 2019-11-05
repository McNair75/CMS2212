<?php

namespace LISE;

class Session {

    private static function _unset($key) {
        unset($_SESSION[$key]);
        return isset($_SESSION[$key]);
    }

    public static final function Store(\LISE $mod, $key, $val) {
        $mod_name = $mod->GetName();
        if (empty($key))
            return FALSE;
        if (!isset($val))
            return FALSE;
        $_SESSION[$mod_name . '_' . $key] = $val;
        return TRUE;
    }

    public static final function Read(\LISE $mod, $key) {
        $mod_name = $mod->GetName();
        if (empty($key))
            return NULL;

        if (isset($_SESSION[$mod_name . '_' . $key])) {
            return $_SESSION[$mod_name . '_' . $key];
        }

        return FALSE;
    }

    public static final function Clear(\LISE $mod, $key) {
        $mod_name = $mod->GetName();
        if (empty($key))
            return NULL;

        if (isset($_SESSION[$mod_name . '_' . $key])) {
            return self::_unset($_SESSION[$mod_name . '_' . $key]);
        }

        return FALSE;
    }

    public static final function ClearAllStartingWith(\LISE $mod, $prefix = '') {
        $prefix = $mod->GetName() . '_' . $prefix;

        foreach (array_keys($_SESSION) as $k) {
            if (startswith($k, $prefix)) {
                self::_unset($k);
            }
        }
    }

    public static final function ClearAll(\LISE $mod) {
        self::ClearAllStartingWith($mod, '');
    }

}
