<?php

if (!defined('CMS_VERSION'))
    exit;

#---------------------
# Set new values
#---------------------

$this->SetPreference('allow_autoscan', (isset($params['allow_autoscan']) ? 1 : 0));
$this->SetPreference('allow_autoinstall', (isset($params['allow_autoinstall']) ? 1 : 0));
$this->SetPreference('allow_autoupdate', (isset($params['allow_autoupdate']) ? 1 : 0));

$params = array('message' => 'changessaved', 'active_tab' => 'optionstab');
$this->Redirect($id, 'defaultadmin', $returnid, $params);
