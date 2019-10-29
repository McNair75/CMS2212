<?php

if (!defined('CMS_VERSION')) {
    exit;
}

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_option')) {
    return;
}

//---------------------
// Error processing
//---------------------

$errors = array();

if (empty($params['url_prefix'])) {
    $errors[] = $this->ModLang('error_optionrequired', $this->ModLang('prompt_url_prefix'));
}

if (empty($params['friendlyname'])) {
    $errors[] = $this->ModLang('error_optionrequired', $this->ModLang('prompt_friendlyname'));
}

if (empty($params['item_title'])) {
    $errors[] = $this->ModLang('error_optionrequired', $this->ModLang('prompt_item_title'));
}

if (empty($params['item_singular'])) {
    $errors[] = $this->ModLang('error_optionrequired', $this->ModLang('prompt_item_singular'));
}

if (empty($params['item_plural'])) {
    $errors[] = $this->ModLang('error_optionrequired', $this->ModLang('prompt_item_plural'));
}

if (count($errors)) {
    $params = array('errors' => $errors[0], 'active_tab' => 'optiontab');
    $this->Redirect($id, 'defaultadmin', '', $params);
}

//---------------------
// Set new values
//---------------------

$this->SetPreference('friendlyname' . '_' . \CmsNlsOperations::get_current_language(), $params['friendlyname']);
$this->SetPreference('adminsection', $params['adminsection']);
$this->SetPreference('moddescription', $params['moddescription']);
$this->SetPreference('item_title', $params['item_title']);
$this->SetPreference('sortorder', $params['items_sortorder']);
$this->SetPreference('item_singular', $params['item_singular']);
$this->SetPreference('item_plural', $params['item_plural']);
$this->SetPreference('display_create_date', (isset($params['display_create_date']) ? 1 : 0));
$this->SetPreference('display_sortable', (isset($params['display_sortable']) ? 1 : 0));
$this->SetPreference('allow_item_sametitle', (isset($params['allow_item_sametitle']) ? 1 : 0));
$this->SetPreference('allow_category_sametitle', (isset($params['allow_category_sametitle']) ? 1 : 0));
$this->SetPreference('item_cols', ((isset($params['item_cols']) && is_array($params['item_cols'])) ? implode(',', $params['item_cols']) : ''));
$this->SetPreference('items_per_page', $params['items_per_page']);
$this->SetPreference('url_prefix', $params['url_prefix']);
$this->SetPreference('display_inline', (isset($params['display_inline']) ? 1 : 0));
$this->SetPreference('subcategory', (isset($params['subcategory']) ? 1 : 0));
$this->SetPreference('urltemplate', (isset($params['urltemplate']) ? $params['urltemplate'] : $this->GetPreference('urltemplate', '{$prefix}/{$item_title}')));

// Module defaults
$this->SetPreference('detailpage', $params['detailpage'] >= 0 ? $params['detailpage'] : null);
$this->SetPreference('summarypage', $params['summarypage'] >= 0 ? $params['summarypage'] : null);

// Cross Module options
$old_reindex_search = $this->GetPreference('reindex_search', 0);
$reindex_search = (isset($params['reindex_search']) ? 1 : 0);
$this->SetPreference('reindex_search', $reindex_search);

if ($reindex_search != $old_reindex_search && $params['_do_reindex']) {
    $Search = cms_utils::get_search_module();
    if ($reindex_search) {
        LISEItemOperations::reindex_search($Search, $this);
    } else {
        LISEItemOperations::remove_index_search($Search, $this);
    }
}

// Misc
$this->SetPreference('auto_upgrade', (isset($params['auto_upgrade']) ? 1 : 0));

if (isset($params['sent_email'])) {
    // Email templates
    $fn = cms_join_path(LISE_TEMPLATE_PATH, 'fe_email_default.tpl');
    if (file_exists($fn)) {
        $template = @file_get_contents($fn);
        $this->SetTemplate('email_default', $template);
        $this->SetPreference($this->_GetModuleAlias() . '_default_email_template', 'default');
    }
}
// Sent Email
$this->SetPreference('sent_email', (isset($params['sent_email']) ? 1 : 0));
$this->SetPreference('maps', (isset($params['maps']) ? 1 : 0));
$this->SetPreference('category_enabled_wysiwyg', (isset($params['category_enabled_wysiwyg']) ? 1 : 0));
$this->SetPreference('category_noparent', (isset($params['category_noparent']) ? 1 : 0));
$this->SetPreference('time_control', (isset($params['time_control']) ? 1 : 0));

$this->SetPreference('extra1_enabled', (isset($params['extra1_enabled']) ? 1 : 0));
$this->SetPreference('extra1_enabled_wysiwyg', (isset($params['extra1_enabled_wysiwyg']) ? 1 : 0));
$this->SetPreference('extra2_enabled', (isset($params['extra2_enabled']) ? 1 : 0));
$this->SetPreference('extra2_enabled_wysiwyg', (isset($params['extra2_enabled_wysiwyg']) ? 1 : 0));
$this->SetPreference('extra3_enabled', (isset($params['extra3_enabled']) ? 1 : 0));
$this->SetPreference('extra3_enabled_wysiwyg', (isset($params['extra3_enabled_wysiwyg']) ? 1 : 0));

//+Lee
$_table = 'cms_module_' . $this->_GetModuleAlias() . '_item';
if (isset($params['extra1_enabled_wysiwyg'])) {
    $q = 'ALTER TABLE ' . $_table . ' MODIFY `key1` text';
    $db->Execute($q);
}

if (isset($params['extra2_enabled_wysiwyg'])) {
    $q = 'ALTER TABLE ' . $_table . ' MODIFY `key2` text';
    $db->Execute($q);
}

if (isset($params['extra3_enabled_wysiwyg'])) {
    $q = 'ALTER TABLE ' . $_table . ' MODIFY `key3` text';
    $db->Execute($q);
}

//+Lee (enabled maps function)
$_table_fielddef = 'cms_module_' . $this->_GetModuleAlias() . '_fielddef';
if (!empty($this->GetPreference('search_text'))) {
    if (isset($params['maps']) && $params['maps'] == 1) {
        $data_inserted = [
            'Latitude',
            'Longitude',
            'Formatted Address',
        ];
        foreach ($data_inserted as $insert) {
            $q_select = 'SELECT fielddef_id FROM ' . $_table_fielddef . ' WHERE `name` = ?';
            $exists = $db->GetOne($q_select, array($insert));
            if (!$exists) {
                $q = 'INSERT INTO ' . $_table_fielddef . ' (`name`,`alias`,`type`,`required`) VALUES (?,?,?,0)';
                $db->Execute($q, array($insert, str_replace('-', '_', munge_string_to_url($insert)), 'TextInput'));
                //+Lee: Set Preference
                $_fielddef_id = 'SELECT fielddef_id FROM ' . $_table_fielddef . ' WHERE `name` = ?';
                $fielddef_id = $db->GetOne($q_select, array($insert));
                $this->SetPreference(str_replace('-', '_', munge_string_to_url($insert)), 'customfield[' . $fielddef_id . ']');
            }
        }
    }
    $this->SetPreference('search_text', 'title');
}

$params = array('message' => 'changessaved', 'active_tab' => 'optiontab');
$this->Redirect($id, 'defaultadmin', '', $params);
