<?php

if (!defined('CMS_VERSION'))
    exit;
$mod_description = !empty($_POST['mod_description']) ? $_POST['mod_description'] : 'LISE allows you to create lists that you can display throughout your website.';
$domain_id = isset($_POST['domain_id']) ? $_POST['domain_id'] : [];

$mod = cmsms()->GetModuleInstance($_POST['module_name']);
if (isset($_POST['submitfilter']) && is_object($mod)) {
    $domain_sections = serialize($domain_id);
    $mod->SetPreference('domainsection', $domain_sections);
    $mod->SetPreference('moddescription', $mod_description);
    cmsms()->clear_cached_files();
}

// all done
$this->Redirect($id, 'defaultadmin', $returnid, array('active_tab' => 'instancestab', 'message' => 'instance_moduleupgraded'));
