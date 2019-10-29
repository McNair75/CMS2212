<?php

/**
 * based on CGBlog own action.ajax_geturl.php
 */
if (!defined('CMS_VERSION'))
    exit;
$item_id = '';
if (isset($_REQUEST['itemid']))
    $item_id = (int) $_REQUEST['itemid'];

$prefix = $this->GetPreference('url_prefix', munge_string_to_url($this->GetName(), true));
$template = $this->GetPreference('urltemplate', '{$prefix}/{$item_title}');

$smarty->assign('item_title', munge_string_to_url(trim($_REQUEST['title'])));
$smarty->assign('prefix', $prefix);

$test = 2;
$url = '';
cms_route_manager::load_routes();
$url = $this->ProcessTemplateFromData('{strip}' . $template . '{/strip}');

while ($test < 100) {
    // generate a unique random url.
    if ($test > 1)
        $url .= '-' . $test;

    $url = trim($url, " /\t\r\n\0\x08");
    $route = cms_route_manager::find_match($url);
    if ($route) {
        $dflts = $route->get_defaults();
        if ($route->is_content() || $route->get_dest() != $this->GetName() || !isset($dflts['articleid']) || $dflts['articleid'] != $item_id) {
            // route is used.
            $test++;
            continue;
        }
    }

    break;
}

echo $url;
