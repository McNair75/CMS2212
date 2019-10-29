<?php

class LISELoader {

    static final public function loader($params, &$smarty) {
        $item = isset($params['item']) ? $params['item'] : 'item';
        $instance = isset($params['instance']) ? $params['instance'] : cms_utils::get_app_data('lise_instance'); // Mandatory
        $identifier = isset($params['identifier']) ? $params['identifier'] : null;
        $value = isset($params['value']) ? $params['value'] : null; // Mandatory
        $force_array = isset($params['force_array']) ? true : false;
        $result = array();

        if (is_null($instance))
            throw new \LISE\Exception($smarty->left_delimiter . "LISELoader" . $smarty->right_delimiter . ": Parameter instance is not given.");

        if (is_null($value))
            throw new \LISE\Exception($smarty->left_delimiter . "LISELoader" . $smarty->right_delimiter . ": Parameter value is not given.");

        // Load wanted instance
        $instance = cmsms()->GetModuleInstance($instance);
        if (!$instance instanceof LISE)
            throw new \LISE\Exception($smarty->left_delimiter . "LISELoader" . $smarty->right_delimiter . ": Loaded instance is not LISE instance.");

        // Get loader info
        switch ($item) {

            case 'item':

                $loader = 'LoadItemByIdentifier';
                if (is_null($identifier))
                    $identifier = 'item_id';
                break;

            case 'category':

                $loader = 'LoadCategoryByIdentifier';
                if (is_null($identifier))
                    $identifier = 'category_id';
                break;

            default:
                throw new \LISE\Exception($smarty->left_delimiter . "LISELoader" . $smarty->right_delimiter . ": Unknown item type");
        } // end switch

        global $mleblock;
        //Load
        $value = explode(',', $value);
        foreach ((array) $value as $one) {
            $result[$one] = $instance->$loader($identifier, $one, $mleblock);
        }

        // Return singular, if singular & force array is Off
        if (count($result) == 1 && !$force_array)
            $result = $result[key($result)];

        // Assign
        if (isset($params['assign'])) {
            $smarty->assign($params['assign'], $result);
            return;
        }

        return $result;
    }

}
