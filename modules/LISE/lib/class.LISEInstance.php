<?php

class LISEInstance extends LISE {
    #---------------------
    # Magic methods
    #---------------------    

    public function __construct() {
        parent::__construct();
        # deal with registering routes for custom urls
        $db = cmsms()->GetDb();

        $query = 'SELECT item_id,url FROM '
                . cms_db_prefix()
                . 'module_'
                . $this->_GetModuleAlias()
                . '_item WHERE url != ?';

        $tmp = $db->GetArray($query, array(''));

        if (is_array($tmp)) {
            $detailpage = $this->GetPreference('detailpage', -1);

            if ($detailpage == -1) {
                $contentops = cmsms()->GetContentOperations();
                $detailpage = $contentops->GetDefaultContent();
            }

            foreach ($tmp as $one) {
                $parms = array(
                    'action' => 'detail',
                    'returnid' => $detailpage,
                    'item' => $one['item_id']
                );

                $route = new CmsRoute($one['url'], $this->GetName(), $parms, TRUE);
                cms_route_manager::register($route);
            }
        }
    }

    #---------------------
    # Module api methods
    #---------------------    

    public function GetName() {
        return get_class($this);
    }

    public function GetFriendlyName() {
        return $this->GetPreference('friendlyname');
    }

    public function GetAdminDescription() {
        return $this->GetPreference('moddescription', $this->ModLang('moddescription'));
    }

    public function GetAdminSection() {
        return $this->GetPreference('adminsection');
    }

    public function GetDomainSection() {
        $domainsection = $this->GetPreference('domainsection');
        $domain_section = ($domainsection) ? unserialize($domainsection) : [];
        return $domain_section;
    }

    function AllowAutoInstall() {
        return $this->GetPreference('auto_install', FALSE);
    }

    public function AllowAutoUpgrade() {
        return ( $this->GetPreference('auto_upgrade', FALSE) );
    }

    public function GetDependencies() {
        return array('LISE' => '1.2.2');
    }

    public function VisibleToAdminUser() {
        return $this->CheckPermission($this->_GetModuleAlias() . '_modify_item');
    }

    public function IsPluginModule() {
        return TRUE;
    }

    public function LazyLoadFrontend() {
        return FALSE;
    }

    public function SubPackage() {
        return FALSE;
    }

    public function GetHelp() {
        $smarty = cmsms()->GetSmarty();
        $config = cmsms()->GetConfig();

        $smarty->assign('parent_name', parent::GetName());
        $smarty->assign('root_url', $config['root_url']);
        $smarty->assign('mod', $this);
        $smarty->assign('usage_text', str_replace('{$module_name}', $this->GetName(), $this->ModLang('help_usage')));
        $smarty->assign('permissions_text', str_replace('{$module_name}', $this->GetName(), $this->ModLang('help_permissions')));
        $smarty->assign('categories_text', str_replace('{$module_name}', $this->GetName(), $this->ModLang('help_categories')));
        $smarty->assign('templates_text', str_replace('{$module_name}', $this->GetName(), $this->ModLang('help_templates')));
        $smarty->assign('duplicating_text', str_replace('{$module_name}', $this->GetName(), $this->ModLang('help_duplicating')));

        return $this->ModProcessTemplate('help.tpl');
    }

    public function InitializeFrontend() {
        $this->RegisterModulePlugin();
        $this->RestrictUnknownParams();

        // Set allowed parameters
        $this->SetParameterType('action', CLEAN_STRING);
        $this->SetParameterType('showall', CLEAN_INT);
        $this->SetParameterType('category', CLEAN_STRING);
        $this->SetParameterType('exclude_category', CLEAN_STRING);
        $this->SetParameterType('subcategory', CLEAN_INT);
        $this->SetParameterType('orderby', CLEAN_STRING);
        $this->SetParameterType('detailtemplate', CLEAN_STRING);
        $this->SetParameterType('summarytemplate', CLEAN_STRING);
        $this->SetParameterType('searchtemplate', CLEAN_STRING);
        $this->SetParameterType('categorytemplate', CLEAN_STRING);
        $this->SetParameterType('archivetemplate', CLEAN_STRING);
        $this->SetParameterType(CLEAN_REGEXP . '/template_.*/', CLEAN_STRING);
        $this->SetParameterType('detailpage', CLEAN_STRING);
        $this->SetParameterType('summarypage', CLEAN_STRING);
        $this->SetParameterType('item', CLEAN_STRING);
        $this->SetParameterType('id_hierarchy', CLEAN_STRING);
        $this->SetParameterType('pagelimit', CLEAN_INT);
        $this->SetParameterType('start', CLEAN_INT);
        $this->SetParameterType('pagenumber', CLEAN_INT);
        $this->SetParameterType('search', CLEAN_STRING);
        $this->SetParameterType(CLEAN_REGEXP . '/search_.*/', CLEAN_STRING);
        $this->SetParameterType('filter', CLEAN_STRING);
        $this->SetParameterType(CLEAN_REGEXP . '/filter_.*/', CLEAN_STRING);
        $this->SetParameterType('filterorderby', CLEAN_STRING); # @since 1.3
        $this->SetParameterType('debug', CLEAN_INT);
        $this->SetParameterType('collapse', CLEAN_INT);
        $this->SetParameterType('show_items', CLEAN_INT);
        $this->SetParameterType('number_of_levels', CLEAN_INT);
        $this->SetParameterType('include_items', CLEAN_STRING);
        $this->SetParameterType('exclude_items', CLEAN_STRING);
        $this->SetParameterType('tag', CLEAN_STRING);

        $this->SetParameterType('fieldid', CLEAN_INT);
        $this->SetParameterType('fieldalias', CLEAN_STRING);
        $this->SetParameterType('fieldval', CLEAN_STRING);

        //+Lee: use action=category
        $this->SetParameterType('popular', CLEAN_INT);
        $this->SetParameterType('alias', CLEAN_STRING);
        $this->SetParameterType('hasitem', CLEAN_INT);

        $this->SetParameterType('sortby', CLEAN_STRING);
        $this->SetParameterType('orderby', CLEAN_STRING);

        //+Lee: use action=ajax_get_items_by_catId
        $this->SetParameterType('catid', CLEAN_INT);

        // Get summarypage
        $summarypage = $this->GetPreference('summarypage', null);
        if (is_null($summarypage)) {

            if (!isset($contentops))
                $contentops = cmsms()->GetContentOperations();

            $summarypage = $contentops->GetDefaultPageID();
        }

        // Get detailpage
        $detailpage = $this->GetPreference('detailpage', null);
        if (is_null($detailpage)) {

            if (!isset($contentops))
                $contentops = cmsms()->GetContentOperations();

            $detailpage = $contentops->GetDefaultPageID();
        }

        // Get subcategory
        $subcategory = $this->GetPreference('subcategory');

        // fix prefixes

        $prefix = str_replace('/', '\/', $this->prefix);

        // Archive
        $this->RegisterRoute(
                '/'
                . $prefix
                . '\/archive\/(?P<filter_year>[0-9]+?)\/(?P<filter_month>[0-9]+?)\/(?P<returnid>[0-9]+)$/', array('action' => 'default')
        );

        $this->RegisterRoute(
                '/'
                . $prefix
                . '\/archive\/(?P<filter_year>[0-9]+?)\/(?P<returnid>[0-9]+)$/', array('action' => 'default')
        );

        // Pagination
        $this->RegisterRoute(
                '/'
                . $prefix
                . '\/(?P<category>.+?)\/page\/(?P<pagenumber>[0-9]+?)\/(?P<pagelimit>[0-9]+)\/(?P<returnid>[0-9]+)$/', array('action' => 'default')
        );

        $this->RegisterRoute(
                '/'
                . $prefix
                . '\/page\/(?P<pagenumber>[0-9]+?)\/(?P<pagelimit>[0-9]+)\/(?P<returnid>[0-9]+)$/', array('action' => 'default')
        );

        $this->RegisterRoute(
                '/'
                . $prefix
                . '\/page\/(?P<pagenumber>[0-9]+?)\/(?P<returnid>[0-9]+)$/', array('action' => 'default')
        );

        // Hierarchy view    
        $this->RegisterRoute(
                '/'
                . $prefix
                . '\/(?P<category>.+?)\/(?P<id_hierarchy>[0-9.]+)\/(?P<item>.+?)\/(?P<returnid>[0-9]+)$/', array('action' => 'detail')
        );

        $this->RegisterRoute(
                '/'
                . $prefix
                . '\/(?P<category>.+?)\/(?P<id_hierarchy>[0-9.]+)\/(?P<returnid>[0-9]+)$/', array('action' => 'default', 'subcategory' => $subcategory)
        );

        // Singular    
        $this->RegisterRoute(
                '/'
                . $prefix
                . '\/(?P<item>.+?)\/(?P<returnid>[0-9]+)\/(?P<detailtemplate>.+?)$/', array('action' => 'detail')
        );


        $this->RegisterRoute(
                '/'
                . $prefix
                . '\/(?P<item>.+?)\/(?P<returnid>[0-9]+)$/', array('action' => 'detail')
        );
    }

    public function InitializeAdmin() {
        $config = \LISE\ConfigManager::GetConfigInstance(cmsms()->GetModuleInstance('LISE'));
        $page_limit = isset($config['global_LISEQuery_page_limit']) ? $config['global_LISEQuery_page_limit'] : 100000;
        $config = \LISE\ConfigManager::GetConfigInstance($this);
        $page_limit = isset($config['LISEQuery_page_limit']) ? $config['LISEQuery_page_limit'] : $page_limit;

        // parameters that can be called in the module tag
        $this->CreateParameter('action', 'default', $this->ModLang('help_param_action'));
        $this->CreateParameter('orderby', 'item_position', $this->ModLang('help_param_orderby'));
        $this->CreateParameter('showall', '0', $this->ModLang('help_param_showall'));
        $this->CreateParameter('category', 'general', $this->ModLang('help_param_category'));
        $this->CreateParameter('exclude_category', '', $this->ModLang('help_param_exclude_category'));
        $this->CreateParameter('subcategory', 'false', $this->ModLang('help_param_subcategory'));
        $this->CreateParameter('detailpage', '', $this->ModLang('help_param_detailpage'));
        $this->CreateParameter('summarypage', '', $this->ModLang('help_param_summarypage'));
        $this->CreateParameter('item', 'alias', $this->ModLang('help_param_item'));
        //$this->CreateParameter('pagelimit',         100000,             $this->ModLang('help_param_pagelimit') );
        $this->CreateParameter('pagelimit', $page_limit, $this->ModLang('help_param_pagelimit'));
        $this->CreateParameter('start', 0, $this->ModLang('help_param_start'));
        $this->CreateParameter('search', '', $this->ModLang('help_param_search'));
        $this->CreateParameter('search_*', '', $this->ModLang('help_param_search_'));
        $this->CreateParameter('filter', '', $this->ModLang('help_param_filter'));
        $this->CreateParameter('filterorderby', '', $this->ModLang('help_param_filter_order_by'));
//    $this->CreateParameter('filter',            '',                 '<strong>Deprecated</strong> - ' . $this->ModLang('help_param_filter') );
        $this->CreateParameter('debug', 'false', $this->ModLang('help_param_debug'));

        $this->CreateParameter('collapse', 'false', $this->ModLang('help_param_collapse'));
        $this->CreateParameter('show_items', 'false', $this->ModLang('help_param_show_items'));
        $this->CreateParameter('number_of_levels', '', $this->ModLang('help_param_number_of_levels'));
        $this->CreateParameter('include_items', '', $this->ModLang('help_param_include_items'));
        $this->CreateParameter('exclude_items', '', $this->ModLang('help_param_exclude_items'));

        // Templates
        $this->CreateParameter('template_detail', 'default', $this->ModLang('help_param_detailtemplate'));
        $this->CreateParameter('template_summary', 'default', $this->ModLang('help_param_summarytemplate'));
        $this->CreateParameter('template_search', 'default', $this->ModLang('help_param_searchtemplate'));
        $this->CreateParameter('template_category', 'default', $this->ModLang('help_param_categorytemplate'));
        $this->CreateParameter('template_archive', 'default', $this->ModLang('help_param_categorytemplate'));

        //+Lee
        $this->CreateParameter('fieldid', '', $this->ModLang('help_fieldid'));
        $this->CreateParameter('fieldalias', '', $this->ModLang('help_fieldalias'));
        $this->CreateParameter('fieldval', '', $this->ModLang('help_fieldval'));
        $this->CreateParameter('popular', 'false', $this->ModLang('help_param_popular'));
        $this->CreateParameter('alias', 'category-alias', $this->ModLang('help_param_alias'));

        // SQL filters  
        $this->CreateParameter('filter_year', '', $this->ModLang('help_param_year'));
        $this->CreateParameter('filter_month', '', $this->ModLang('help_param_month'));
        # to be implemented....
        //$this->CreateParameter('filter_active',     TRUE,               $this->ModLang('help_param_active'));
    }

    public function GetHeaderHTML() {
        // SSL check (Hopefully core would do this soon...)
        $use_ssl = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $use_ssl = true;

        $config = cmsms()->GetConfig();
        $smarty = cmsms()->GetSmarty();
        $modulename = $this->GetName();
        $moduledir = $this->GetParentURLPath($use_ssl);
        $globals_js = ($use_ssl ? $config['ssl_url'] : $config['root_url']) . '/modules/' . LISE . '/lib/js/';
        $globals_css = ($use_ssl ? $config['ssl_url'] : $config['root_url']) . '/modules/' . LISE . '/lib/css/';
        $admindir = $config['admin_url'];
        $userkey = get_secure_param();
        $jqueryui = '';

        if (version_compare(CMS_VERSION, '1.11-alpha0') < 0) {
            $jqueryui = '<link type="text/css" rel="stylesheet" href="' . $moduledir . '/css/jquery-ui-1.8.2.custom.css"></link>';
        }

        $smarty->assign('moduledir', $moduledir);
        $smarty->assign('globals_css', $globals_css);
        $smarty->assign('globals_js', $globals_js);
        $smarty->assign('jqueryui', $jqueryui);
        $smarty->assign('admindir', $admindir);
        $smarty->assign('userkey', $userkey);
        $smarty->assign('modulename', $modulename);

        $tmpl = $this->ModProcessTemplate('HTML_Header.tpl');

        $tmpl .= LISEFielddefOperations::GetHeaderHTML();

        return $tmpl;
    }

    public function DoAction($name, $id, $params, $returnid = '') {
        global $CMS_ADMIN_PAGE;

        $config = cmsms()->GetConfig();

        if (lise_utils::isCMS201()) {
            $smarty = $this->GetActionTemplateObject();
        } else {
            $smarty = cmsms()->GetSmarty();
        }

        $db = cmsms()->GetDb();

        $this->LoadModuleHints($params);

        $smarty->assign('mod', $this);
        $smarty->assign('actionid', $id);
        $smarty->assign('returnid', $returnid);

        cms_utils::set_app_data('lise_instance', $this->GetName());

        if (cmsms()->is_frontend_request()) {
            if (isset($params['id_hierarchy'])) {
                cms_utils::set_app_data('lise_id_hierarchy', $params['id_hierarchy']);
                $category = $this->LoadCategoryByIdentifier('id_hierarchy', $params['id_hierarchy']);
                $smarty->assign('category', $category);
                $smarty->assign($this->GetName() . '_category', $category); // <- Alias for $category        
            }
        }

        if ($CMS_ADMIN_PAGE) {
            $themeObject = cms_utils::get_theme_object();
            $smarty->assign('themeObject', $themeObject);
        }

        if ($name != '') {
            $got_contents = false;
            $contents = '';
            $name = preg_replace('/[^A-Za-z0-9\-_+]/', '', $name);

            if ($CMS_ADMIN_PAGE && !($name == 'ajax_geturl' || $name == 'ajax_get_alias'))
                $contents .= '<div class="lise-admin-wrapper">'; // we need to revisit this (JM)



                
// Check if we have an action file
            $filename = cms_join_path(
                    $this->GetModulePath(), 'action.' . $name . '.php'
            );

            if (@is_file($filename) && !$got_contents) {
                ob_start();
                include($filename);
                $contents .= ob_get_contents();
                ob_end_clean();
                $got_contents = true;
            }

            // Check parent if we don't

            $filename = cms_join_path(
                    $this->GetParentPath(), 'action.' . $name . '.php'
            );

            if (@is_file($filename) && !$got_contents) {
                ob_start();
                include($filename);
                $contents .= ob_get_contents();
                ob_end_clean();
                $got_contents = true;
            }

            if ($CMS_ADMIN_PAGE && !($name == 'ajax_geturl' || $name == 'ajax_get_alias'))
                $contents .= '</div>'; // we need to revisit this too (JM)

            echo $contents;
        }
    }

    function SearchReindex($module) {
        LISEItemOperations::reindex_search($module, $this);
    }

    function Upgrade($oldversion, $newversion) {
        $config = cmsms()->GetConfig();
        $smarty = cmsms()->GetSmarty();
        $db = cmsms()->GetDb();

        $response = FALSE;

        $filename = cms_join_path(
                $this->GetParentPath(), 'method.upgradeGlobals.php'
        );

        if (@is_file($filename)) {

            $res = include($filename);

            if (lise_utils::isCMS2()) {
                if ($res == 1 || $res == '') {
                    $response = FALSE;
                } else {
                    $response = $res;
                }
            } else {
                if ($res == 1 || $res == '')
                    $response = TRUE;
            }
        }

        $filename = cms_join_path(
                $this->GetModulePath(), 'method.upgrade.php'
        );

        if (@is_file($filename)) {
            $res = include($filename);

            if (lise_utils::isCMS2()) {
                if ($res == 1 || $res == '') {
                    $response = FALSE;
                } else {
                    $response = $res;
                }
            } else {
                if ($res == 1 || $res == '')
                    $response = TRUE;
            }
        }

        // Check if module in our own control tables yet
        $query = "SELECT module_id FROM " . cms_db_prefix() . "module_lise_instances WHERE module_name = ?";
        $modstatus = $db->GetOne($query, array($this->GetName()));

        if (!$modstatus)
            $this->RegisterModule();

        return $response;
    }

    function Install() {
        $config = cmsms()->GetConfig();
        $smarty = cmsms()->GetSmarty();
        $db = cmsms()->GetDb();

        $response = FALSE;

        $filename = cms_join_path(
                $this->GetParentPath(), 'method.installGlobals.php'
        );

        if (@is_file($filename)) {
            $res = include($filename);

            if ($res == 1 || $res == '') {
                $response = FALSE;
            } else {
                $response = $res;
            }
        }

        $filename = cms_join_path(
                $this->GetModulePath(), 'method.install.php'
        );

        if (@is_file($filename)) {
            $res = include($filename);

            if ($res == 1 || $res == '') {
                $response = FALSE;
            } else {
                $response = $res;
            }
        }

        if (!$response) {
            $this->RegisterModule();
        }

        return $response;
    }

    function Uninstall() {
        $config = cmsms()->GetConfig();
        $smarty = cmsms()->GetSmarty();
        $db = cmsms()->GetDb();

        $response = FALSE;

        $filename = cms_join_path(
                $this->GetParentPath(), 'method.uninstallGlobals.php'
        );

        if (@is_file($filename)) {
            $res = include($filename);

            if ($res == 1 || $res == '') {
                $response = FALSE;
            } else {
                $response = $res;
            }
        }

        $filename = cms_join_path(
                $this->GetModulePath(), 'method.uninstall.php'
        );

        if (@is_file($filename)) {
            $res = include($filename);

            if ($res == 1 || $res == '') {
                $response = FALSE;
            } else {
                $response = $res;
            }
        }

        if (!$response) {

            $this->UnregisterModule();
        }

        return $response;
    }

    #---------------------
    # Manipulation methods
    #---------------------   

    public function ModLang() {
        $mod = cmsms()->GetModuleInstance(LISE);
        if (!is_object($mod))
            return '';
        #throw new \LISE\Exception('Cannot retrive LISE Object.');
        #throw new LISEException('Cannot retrive LISE Object.');

        $mod->LoadLangMethods();

        $args = func_get_args();
        array_unshift($args, '');

        if (lise_utils::isCMS2()) {
            $args[0] = $mod->GetName();
            return CmsLangOperations::lang_from_realm($args);
        } else {
            $args[0] = &$mod;
            return call_user_func_array('cms_module_Lang', $args);
        }
    }

    public function GetParentPath() {
        $config = cmsms()->GetConfig();
        return cms_join_path($config['root_path'], 'modules', LISE, 'framework');
    }

    public function GetParentURLPath($use_ssl = false) {
        $config = cmsms()->GetConfig();
        return ($use_ssl ? $config['ssl_url'] : $config['root_url']) . '/modules/' . LISE . '/framework';
    }

    #---------------------
    # Instance methods
    #---------------------     

    final private function RegisterModule() {
        $db = cmsms()->GetDb();
        $query = 'INSERT INTO ' . cms_db_prefix() . 'module_lise_instances (module_name) VALUES (?)';
        $result = $db->Execute($query, array($this->GetName()));

        //if (!$result) die('FATAL SQL ERROR: ' . $db->ErrorMsg() . '<br/>QUERY: ' . $db->sql); // TODO: exceptions
        if (!$result)
            throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() . ' - Query: ' . $db->sql, \LISE\Error::DISCRETE_DB);

        return true;
    }

    final private function UnregisterModule() {
        $db = cmsms()->GetDb();

        $query = 'DELETE FROM ' . cms_db_prefix() . 'module_lise_instances WHERE module_name = ?';
        $result = $db->Execute($query, array($this->GetName()));

        if (!$result)
            throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() . ' - Query: ' . $db->sql, \LISE\Error::DISCRETE_DB);

        return true;
    }

}

// end of class
