<?php

class lise_utils {

    private static $_init = FALSE;
    private static $_mod;
    private static $_db;
    private static $_config;

    private static final function _initialize() {
        if (!self::$_init) {
            self::$_mod = cmsms()->GetModuleInstance('LISE');
            self::$_db = cmsms()->GetDb();
            self::$_config = \LISE\ConfigManager::GetConfigInstance(self::$_mod);
            self::$_init = TRUE;
        }
    }

    /**
     * first pass to conform to a valid alias
     */
    private static function _to_alias_format($str) {
        $str = munge_string_to_url($str);
        return $str;
    }

    public static final function &array_to_object($array) {
        $obj = new StdClass;
        foreach ($array as $key => $value) {

            $obj->$key = $value;
        }

        return $obj;
    }

    public static final function clean_params(&$params, $list, $accesslist = false) {

        foreach ($params as $key => $value) {

            if ($accesslist) {

                if (!in_array($key, (array) $list)) {

                    unset($params[$key]);
                }
            } else {

                if (in_array($key, (array) $list)) {

                    unset($params[$key]);
                }
            }
        }
    }

    public static final function init_var($string, $params, $default = '') {
        $var = $default;

        if (isset($params[$string]))
            $var = $params[$string];

        return $var;
    }

    /**
     * Generate alias
     * @param string $name The string that the alias will be generated from
     * @return string Returns the alias
     */
    public static final function generate_alias($name, $prefix = 'item') {
        if (!is_string($name))
            return;

        $alias = self::_to_alias_format($name);
        $prefix = self::_to_alias_format($prefix);

        # aliases cannot start with numbers
        if (self::StartsWithNumber($alias)) {
            if (!is_string($prefix))
                $prefix = 'item';
            if (self::StartsWithNumber($prefix))
                $prefix = 'item';

            $alias = $prefix . '-' . $alias;
        }

        return $alias;
    }

    /**
     * Generate an unique alias given a module instance
     * @param string $name The string that the alias will be generated from
     * @return string Returns the alias
     */
    public static final function generate_unique_alias(LISE $mod, $name, $item_id = -1, $table = 'item') {
        // $prefix = $mod->GetPreference('item_singular', 'item');
        // $alias = $tmp = self::generate_alias($name, $prefix);
        // $c = 2;
        // while (!self::is_unique_alias($mod, $alias)) {
        //     $alias = $tmp . '-' . (string) $c++;
        // }
        // return $alias;
        $alias = trim($name);
        if ($alias == '')
            $alias = trim($name);
        // auto generate an alias
        $alias = munge_string_to_url($alias, true);

        // now auto-increment the alias.
        $prefix = $alias;
        $num = 1;
        if (preg_match('/(.*)-([0-9]*)$/', $alias, $matches)) {
            $prefix = $matches[1];
            $num = (int) $matches[2];
        }
        $test = $alias;
        $testnum = 1;
        do {
            if (!self::CheckAliasUsed($mod, $test, $item_id, $table)) {
                $alias = $test;
                break;
            }
            $num++;
            $test = $prefix . '-' . $num;
        } while ($testnum < 100);

        return $alias;
    }

    /**
     * Generate an unique alias given a module instance
     * @param string $name The string that the alias will be generated from
     * @return string Returns the alias
     */
    public static final function generate_unique_alias_category(LISE $mod, $name) {
        $prefix = $mod->GetPreference('item_singular', 'item');
        $alias = $tmp = self::generate_alias($name, $prefix);
        $c = 2;
        while (!self::is_unique_alias_category($mod, $alias)) {
            $alias = $tmp . '-' . (string) $c++;
        }
        return $alias;
    }

    /**
     * Check if a potential alias is valid.
     * Lipit
     * @param string $alias The alias to check
     * @return bool
     * @since 2.2.2
     */
    public static final function CheckAliasValid($alias) {
        if (((int) $alias > 0 || (float) $alias > 0.00001) && is_numeric($alias))
            return FALSE;
        $tmp = munge_string_to_url($alias, TRUE);
        if ($tmp != mb_strtolower($alias))
            return FALSE;
        return TRUE;
    }

    /**
     * Check if a content alias is used
     * Lipit
     * @param string $alias The alias to check
     * @param int $content_id The id of hte current page, if any
     * @return bool
     * @since 2.2.2
     */
    public static final function CheckAliasUsed(LISE $mod, $alias, $item_id = -1, $table = 'item') {
        self::_initialize();
        $alias = trim($alias);
        $item_id = (int) $item_id;

        $params = [$alias];
        if ($table == 'item') {
            $query = 'SELECT count(*) as c FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_item WHERE alias = ?';
            if ($item_id > 0) {
                $query .= " AND item_id != ?";
                $params[] = $item_id;
            }
        } else {
            $query = 'SELECT count(*) as c FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_category WHERE category_alias = ?';
            if ($item_id > 0) {
                $query .= " AND category_id != ?";
                $params[] = $item_id;
            }
        }
        $db = CmsApp::get_instance()->GetDb();
        $out = (int) $db->GetOne($query, $params);
        if ($out > 0)
            return TRUE;
    }

    /**
     * Is valid alias
     * @param string $alias
     * @return bool Returns true if string in question is a valid alias, or
     * false otherwise
     */
    public static final function is_valid_alias($alias) {
        if (!is_string($alias))
            return FALSE;

        if (self::StartsWithNumber($alias))
            return FALSE;

        // check alias
        // http://www.php.net/manual/en/language.variables.basics.php
        if (preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\-\x7f-\xff]*$/', $alias)) {
            return true;
        } else {
            return false;
        }
    }

    public static function StartsWithNumber($str) {
        return preg_match('/^\d/', $str) === 1;
    }

    /**
     * Has Prefix
     * @param string $string The string in question
     * @param array $prefix Array of prefixes to match against
     * @return bool Returns true if prefix is found, or false otherwise
     */
    public static final function has_prefix($string, $prefixes) {
        if (!is_string($string))
            return FALSE;
        if (!is_array($prefixes))
            return FALSE;

        foreach ($prefixes as $prefix) {
            if (strpos($string, $prefix) === 0) {
                return true;
            }
        }

        return false;
    }

    public static final function explode_orderby($param, $valid_cols) {

        $order_cols = array();
        $custom_cols = array();
        $index = 0;

        foreach (explode(',', $param) as $col) {
            $col = trim($col);
            $col_parts = explode('|', $col);

            // column name
            $col_name = $col_parts[0];
            $col_order = 'ASC';

            // order column ascending or descending
            if (isset($col_parts[1])) {
                $col_order = (in_array($col_parts[1], array('ASC', 'DESC')) ? $col_parts[1] : 'ASC');
            }

            $col_order = (in_array($col_order, array('ASC', 'DESC')) ? $col_order : 'ASC');

            if (isset($valid_cols[$col_name])) {
                $order_cols[$index] = $valid_cols[$col_name] . ' ' . $col_order;
            } elseif (startswith($col_name, 'custom_')) {

                $custom_name = substr($col_name, 7);
                $obj = new stdClass;
                $obj->name = $custom_name;
                $obj->order = $col_order;

                $custom_cols[$index] = $obj;
            }

            $index++;
        }

        return array($order_cols, $custom_cols);
    }

    public static final function isCMS2() {
        return (bool) (version_compare(CMS_VERSION, '2.0') > -1);
    }

    public static final function isCMS201() {
        return (bool) (version_compare(CMS_VERSION, '2.0.1') > -1);
    }

    public static final function UIgif($name = '') {
        self::_initialize();
        $url = self::$_mod->GetModuleURLPath() . '/images/moduleui/' . $name . '.gif';
        return $url;
    }

    public static final function UIpng($name = '') {
        self::_initialize();
        $url = self::$_mod->GetModuleURLPath() . '/images/moduleui/' . $name . '.png';
        return $url;
    }

    public static final function DisplayImage($imagefn = '', $alt = '', $width = '', $height = '', $class = '') {

        $retStr = '<img src="' . $imagefn . '"';

        if ($class != '') {
            $retStr .= ' class="' . $class . '"';
        }

        if ($width != '') {
            $retStr .= ' width="' . $width . '"';
        }

        if ($height != '') {
            $retStr .= ' height="' . $height . '"';
        }

        if ($alt != '') {
            $retStr .= ' alt="' . $alt . '" title="' . $alt . '"';
        }

        $retStr .= ' />';

        return $retStr;
    }

    public static function db_column_exists($table, $column) {
        self::_initialize();
        $q = 'SELECT * FROM ' . $table . ' LIMIT 1';

        try {
            $r = self::$_db->GetRow($q);
        } catch (Exception $e) {
            // nvm errors.... we give'm false
        }

        return isset($r[$column]);
    }

    public static function log($message = '') {
        $message = empty($message) ? '[empty message]' : $message;
        $dbt = debug_backtrace(true, 5);
        $caller_mod = NULL;


        # now  find the latest LISE Object... a bit too expensive but it works for debugging
        foreach ($dbt as $one) {
            if (!isset($one['object']))
                continue;

            if (!is_a($one['object'], 'LISE'))
                continue;

            $caller_mod = $one['object'];
            break;
        }

        if (!is_object($caller_mod)) {
            \audit(0, 'lise_utils::log', 'failed to determine a LISE instance: moving on...');
            return;
        }

        $config = \LISE\ConfigManager::GetConfigInstance($caller_mod);

        if (empty($config['log']))
            return;

        $root = dirname(dirname(dirname(dirname(__FILE__))));

        $path = isset($config['log_path']) ? cms_join_path($config['module_path'], $config['log_path']) : $root;

        $file_name = isset($config['log_filename']) ? $config['log_filename'] : 'LISE.log';
        $fn = cms_join_path($path, $file_name);

        $datetime = date('Y-m-d H:i:s');
        $text = $datetime . ' :: ';
        $text .= $message . PHP_EOL;
        $tmp_max = ini_set('log_errors_max_len', 0);
        error_log($text, 3, $fn);
        // reset to the old value;
        ini_set('log_errors_max_len', $tmp_max);
    }

    public static function is_unique_alias(LISE $mod, $alias) {
        self::_initialize();
        $query = 'SELECT count(*) as c FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_item WHERE alias = ?';
        return !(bool) self::$_db->GetOne($query, array($alias));
    }

    public static function is_unique_alias_category(LISE $mod, $alias) {
        self::_initialize();
        $query = 'SELECT * FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_category WHERE category_alias = ?';
        return !(bool) self::$_db->GetOne($query, array($alias));
    }

    /**
     * @param $module_name
     * to avoid using the internal CMSMS function
     * we basicaly reproduce it here
     */
    public static function queue_for_upgrade($module_name) {
        $module_name = trim((string) $module_name);
        if (!$module_name)
            return;
        if (!isset($_SESSION['moduleoperations']))
            $_SESSION['moduleoperations'] = array();
        if (!isset($_SESSION['moduleoperations'][$module_name]))
            $_SESSION['moduleoperations'][$module_name] = 1;
    }

}

// end of class
