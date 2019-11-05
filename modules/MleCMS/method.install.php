<?php
die();
if (!isset($gCms))
    exit;

$taboptarray = array('mysql' => 'TYPE=MyISAM');

// Creates the otazky table
$flds = "
	id I KEY AUTO,
	name C(64),
	alias C(255),
	locale C(10),
	setlocale C(100),
	direction C(10),
	flag C(60),
	extra C(60),
	canonical C(60),
	sort I,
	active I4,
	modified_date " . CMS_ADODB_DT . ",
	created_date " . CMS_ADODB_DT . "
	";
$dict = NewDataDictionary($db);
$sqlarray = $dict->CreateTableSQL(cms_db_prefix() . "module_mlecms_config", $flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

// permissions
$this->CreatePermission('manage translator_mle', 'manage translator_mle');
$this->CreatePermission('manage mle_cms', 'manage mle_cms');
$this->CreatePermission('manage ' . MLE_SNIPPET . 'mle', 'manage ' . MLE_SNIPPET . 'mle');
$this->CreatePermission('manage ' . MLE_BLOCK . 'mle', 'manage ' . MLE_BLOCK . 'mle');

// preference
$this->SetPreference('mle_hierarchy_switch', 1);
$this->SetPreference('mle_auto_redirect', 0);

$fn = cms_join_path(dirname(__FILE__), 'templates', 'orig_mle_template.tpl');
if (file_exists($fn)) {
    $template = file_get_contents($fn);
    $this->SetPreference('default_mle_template', $template);
    $this->SetTemplate('mle_templateFlags', $template);
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

$this->RegisterEvents();

$this->CreateEvent('LangEdited');
$this->CreateEvent('BlockEdited');

//+Lee
$query = 'ALTER TABLE '.cms_db_prefix().'modules ADD sync INT NULL';
$db->Execute($query);

// put mention into the admin log
$this->Audit(0, $this->Lang('friendlyname'), $this->Lang('installed', $this->GetVersion()));
