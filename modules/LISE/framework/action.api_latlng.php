<?php

if (!defined('CMS_VERSION')) {
    exit;
}

$ignored = '27061987';

if ($_SERVER['CONTENT_TYPE'] != 'application/json') {
    $json['status'] = false;
    $json['message'] = 'Invalid HTTP method or parameters';
    \cge_utils::send_ajax_and_exit($json);
}

$data_ = json_decode(file_get_contents("php://input"), true);
if (!empty($data_['address'])) {
    $address = $data_['address'];
}

if (trim($data_['token']) != $ignored) {
    $json['status'] = false;
    $json['message'] = 'Invalid HTTP method or parameters';
    unset($_REQUEST['page']);
    unset($_REQUEST['mact']);
    unset($_REQUEST['cntnt01returnid']);
    $json['params_request'] = json_decode(file_get_contents("php://input"), true);
    \cge_utils::send_ajax_and_exit($json);
}

$mod_maps = cge_utils::get_module('CGGoogleMaps2');
$key = $mod_maps->GetPreference('apikey');

$address = urlencode($address);
$url = "https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&key=$key";
$json = file_get_contents($url);
$status = json_decode($json)->status;
while ($status == "OVER_QUERY_LIMIT") {
    sleep(0.2); // seconds
    $json = file_get_contents($url);
    $status = json_decode($json)->status;
}
$json = json_decode($json);

$obj['lat'] = substr($json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'}, 0, -1);
$obj['lng'] = substr($json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}, 0, -1);
$obj['formated_address'] = $json->{'results'}[0]->{'formatted_address'};
$obj['place_id'] = $json->{'results'}[0]->{'place_id'};

$items['address'] = $address;
$items['status'] = $json->status;
$items['data'] = $obj;
$items['header'] = $_SERVER;

\cge_utils::send_ajax_and_exit($items);

