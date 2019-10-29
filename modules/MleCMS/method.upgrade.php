<?php
die();
if (!isset($gCms))
    exit;
$current_version = $oldversion;
$db = cmsms()->GetDb();

$taboptarray = array('mysql' => 'TYPE=MyISAM');
$dict = NewDataDictionary($db);

switch ($current_version) {
    case "1.0":
        $dict = NewDataDictionary($db);
        $sqlarray = $dict->AddColumnSQL(cms_db_prefix() . "module_mlecms_config", "flag C(60)");
        $dict->ExecuteSQLArray($sqlarray);
        $current_version = "1.1";
    case "1.1":
        $this->AddEventHandler('Core', 'ContentPostRender', false);
        $this->AddEventHandler('Search', 'SearchCompleted', false);
        $this->SetPreference('mle_auto_redirect', 0);
        $this->SetPreference('mle_id', '{MleCMS action="get_root_alias"}');
        $current_version = "1.2";
    case "1.2":
    case "1.3":
        $this->CreateEvent('LangEdited');
        $this->CreateEvent('BlockEdited');
        $current_version = "1.4";
    case "1.4":
        $this->AddEventHandler('Core', 'ContentPostRender', false);
        $this->AddEventHandler('Search', 'SearchCompleted', false);
        $this->SetPreference('mle_search_restriction', 1);
        $current_version = "1.5";
    case "1.5":
        $this->SetPreference('mle_separator', '/');

        $fn = cms_join_path(dirname(__FILE__), 'templates', 'orig_mle_template.tpl');
        if (file_exists($fn)) {
            $template = file_get_contents($fn);
            $this->SetPreference('default_mle_template', $template);
            $this->SetTemplate('mle_templateFlags', $this->GetTemplate('mle_template'));
            $this->SetPreference('current_mle_template', 'Flags');
        }
        $fn = cms_join_path(dirname(__FILE__), 'templates', 'orig_mle_template_dropdown.tpl');
        if (file_exists($fn)) {
            $template = file_get_contents($fn);
            $this->SetTemplate('mle_templateDropdown', $template);
        }

        $fn = cms_join_path(dirname(__FILE__), 'templates', 'orig_mle_template_separator.tpl');
        if (file_exists($fn)) {
            $template = file_get_contents($fn);
            $this->SetTemplate('mle_templateSeparator', $template);
        }
        $this->DeleteTemplate('mle_template');
        $current_ve2rsion = "1.6";
    case "1.6":
        $this->CreatePermission('manage translator_mle', 'manage translator_mle');
        $current_version = "1.7";
    case "1.8":
        $current_version = "1.9";
    case "1.9":
        // delete any dependencies
        $query = "DELETE FROM " . cms_db_prefix() . "module_deps WHERE child_module = ? AND parent_module = ?";
        $db->Execute($query, array($this->GetName(), 'ContentCache'));
        $contentops = cmsms()->GetContentOperations();
        $current_version = "1.9";
    case "1.10.1":
        $sqlarray = $dict->AddColumnSQL(cms_db_prefix() . 'module_mlecms_config', 'extra C(60)');
        $dict->ExecuteSQLArray($sqlarray);
    case "1.10.2":
        $sqlarray = $dict->AddColumnSQL(cms_db_prefix() . 'module_mlecms_config', 'direction C(10)');
        $dict->ExecuteSQLArray($sqlarray);
        $current_version = "1.10.3";
    case "1.10.3":
        if ($this->GetPreference('mle_id') == '{MleCMS action="get_root_alias"}' || $this->GetPreference('mle_id') == "{MleCMS action='get_root_alias'}")
            $this->SetPreference('mle_id', '{get_root_alias}');
    case "1.10.4":
        $sqlarray = $dict->AddColumnSQL(cms_db_prefix() . 'module_mlecms_config', 'setlocale C(100)');
        $dict->ExecuteSQLArray($sqlarray);
    case "1.10.5":
        $query = "UPDATE " . cms_db_prefix() . "module_mlecms_config SET setlocale = ''";
        $db->Execute($query, array());

        $current_version = "1.10.6";
    case "1.11":
        $langs = $this->getLangs();
        $langs[] = array('name' => 'keys', 'locale' => 'keys');
        Translation_old::setLanguages($langs);
        $items = Translation_old::getContentTable();
        if ($items) {
            foreach ($items["xml"]["keys"]["items"] as $item) {
                $key = $item;
                foreach ($items["langs"] as $lang) {
                    if (!isset($lang["id"]))
                        continue;
                    $locale = $lang["locale"];
                    if (isset($items["xml"][$locale]["items"][$key]))
                        Translation::add_to_translations($key, $locale, $items["xml"][$locale]["items"][$key]);
                }
            }
            Translation::save();
        }
    case "1.11.4":
        if ($this->GetPreference('translations')) {
            Translation::set_translation_data(unserialize($this->GetPreference('translations')));
            Translation::save();
        }
    case "1.11.5":
        $sqlarray = $dict->AddColumnSQL(cms_db_prefix() . 'module_mlecms_config', 'canonical C(60)');
        $dict->ExecuteSQLArray($sqlarray);

        $current_version = "1.12";
}

// put mention into the admin log
$this->Audit(0, $this->Lang('friendlyname'), $this->Lang('upgraded', $this->GetVersion()));
?>
