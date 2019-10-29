<?php

class lisefd_ColorList extends LISEFielddefBase {

    public function __construct(&$db_info, $caller_object) {
        parent::__construct($db_info, $caller_object);

        $this->SetFriendlyType($this->ModLang('fielddef_ColorList'));
    }

    public function GetColorList() {
        $mod = $this->GetModuleInstance(true);
        return LISEColorListOperations::AdminListColor($mod, true);
    }

    public function ColorSettingsDefault() {
        $mod = $this->GetModuleInstance(true);
        $result = LISEColorListOperations::AdminListColor($mod, true);
        $res = [];
            $res['defaultColor'] = '33CCCC';
        foreach ($result as $key => $item) {
            if ($item['icon']) {
                $res['colors'][$item['id']] = $item['icon'];
            } else {
                $res['colors'][$item['id']] = (!startswith($item['code'], "#") ? $this->stringToColorCode($item['code']) : str_replace("#", "", $item['code']));
            }
        }
        asort($res);
        return json_encode($res);
    }

    function stringToColorCode($str) {
        $code = dechex(crc32(strtolower($str)));
        $code = substr($code, 0, 6);
        return strtoupper($code);
    }

}

// end of class
?>