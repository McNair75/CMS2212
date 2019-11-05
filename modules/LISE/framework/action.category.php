<?php

if (!defined('CMS_VERSION'))
    exit;

#Start MLE
global $hls, $hl, $mleblock;
$thisURL = $_SERVER['SCRIPT_NAME'] . '?';
foreach ($_GET as $key => $val)
    if ('hl' != $key)
        $thisURL .= $key . '=' . $val . '&amp;';
if (isset($hls)) {
    $langList = ' &nbsp; &nbsp; ';
    foreach ($hls as $key => $mle) {
        $langList .= ($hl == $key) ? $mle['flag'] . ' ' : '<a href="' . $thisURL . 'hl=' . $key . '">' . $mle['flag'] . '</a> ';
    }
}
$smarty->assign('langList', $langList);
#End MLE
#---------------------
# Init objects
#---------------------

$query_object = $this->GetCategoryQuery($params);

Events::SendEvent($this->GetName(), 'PreRenderAction', array('action_name' => $name, 'query_object' => &$query_object));

#---------------------
# Init
#---------------------
//which template to use
$template = 'category_' . $this->GetPreference($this->_GetModuleAlias() . '_default_category_template');
if (isset($params['template_category'])) {
    $template = 'category_' . $params['template_category'];
} elseif (isset($params['categorytemplate'])) {
    $template = 'category_' . $params['categorytemplate'];
}

$debug = (isset($params['debug']) ? true : false);
$inline = $this->GetPreference('display_inline', 0);

#---------------------
# Init items
#---------------------
if ($params['popular']) {
    $query_object->AppendTo(LISEQuery::VARTYPE_WHERE, 'B.popular = 1');
}
if ($params['alias']) {
    $query_object->AppendTo(LISEQuery::VARTYPE_WHERE, 'B.category_alias = \'' . $params['alias'] . '\'');
}
if ($params['hasitem']) {
    $query_object->AppendTo(LISEQuery::VARTYPE_WHERE, '(SELECT COUNT(*) > 0 AS my_bool FROM cms_module_liselocations_item_categories WHERE category_id = B.category_id) = 1');
}

$query_object->AppendTo(LISEQuery::VARTYPE_WHERE, 'B.active = 1 AND B.parent_id = -1');
$dbr = $query_object->Execute(true);

$items = array();
while ($dbr && !$dbr->EOF) {

    $items[$dbr->fields['category_id']] = $dbr->fields['category_id'];
    $dbr->MoveNext();
}

if ($dbr)
    $dbr->Close();

#---------------------
# Build hierarchy
#---------------------
// Set collapse true/false
$showparents = array();
if (isset($params['collapse'])) {

    // Grap current path
    $current_path = cms_utils::get_app_data('lise_id_hierarchy');

    if ($current_path) {

        $splitted_path = explode('.', $current_path);
        foreach ($splitted_path as $path) {

            $showparents[] = $path;
        }
    }
}

$origdepth = 1;
$prevdepth = 1;
$count = 0;
$nodelist = array();


LISEHierarchyManager::GetChildNodes($this, $id, $returnid, $items, $nodelist, $count, $prevdepth, $origdepth, $params, $showparents, $mleblock);

#---------------------
# Smarty processing
#---------------------

$smarty->assign('categories', $nodelist);

echo $this->ProcessTemplateFromDatabase($template);