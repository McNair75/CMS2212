<?php

if (!defined('CMS_VERSION')) {
    exit;
}

//---------------------
// Database tables
//---------------------

$dict = NewDataDictionary($db);
$taboptarray = array('mysql' => 'Engine=InnoDB');

// create item table
$fields = '
            item_id I KEY AUTO,
            url C(255),
            category_id I,
            "`title`" C(255),
            "`desc`" text,
            alias C(255),
            position I,
            active I4,
            create_time DT,
            modified_time DT,
            start_time D,
            end_time D,
            key1 text,
            key2 text,
            key3 text,
            owner I,
            domain_id I
';

$sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_item', $fields, $taboptarray);
$abc = $dict->ExecuteSQLArray($sqlarray);

// create category table
$fields = '
            category_id I KEY AUTO,
            category_name C(255),
            category_alias C(255),
            category_description X,
            parent_id I,
            position I,
            hierarchy C(255),
            hierarchy_path C(255),
            id_hierarchy C(255),
            icon C(100),
            picture C(100),
            active I4,
            popular I4,
            create_date DT,
            modified_date DT,
            key1 text,
            key2 text,
            key3 text
';

$sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_category', $fields, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

// create color table
//$fields = '
//            color_id I KEY AUTO,
//            color_name C(255),
//            color_code C(30),
//            color_alias C(255),
//            color_description X,
//            parent_id I,
//            position I,
//            hierarchy C(255),
//            hierarchy_path C(255),
//            id_hierarchy C(255),
//            icon C(100),
//            picture C(100),
//            active I4,
//            popular I4,
//            create_date DT,
//            modified_date DT,
//            key1 text,
//            key2 text,
//            key3 text
//';

//$sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_color', $fields, $taboptarray);
//$dict->ExecuteSQLArray($sqlarray); TODO

// create item_colors
//$fields = '
//    item_id I KEY NOT NULL DEFAULT \'-1\',
//    color_id I KEY NOT NULL DEFAULT \'-1\'
//';

//$sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_item_colors', $fields, $taboptarray);
//$dict->ExecuteSQLArray($sqlarray); TODO


// create item_categories
$fields = '
    item_id I KEY NOT NULL DEFAULT \'-1\',
    category_id I KEY NOT NULL DEFAULT \'-1\'
';

$sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_item_categories', $fields, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

// create fielddef table
$fields = '
            fielddef_id I KEY AUTO,
            name C(255),
            alias C(255),
            help C(255),
            type C(50),
            position I,
            required I,
            map I,
            template C(255),
            extra X
';

$sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fielddef', $fields, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

// create fielddef options table
$fields = '
            fielddef_id I KEY,
            name C(255) KEY,
            value X
';

$sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fielddef_opts', $fields, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

// create fieldval table
$fields = '
            item_id I KEY NOT null,
            fielddef_id I KEY NOT null,
            value_index I KEY NOT null,
            value X,
            data X
';

$sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fieldval', $fields, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

//---------------------
// Templates
//---------------------
// Summary templates
$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_summary_default.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('summary_default', $template);
    $this->SetPreference($this->_GetModuleAlias() . '_default_summary_template', 'default');
}

$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_summary_sample.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('summary_sample', $template);
    $this->SetPreference($this->_GetModuleAlias() . '_sample_summary_template', 'sample');
}

$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_summary_categorized.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('summary_categorized', $template);
}

$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_summary_accordion.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('summary_accordion', $template);
}

$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_summary_searchresults.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('summary_searchresults', $template);
}

// Detail templates
$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_detail_default.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('detail_default', $template);
    $this->SetPreference($this->_GetModuleAlias() . '_default_detail_template', 'default');
}

// Search templates
$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_search_default.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('search_default', $template);
    $this->SetPreference($this->_GetModuleAlias() . '_default_search_template', 'default');
}

// Email templates
$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_email_default.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('email_default', $template);
    $this->SetPreference($this->_GetModuleAlias() . '_default_email_template', 'default');
}

$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_search_filter.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('search_filter', $template);
}

$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_search_multiselect.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('search_multiselect', $template);
}

// Category templates
$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_category_default.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('category_default', $template);
    $this->SetPreference($this->_GetModuleAlias() . '_default_category_template', 'default');
}

$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_category_hierarchy.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('category_hierarchy', $template);
}

// Archive templates
$fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_archive_default.tpl');
if (file_exists($fn)) {
    $template = @file_get_contents($fn);
    $this->SetTemplate('archive_default', $template);
    $this->SetPreference($this->_GetModuleAlias() . '_default_archive_template', 'default');
}

//---------------------
// Preferences
//---------------------

$this->SetPreference('sortorder', 'ASC');
$this->SetPreference('adminsection', 'content');
$this->SetPreference('url_prefix', munge_string_to_url($this->GetName(), true));
$this->SetPreference('urltemplate', '{$prefix}/{$item_title}');
$this->SetPreference('friendlyname', $this->GetName());
$this->SetPreference('item_singular', utf8_encode(html_entity_decode($this->ModLang('item'))));
$this->SetPreference('item_plural', utf8_encode(html_entity_decode($this->ModLang('items'))));
$this->SetPreference('item_title', utf8_encode(html_entity_decode($this->ModLang('item_title'))));
//$this->SetPreference($this->_GetModuleAlias() . '_default_category', '1');
//---------------------
// Permissions
//---------------------

$this->CreatePermission($this->_GetModuleAlias() . '_modify_item', $this->_GetModuleAlias() . ': Modify Items');
$this->CreatePermission($this->_GetModuleAlias() . '_approve_item', $this->_GetModuleAlias() . ': Approve items');
$this->CreatePermission($this->_GetModuleAlias() . '_modify_all_item', $this->_GetModuleAlias() . ': Modify all items');
$this->CreatePermission($this->_GetModuleAlias() . '_remove_item', $this->_GetModuleAlias() . ': Remove items');
$this->CreatePermission($this->_GetModuleAlias() . '_modify_category', $this->_GetModuleAlias() . ': Modify Categories');
//$this->CreatePermission($this->_GetModuleAlias() . '_modify_color', $this->_GetModuleAlias() . ': Modify Colors'); //TODO
$this->CreatePermission($this->_GetModuleAlias() . '_modify_option', $this->_GetModuleAlias() . ': Modify Options');


//---------------------
// Events
//---------------------

$this->CreateEvent('PreItemSave');
$this->CreateEvent('PostItemSave');

$this->CreateEvent('PreItemDelete');
$this->CreateEvent('PostItemDelete');

$this->CreateEvent('PreItemLoad');
$this->CreateEvent('PostItemLoad');

$this->CreateEvent('PreRenderAction');
