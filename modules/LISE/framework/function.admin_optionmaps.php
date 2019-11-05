<?php

if (!defined('CMS_VERSION'))
    exit;


#---------------------
# Init
#---------------------

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_option'))
    return;

$maps = $this->GetPreference('maps', 0);
$latitude = $this->GetPreference('latitude', '');
$longitude = $this->GetPreference('longitude', '');
$search_text = $this->GetPreference('search_text', '');
$formatted_address = $this->GetPreference('formatted_address', '');

#---------------------
# Smarty processing
#---------------------

$mod_maps = cge_utils::get_module('CGGoogleMaps2');
$smarty->assign('maps_key', $mod_maps->GetPreference('apikey'));

$smarty->assign('startform', $this->CreateFormStart($id, 'admin_editmaps', $returnid));
$smarty->assign('endform', $this->CreateFormEnd());
$smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));

$smarty->assign('input_search_text', $this->CreateInputText($id, 'search_text', $search_text, 50));
$smarty->assign('input_latitude', $this->CreateInputText($id, 'latitude', $latitude, 50));
$smarty->assign('input_longitude', $this->CreateInputText($id, 'longitude', $longitude, 50));
$smarty->assign('input_formatted_address', $this->CreateInputText($id, 'formatted_address', $formatted_address, 50));

echo $this->ModProcessTemplate('optionmaps.tpl');
