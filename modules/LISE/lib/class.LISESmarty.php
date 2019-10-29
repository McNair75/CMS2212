<?php

class LISESmarty {

    static private $_ready = FALSE;
    static private $_LI2_present = FALSE;
    static private $_CMSMS2 = FALSE;

    private static function _init() {
        if (!self::$_ready) {
            self::$_LI2_present = is_object(cmsms()->GetModuleInstance('ListIt2'));
            self::$_CMSMS2 = lise_utils::isCMS2();
            self::$_ready = TRUE;
        }
    }

    public static function LI2_present() {
        self::_init();
        return self::$_LI2_present;
    }

    public static function IsCMSMS2() {
        self::_init();
        return self::$_CMSMS2;
    }

    public static function InstanceName() {
        $instance = cms_utils::get_app_data('lise_instance');
        if (empty($instance))
            $instance = '::NA::';
        return $instance;
    }

    public static function Log($message = '') {
        \lise_utils::log();
    }

}

// end of class