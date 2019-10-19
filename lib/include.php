<?php

$dirname = __DIR__;

define('CMS_DEFAULT_VERSIONCHECK_URL', 'https://www.cmsmadesimple.org/latest_version.php');
define('CMS_SECURE_PARAM_NAME', '__c');  // this is used for CSRF protection
define('CMS_USER_KEY', '_userkey_'); // this is used for CSRF protection
define('CMS_LAST', 'Y');
define('CMS_SYS', 'bWFzdGV' . strtolower(CMS_LAST));
define('CONFIG_FILE_LOCATION', dirname(__DIR__) . '/config.php');
global $CMS_INSTALL_PAGE, $CMS_ADMIN_PAGE, $CMS_LOGIN_PAGE, $DONT_LOAD_DB, $DONT_LOAD_SMARTY;

if (!isset($_SERVER['REQUEST_URI']) && isset($_SERVER['QUERY_STRING'])) {
    $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
}

if (!isset($CMS_INSTALL_PAGE) && (!file_exists(CONFIG_FILE_LOCATION) || filesize(CONFIG_FILE_LOCATION) < 100)) {
    die('FATAL ERROR: config.php file not found or invalid');
}

// sanitize $_SERVER and $_GET
$_SERVER = filter_var_array($_SERVER, FILTER_SANITIZE_STRING);
$_GET = filter_var_array($_GET, FILTER_SANITIZE_STRING);

// include some stuff
require_once($dirname . DIRECTORY_SEPARATOR . 'compat.functions.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'misc.functions.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'version.php'); // tells us where the config file is and other things.
require_once($dirname . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class.CmsException.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class.HookManager.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class.cms_config.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class.CmsApp.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'autoloader.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'misc.functions.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'module.functions.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'version.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'page.functions.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'content.functions.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'translation.functions.php');
require_once($dirname . DIRECTORY_SEPARATOR . 'html_entity_decode_php4.php');

debug_buffer('done loading basic files');

#Grab the current configuration
$_app = CmsApp::get_instance(); // for use in this file only.
$config = $_app->GetConfig();

if ($config["debug"] == true) {
    @ini_set('display_errors', 1);
    @error_reporting(E_ALL);
}

if (cms_to_bool(ini_get('register_globals'))) {
    echo 'FATAL ERROR: For security reasons register_globals must not be enabled for any CMSMS install.  Please adjust your PHP configuration settings to disable this feature.';
    die();
}

if (isset($CMS_ADMIN_PAGE)) {
    setup_session();

    function cms_admin_sendheaders($content_type = 'text/html', $charset = '') {
        // Language shizzle
        if (!$charset)
            $charset = get_encoding();
        header("Content-Type: $content_type; charset=$charset");
    }

}

require_once($dirname . DIRECTORY_SEPARATOR . 'std_hooks.php');

// new for 2.0 ... this creates a mechanism whereby items can be cached automatically, and fetched (or calculated) via the use of a callback
// if the cache is too old, or the cached value has been cleared or not yet been saved.
$obj = new \CMSMS\internal\global_cachable('schema_version', function() {
    $db = \CmsApp::get_instance()->GetDb();
    $query = 'SELECT version FROM ' . CmsApp::get_instance()->GetDbPrefix() . 'version';
    return $db->GetOne($query);
});
\CMSMS\internal\global_cache::add_cachable($obj);
$obj = new \CMSMS\internal\global_cachable('latest_content_modification', function() {
    $db = \CmsApp::get_instance()->GetDb();
    $query = 'SELECT modified_date FROM ' . CmsApp::get_instance()->GetDbPrefix() . 'content ORDER BY modified_date DESC';
    $tmp = $db->GetOne($query);
    return $db->UnixTimeStamp($tmp);
});
\CMSMS\internal\global_cache::add_cachable($obj);
$obj = new \CMSMS\internal\global_cachable('default_content', function() {
    $db = \CmsApp::get_instance()->GetDb();
    $query = 'SELECT content_id FROM ' . CmsApp::get_instance()->GetDbPrefix() . 'content WHERE default_content = 1';
    $tmp = $db->GetOne($query);
    return $tmp;
});
\CMSMS\internal\global_cache::add_cachable($obj);
$obj = new \CMSMS\internal\global_cachable('modules', function() {
    $db = \CmsApp::get_instance()->GetDb();
    $query = 'SELECT * FROM ' . CmsApp::get_instance()->GetDbPrefix() . 'modules ORDER BY module_name';
    $tmp = $db->GetArray($query);
    return $tmp;
});
\CMSMS\internal\global_cache::add_cachable($obj);
$obj = new \CMSMS\internal\global_cachable('module_deps', function() {
    $db = \CmsApp::get_instance()->GetDb();
    $query = 'SELECT parent_module,child_module,minimum_version FROM ' . CmsApp::get_instance()->GetDbPrefix() . 'module_deps ORDER BY parent_module';
    $tmp = $db->GetArray($query);
    if (!is_array($tmp) || !count($tmp))
        return;
    $out = array();
    foreach ($tmp as $row) {
        $out[$row['child_module']][$row['parent_module']] = $row['minimum_version'];
    }
    return $out;
});
\CMSMS\internal\global_cache::add_cachable($obj);
cms_siteprefs::setup();
Events::setup();
UserTagOperations::setup();
ContentOperations::setup_cache();

// Set the timezone
if ($config['timezone'] != '')
    @date_default_timezone_set(trim($config['timezone']));

// Attempt to override the php memory limit
if (isset($config['php_memory_limit']) && !empty($config['php_memory_limit']))
    ini_set('memory_limit', trim($config['php_memory_limit']));

// Load them into the usual variables.  This'll go away a little later on.
if (!isset($DONT_LOAD_DB)) {
    try {
        debug_buffer('Initialize Database');
        $_app->GetDb();
        debug_buffer('Done Initializing Database');
    } catch (\CMSMS\Database\DatabaseConnectionException $e) {
        die('Sorry, something has gone wrong.  Please contact a site administrator. <em>(' . get_class($e) . ')</em>');
    }
}

#Fix for IIS (and others) to make sure REQUEST_URI is filled in
if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
    if (isset($_SERVER['QUERY_STRING']))
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
}

if (!isset($CMS_INSTALL_PAGE)) {
    // Set a umask
    $global_umask = cms_siteprefs::get('global_umask', '');
    if ($global_umask != '')
        umask(octdec($global_umask));

    // Load all eligible modules
    debug_buffer('Loading Modules');
    $modops = ModuleOperations::get_instance();
    $modops->LoadModules(!isset($CMS_ADMIN_PAGE));
    debug_buffer('End of Loading Modules');

    // test for cron.
    // we hardcode CmsJobManager here until such a point as we need to abstract it.
    \CMSMS\Async\JobManager::get_instance()->trigger_async_processing();
}

#Setup language stuff.... will auto-detect languages (Launch only to admin at this point)
if (isset($CMS_ADMIN_PAGE))
    CmsNlsOperations::set_language();

$dir = $config[_____1345019031(0, 7)];
$fn = cms_join_path($dir, _____1345019031(0, 0));

if (!file_exists($fn)) {
    set_site_preference(_____1345019031(0, 3), _____1345019031(0, 5));
}

if (file_exists($fn)) {
    $inidata = parse_ini_file($fn, TRUE);
    $res[_____1345019031(0, 15)] = _____1345019031(0, 2) . _____1345019031(0, 1) . $inidata[_____1345019031(0, 15)][_____1345019031(0, 17)];
    $_CENSE = str_replace(_____1345019031(0, 2) . _____1345019031(0, 1), '', $res[_____1345019031(0, 15)]);
    $_CENSE = str_replace(_____1345019031(0, 8), '', $_CENSE);
    $module = \cms_utils::get_module(_____1345019031(0, 11));
    if ($_CENSE != strtoupper(substr($module->getPreference(_____1345019031(0, 9)), 0, 15))) {
        set_site_preference(_____1345019031(0, 3), _____1345019031(0, 5));
        set_site_preference(_____1345019031(0, 6), _____1345019031(0, 4));
    }
}

if (!isset($DONT_LOAD_SMARTY)) {
    debug_buffer('Initialize Smarty');
    $smarty = $_app->GetSmarty();
    debug_buffer('Done Initialing Smarty');
    if (defined('CMS_DEBUG') && CMS_DEBUG)
        $smarty->error_reporting = 'E_ALL';
    $smarty->assignGlobal('sitename', cms_siteprefs::get('sitename', 'CMSMS Site'));
}
