<?php

if (!defined('CMS_VERSION'))
    exit;

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_option'))
    return;

#---------------------
# Error processing
#---------------------

$errors = array();

if (count($errors)) {

    $params = array('errors' => $errors[0], 'active_tab' => 'mapstab');
    $this->Redirect($id, 'defaultadmin', '', $params);
}

#---------------------
# Set new values
#---------------------

$this->SetPreference('latitude', $params['latitude']);
$this->SetPreference('longitude', $params['longitude']);
$this->SetPreference('search_text', $params['search_text']);
$this->SetPreference('formatted_address', $params['formatted_address']);

$params = array('message' => 'changessaved', 'active_tab' => 'mapstab');
$this->Redirect($id, 'defaultadmin', '', $params);