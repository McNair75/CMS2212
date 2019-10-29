<?php

if (!defined('CMS_VERSION'))
    exit;

#---------------------
# Check params
#---------------------
$template = 'search_' . $this->GetPreference($this->_GetModuleAlias() . '_default_search_template');

if (isset($params['template_search'])) {
    $template = 'search_' . $params['template_search'];
} elseif (isset($params['searchtemplate'])) {
    $template = 'search_' . $params['searchtemplate'];
}

if (isset($params['template_summary'])) {
    $summarytemplate_bypass = $params['template_summary'];
} elseif (isset($params['summarytemplate'])) {
    $summarytemplate_bypass = $params['summarytemplate'];
}

$summarypage = $this->GetPreference('summarypage', $returnid);

if (isset($params['summarypage'])) {
    if (is_numeric($params['summarypage'])) {
        $summarypage = $params['summarypage'];
    } else {
        if (!isset($hm))
            $hm = cmsms()->GetHierarchyManager();

        $summarypage_obj = $hm->sureGetNodeByAlias($params['summarypage']);

        if (is_object($summarypage_obj))
            $summarypage = $summarypage_obj->GetId();
    }
}

$debug = (isset($params['debug']) ? true : false);

#---------------------
# Grab template
#---------------------
$template = $this->GetTemplate($template);

#---------------------
# Init fielddefs
#---------------------
#Load fielddefs only if we have variable request on template
if (stripos($template, '$fielddefs')) {
    $filters = array();
    if (!empty($params['filter'])) {
        $filters = explode(',', $params['filter']);
    }

    $filters_order_by = array();
    if (!empty($params['filterorderby'])) {
        $filters_order_by = explode(',', $params['filterorderby']);
    }

    $fielddefs = $this->GetFieldDefs($filters);
    $i = 0;

    foreach ($fielddefs as $fielddef) {
        $orderby = isset($filters_order_by[$i]) ? $filters_order_by[$i++] : '';
        LISEFielddefOperations::LoadValuesForFieldDef($this, $fielddef, $orderby);
    }

    $smarty->assign('fielddefs', $fielddefs);
    $smarty->assign('filterprompt', $this->ModLang('filterprompt', $this->GetPreference('item_plural', '')));
}

#---------------------
# Smarty processing
#---------------------

$smarty->assign('formstart', $this->CreateFormStart($id, 'default', $summarypage, 'post', 'multipart/form-data', false, '', $params));
$smarty->assign('formend', $this->CreateFormEnd());
$smarty->assign('modulealias', $this->_GetModuleAlias()); // Deprecated

echo $this->ProcessTemplateFromData($template);

if ($debug)
    $smarty->display('string:<pre>{$fielddefs|@print_r}</pre>');
