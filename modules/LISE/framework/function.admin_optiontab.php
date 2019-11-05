<?php

if (!defined('CMS_VERSION'))
    exit;

#---------------------
# Init
#---------------------

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_option'))
    return;

$systemdefs = $this->GetFieldDefs();
$fielddefs = array(
    $this->ModLang('desc') => 'desc',
    $this->ModLang('alias') => 'alias',
    $this->ModLang('create_time') => 'create_time',
    $this->ModLang('modified_time') => 'modified_time',
    $this->ModLang('start_time') => 'start_time',
    $this->ModLang('end_time') => 'end_time'
);

foreach ($systemdefs as $onedef) {
    $fielddefs[$onedef->GetName()] = $onedef->GetAlias();
}

// pagination options
$items_per_page = array(
    10 => 10,
    20 => 20,
    50 => 50,
    100 => 100,
    200 => 200,
    300 => 300,
    400 => 400,
    500 => 500,
    1000 => 1000
);

// module admin section options
$admin_sections = array(
    lang('main') => 'main',
    lang('content') => 'content',
    lang('lise') => 'lise',
    lang('layout') => 'layout',
    lang('usersgroups') => 'usersgroups',
    lang('extensions') => 'extensions',
    lang('siteadmin') => 'siteadmin',
    lang('myprefs') => 'myprefs',
    lang('ecommerce') => 'ecommerce'
);

// sortorder options
$sortorder = array(
    $this->ModLang('ascending') => 'ASC',
    $this->ModLang('descending') => 'DESC'
);

$content_ops = cmsms()->GetContentOperations();

$display_inline = $this->GetPreference('display_inline', 0);
$subcategory = $this->GetPreference('subcategory', 0);
$display_create_date = $this->GetPreference('display_create_date', 0);
$display_sortable = $this->GetPreference('display_sortable', 0);
$allow_item_sametitle = $this->GetPreference('allow_item_sametitle', 0);
$allow_category_sametitle = $this->GetPreference('allow_category_sametitle', 0);
$reindex_search = $this->GetPreference('reindex_search', 0);
$auto_upgrade = $this->GetPreference('auto_upgrade', 0);
$sent_email = $this->GetPreference('sent_email', 0);
$time_control = $this->GetPreference('time_control', 0);
$extra1_enabled = $this->GetPreference('extra1_enabled', 0);
$extra1_enabled_wysiwyg = $this->GetPreference('extra1_enabled_wysiwyg', 0);
$extra2_enabled = $this->GetPreference('extra2_enabled', 0);
$extra2_enabled_wysiwyg = $this->GetPreference('extra2_enabled_wysiwyg', 0);
$extra3_enabled = $this->GetPreference('extra3_enabled', 0);
$extra3_enabled_wysiwyg = $this->GetPreference('extra3_enabled_wysiwyg', 0);

$maps = $this->GetPreference('maps', 0);
$category_enabled_wysiwyg = $this->GetPreference('category_enabled_wysiwyg', 0);
$category_noparent = $this->GetPreference('category_noparent', 0);

#---------------------
# Smarty processing
#---------------------
$smarty->assign('startform', $this->CreateFormStart($id, 'admin_editoption', $returnid));
$smarty->assign('endform', $this->CreateFormEnd());
$smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));

// Module Options
$smarty->assign('input_friendlyname', $this->CreateInputText($id, 'friendlyname', $this->GetPreference('friendlyname' . '_' . \CmsNlsOperations::get_current_language(), $this->GetPreference('friendlyname', '')), 50));
$smarty->assign('input_moddescription', $this->CreateTextArea(false, $id, $this->GetPreference('moddescription', $this->ModLang('moddescription')), 'moddescription', 'pagesmalltextarea', '', '', '', '80', '3'));
$smarty->assign('input_adminsection', $this->CreateInputDropdown($id, 'adminsection', $admin_sections, -1, $this->GetPreference('adminsection', 'content')));

// Module defaults
$smarty->assign('input_detailpage', $content_ops->CreateHierarchyDropdown('', $this->GetPreference('detailpage'), $id . 'detailpage'));
$smarty->assign('input_summarypage', $content_ops->CreateHierarchyDropdown('', $this->GetPreference('summarypage'), $id . 'summarypage'));

// Item options
$smarty->assign('input_item_title', $this->CreateInputText($id, 'item_title', $this->GetPreference('item_title', ''), 50));
$smarty->assign('input_item_singular', $this->CreateInputText($id, 'item_singular', $this->GetPreference('item_singular', ''), 50));
$smarty->assign('input_item_plural', $this->CreateInputText($id, 'item_plural', $this->GetPreference('item_plural', ''), 50));
$smarty->assign('input_items_per_page', $this->CreateInputDropdown($id, 'items_per_page', $items_per_page, -1, $this->GetPreference('items_per_page', 20)));
$smarty->assign('input_sortorder', $this->CreateInputDropdown($id, 'items_sortorder', $sortorder, -1, $this->GetPreference('sortorder', 'ASC')));
$smarty->assign('input_create_date', $this->CreateInputCheckbox($id, 'display_create_date', 1, $display_create_date, ($display_create_date ? 'checked="checked"' : '')));
$smarty->assign('input_sortable', $this->CreateInputCheckbox($id, 'display_sortable', 1, $display_sortable, ($display_sortable ? 'checked="checked"' : '')));
$smarty->assign('input_item_sametitle', $this->CreateInputCheckbox($id, 'allow_item_sametitle', 1, $allow_item_sametitle, ($allow_item_sametitle ? 'checked="checked"' : '')));
$smarty->assign('input_category_sametitle', $this->CreateInputCheckbox($id, 'allow_category_sametitle', 1, $allow_category_sametitle, ($allow_category_sametitle ? 'checked="checked"' : '')));
$smarty->assign('input_auto_upgrade', $this->CreateInputCheckbox($id, 'auto_upgrade', 1, $auto_upgrade, ($auto_upgrade ? 'checked="checked"' : '')));
$smarty->assign('input_sent_email', $this->CreateInputCheckbox($id, 'sent_email', 1, $sent_email, ($sent_email ? 'checked="checked"' : '')));
$smarty->assign('input_maps', $this->CreateInputCheckbox($id, 'maps', 1, $maps, ($maps ? 'checked="checked"' : '')));
$smarty->assign('input_category_enabled_wysiwyg', $this->CreateInputCheckbox($id, 'category_enabled_wysiwyg', 1, $category_enabled_wysiwyg, ($category_enabled_wysiwyg ? 'checked="checked"' : '')));
$smarty->assign('input_category_noparent', $this->CreateInputCheckbox($id, 'category_noparent', 1, $category_noparent, ($category_noparent ? 'checked="checked"' : '')));
$smarty->assign('input_time_control', $this->CreateInputCheckbox($id, 'time_control', 1, $time_control, ($time_control ? 'checked="checked"' : '')));
$smarty->assign('input_extra1_enabled', $this->CreateInputCheckbox($id, 'extra1_enabled', 1, $extra1_enabled, ($extra1_enabled ? 'checked="checked"' : '')));
$smarty->assign('input_extra1_enabled_wysiwyg', $this->CreateInputCheckbox($id, 'extra1_enabled_wysiwyg', 1, $extra1_enabled_wysiwyg, ($extra1_enabled_wysiwyg ? 'checked="checked"' : '')));
$smarty->assign('input_extra2_enabled', $this->CreateInputCheckbox($id, 'extra2_enabled', 1, $extra2_enabled, ($extra2_enabled ? 'checked="checked"' : '')));
$smarty->assign('input_extra2_enabled_wysiwyg', $this->CreateInputCheckbox($id, 'extra2_enabled_wysiwyg', 1, $extra2_enabled_wysiwyg, ($extra2_enabled_wysiwyg ? 'checked="checked"' : '')));
$smarty->assign('input_extra3_enabled', $this->CreateInputCheckbox($id, 'extra3_enabled', 1, $extra3_enabled, ($extra3_enabled ? 'checked="checked"' : '')));
$smarty->assign('input_extra3_enabled_wysiwyg', $this->CreateInputCheckbox($id, 'extra3_enabled_wysiwyg', 1, $extra3_enabled_wysiwyg, ($extra3_enabled_wysiwyg ? 'checked="checked"' : '')));
$smarty->assign('input_item_cols', $this->CreateInputSelectList($id, 'item_cols[]', $fielddefs, explode(',', $this->GetPreference('item_cols', '')), 10));

// URL options
$smarty->assign('input_url_prefix', $this->CreateInputText($id, 'url_prefix', $this->GetPreference('url_prefix', ''), 50));
$smarty->assign('input_url_template', $this->CreateInputText($id, 'urltemplate', $this->GetPreference('urltemplate', '{$prefix}/{$item_title}'), 50));
$smarty->assign('input_display_inline', $this->CreateInputCheckbox($id, 'display_inline', 1, $display_inline, ($display_inline ? 'checked="checked"' : '')));
$smarty->assign('input_subcategory', $this->CreateInputCheckbox($id, 'subcategory', 1, $subcategory, ($subcategory ? 'checked="checked"' : '')));

// Cross Module options
$smarty->assign('input_reindex_search', $this->CreateInputCheckbox($id, 'reindex_search', 1, $reindex_search, ($reindex_search ? 'checked="checked"' : '')));

echo $this->ModProcessTemplate('optiontab.tpl');
