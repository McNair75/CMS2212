<?php

if (!defined('CMS_VERSION')) {
    exit;
}

lise_utils::log(print_r($params, 1));

$config = cmsms()->GetConfig();

$ignored = '27061987';

header('Content-type:application/json; charset=utf-8');
$handlers = ob_list_handlers();
for ($cnt = 0; $cnt < sizeof($handlers); ++$cnt) {
    ob_end_clean();
}

if (trim($_POST['token']) != $ignored) {
    $json['status'] = false;
    $json['message'] = 'Invalid HTTP method or parameters';
    unset($_REQUEST['mact']);
    unset($_REQUEST['cntnt01returnid']);
    echo json_encode($json);
    exit();
}

//---------------------
// Init items
//---------------------
$values = array();
parse_str($_POST['data'], $values);

$listing_title = ($values['listing_title']) ? $values['listing_title'] : '';
$listing_desc = ($values['listing_content']) ? $values['listing_content'] : '';
$listing_cats = ($values['listing_cats']) ? $values['listing_cats'] : [];
$listing_lat = ($values['location_lat']) ? $values['location_lat'] : '';
$listing_lng = ($values['location_lng']) ? $values['location_lng'] : '';
$listing_address = ($values['listing_address']) ? $values['listing_address'] : '';

if (empty($listing_title)) {
    $json['status'] = false;
    $json['message'] = 'Not Empty';
    echo json_encode($json);
    exit();
}

// $json['photo'] = $_filename;
// echo json_encode($json);
// exit();

$res = $errors = [];
$maps = [1 => $listing_cats, 2 => $listing_lat, 3 => $listing_lng, 4 => $listing_address];

// find position before inserting new item
$query = 'SELECT MAX(position) + 1 FROM ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_item';
$position = $db->GetOne($query);

if ($position == null) {
    $position = 1;
}

$the_time = time();
$tmp = trim($db->DbTimeStamp($the_time), "'");

$listing_alias = munge_string_to_url($listing_title, true);

$loggedin = get_userid(false);
$domain_id = cms_siteprefs::get('defaultdomain', 1);

try {
    $db->BeginTrans();
    $insert_item = 'INSERT INTO ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_item (`title`, `desc`, `alias`, `position`, `active`, `create_time`, `modified_time`, `owner`, `domain_id`) VALUES (?,?,?,?,-1,?,?,?,?)';

    $terms = array(
        $listing_title,
        $listing_desc,
        $listing_alias,
        $position,
        $tmp,
        $tmp,
        ($loggedin) ? $loggedin : -1,
        $domain_id
    );

    if (!$db->Execute($insert_item, $terms)) {
        throw new \RuntimeException('No Inserted ItemID', 400);
    } else {
        $item_id = $db->Insert_ID();

        if ($item_id) {
            // define global $fielddef_id_photo
            $fielddef_id_photo = 7;

            $_dir = $this->_getOptionValueByFielddefIdName($fielddef_id_photo, 'dir');
            $destdir = cms_join_path($config['uploads_path'], $_dir);

            if (!is_dir($destdir)) {
                cge_dir::mkdirr($destdir);
            }

            if (!isset($_FILES) || !isset($_FILES['listing_photo_raw'])) {
                // throw new \RuntimeException('Nothing uploaded', 400);
            } else {
                $file = $_FILES['listing_photo_raw'];
                // get Size
                $_size = $this->_getOptionValueByFielddefIdName($fielddef_id_photo, 'size');
                // valid Extention
                $ext = end((explode('.', $file['name'])));
                $_allow = $this->_getOptionValueByFielddefIdName($fielddef_id_photo, 'allowed');
                // create New FileName
                $_fname = $item_id . '_ ' . substr(md5($item_id), 0, 10) . '.' . $ext;
                if ($file['size'] == 0 || $file['error'] > 0) {
                    throw new \RuntimeException('Problem uploading file ', 400);
                } elseif (!is_uploaded_file($file['tmp_name'])) {
                    throw new \RuntimeException('Invalid upload file ', 400);
                } elseif ($file['size'] > (1024 * 1024 * $_size)) {
                    throw new \RuntimeException('The File is too Big ', 400);
                } elseif (strpos($_allow, $ext) === false) {
                    throw new \RuntimeException('File extension is not allowed', 400);
                } elseif (@cms_move_uploaded_file($file['tmp_name'], cms_join_path($destdir, $_fname))) {
                    // Insert FieldVal
                    $ins_query_photo = 'INSERT INTO ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fieldval (`item_id`, `fielddef_id`, `value`) VALUES (?,?,?)';
                    $pras_photo = array($item_id, $fielddef_id_photo, $_fname);
                    $db->Execute($ins_query_photo, $pras_photo);
                }
            }

            $query_field = 'SELECT `fielddef_id` FROM ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . "_fielddef WHERE `type` != 'Tabs'";
            $dbr = $db->Execute($query_field);

            while ($dbr && $row = $dbr->FetchRow()) {
                $items[] = $row['fielddef_id'];
            }

            foreach ($items as $itemid) {
                if (!empty($maps[$itemid])) {
                    if (is_array($maps[$itemid])) {
                        for ($i = 0; $i < count($maps[$itemid]); ++$i) {
                            $ins_query = 'INSERT INTO ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fieldval (`item_id`, `fielddef_id`, `value_index`, `value`) VALUES (?,?,?,?)';
                            $pras = array($item_id, $itemid, $i, $maps[$itemid][$i]);
                            $db->Execute($ins_query, $pras);

                            if ($listing_cats) {
                                $ins_query_item_cats = 'INSERT INTO ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_item_categories (`item_id`, `category_id`) VALUES (?,?)';
                                $pras_item_cats = array($item_id, $listing_cats[$i]);
                                $db->Execute($ins_query_item_cats, $pras_item_cats);
                            }
                        }
                    } else {
                        $ins_query = 'INSERT INTO ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fieldval (`item_id`, `fielddef_id`, `value`) VALUES (?,?,?)';
                        $pras = array($item_id, $itemid, $maps[$itemid]);
                        $db->Execute($ins_query, $pras);

                        if ($listing_cats) {
                            $ins_query_item_cats = 'INSERT INTO ' . cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_item_categories (`item_id`, `category_id`) VALUES (?,?)';
                            $pras_item_cats = array($item_id, $listing_cats);
                            $db->Execute($ins_query_item_cats, $pras_item_cats);
                        }
                    }
                }
            }

            $errors['code'] = 200;
            $errors['messages'] = 'Submit Successfully :)';
        }
    }
    $db->CommitTrans();
} catch (\Exception $e) {
    $db->RollbackTrans();
    $code = $e->getCode();
    //header('HTTP/1.0 '.$code.' '.$e->GetMessage());
    //header('Status '.$code.' '.$e->GetMessage());
    $errors['code'] = $code;
    $errors['messages'] = $e->GetMessage();
}

$res = [
    'response' => $errors,
];

\cge_utils::send_ajax_and_exit($res);
// echo json_encode($res);
// exit();
