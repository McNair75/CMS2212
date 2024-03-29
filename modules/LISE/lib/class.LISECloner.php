<?php

class LISECloner {
    #---------------------
    # Constants
    #---------------------

    const MOD_PREFIX = 'LISE';

    #---------------------
    # Attributes
    #---------------------

    private $src;
    private $dup;
    private $dst;
    private $modname;
    private $oldmodname;
    private static $_invalid = array('.', '..');

    #---------------------
    # Magic methods
    #---------------------		

    public function __construct($oldmodname = NULL, $newmodename = NULL) {
        if (empty($oldmodname))
            throw new \LISE\Exception('Error in ' . __CLASS__ . ' constructor: invalid parameter!');
        if (empty($newmodename))
            throw new \LISE\Exception('Error in ' . __CLASS__ . ' constructor: invalid parameter!');

        if (!startswith($oldmodname, self::MOD_PREFIX)) {
            $oldmodname = self::MOD_PREFIX . $oldmodname;
        }

        if (!startswith($newmodename, self::MOD_PREFIX)) {
            $newmodename = self::MOD_PREFIX . $newmodename;
        }

        $newmodename = self::_make_valid_modname($newmodename);
        $this->oldmodname = $oldmodname;
        $this->modname = $newmodename;
        $this->src = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . $this->oldmodname;
        $this->dst = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . $this->modname;
    }

    #---------------------
    # Set/Get
    #---------------------		

    public function SetModule($name) {
        $this->modname = $name;
    }

    public function SetDuplicate($name) {
        $this->dup = $name;
    }

    public function SetSource($name) {
        $this->src = $name;
    }

    public function SetDestination($name) {
        $this->dst = $name;
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
        # clone the rest of the data
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
        $dir = opendir($src); // <- Throw exception on failure?
        @mkdir($dst); // <- Throw exception on failure?

        while (false !== ( $file = readdir($dir))) {

            if (in_array($file, self::$_invalid))
                continue; // <- Skip stuff we never allow to copy

            if (is_dir($src . DIRECTORY_SEPARATOR . $file)) {
                $this->CopyRecursive($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
            } else {
                @copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file); // <- Throw exception on failure?
            }
        }

        closedir($dir);
    }

    private final function Rename() {
        $old = cms_join_path($this->dst, $this->oldmodname . '.module.php');
        $new = cms_join_path($this->dst, $this->modname . '.module.php');
        @rename($old, $new); // <- Throw exception on failure?
    }

    private final function FixModulefile() {
        $filename = $this->dst . DIRECTORY_SEPARATOR . $this->modname . '.module.php';

        // Replacements
        $_contents = file_get_contents($filename); // <- Throw exception on failure?
        $_contents = str_replace($this->oldmodname, $this->modname, $_contents);

        // Write file
        $fh = @fopen($filename, 'w');
        fwrite($fh, $_contents); // <- Throw exception on failure?
        fclose($fh);
    }

    private final function CopyDataBase() {
        $mod_prefix = cms_db_prefix() . 'module_' . strtolower($this->modname) . '_';
        $old_mod_prefix = cms_db_prefix() . 'module_' . strtolower($this->oldmodname) . '_';
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
            $ot = $old_mod_prefix . $one;

            $q = 'DROP TABLE IF EXISTS ' . $nt;
            $r = $db->Execute($q);

            if (!$r)
                throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg(), \LISE\Error::DISCRETE_DB);
            //if(!$r) throw new Exception( $db->ErrorMsg() ); 

            $q = 'CREATE TABLE IF NOT EXISTS ' . $nt . ' LIKE ' . $ot;
            $r = $db->Execute($q);

            if (!$r)
                throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg(), \LISE\Error::DISCRETE_DB);
            //if(!$r) throw new Exception( $db->ErrorMsg() );

            $q = 'INSERT ' . $nt . ' SELECT * FROM ' . $ot;
            $r = $db->Execute($q);

            if (!$r)
                throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg(), \LISE\Error::DISCRETE_DB);
            //if(!$r) throw new Exception( $db->ErrorMsg() );
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

        $newmod = ModuleOperations::get_instance()->get_module_instance($this->modname, NULL, TRUE);
        $oldmod = ModuleOperations::get_instance()->get_module_instance($this->oldmodname, NULL, TRUE);

        foreach ($prefs as $pref) {
            if ($pref == 'friendlyname') {
                $newmod->SetPreference($pref, $oldmod->GetPreference($pref, '') . '*');
                continue;
            }

            $newmod->SetPreference($pref, $oldmod->GetPreference($pref, ''));
        }

        foreach ($template_prefs as $pref) {
            $newmod->SetPreference($this->modname . $pref, $oldmod->GetPreference($this->modname . $pref, ''));
        }
    }

    private final function CopyTemplates() {

        $newmod = ModuleOperations::get_instance()->get_module_instance($this->modname, NULL, TRUE);
        $oldmod = ModuleOperations::get_instance()->get_module_instance($this->oldmodname, NULL, TRUE);


        $templates = $newmod->ListTemplates();

        foreach ($templates as $one)
            $newmod->DeleteTemplate($one);

        $templates = $oldmod->ListTemplates();

        foreach ($templates as $one) {
            $newmod->SetTemplate($one, $oldmod->GetTemplate($one));
        }
    }

    private final function UpgradeFromBase() {
        $sqlarray = $dict->AddColumnSQL(cms_db_prefix() . 'module_' . $this->modname . '_fielddef', 'template C(255)');
        $dict->ExecuteSQLArray($sqlarray);
    }

    /**
     * carefull: no error checking
     *
     * @param mixed $fn
     *
     * @return bool
     */
    private final function _is_valid_filename($fn) {
        $test = cms_join_path(
                dirname(dirname(dirname(__FILE__))), $fn
        );

        return !is_dir($test);
    }

    private final function _make_valid_modname($name) {
        $cnt = 0;
        $test = $name;
        while (!self::_is_valid_filename($test)) {
            $test = $name . ++$cnt;
        }

        return $test;
    }

}

// end of class