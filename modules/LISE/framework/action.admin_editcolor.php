<?php

if (!defined('CMS_VERSION')) {
    exit;
}

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_color')) {
    return;
}

//---------------------
// Check params
//---------------------
//Start MLE
global $hls, $hl, $mleblock;
$thisURL = $_SERVER['SCRIPT_NAME'] . '?';
foreach ($_GET as $key => $val) {
    if ('hl' != $key) {
        $thisURL .= $key . '=' . $val . '&amp;';
    }
}
if (isset($hls)) {
    $langList = ' &nbsp; &nbsp; ';
    foreach ($hls as $key => $mle) {
        $langList .= ($hl == $key) ? $mle['flag'] . ' ' : '<a href="' . $thisURL . 'hl=' . $key . '">' . $mle['flag'] . '</a> ';
    }
}
$smarty->assign('langList', $langList);
//End MLE

if (isset($params['cancel'])) {
    $params = array('active_tab' => 'colorlisttab');
    $this->Redirect($id, 'defaultadmin', $returnid, $params);
}

//---------------------
// Init params
//---------------------

$color_id = lise_utils::init_var('color_id', $params, -1);
$name = lise_utils::init_var('name', $params, '');
$code = lise_utils::init_var('code', $params, '');
$alias = lise_utils::init_var('alias', $params, '');
$description = lise_utils::init_var('description', $params, '');
$parent_id = lise_utils::init_var('parent_id', $params, -1);
$active = 1;

$icon_upload = lise_utils::init_var('icon_upload', $params, '');
$picture_upload = lise_utils::init_var('picture_upload', $params, '');

$color_noparent = $this->GetPreference('color_noparent', 0);
//---------------------
// Init Item
//---------------------

$obj = $this->LoadColorListByIdentifier('color_id', $color_id, $mleblock);
//---------------------
// Submit
//---------------------

if (isset($params['submit']) || isset($params['apply']) || isset($params['save_create'])) {
    $destdir = cms_join_path($config['image_uploads_path'], $this->GetName());

    $errors = array();

    if (!is_dir($destdir)) {
        cge_dir::mkdirr($destdir);
    }

    // Get new position
    $query_cid = 'SELECT max(color_id) + 1 FROM ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_color';
    $new_position = $db->GetOne($query_cid);
    $cid = ($color_id == -1) ? $new_position : $color_id;

    $handler = cge_setup::get_uploader($id, $destdir);
    $handler->set_allow_overwrite(true);
    $res = $handler->handle_upload('icon_upload', 'i_' . substr(md5('color' . $cid), 0, 10), '');
    $err = $handler->get_error();

    if ($res === false) {
        $icon_upload = $obj->icon;
    } else {
        $icon_upload = 'images/' . $this->GetName() . '/_color/' . $res;
    }

    $handler = cge_setup::get_uploader($id, $destdir);
    $handler->set_allow_overwrite(true);
    $res_picture = $handler->handle_upload('picture_upload', 'p_' . substr(md5('color' . $cid), 0, 10), '');

    if ($res_picture === false) {
        $picture_upload = $obj->picture;
    } else {
        $picture_upload = 'images/' . $this->GetName() . '/_color/' . $res_picture;
    }

    // handle image delete first
    if (isset($params['deleteimg'])) {
        $srcname = cms_join_path($config['uploads_path'], $params['deleteimg']);
        @unlink($srcname);
        $icon_upload = '';
    }

    // handle image delete first
    if (isset($params['picture_deleteimg'])) {
        $srcname = cms_join_path($config['uploads_path'], $params['picture_deleteimg']);
        @unlink($srcname);
        $picture_upload = '';
    }

    // check color name
    if ($name == '') {
        $errors[] = $this->ModLang('color_name_empty');
    }

    // check alias
    /*    if (!lise_utils::is_valid_alias($alias) && !empty($alias)) {
      $errors[] = $this->ModLang('alias_invalid');
      } */

    // Check for duplicate
    $parms = array();
    $query = 'SELECT * FROM ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_color WHERE color_alias = ?';
    $parms[] = $alias;

    if ($color_id > 0) {
        $query .= ' AND color_id != ?';
        $parms[] = $color_id;
    }

    $exists = $db->GetOne($query, $parms);

    if ($exists) {
        $errors[] = $this->ModLang('color_alias_exists');
    }

    // title and required fields have values, let's continue
    if (empty($errors)) {
        $obj->name = $name;
        $obj->code = $code;
        $obj->alias = $alias;
        $obj->description = $description;
        $obj->parent_id = $parent_id;
        $obj->icon = $icon_upload;
        $obj->picture = $picture_upload;
        $obj->active = isset($params['active']) ? 1 : 0;

        $this->SaveColorList($obj, $mleblock);

        // if apply and ajax
        if (isset($params['apply']) && isset($params['ajax'])) {
            $response = '<EditItem>';
            $response .= '<Response>Success</Response>';
            $response .= '<Details><![CDATA[' . $this->ModLang('changessaved') . ']]></Details>';
            $response .= '</EditItem>';
            echo $response;

            return;
        }

        // if save and create new
        if (isset($params['save_create'])) {
            $this->Redirect($id, 'admin_editcolor', $returnid, array(
                'message' => 'savecreate_message',
            ));
        }

        // show saved message
        if (isset($params['submit'])) {
            $this->Redirect($id, 'defaultadmin', $returnid, array(
                'active_tab' => 'colorlisttab',
                'message' => 'changessaved',
            ));
        } else {
            echo $this->ShowMessage($this->ModLang('changessaved'));
        }
    } // end error check
} // end submit or apply
elseif ($obj->color_id > 0) {

    $icon_upload = $obj->icon;
    $picture_upload = $obj->picture;
    $color_id = $obj->color_id;
    $name = $obj->name;
    $code = $obj->code;
    $alias = $obj->alias;
    $description = $obj->description;
    $parent_id = $obj->parent_id;
    $active = $obj->active;

    $href = cms_join_path($config['uploads_url'], $obj->picture);
    $params['src'] = $href;
    $params['filter_croptofit'] = '48,48';
    $params['notag'] = true;
    $params['noembed'] = true;
    $params['quality'] = '100%';
    $output = cgsi_utils::process_image($params);
    $url = $output['output'];
    $url_photo = '<a href="' . $href . '" class="cbox thumb"><img src="' . $url . '" width="48" height="48" /></a>';
    $smarty->assign('url_photo', $url_photo);
}

//---------------------
// Message control
//---------------------
// display errors if there are any
if (!empty($errors)) {
    if (isset($params['apply']) && isset($params['ajax'])) {
        $response = '<EditItem>';
        $response .= '<Response>Error</Response>';
        $response .= '<Details><![CDATA[';
        foreach ($errors as $error) {
            $response .= '<li>' . $error . '</li>';
        }
        $response .= ']]></Details>';
        $response .= '</EditItem>';
        echo $response;

        return;
    } else {
        echo $this->ShowErrors($errors);
    }
}

if (isset($params['message']) && empty($errors)) {
    echo $this->ShowMessage($this->ModLang('changessaved_create'));
}

//---------------------
// Smarty processing
//---------------------

$desc_turn = $this->GetPreference('color_enabled_wysiwyg');

$smarty->assign('colorlistObject', $obj);

$smarty->assign('backlink', $this->CreateBackLink('colorlisttab'));
$smarty->assign('title', (isset($color_id) ? $this->ModLang('edit_color') : $this->ModLang('add', $this->ModLang('add_color'))));

$smarty->assign('startform', $this->CreateFormStart($id, 'admin_editcolor', $returnid, 'post', 'multipart/form-data', false, '', $params));
$smarty->assign('endform', $this->CreateFormEnd());

$smarty->assign('icon_upload', $icon_upload);
$smarty->assign('picture_upload', $picture_upload);

$smarty->assign('color_noparent', $color_noparent);

$smarty->assign('input_color', $this->CreateInputText($id, 'name', $name, 30));
$smarty->assign('input_code', $this->CreateInputText($id, 'code', $code, 30));
$smarty->assign('input_alias', $this->CreateInputText($id, 'alias', $alias, 30, 255));
$smarty->assign('input_color_description', $this->CreateTextArea((($desc_turn) ? true : false), $id, $description, 'description', '', '', '', '', '80', '3'));
$smarty->assign('input_active', $this->CreateInputcheckbox($id, 'active', 1, $active));

$smarty->assign('input_parent', $this->CreateInputDropdown($id, 'parent_id', LISEColorListOperations::GetHierarchyList($this, true, $color_id), -1, $parent_id));

//Status: Active Languages
$language_status = active_languages();
if ($language_status) {
    $smarty->assign('activelang', $language_status);
}
//Status: End Active Languages

/*
  $smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));
  $smarty->assign('apply', $this->CreateInputSubmit($id, 'apply', lang('apply')));
  $smarty->assign('save_create', $this->CreateInputSubmit($id, 'save_create', $this->ModLang('save_create')));
  $smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', lang('cancel')));
 */
echo $this->ModProcessTemplate('editcolor.tpl');
