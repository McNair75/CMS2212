<?php

class LISEInstanceImporter {
    #---------------------
    # Constants
    #---------------------

    const MOD_PREFIX = 'LISE';
    const PLACEHOLDER = 'PLACE_HOLDER___';

    #---------------------
    # Attributes
    #---------------------

    private $src;
    private $dup;
    private $dst;
    private $modname;
    private $friendlyname;
    private $_data;
    private $_xml;
    private static $_invalid = array('.', '..');

    #---------------------
    # Magic methods
    #---------------------		

    public function __construct($xml = '') {
        if (empty($xml))
            throw new \LISE\Exception('Error in ' . __CLASS__ . ' constructor: invalid parameter!');
        $this->_xml = $xml;
        $this->_expand_xml();
        $this->src = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'duplicate';
        $this->dst = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . $this->modname;
    }

    #---------------------
    # Runner
    #---------------------

    public function Run() {
        # 1st copy the original
        $this->CopyRecursive($this->src, $this->dst);
        $this->Rename();
        $this->FixModulefile();

        # now we install new
        $modops = cmsms()->GetModuleOperations();
        $modops->InstallModule($this->modname);

        # expand the rest of the data
        $this->CopyDataBase();
        $this->CopyTemplates();
        $this->CopyPreferences();

        # now we have a new LISE Module
        return $this->modname;
    }

    #---------------------
    # File handling methods
    #---------------------		

    private final function CopyRecursive($src, $dst) {
        $dir = opendir($src);
        if (!$dir)
            throw new \LISE\Exception('Error: ' . __CLASS__ . ' - ' . __METHOD__ . ' -> failed to open directory: ' . $src, \LISE\Error::DISCRETE);
        if (!@mkdir($dst))
            throw new \LISE\Exception('Error: ' . __CLASS__ . ' - ' . __METHOD__ . ' -> failed to create directory: ' . $dst, \LISE\Error::DISCRETE);

        try {
            while (false !== ( $file = readdir($dir))) {
                # Skip stuff we never allow to copy
                if (in_array($file, self::$_invalid))
                    continue;

                if (is_dir($src . DIRECTORY_SEPARATOR . $file)) {
                    $this->CopyRecursive($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                } else {
                    if (!@copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file))
                        throw new \LISE\Exception('Error: ' . __CLASS__ . ' - ' . __METHOD__ . ' -> failed to copy: ' . $src . DIRECTORY_SEPARATOR . $file . ' to ' . $dst . DIRECTORY_SEPARATOR . $file, \LISE\Error::DISCRETE);
                }
            }
        } catch (Exception $e) {
            # make sure you close the dir and rethrow exception
            closedir($dir);
            throw $e;
        }
    }

    private final function Rename() {
        $old = cms_join_path($this->dst, self::PLACEHOLDER . '.php');
        $new = cms_join_path($this->dst, $this->modname . '.module.php');
        if (!@rename($old, $new))
            throw new \LISE\Exception('Error: ' . __CLASS__ . ' - ' . __METHOD__ . ' -> failed to rename: ' . $new, \LISE\Error::DISCRETE);
    }

    private final function FixModulefile() {
        $filename = $this->dst . DIRECTORY_SEPARATOR . $this->modname . '.module.php';

        // Replacements
        $_contents = file_get_contents($filename); // <- Throw exception on failure?
        $_contents = str_replace(self::PLACEHOLDER, $this->modname, $_contents);

        // Write file
        $fh = @fopen($filename, 'w');
        if (!$fh)
            throw new \LISE\Exception('Error: ' . __CLASS__ . ' - ' . __METHOD__ . ' -> failed to open ' . $filename, \LISE\Error::DISCRETE);

        try {
            if (!fwrite($fh, $_contents))
                throw new \LISE\Exception('Error: ' . __CLASS__ . ' - ' . __METHOD__ . ' -> failed to write to ' . $filename, \LISE\Error::DISCRETE);
        } catch (Exception $e) {
            # make sure you close the file and rethrow exception
            fclose($fh);
            throw $e;
        }
    }

    private final function CopyDataBase() {
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

        foreach ($table_list as $one) {
            $nt = $mod_prefix . $one;

            $rows = array();

            foreach ($this->_data['db'][$one] as $row) {
                $fields = implode(',', array_keys($row));
                $phs = implode(',', array_fill(0, count($row), '?'));

                $q = 'INSERT INTO ' . $nt . ' (' . $fields . ') VALUES (' . $phs . ')';
                $r = $db->Execute($q, array_values($row));

                if (!$r)
                    throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg(), \LISE\Error::DISCRETE_DB);
            }
        }
    }

    private final function CopyPreferences() {

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
            $mod->SetPreference($pref, $this->_data['prefs'][$pref]);
        }

        foreach ($template_prefs as $pref) {
            $mod->SetPreference($pref, $this->_data['template_prefs'][$pref]);
        }
    }

    private final function CopyTemplates() {

        $mod = ModuleOperations::get_instance()->get_module_instance($this->modname, NULL, TRUE);
        $templates = $mod->ListTemplates();

        foreach ($templates as $one)
            $mod->DeleteTemplate($one);

        foreach ($this->_data['templates'] as $k => $v) {
            $mod->SetTemplate($k, $v);
        }
    }

    private final function _expand_xml() {
        $tmp = simplexml_load_string($this->_xml);

        if ($tmp) {
            if (!isset($tmp->XMLVERSION) || $tmp->XMLVERSION > LISEInstanceExporter::XML_VERSION)
                throw new \LISE\Exception('Error in ' . __CLASS__ . ' invalid XML!');
            if (!isset($tmp->ModuleName))
                throw new \LISE\Exception('Error in ' . __CLASS__ . ' invalid XML!');
            if (!isset($tmp->Version))
                throw new \LISE\Exception('Error in ' . __CLASS__ . ' invalid XML!');
            if (!isset($tmp->ModuleFriendlyName))
                throw new \LISE\Exception('Error in ' . __CLASS__ . ' invalid XML!');
            if (!isset($tmp->Data))
                throw new \LISE\Exception('Error in ' . __CLASS__ . ' invalid XML!');

            $this->modname = (string) $tmp->ModuleName;
            $this->friendlyname = (string) $tmp->ModuleFriendlyName;
            $this->_data = unserialize(base64_decode($tmp->Data));
        }
        else {
            throw new \LISE\Exception('Error in ' . __CLASS__ . 'invalid XML!');
        }
    }

}

// end of class