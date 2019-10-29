<?php

if (!defined('CMS_VERSION'))
    exit;


if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_options'))
    return;

if (!isset($params['name'])) {
    die('missing parameter, this should not happen');
}

list($tpl_type, $tpl_name) = explode('_', $params['name'], 2);

// set default template preference
$this->SetPreference($this->_GetModuleAlias() . '_default_' . $tpl_type . '_template', $tpl_name);

// all done
$this->Redirect($id, 'defaultadmin', $returnid, array('active_tab' => 'templatetab'));