<?php

if (!defined('CMS_VERSION'))
    exit;

#---------------------
# Check params
#---------------------
if (!isset($params['item'])) {
    die('missing parameter, this should not happen');
}

global $mle, $mleblock;

$template = 'detail_' . $this->GetPreference($this->_GetModuleAlias() . '_default_detail_template');
if (isset($params['template_detail'])) {

    $template = 'detail_' . $params['template_detail'];
} elseif (isset($params['detailtemplate'])) {

    $template = 'detail_' . $params['detailtemplate'];
}

if (isset($params['item'])) {
    cms_utils::set_app_data('lise_item', $params['item']);
}

// Summary page check
$summarypage = $this->GetPreference('summarypage', $returnid);
if (isset($params['summarypage'])) {

    if (is_numeric($params['summarypage'])) {
        $summarypage = $params['summarypage'];
    } else {
        if (!isset($hm))
            $hm = cmsms()->GetHierarchyManager();

        $summarypage = $hm->sureGetNodeByAlias($params['summarypage'])->GetId();
    }
}

// return page can be in a session var
$test = isset($_SESSION[$this->GetName() . 'dp']) ? $_SESSION[$this->GetName() . 'dp'] : '';

if (is_array($test)) {
    $contentops = cmsms()->GetContentOperations();
    $current_page_obj = $contentops->getContentObject();
    $current_page = $current_page_obj->Id();

    if (isset($test[$current_page])) {
        $summarypage = $test[$current_page];
        $return_page_obj = $contentops->LoadContentFromId($summarypage);
        $ret_pretty_url = $return_page_obj->GetURL();
    }
}

$identifier = is_numeric($params['item']) ? 'item_id' : 'alias';
$debug = (isset($params['debug']) ? true : false);

#---------------------
# Init item
#---------------------
$item = $this->LoadItemByIdentifier($identifier, $params['item'], $mleblock);

/* * ********************************************************************* */
# quick dirty fix (needs to be revisited)
# we do need an url for each item here already 
# probably there are parameters missing so this needs more work
# JoMorg
/* * ********************************************************************* */
$config = cmsms()->GetConfig();

if (empty($item->url) || $config['url_rewriting'] != 'mod_rewrite') {
    $detailspage = $this->GetPreference('detailpage', $returnid);
    $string_array = array();
    $string_array[] = $this->prefix;
    $string_array[] = $item->alias;
    $string_array[] = $detailspage;

    $prettyurl = implode('/', $string_array);

    $item->url = $this->create_url($id, 'detail', $detailspage, array('item' => $params['item']), false, false, $prettyurl);
}
/* * ********************************************************************* */

// lets deal with tags if they are available
foreach ($item->fielddefs as $one) {
    $linkparams = array();
    if ('Tags' !== $one->type)
        continue;
    $one->SetTagsParams($this, $id, 'default', $summarypage, $linkparams);
}

# try to get the return link right for pagedetail overrides...
if (!empty($ret_pretty_url)) {
    $ret_pretty_link = '<a href="' . $ret_pretty_url . '" class="return-link">return</a>';
} else {
    $ret_pretty_url = $this->CreatePrettyLink($id, 'default', $summarypage, '', $params, '', TRUE);
    $ret_pretty_link = $this->CreatePrettyLink($id, 'default', $summarypage, $this->ModLang('return_url'), $params);
}



#---------------------
# Smarty processing
#---------------------

$smarty->assign('item', $item);
$smarty->assign('LISE_action', 'detail');
$smarty->assign($this->GetName() . '_item', $item); // <- Alias for $item

$smarty->assign('return_url', $ret_pretty_url);
$smarty->assign('return_link', $ret_pretty_link);

echo $this->ProcessTemplateFromDatabase($template);

if ($debug)
    $smarty->display('string:<pre>{$item|@print_r}</pre>');