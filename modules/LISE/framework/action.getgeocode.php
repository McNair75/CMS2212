<?php

//if( !isset($gCms) ) exit;
//if( !isset($params['address']) ) return;

$mod_maps = \cge_utils::get_module('CGGoogleMaps2');
$res = $mod_maps->GetCoordsFromAddress($_POST['address']);

if (!$res) {
    echo json_encode(array('status' => 'error'));
} else {
    $res['status'] = 'success';
    echo json_encode($res);
}
exit();