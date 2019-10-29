<?php

class LISEInstanceExporter {
    #---------------------
    # Constants
    #---------------------

    const MOD_PREFIX = 'LISE';
    #internal version
    const XML_VERSION = '1.1';

    #---------------------
    # Attributes
    #---------------------

    private $modname;
    private $_data;
    private $_prefs;
    private $_template_prefs;
    private $_xml;
    private static $_invalid = array('.', '..');

    #---------------------
    # Magic methods
    #---------------------		

    public function __construct($modname = NULL) {
        if (empty($modname))
            throw new \LISE\Exception('Error in ' . __CLASS__ . ' constructor: invalid parameter!');
        //if( empty($modname) ) throw new Exception('Error in ' . __CLASS__ . ' constructor: invalid parameter!');

        if (!startswith($modname, self::MOD_PREFIX)) {
            $modname = self::MOD_PREFIX . $modname;
        }

        $this->modname = $modname;
    }

    #---------------------
    # Runner
    #---------------------

    public function Run() {
        $this->CopyFromDataBase();
        $this->CopyFromPreferences();
        $this->CopyFromTemplates();
        $this->_build_xml();

        // and send the file
        ob_end_clean();
        header('Content-Description: File Transfer');
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename=' . $this->modname . '.LISE.xml');
        echo $this->_xml;
        exit();
    }

    /**
     * TODO: error checks
     */
    private final function CopyFromDataBase() {
        $mod_prefix = cms_db_prefix() . 'module_' . strtolower($this->modname) . '_';

        $table_list = array(
            'item',
            'category',
            'item_categories',
            'fielddef',
            'fielddef_opts',
            'fieldval'
        );
        $db = cmsms()->GetDb();

        $data = array();
        foreach ($table_list as $one) {
            $nt = $mod_prefix . $one;
            $q = 'SELECT * FROM ' . $nt;
            $data[$one] = $db->GetArray($q);
        }

        $this->_data['db'] = $data;
    }

    private final function CopyFromPreferences() {

        $prefs = array(
            'friendlyname',
            'adminsection',
            'moddescription',
            'item_title',
            'sortorder',
            'item_singular',
            'item_plural',
            'display_create_date',
            'item_cols',
            'items_per_page',
            'url_prefix',
            'display_inline',
            'subcategory',
            'urltemplate',
            'detailpage',
            'summarypage',
            'reindex_search'
        );

        $template_prefs = array(
            '_default_summary_template',
            '_default_detail_template',
            '_default_search_template',
            '_default_category_template',
            '_default_archive_template'
        );

        $mod = ModuleOperations::get_instance()->get_module_instance($this->modname, NULL, TRUE);

        foreach ($prefs as $pref) {
            $this->_data['prefs'][$pref] = $mod->GetPreference($pref, '');
        }

        foreach ($template_prefs as $pref) {
            $this->_data['template_prefs'][$pref] = $mod->GetPreference($this->modname . $pref, '');
        }
    }

    private final function CopyFromTemplates() {
        $mod = ModuleOperations::get_instance()->get_module_instance($this->modname, NULL, TRUE);

        $templates = $mod->ListTemplates();

        foreach ($templates as $one) {
            $this->_data['templates'][$one] = $mod->GetTemplate($one);
        }
    }

    /**
     * pretty simple xml builder, but prone to errors (I think)
     * Will be worked on depending on usability...
     */
    private final function _build_xml() {
        $mod = ModuleOperations::get_instance()->get_module_instance($this->modname, NULL, TRUE);

        $this->_xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $this->_xml .= '<LISEInstanceModule>' . "\n";
        $this->_xml .= ' <XMLVERSION>' . self::XML_VERSION . '</XMLVERSION>' . "\n";
        $this->_xml .= ' <ModuleName>' . $this->modname . '</ModuleName>' . "\n";
        $this->_xml .= ' <Version>' . $mod->GetVersion() . '</Version>' . "\n";
        $this->_xml .= ' <ModuleFriendlyName><![CDATA[' . $mod->GetFriendlyName() . ']]></ModuleFriendlyName>' . "\n";
        $this->_xml .= ' <Data>' . base64_encode(serialize($this->_data)) . '</Data>' . "\n";
        $this->_xml .= '</LISEInstanceModule>' . "\n";
    }

}

// end of class