<?php

if (!defined('CMS_VERSION'))
    exit;

if (!$this->CheckPermission($this->_GetModuleAlias() . '_modify_item')) {
    return;
}

#Start MLE
global $hls, $hl, $mleblock;
$thisURL = $_SERVER['SCRIPT_NAME'] . '?';
foreach ($_GET as $key => $val)
    if ('hl' != $key)
        $thisURL .= $key . '=' . $val . '&amp;';
if (isset($hls)) {
    $langList = ' &nbsp; &nbsp; ';
    foreach ($hls as $key => $mle) {
        $langList .= ($hl == $key) ? $mle['flag'] . ' ' : '<a href="' . $thisURL . 'hl=' . $key . '">' . $mle['flag'] . '</a> ';
    }
}
$smarty->assign('langList', $langList);
#End MLE
// Get prefs
$show_categories = LISEFielddefOperations::TestExistanceByType($this, 'Categories');
$show_colorlists = LISEFielddefOperations::TestExistanceByType($this, 'ColorList');
$show_maps_settings = $this->GetPreference('maps');

if (isset($params['message'])) {
    echo $this->ShowMessage($this->ModLang($params['message']));
}

if (isset($params['errors']) && count($params['errors'])) {
    echo $this->ShowErrors($params['errors']);
}

if (!empty($params['active_tab'])) {
    $tab = $params['active_tab'];
} else {
    $tab = 'itemtab';
}

# something odd with preferences in 2.0 ??? this is a workaround 
$pref = $this->GetPreference('item_plural', '');
$item_plural_string = empty($pref) ? $this->ModLang('items') : $pref;

echo $this->StartTabHeaders();
echo $this->SetTabHeader('itemtab', $item_plural_string, ($tab == 'itemtab'));

if ($this->CheckPermission($this->_GetModuleAlias() . '_modify_category') && $show_categories) {
    echo $this->SetTabHeader('categorytab', $this->ModLang('categories'), ($tab == 'categorytab'));
}

if ($this->CheckPermission($this->_GetModuleAlias() . '_modify_color') && $show_colorlists) {
    echo $this->SetTabHeader('colorlisttab', $this->ModLang('color_list'), ($tab == 'colorlisttab'));
}


if ($this->CheckPermission($this->_GetModuleAlias() . '_modify_option')) {
    echo $this->SetTabHeader('fielddeftab', $this->ModLang('fielddefs'), ($tab == 'fielddeftab'), 'system');
    echo $this->SetTabHeader('templatetab', $this->ModLang('templates'), ($tab == 'templatetab'), 'system');
    if ($show_maps_settings) {
        echo $this->SetTabHeader('mapstab', $this->ModLang('maps'), ($tab == 'mapstab'));
    }
    echo $this->SetTabHeader('optiontab', $this->ModLang('options'), ($tab == 'optiontab'), 'system');
}



echo $this->EndTabHeaders();
echo $this->StartTabContent();

echo $this->StartTab('itemtab', $params);
include dirname(__FILE__) . '/function.admin_itemtab.php';
echo $this->EndTab();

if ($this->CheckPermission($this->_GetModuleAlias() . '_modify_category') && $show_categories) {
    echo $this->StartTab('categorytab', $params);
    include dirname(__FILE__) . '/function.admin_categorytab.php';
    echo $this->EndTab();
}

if ($this->CheckPermission($this->_GetModuleAlias() . '_modify_color') && $show_colorlists) {
    echo $this->StartTab('colorlisttab', $params);
    include dirname(__FILE__) . '/function.admin_colorlisttab.php';
    echo $this->EndTab();
}

if ($this->CheckPermission($this->_GetModuleAlias() . '_modify_option')) {
    echo $this->StartTab('fielddeftab', $params);
    include dirname(__FILE__) . '/function.admin_fielddeftab.php';
    echo $this->EndTab();

    echo $this->StartTab('templatetab', $params);
    include dirname(__FILE__) . '/function.admin_templatetab.php';
    echo $this->EndTab();

    if ($show_maps_settings) {
        echo $this->StartTab('mapstab', $params);
        include dirname(__FILE__) . '/function.admin_optionmaps.php';
        echo $this->EndTab();
    }

    echo $this->StartTab('optiontab', $params);
    include dirname(__FILE__) . '/function.admin_optiontab.php';
    echo $this->EndTab();
}

echo $this->EndTabContent();