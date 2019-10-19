<?php

$gCms = cmsms();
$config = $gCms->GetConfig();
$smarty = $gCms->GetSmarty();

debug_buffer('Debug in the page is: ' . $error);
if (isset($error) && $error != '') {
    $smarty->assign('error', $error);
} else if (isset($warningLogin) && $warningLogin != '') {
    $smarty->assign('warninglogin', $warningLogin);
} else if (isset($acceptLogin) && $acceptLogin != '') {
    $smarty->assign('acceptlogin', $acceptLogin);
}

if ($changepwhash != '') {
    $smarty->assign('changepwhash', $changepwhash);
}

#Start::Images & Icon
if (!empty($config['admin_path_url'])) {
    $admin_path_url = cms_join_path($config['admin_url'], 'themes', '_src', $config['admin_path_url'], 'logoCMS.png');
    $admin_icon_url = cms_join_path($config['admin_url'], 'themes', '_src', $config['admin_path_url'], 'favicon', 'cmsms-favicon.ico');
    $admin_touch = [
        cms_join_path($config['admin_url'], 'themes', '_src', $config['admin_path_url'], 'favicon', 'apple-touch-icon-iphone.png'),
        cms_join_path($config['admin_url'], 'themes', '_src', $config['admin_path_url'], 'favicon', 'apple-touch-icon-ipad.png'),
        cms_join_path($config['admin_url'], 'themes', '_src', $config['admin_path_url'], 'favicon', 'apple-touch-icon-iphone4.png'),
        cms_join_path($config['admin_url'], 'themes', '_src', $config['admin_path_url'], 'favicon', 'apple-touch-icon-ipad3.png'),
        cms_join_path($config['admin_url'], 'themes', '_src', $config['admin_path_url'], 'favicon', 'ms-application-icon.png')
    ];
} else {
    $admin_path_url = cms_join_path($config['admin_url'], 'themes', 'OneEleven', 'images', 'logoCMS.png');
    $admin_icon_url = cms_join_path($config['admin_url'], 'themes', 'OneEleven', 'images', 'favicon', 'cmsms-favicon.ico');
    $admin_touch = [
        cms_join_path($config['admin_url'], 'themes', 'OneEleven', 'images', 'favicon', 'apple-touch-icon-iphone.png'),
        cms_join_path($config['admin_url'], 'themes', 'OneEleven', 'images', 'favicon', 'apple-touch-icon-ipad.png'),
        cms_join_path($config['admin_url'], 'themes', 'OneEleven', 'images', 'favicon', 'apple-touch-icon-iphone4.png'),
        cms_join_path($config['admin_url'], 'themes', 'OneEleven', 'images', 'favicon', 'apple-touch-icon-ipad3.png'),
        cms_join_path($config['admin_url'], 'themes', 'OneEleven', 'images', 'favicon', 'ms-application-icon.png')
    ];
}
$src['path'] = $admin_path_url;
$src['icon'] = $admin_icon_url;
$src['icon_touch'] = $admin_touch;
$src['title'] = lang('adminpaneltitle');
$smarty->assign('src', $src);
#End::Images & Icon

$smarty->assign('encoding', get_encoding());
$smarty->assign('config', $gCms->GetConfig());

$smarty->assign('sitename', $config['admin_title']);
