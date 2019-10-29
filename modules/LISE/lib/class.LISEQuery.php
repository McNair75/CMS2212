<?php

abstract class LISEQuery {
    #---------------------
    # Constants
    #---------------------

    const VARTYPE_JOINS = 'joins';
    const VARTYPE_WHERE = 'where';
    const VARTYPE_ORDERBY = 'orderby';
    const VARTYPE_QPARAMS = 'qparams';

    #---------------------
    # Attributes
    #---------------------

    private $_params;
    private $_instance_name;
    //deprecated
    //private $_instance;
    //private $_db;

    protected $joins;
    protected $where;
    protected $orderby;
    protected $qparams;
    protected $query;         // Not set	
    protected $resultset;     // Not set
    protected $totalcount;    // Not set
    protected $pagecount;     // Not set

    #---------------------
    # Magic methods
    #---------------------		

    public function __construct(LISE $mod, &$params) {
        $this->_instance_name = $mod->GetName();

        $this->_params = $params;

        $this->joins = array();
        $this->where = array();
        $this->orderby = array();
        $this->qparams = array();
    }

    #---------------------
    # Abstract methods
    #---------------------	

    abstract protected function _query();

    #---------------------
    # Utility methods
    #---------------------	

    protected function GetDb() {
        return cmsms()->GetDb();
    }

    public function GetModuleInstanceName() {
        return $this->_instance_name;
    }

    public function GetModuleInstance() {
        return cmsms()->GetModuleInstance($this->_instance_name);
    }

    public function GetParams() {
        return $this->_params;
    }

    public function SetParam($key, $value) {
        $this->_params[$key] = $value;
    }

    public function GetParam($key) {
        return isset($this->_params[$key]) ? $this->_params[$key] : null;
    }

    #---------------------
    # Database methods
    #---------------------	

    public function AppendTo($var, $value) {
        $var = strtolower($var);
        $value = (string) $value;

        switch ($var) {

            case self::VARTYPE_JOINS:
                $this->joins[] = $value;
                break;

            case self::VARTYPE_WHERE:
                $this->where[] = $value;
                break;

            case self::VARTYPE_ORDERBY:
                $this->orderby[] = $value;
                break;

            case self::VARTYPE_QPARAMS:
                $this->qparams[] = $value;
                break;

            default:
                throw new \LISE\Exception('Attempt to set unidentified VARTYPE');
        }

        return true;
    }

    public function Execute($force_execute = false) {
        if (!isset($this->resultset) || $force_execute)
            $this->_query();

        return $this->resultset;
    }

    public function TotalCount() {
        if (isset($this->totalcount))
            return $this->totalcount;

        return null;
    }

    public function GetPageCount() {
        if (isset($this->pagecount))
            return $this->pagecount;

        return null;
    }

    public function GetPageLimit() {
        if (isset($this->_params['pagelimit']))
            return (int) $this->_params['pagelimit'];

        $config = \LISE\ConfigManager::GetConfigInstance(cmsms()->GetModuleInstance('LISE'));
        $page_limit = isset($config['global_LISEQuery_page_limit']) ? $config['global_LISEQuery_page_limit'] : 100000;
        $mod = $this->GetModuleInstance();

        $config = \LISE\ConfigManager::GetConfigInstance($this->GetModuleInstance());
        $page_limit = isset($config['LISEQuery_page_limit']) ? $config['LISEQuery_page_limit'] : $page_limit;
        return $page_limit;
    }

    public function GetPageNumber() {
        if (isset($this->_params['pagenumber']))
            return (int) $this->_params['pagenumber'];

        if (isset($this->_params['page']))
            return (int) $this->_params['page'];

        return 1;
    }

}

// end of class