<?php

class LISEConfig implements ArrayAccess {
    #---------------------
    # Attributes
    #---------------------	

    private $_data = array();
    private $_CMSMS_CFG;

    #---------------------
    # Magic methods
    #--------------------- 	

    public function __construct(LISE $mod) {
        $this->_CMSMS_CFG = \cmsms()->GetConfig();
        $this->_data['module_path'] = $mod->GetModulePath();
        $this->_data['LISE_instance_name'] = $mod->GetName();

        # for backward compatibility
        $this->_data['module_alias'] = strtolower($this->_data['LISE_instance_name']);
        $this->_data['item_query_class'] = 'LISEItemQuery';
        $this->_data['category_query_class'] = 'LISECategoryQuery';
        $this->_data['colorlist_query_class'] = 'LISEColorListQuery';
        $this->_data['archive_query_class'] = 'LISEArchiveQuery';

        $fn = cms_join_path($this->_data['module_path'], 'data', 'cfg.inc');
        $cfg = array();

        if (is_readable($fn)) {
            include($fn);

            if (isset($cfg)) {
                foreach ($cfg as $key => $value) {
                    $this->_data[$key] = $value;
                }
            }
        }

        # the following is deprecated
        # kept for compatibility purposes with very old LI2 -> LISE conversions
        # scheduled for removal

        $lise_config = array();
        $fn = cms_join_path($this->_data['module_path'], LISE_CONFIG_FILE);

        if (is_readable($fn)) {
            include($fn);

            if (isset($lise_config)) {
                foreach ($lise_config as $key => $value) {
                    $this->_data[$key] = $value;
                }
            }
        }
    }

    /**
     * Get a data by key
     *
     * @param string The key data to retrieve
     *
     * @access public
     * @return mixed|null
     */
    public function __get($key) {
        if ($this->_CMSMS_CFG->offsetExists($key))
            return $this->_CMSMS_CFG[$key];
        return $this->_data[$key];
    }

    public function __set($key, $value) {
        throw new \LISE\Exception('Read Only');
    }

    public function offsetExists($key) {
        return ( isset($this->_data[$key]) || $this->_CMSMS_CFG->offsetExists($key) );
    }

    public function offsetGet($key) {
        if ($this->_CMSMS_CFG->offsetExists($key))
            return $this->_CMSMS_CFG[$key];

        if (isset($this->_data[$key])) {
            $ret = $this->_data[$key];
        } else {
            $ret = NULL;
        }

        return $ret;
    }

    public function offsetSet($key, $value) {
        throw new \LISE\Exception(\LISE\Error::READ_ONLY_CFG);
    }

    public function offsetUnset($key) {
        throw new \LISE\Exception(\LISE\Error::READ_ONLY_CFG);
    }

    public function get($key) {
        if ($this->_CMSMS_CFG->offsetExists($key))
            return $this->_CMSMS_CFG[$key];

        if ($this->exists($key)) {
            return $this->_data[$key];
        }
    }

    public function set($key, $value) {
        throw new \LISE\Exception(\LISE\Error::READ_ONLY_CFG);
    }

    public function erase($key) {
        throw new \LISE\Exception(\LISE\Error::READ_ONLY_CFG);
    }

}

// end of class