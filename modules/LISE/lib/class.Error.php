<?php

namespace LISE;

class Error {
    # this is a common error to all classes unless overriden...

    const UNKOWN = '00000';      # 'UNKOWN'
    const DISCRETE = '00001';      # 'DISCRETE'
    const DISCRETE_DB = '00002';      # 'DISCRETE_DB'
    # LISE Config...
    const READ_ONLY_CFG = '00010';      # 'READ_ONLY_CFG'

    #then just add to this list here and in the lang file of the module

    static public function GetErrorsList() {
        $r = new \ReflectionClass(get_called_class());
        return $r->getConstants();
    }

    static public function HasCode($code) {
        $codes = self::GetErrorsList();
        return in_array($code, $codes);
    }

}