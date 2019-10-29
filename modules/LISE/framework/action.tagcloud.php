<?php

if (!defined('CMS_VERSION'))
    exit;

$fielddef_id = LISEFielddefOperations::TestExistanceByType($this, 'Tags');

if (!$fielddef_id)
    return;

$template = 'search_' . $this->GetPreference($this->_GetModuleAlias() . '_default_search_template');

if (isset($params['template_search'])) {

    $template = 'search_' . $params['template_search'];
} elseif (isset($params['searchtemplate'])) {

    $template = 'search_' . $params['searchtemplate'];
}

unset($params['template_search']);
unset($params['searchtemplate']);

$template = $this->GetTemplate($template);

$query = "SELECT * FROM " . cms_db_prefix() . "module_" . $this->_GetModuleAlias() . "_fielddef WHERE fielddef_id=?";
$dbr = $db->Execute($query, array($fielddef_id));
$row = $dbr->FetchRow();
$obj = LISEFielddefOperations::Load($this, $row);

$values = array();
if (is_a($obj, 'LISEFielddefBase')) {
    $values = LISEFielddefOperations::LoadValuesForFieldDef($this, $obj);
}

$tmp = array();

foreach ($values as $one) {
    $tmp = array_unique(array_merge($tmp, explode(',', $one)));
}

$tags = array();

foreach ($tmp as $one) {
    $params['tag'] = urlencode($one);
    $tags[$one] = $this->CreatePrettyLink($id, 'default', $returnid, '', $params, '', TRUE);
}

natcasesort($tags);
$smarty->assign('tags', $tags);

echo $this->ProcessTemplateFromData($template);