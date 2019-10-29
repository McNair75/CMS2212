<?php

if (!defined('CMS_VERSION')) {
    exit;
}

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_category')) {
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
    $params = array('active_tab' => 'categorytab');
    $this->Redirect($id, 'defaultadmin', $returnid, $params);
}

//---------------------
// Init params
//---------------------

$category_id = lise_utils::init_var('category_id', $params, -1);
$name = lise_utils::init_var('name', $params, '');
$alias = lise_utils::init_var('alias', $params, '');
$description = lise_utils::init_var('description', $params, '');
$parent_id = lise_utils::init_var('parent_id', $params, -1);
$active = 1;

$icon_upload = lise_utils::init_var('icon_upload', $params, '');
$picture_upload = lise_utils::init_var('picture_upload', $params, '');

$category_noparent = $this->GetPreference('category_noparent', 0);

$allow_category_sametitle = $this->GetPreference('allow_category_sametitle', 0);
//---------------------
// Init Item
//---------------------

$obj = $this->LoadCategoryByIdentifier('category_id', $category_id, $mleblock);
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
    $query_cid = 'SELECT max(category_id) + 1 FROM ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_category';
    $new_position = $db->GetOne($query_cid);
    $cid = ($category_id == -1) ? $new_position : $category_id;

    $handler = cge_setup::get_uploader($id, $destdir);
    $handler->set_allow_overwrite(true);
    $res = $handler->handle_upload('icon_upload', 'i_' . substr(md5($cid), 0, 6), '');
    $err = $handler->get_error();

    if ($res === false) {
        $icon_upload = $obj->icon;
    } else {
        $icon_upload = 'images/' . $this->GetName() . '/' . $res;
    }

    $handler = cge_setup::get_uploader($id, $destdir);
    $handler->set_allow_overwrite(true);
    $res_picture = $handler->handle_upload('picture_upload', 'p_' . substr(md5($cid), 0, 6), '');

    if ($res_picture === false) {
        $picture_upload = $obj->picture;
    } else {
        $picture_upload = 'images/' . $this->GetName() . '/' . $res_picture;
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

    // check category name
    if (empty($name)) {
        $errors[] = $this->ModLang('category_name_empty');
    }

    if (empty($alias)) {
        $alias = munge_string_to_url($name, true, true);
    } else {
        $alias = munge_string_to_url($alias, true, true);
    }
    if (!$allow_category_sametitle) {
        // Check for duplicate
        if ($cid > 0) {
            $query = 'SELECT category_id FROM ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_category WHERE category_alias = ? AND category_id != ?';
            $exists = $db->GetOne($query, array($alias, $cid));
        } else {
            $query = 'SELECT category_id FROM ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_category WHERE category_alias = ?';
            $exists = $db->GetOne($query, array($alias));
        }
        if ($exists) {
            $errors[] = $this->ModLang('item_alias_exists');
        }
    }

    // title and required fields have values, let's continue
    if (empty($errors)) {
        $obj->name = $name;
        $obj->alias = $alias;
        $obj->description = $description;
        $obj->parent_id = $parent_id;
        $obj->icon = $icon_upload;
        $obj->picture = $picture_upload;
        $obj->active = isset($params['active']) ? 1 : 0;

        $this->SaveCategory($obj, $mleblock);

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
            $this->Redirect($id, 'admin_editcategory', $returnid, array(
                'message' => 'savecreate_message',
            ));
        }

        // show saved message
        if (isset($params['submit'])) {
            $this->Redirect($id, 'defaultadmin', $returnid, array(
                'active_tab' => 'categorytab',
                'message' => 'changessaved',
            ));
        } else {
            echo $this->ShowMessage($this->ModLang('changessaved'));
        }
    } // end error check
} // end submit or apply
elseif ($obj->category_id > 0) {

    $icon_upload = $obj->icon;
    $picture_upload = $obj->picture;
    $category_id = $obj->category_id;
    $name = $obj->name;
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

$desc_turn = $this->GetPreference('category_enabled_wysiwyg');

$smarty->assign('categoryObject', $obj);

$smarty->assign('backlink', $this->CreateBackLink('categorytab'));
$smarty->assign('title', (isset($category_id) ? $this->ModLang('edit_category') : $this->ModLang('add', $this->ModLang('category'))));

$smarty->assign('startform', $this->CreateFormStart($id, 'admin_editcategory', $returnid, 'post', 'multipart/form-data', false, '', $params));
$smarty->assign('endform', $this->CreateFormEnd());

$smarty->assign('icon_upload', $icon_upload);
$smarty->assign('picture_upload', $picture_upload);

$smarty->assign('category_noparent', $category_noparent);

$smarty->assign('input_category', $this->CreateInputText($id, 'name', $name, 50));
$smarty->assign('input_alias', $this->CreateInputText($id, 'alias', $alias, 50, 255));
$smarty->assign('input_category_description', $this->CreateTextArea((($desc_turn) ? true : false), $id, $description, 'description', '', '', '', '', '80', '3'));
$smarty->assign('input_active', $this->CreateInputcheckbox($id, 'active', 1, $active));

$smarty->assign('input_parent', $this->CreateInputDropdown($id, 'parent_id', LISECategoryOperations::GetHierarchyList($this, true, $category_id), -1, $parent_id));

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
echo $this->ModProcessTemplate('editcategory.tpl');
