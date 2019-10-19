<?php
#BEGIN_LICENSE
#-------------------------------------------------------------------------
# Module: CGExtensions (c) 2008-2014 by Robert Campbell
#         (calguy1000@cmsmadesimple.org)
#  An addon module for CMS Made Simple to provide useful functions
#  and commonly used gui capabilities to other modules.
#
#-------------------------------------------------------------------------
# CMSMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# Visit the CMSMS Homepage at: http://www.cmsmadesimple.org
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple.  You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------
#END_LICENSE

/**
 * A base class for all CMSMS modules written by me to provide optimizations and
 * conveniences that are not built into CMSMS.
 *
 * Some of these functions and tools have, over time made their way in one way, shape
 * or form into the core.  However, many have not, and will not.
 *
 * This module provides nuermous utility functions and classes for developing modules and some higher level functionality
 * including:
 *   lookup tables with classes and interfaces
 *   sortable list API functions with UI
 *   template driven email processing
 *   Advanced reporting mechanisms.
 *
 * @package CGExtensions
 */

declare(strict_types=1);
use CGExtensions\internal_utils;
use CGExtensions\internals;
use CGExtensions\internal\settings;
use CGExtensions\content_list_builder;
use CGExtensions\jsloader\jsloader;
use CGExtensions\Email\Email;
use CGExtensions\Email\IEmailStorage;
use CGExtensions\Email\FileEmailStorage;
use CGExtensions\Email\EmailProcessor;
use CGExtensions\Email\SimpleEmailProcessor;
use CGExtensions\FileTemplateResource;
use CGExtensions\smarty_plugins;
use CGExtensions\HourlyHookTask;
use CMSMS\Database\Connection as Database;
use CMSMS\Database\compatibility as db_compatibility;
use CMSMS\HookManager;

if( defined('CGEXTENSIONS_TABLE_COUNTRIES') ) return;

/**
 * @ignore
 */
define('CGEXTENSIONS_TABLE_COUNTRIES',cms_db_prefix().'module_cge_countries');

/**
 * @ignore
 */
define('CGEXTENSIONS_TABLE_STATES',cms_db_prefix().'module_cge_states');

/**
 * @ignore
 */
define('CGEXTENSIONS_TABLE_ASSOCDATA',cms_db_prefix().'module_cge_assocdata');

function __cge_unserialize_callback(string $classname) {
    throw new \RuntimeException('Cannot unserialize object of type '.$classname);
}

/**
 * A base class for all CMSMS modules written by me to provide optimizations and
 * conveniences that are not built into CMSMS.
 *
 * @package CGExtensions
 */
class CGExtensions extends CMSModule
{
    /**
     * @ignore
     */
    private static $_initialized;

    /**
     * @ignore
     */
    public $_actionid = null;

    /**
     * @ignore
     */
    public $_actionname = null;

    /**
     * @ignore
     */
    public $_image_directories;

    /**
     * @ignore
     */
    public $_current_action;

    /**
     * @ignore
     */
    public $_errormsg;

    /**
     * @ignore
     */
    public $_returnid;

    /**
     * The constructor.
     * This method does numerous things, including setup an extended autoloader,
     * create defines for the module itself.  i.e: MOD_CGEXTENSIONS, or MOD_FRONTENDUSERS.
     * sets up a built in cache driver for temporarily caching data.
     * and register numerous smarty plugins (see the documentation for those).
     */
    public function __construct()
    {
        spl_autoload_register(array($this,'autoload'));
        parent::__construct();

        global $CMS_INSTALL_PAGE, $CMS_PHAR_INSTALL;
        if( isset($CMS_INSTALL_PAGE) || isset($CMS_PHAR_INSTALL) ) return;

        $class = get_class($this);
        if( !defined('MOD_'.strtoupper($class)) ) {
            /**
             * @ignore
             */
            define('MOD_'.strtoupper($class),$class);
        }

        if( self::$_initialized || $class != 'CGExtensions' ) return;
        self::$_initialized = TRUE;

        ini_set('unserialize_callback_func','__cge_unserialize_callback');

        //
        // from here down only happens once per request (for CGExtensions only)
        //

        // allthough this hook is added relatively early in the request process, we want to ensure that it is executed
        // as late as possible.
        HookManager::add_hook('Core::ContentPostRender', [$this, 'onContentPostRender'], HookManager::PRIORITY_LOW );

        $smarty = $this->cms->GetSmarty();
        if( !$smarty ) return;

        static $_utils;
        $_utils = new cge_utils($this, $this->cge_settings());

        static $_image;
        $_image = new cge_image($this->cge_settings()->thumbnailsize);

        static $_setup;
        $_setup = new cge_setup($this->config, $this->cge_settings(), $this->GetModulePath());

        $smarty->register_resource( 'cg_modfile', new FileTemplateResource() );
        static $_plugins;
        if( !$_plugins ) $_plugins = new smarty_plugins( $this, $smarty );

        $db = cms_utils::get_db();
        if( is_object($db) ) {
            $query = 'SET @CG_ZEROTIME = NOW() - INTERVAL 150 YEAR,@CG_FUTURETIME = NOW() + INTERVAL 5 YEAR';
            $db->Execute($query);
            $config = $this->config;
            if( cge_param::get_bool($config,'cge_relax_sql') ) {
                $query = 'SET SESSION sql_mode=\'TRADITIONAL\'';
                $db->Execute($query);
            }
        }

    }

    /**
     * An extended autoload method.
     * Search for classes a <module>/lib/class.classname.php file.
     * or for interfaces in a <module>/lib/interface.classname.php file.
     * or as a last ditch effort, for simple classes in the <module>/lib/extraclasses.php file.
     * This method also supports namespaces,  including <module> and <module>/sub1/sub2 which should exist in files as described above.
     * in subdirectories below the <module>/lib directory.
     *
     * @internal
     * @param string $classname
     */
    public function autoload($classname)
    {
        if( !is_object($this) ) return FALSE;

        // check for classes.
        $path = $this->GetModulePath().'/lib';
        if( strpos($classname,'\\') !== FALSE ) {
            $t_path = str_replace('\\','/',$classname);
            if( startswith( $t_path, $this->GetName().'/' ) ) {
                $classname = basename($t_path);
                $t_path = dirname($t_path);
                $t_path = substr($t_path,strlen($this->GetName())+1);
                $path = $this->GetModulePath().'/lib/'.$t_path;
            }
        }

        $fn = $path."/class.{$classname}.php";
        if( is_file($fn) ) {
            require_once($fn);
            return TRUE;
        }

        // check for abstract classes.
        $fn = $path."/abstract.{$classname}.php";
        if( is_file($fn) ) {
            require_once($fn);
            return TRUE;
        }

        // check for interfaces
        $fn = $path."/interface.{$classname}.php";
        if( is_file($fn) ) {
            require_once($fn);
            return TRUE;
        }

        // check for traits
        $fn = $path."/trait.{$classname}.php";
        if( is_file($fn) ) {
            require_once($fn);
            return TRUE;
        }

        // check for a master file
        $fn = $this->GetModulePath()."/lib/extraclasses.php";
        if( is_file($fn) ) {
            require_once($fn);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @ignore
     */
    public function InitializeFrontend()
    {
        parent::InitializeFrontend();
        $this->SetParameterType('cge_msg',CLEAN_STRING);
        $this->SetParameterType('cge_msgkey',CLEAN_STRING);
        $this->SetParameterType('cge_error',CLEAN_INT);
        $this->SetParameterType('nocache',CLEAN_INT);
        $this->SetParameterType('cg_activetab',CLEAN_STRING);
        $this->SetParameterType('_d', CLEAN_STRING );
        if( get_class($this) != 'CGExtensions' ) return;
        $this->RestrictUnknownParams();
    }

    /**
     * @ignore
     * @deprecated
     */
    protected function get_utils() : internal_utils
    {
        static $_obj;
        if( !$_obj ) $_obj = new internal_utils($this->get_cge());
        return $_obj;
    }


    /**
     * @ignore
     * @deprecated
     */
    private function _load_form()
    {
        require_once(__DIR__.'/form_tools.php');
    }

    /**
     * The Friendly name for this module.  For use in the admin navigation.
     *
     * @see CMSModule::GetFriendlyName()
     * @abstract
     * @return string
     */
    public function GetFriendlyName()
    {
        if( CmsLangOperations::lang_key_exists($this->GetName(),'friendlyname') ) return $this->Lang('friendlyname');
        return parent::GetFriendlyName();
    }

    /**
     * Return the version of this module.
     *
     * @see CMSModule::GetVersion()
     * @abstract
     * @return string
     */
    public function GetVersion() {
        if( get_class($this) == 'CGExtensions' ) return '1.64.10';
        $str = parent::GetVersion();
        return $str;
    }

    /**
     * Return the help of this module.
     * This method will look in a series of files to try to find the help for a module.
     *    doc/help.inc, docs/help.inc, doc/help.html, docs/help.html, help.inc and help.html in that order
     * This method will also try to find API documentation HTML files in numerous directories:
     *    doc/apidoc, doc/apidocs, docs/apidoc and docs/apidocs in that order.
     * If API documentation can be found, then the top of the help will be modified to include a link to the API docs.
     *
     * @see CMSModule::GetHelp()
     * @abstract
     * @return string
     */
    public function GetHelp() {
        $dir = $this->GetModulePath();
        $out = '';
        $fns1 = array('doc/help.inc','docs/help.inc','doc/help.html','docs/help.html','help.inc','help.html');
        foreach( $fns1 as $p1 ) {
            $test = cms_join_path($dir,$p1);
            if( is_file($test) ) {
                $out = file_get_contents($test);
                break;
            }
        }

        // check if we have api documentation and we are not generating an XML document.
        global $CMSMS_GENERATING_XML;
        if( !isset($CMSMS_GENERATING_XML) ) {
            $cge = $this->get_cge();
            $txt = '** Help me to help you, with a small donation to support my ongoing efforts.';
            $out = '<p><a href="https://paypal.me/calguy1000/20">'.$txt.'</a></p><hr/>'. $out;
            $fns1 = array('doc/apidoc','doc/apidocs','docs/apidoc','docs/apidocs');
            foreach( $fns1 as $p1 ) {
                $test = cms_join_path($dir,$p1);
                if( is_dir($test) ) {
                    $url = $this->GetModuleURLPath()."/$p1";
                    $lbl = $cge->Lang('view_api_docs');
                    $out = "<p><a href=\"$url\">$lbl</a></p>" . $out;
                    break;
                }
            }

        }

        return $out;
    }

    /**
     * Return the Author of this module.
     *
     * @see CMSModule::GetAuthor()
     * @abstract
     * @return string
     */
    public function GetAuthor() { return 'calguy1000'; }

    /**
     * Return the email address for the author of this module.
     *
     * @see CMSModule::GetAuthorEmail()
     * @abstract
     * @return string
     */
    public function GetAuthorEmail() { return 'calguy1000@cmsmadesimple.org'; }

    /**
     * Return the changelog for this module.
     *
     * @see CMSModule::GetChangeLog()
     * @abstract
     * @return string
     */
    public function GetChangeLog()
    {
        $dir = $this->GetModulePath();
        $files = array('docs/changelog.inc','doc/changelog.inc','docs/changelog.html','doc/changelog.html','changelog.inc');
        foreach( $files as $file ) {
            $fn = cms_join_path($dir,$file);
            if( is_file($fn) ) return file_get_contents($fn);
        }
    }

    /**
     * Return if this is a plugin module (for the frontend of the website) or not.
     *
     * @see CMSModule::IsPluginModule()
     * @abstract
     * @return bool
     */
    public function IsPluginModule() {
        if( get_class($this) == MOD_CGEXTENSIONS ) return true;
        return parent::IsPluginModule();
    }

    /**
     * Return if this module has an admin section.
     *
     * @see CMSModule::HasAdmin()
     * @abstract
     * @return string
     */
    public function HasAdmin() {
        if( get_class($this) == MOD_CGEXTENSIONS ) return true;
        return parent::HasAdmin();
    }

    /**
     * @ignore
     */
    function HasCapability($capability,$params = array())
    {
        if( get_class($this) != MOD_CGEXTENSIONS ) return false;
        switch( $capability ) {
        case 'tasks':
            return true;
        }
        return false;
    }

    /**
     * @ignore
     */
    public function get_tasks()
    {
        if( get_class($this) != MOD_CGEXTENSIONS ) return;
        $out = null;
        $out[] = new HourlyHookTask();
        return $out;
    }

    /**
     * Get the section of the admin navigation that this module belongs to.
     *
     * @abstract
     * @return string
     */
    public function GetAdminSection()
    {
        $name = strtolower($this->GetName().'_adminsection');
        return cge_param::get_string($this->config,$name,'extensions');
    }

    /**
     * Get a human readable description for this module.
     *
     * @abstract
     * @return string
     */
    public function GetAdminDescription() {
        if( get_class($this) == MOD_CGEXTENSIONS ) return $this->Lang('moddescription');
        return parent::GetAdminDescription();
    }

    /**
     * Get a hash containing dependent modules, and their minimum versions.
     *
     * @abstract
     * @return string
     */
    public function GetDependencies() { return []; }

    /**
     * Display a custom message after the module has been installed.
     *
     * @abstract
     * @return string
     */
    public function InstallPostMessage() {
        if( get_class($this) == MOD_CGEXTENSIONS ) return $this->Lang('postinstall');
        return parent::InstallPostMessage();
    }

    /**
     * Return the minimum CMSMS version that this module is compatible with.
     *
     * @abstract
     * @return string
     */
    public function MinimumCMSVersion() { return '2.2.9'; }

    /**
     * Return a message to display after the module has been uninstalled.
     *
     * @abstract
     * @return string
     */
    public function UninstallPostMessage() {
        if( get_class($this) == MOD_CGEXTENSIONS ) return $this->Lang('postuninstall');
        return parent::UninstallPostMessage();
    }

    /**
     * Test if this module is visible in the admin navigation to the currently logged in admin user.
     *
     * @abstract
     * @return bool
     */
    public function VisibleToAdminUser()
    {
        if( get_class($this) == MOD_CGEXTENSIONS ) return $this->CheckPermission('Modify Site Preferences') ||  $this->CheckPermission('Modify Templates');
        return parent::VisibleToAdminUser();
    }

    /**
     * Retrieve some HTML to be output in all admin requests for this module (and its descendants).
     * By default this module calls the jsloader::render method,  and includes some standard styles
     * If a file entitled css/admin_styles.css exists in a derived module's root directory then this stylesheet
     * will be included in the header html.
     *
     * @abstract
     * @see \CGExtensions\jsloader\jsloader::render();
     * @return bool
     */
    public function GetHeaderHTML()
    {
        $mod = $this->get_cge();
        $file = $mod->find_module_file('js/common.js');
        if( $file ) {
            $out = $mod->get_jsloader()->add_jsfile($file);
        }
        $out = $mod->get_jsloader()->render(['addkey'=>session_id()]);
        $fn = $mod->find_module_file('css/admin_styles.css');
        if( $fn ) {
            $css = str_replace(CMS_ROOT_PATH,CMS_ROOT_URL,$fn);
            $out .= '<link rel="stylesheet" href="'.$css.'"/>'."\n";
        }
        if( $this->GetName() != MOD_CGEXTENSIONS ) {
            $fn = $this->find_module_file('css/admin_styles.css');
            if( $fn ) {
                $css = str_replace(CMS_ROOT_PATH,CMS_ROOT_URL,$fn);
                $out .= '<link rel="stylesheet" href="'.$css.'"/>'."\n";
            }
        }
        return $out;
    }

    /**
     * A replacement for the built in DoAction method
     * For CGExtensions derived modules some  builtin smarty variables are created
     * module hints are handled,  and input type=image values are corrected in input parameters.
     *
     * this method also handles setting the active tab, and displaying any messages or errors
     * set with the SetError or SetMessage methods.
     *
     * This method is called automatically by the system based on the incoming request, and the page template.
     * It should almost never be called manually.
     *
     * @see SetError()
     * @see SetMessage()
     * @see SetCurrentTab()
     * @see RedirectToTab()
     * @param string $name the action name
     * @param string $id The module action id
     * @param array  $params The module parameters
     * @param int    $returnid  The page that will contain the HTML results.  This is empty for admin requests.
     */
    public function DoAction($name,$id,$params,$returnid='')
    {
        $this->set_action_id($id);
        $this->set_action_name($name);
        $active_tab = cge_param::get_string( $params, 'cg_activetab' );
        if( $active_tab ) $this->SetCurrentTab( $active_tab );

        // handle the stupid input type='image' problem.
        foreach( $params as $key => $value ) {
            if( endswith($key,'_x') ) {
                $base = substr($key,0,strlen($key)-2);
                if( isset($params[$base.'_y']) && !isset($params[$base]) ) $params[$base] = $base;
            }
        }

        // handle module hints
        $hints = cms_utils::get_app_data('__MODULE_HINT__'.$this->GetName());
        if( is_array($hints) ) {
            foreach( $hints as $key => $value ) {
                if( isset($params[$key]) ) continue;
                $params[$key] = $value;
            }
        }

        // redundant for cmsms 2.0+
        $smarty = $this->GetActionTemplateObject();
	if( !$smarty ) $smarty = $this->cms->GetSmarty();
        $smarty->assign('actionid',$id);
        $smarty->assign('actionparams',$params);
        $smarty->assign('returnid',$returnid);
        $smarty->assign('mod',$this);
        $smarty->assign('actionname',$name);
        $smarty->assign($this->GetName(),$this);
        cge_tmpdata::set('module',$this->GetName());

        parent::DoAction($name,$id,$params,$returnid);
    }


    /**
     * A convenience method to encrypt some data
     *
     * @see cge_encrypt
     * @param string $key The encryption key
     * @param string $data The data to encrypt
     * @return string The encrypted data
     */
    function encrypt(string $key,$data)
    {
        return cge_encrypt::encrypt($key,$data);
    }


    /**
     * A convenience method to decrypt some data
     *
     * @see cge_encrypt
     * @param string $key The encryption key
     * @param string $data The data to decrypt
     * @return string The derypted data
     */
    function decrypt(string $key,string $data)
    {
        return cge_encrypt::decrypt($key,$data);
    }

    /**
     * A convenience function to create a url for a module action.
     * This method is deprecated as the CMSModule::create_url method replaces it.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $action The module action
     * @param string $returnid The page that the url will refer to.  This is empty for admin requests
     * @param array  $params Module parameters
     * @param bool   $inline For frontend requests only dicates wether this url should be inline only.
     * @param string $prettyurl
     */
    function CreateURL($id,$action,$returnid,$params=array(),$inline=false,$prettyurl='')
    {
        trigger_error('Deprecated usage of '.__METHOD__);
        return $this->create_url($id, $action, $returnid, $params,$inline,false,$prettyurl);
    }


    /* ======================================== */
    /* FORM FUNCTIONS                           */
    /* ======================================== */

    /**
     * A convenience method to create a control that contains a 'sortable list'.
     * The output control is translated, and interactive and suitable for use in forms.
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name The input element name
     * @param array $items An associative array of the items for this list.
     * @param string $selected A comma separated string of selected item keys
     * @param bool $allowduplicates
     * @param int $max_selected The maximum number of items that can be selected
     * @param string $template Specify an alternate template for the sortable list control
     * @param string $label_left  A label for the left column.
     * @param string $label_right A label for the right column.
     * @return string
     */
    function CreateSortableListArea($id,$name,$items, $selected = '', $allowduplicates = true, $max_selected = -1,
                                    $template = '', $label_left = '', $label_right = '')
    {
        $cge = $this->get_cge();
        if( empty($label_left) ) $label_left = $cge->Lang('selected');
        if( empty($label_right) ) $label_right = $cge->Lang('available');
        if( !$template ) $template = 'sortablelist.tpl';

        $tpl = $this->CreateSmartyTemplate($template);
        $tpl->assign('selectarea_selected_str',null);
        $tpl->assign('selectarea_selected',[]);
        if( !empty($selected) ) {
            $sel = explode(',',$selected);
            $tmp = [];
            foreach($sel as $theid) {
                if( array_key_exists($theid,$items) ) $tmp[$theid] = $items[$theid];
            }
            $tpl->assign('selectarea_selected_str',$selected);
            $tpl->assign('selectarea_selected',$tmp);
        }
        $tpl->assign('cge',$cge);
        $tpl->assign('max_selected',$max_selected);
        $tpl->assign('label_left',$label_left);
        $tpl->assign('label_right',$label_right);
        $tpl->assign('selectarea_masterlist',$items);
        $tpl->assign('selectarea_prefix',$id.$name);
        if( $allowduplicates ) $allowduplicates = 1; else $allowduplicates = 0;
        $tpl->assign('allowduplicates',$allowduplicates);
        $tpl->assign('upstr',$cge->Lang('up'));
        $tpl->assign('downstr',$cge->Lang('down'));
        return $tpl->fetch();
    }


    /**
     * Create a translated Yes/No dropdown.
     * The output control is translated, and suitable for use in forms.
     * This method is deprecated.  It is best to assign all data to smarty and then create input elements as necessary in the smarty template.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $name The name for the input element
     * @param int    $selectedvalue The selected value (0 == no, 1 == yes)
     * @param string $addtext
     * @return string
     */
    function CreateInputYesNoDropdown($id,$name,$selectedvalue='',$addtext='')
    {
        return $this->get_utils()->cge_CreateInputYesNoDropdown($id,$name,$selectedvalue,$addtext);
    }

    /**
     * Create a custom submit button.
     * The output control is translated, and suitable for use in forms.
     * This method is deprecated.  It is best to assign all data to smarty and then create input elements as necessary in the smarty template.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $name The name for the input element
     * @param string $value The value for the submit button
     * @param string $addtext Additional text for the tag
     * @param string $image an optional image path
     * @param string $confirmtext Optional confirmation text
     * @param string $class Optional value for the class attribute
     * @return string
    function CGCreateInputSubmit($id,$name,$value='',$addtext='',$image='', $confirmtext='',$class='')
    {
        $this->_load_form();
        return cge_CreateInputSubmit($this,$id,$name,$value,$addtext,$image,$confirmtext,$class);
    }
     */


    /**
     * Create a custom checkbox.
     * This is similar to the standard checkbox but has a hidden field with the same name
     * before it so that some value for this field is always returned to the form handler.
     * This method is deprecated.  It is best to assign all data to smarty and then create input elements as necessary in the smarty template.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $name The name for the input element
     * @param string $value The value for the checkbox
     * @param string $selectedvalue The current value of the field.
     * @param string $addtext Additional text for the tag
     * @return string
     */
    function CreateInputCheckbox($id,$name,$value='',$selectedvalue='', $addtext='')
    {
        @trigger_error('Deprecated usage of '.__METHOD__);
        $this->_load_form();
        return cge_CreateInputCheckbox($this,$id,$name,$value,$selectedvalue,$addtext);
    }


    /**
     * A Convenience function for creating form tags.
     * This method re-organises some of the parameters of the original CreateFormStart method
     * and handles current tab functionalty, and sets the encoding type of the form to multipart/form-data
     *
     * This method is deprecated and will be replaced in CMSMS 2.0 by the core {form_start} tag.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $action the destination action
     * @param string $returnid The destination pagpe for the action handler.  Empty for admin requests
     * @param array  $params additional parameters to be passed with the form
     * @param bool   $inline wether this is an inline form request (output will replace module tag rather than the entire content section of the template.
     * @param string $method The form method.
     * @param string $enctype The form encoding type
     * @param string $idsuffix
     * @param string $extra Extra text for thhe form tag
     * @return string
     */
    function CGCreateFormStart($id,$action='default',$returnid='',$params=array(),$inline=false,$method='post',
                               $enctype='',$idsuffix='',$extra='')
    {
        @trigger_error('Deprecated usage of '.__METHOD__);
        if( $enctype == '' ) $enctype = 'multipart/form-data';
        return $this->CreateFormStart($id,$action,$returnid,$method,$enctype,$inline,$idsuffix,$params,$extra);
    }


    /**
     * A convenience function for creating a frontend form
     * This method re-organises some of the parameters of the original CreateFormStart method
     * and sets the encoding type of the form to multipart/form-data
     *
     * This method is deprecated and will be replaced in CMSMS 2.0 by the core {form_start} tag.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $action the destination action
     * @param string $returnid The destination pagpe for the action handler.  Empty for admin requests
     * @param array  $params additional parameters to be passed with the form
     * @param bool   $inline wether this is an inline form request (output will replace module tag rather than the entire content section of the template.
     * @param string $method The form method.
     * @param string $enctype The form encoding type
     * @param string $idsuffix
     * @param string $extra Extra text for thhe form tag
     * @return string
     */
    function CGCreateFrontendFormStart($id,$action='default',$returnid='', $params=array(),$inline=true,$method='post',
                                       $enctype='',$idsuffix='',$extra='')
    {
        @trigger_error('Deprecated usage of '.__METHOD__);
        $this->_load_form();
        return $this->CreateFrontendFormStart($id,$returnid,$action,$method,$enctype,$inline,$idsuffix,$params,$extra);
    }


    /**
     * A convenience method to create a hidden input element for forms.
     * This method is deprecated.  It is best to assign all data to smarty and then create input elements as necessary in the smarty template.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $name The name of the input element
     * @param string $value The value of the input element
     * @param string $addtext Additional text for the tag
     * @param string $delim the delimiter for value separation.
     * @return string
     */
    function CreateInputHidden($id,$name,$value='',$addtext='',$delim=',')
    {
        @trigger_error('Deprecated usage of '.__METHOD__);
        $this->_load_form();
        return cge_CreateInputHidden($this,$id,$name,$value,$addtext,$delim);
    }


    /**
     * For admin requests only, pass variables so that the specified tab will be displayed
     * by default in the resulting action.
     *
     * @param string $ignored (old actionid param)
     * @param string $tab The name of the parameter
     * @param string $params Extra parameters for the request
     * @param string $action The designated module action.  If none is specified 'defaultadmin' is assumed.
     * @return void
     */
    function RedirectToTab( $ignored = '', $tab = '', $params = '', $action = '' )
    {
        if( !$action ) $action = $this->_current_action;
        $this->RedirectToAdminTab( $tab, $params, $action );
    }

    /**
     * @ignore
     */
    function CGRedirect($id,$action,$returnid='',$params=array(),$inline = false)
    {
        $this->Redirect($id,$action,$returnid,$params,$inline);
    }

    /**
     * Test if the current request is an admin request.
     *
     * @deprecated
     * @return bool True for an admin action, false otherwise.
     */
    function IsAdminAction()
    {
        $gCms = $this->cms;
        if( $gCms->test_state(CmsApp::STATE_ADMIN_PAGE) && !$gCms->test_state(CmsApp::STATE_INSTALL) &&
            !$gCms->test_state(CmsApp::STATE_STYLESHEET) ) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Set the current action for the next request of the admin console.
     * Used for the various admin forms.
     *
     * @param string $action The action name
     */
    function SetCurrentAction(string $action)
    {
        $action = trim($action);
        $this->_current_action = $action;
    }


    /**
     * A function for using a template to display an error message.
     * This method is suitable for frontend displays.
     *
     * @deprecated
     * @param string $txt The error message
     * @param string $class An optional class attribute value.
     */
    function DisplayErrorMessage(string $txt,string $class = 'alert alert-danger')
    {
        $mod = $this->get_cge();
        $tpl = $mod->CreateSmartyTemplate('error.tpl');
        $tpl->assign('cg_errorclass',$class);
        $tpl->assign('cg_errormsg',$txt);
        return $tpl->fetch();
    }


    /**
     * Get the CGExtensions module
     */
    final protected function get_cge() : CGExtensions
    {
        static $_obj;
	if( !$_obj ) {
		$_obj = $this->GetModuleInstance('CGExtensions');
		if( !$_obj ) throw new \RuntimeException('CGExtensions module not loadable.. does it need an upgrade?');
	}
        return $_obj;
    }

    /**
     * Get a settings object
     *
     * @return settings
     */
    protected function cge_settings() : settings
    {
        static $_obj;
        if( !$_obj ) $_obj = new settings($this->config);
        return $_obj;
    }

    /**
     * A function to return an array of of country codes and country names.
     * i.e:  array( array('code'=>'AB','name'=>'Alberta'), array('code'=>'MB','code'=>'Manitoba'));
     *
     * @deprecated
     * @return array|null
     */
    public function get_state_list()
    {
        $list = $this->get_state_list_options();
        if( !$list ) return;
        $out = null;
        foreach( $list as $key => $val ) {
            $out[] = [ 'code'=>$key, 'name'=>$val ];
        }
        return $out;
    }


    /**
     * A function to return an array of of country codes and country names.
     * This method returns data that is suitable for use in a list.
     * i.e:  array( array('code'=>'AB','name'=>'Alberta'), array('code'=>'MB','code'=>'Manitoba'));
     *
     * @return array|null
     */
    public function get_state_list_options()
    {
        $cge = $this->get_cge();
        $fn = $cge->find_module_file('/etc/states.json');
        if( $fn ) return json_decode(file_get_contents($fn), TRUE);
    }


    /**
     * A convenience function to create a state dropdown list.
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name the name for the dropdown.
     * @param string $value The initial value for the dropdown.
     * @param mixed $selectone  If true, then a hardcoded "Select One" string will be prepended to the list.  If a string then that string will be used.
     * @param string $addtext Additional text for the select tag.
     * @return string
     */
    function CreateInputStateDropdown($id,$name,$value='AL',$selectone=false,$addtext='')
    {
	trigger_error('Deprecated usage of '.__METHOD__);
        $states = $this->get_state_list_options();
        if( !$states ) $states = [];
        if( is_bool($selectone) && $selectone ) {
            $cge = $this->get_cge();
            $selectone = $cge->Lang('select_one');
        }
        $states = array_merge( [''=>$selectone], $states);
        return $this->CreateInputDropdown($id,$name,array_flip($states),-1,strtoupper($value),$addtext);
    }


    /**
     * A function to return an array of of country codes and country names.
     * i.e:  array( array('code'=>'US','name'=>'United States'), array('code'=>'CA','code'=>'Canada'));
     *
     * @deprecated
     */
    public function get_country_list()
    {
        $list = $this->get_country_list_options();
        if( !$list ) return;
        $out = null;
        foreach( $list as $key => $val ) {
            $out[] = [ 'code'=>$key, 'name'=>$val ];
        }
        return $out;
    }


    /**
     * A function to return an array of of country codes and country names.
     * This method returns data suitable for giving to smarty and displaying in a dropdown.
     *
     * @return array|null
     */
    public function get_country_list_options()
    {
        $cge = $this->get_cge();
        $fn = $cge->find_module_file('etc/countries.json');
        if( $fn ) return json_decode(file_get_contents($fn), TRUE);
    }


    /**
     * Get a list of currency codes and thei names.
     *
     * @return array|null
     */
    public function get_currency_list_options()
    {
        $cge = $this->get_cge();
        $fn = $cge->find_module_file('etc/currencies.json');
        if( $fn ) return json_decode(file_get_contents($fn), TRUE);
    }


    /**
     * A convenience function to create a country dropdown list
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name the name for the dropdown.
     * @param string $value The initial value for the dropdown.
     * @param mixed $selectone  If true, then a hardcoded "Select One" string will be prepended to the list.  If a string then that string will be used.
     * @param string $addtext Additional text for the select tag.
     * @return string
     */
    function CreateInputCountryDropdown($id,$name,$value='US',$selectone=false,$addtext='')
    {
	trigger_error('Deprecated usage of '.__METHOD__);
        $countries = $this->get_country_list_options();
        if( !$countries ) $countries = [];
        if( is_bool($selectone) && $selectone ) {
            $cge = $this->get_cge();
            $selectone = $cge->Lang('select_one');
        }
        $countries = array_merge( [''=>$selectone], $countries);
        return $this->CreateInputDropdown($id, $name, array_flip($countries), -1, strtoupper($value), $addtext);
    }


    /**
     * A convenience function to get the country name given the acronym
     *
     * @param string $the_acronym
     * @return string
     */
    function GetCountry(string $the_acronym)
    {
        $list = $this->get_country_list_options();
        return $list[$the_acronym] ?? null;
    }


    /**
     * A convenience function to get the state name given the acronym
     *
     * @param string $the_acronym
     * @return string
     */
    function GetState(string $the_acronym)
    {
        $list = $this->get_state_list_options();
        return $list[$the_acronym] ?? null;
    }


    /**
     * A convenience function to create an image dropdown from all of the image files in a specified directory.
     * This method will not ignore thumbnails.
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name the name for the dropdown.
     * @param string $selectedfile The initial value for the dropdown (an image filename)
     * @param string $dir The path (relative to the uploads path) to the directory to pull images from.  If not specified, the image uploads path will be used.
     * @param mixed  $none  If true, then 'None' will be prepended to the list of output images.  If a string it's value will be used.
     * @return string.
     */
    function CreateImageDropdown($id,$name,$selectedfile,$dir = '',$none = '')
    {
        trigger_error('Deprecated usage of '.__METHOD__);
        $config = $this->config;
        if( startswith( $dir, '.' ) ) $dir = '';
        if( $dir == '' ) $dir = $config['image_uploads_path'];
        if( !is_dir($dir) ) $dir = cms_join_path($config['uploads_path'],$dir);

        $extensions = $this->cge_settings()->imageextensions;
        $filelist = cge_dir::get_file_list($dir,$extensions);
        if( $none ) {
            if( !is_string($none) ) {
                $cge = $this->get_cge();
                $none = $cge->Lang('none');
            }
            $filelist = array_merge(array($none=>''),$filelist);
        }
        return $this->CreateInputDropdown($id,$name,$filelist,-1,$selectedfile);
    }


    /**
     * A convenience function to create a list of filenames in a specified directory.
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name the name for the dropdown.
     * @param string $selectedfile The initial value for the dropdown (an image filename)
     * @param string $dir The path (relative to the uploads path) to the directory to pull images from.  If not specified, the image uploads path will be used.
     * @param string $extensions A comma separated list of filename extensions to include in the list.  If not specified the module preference will be used.
     * @param bool   $allownone Allow no files to be selected.
     * @param bool   $allowmultiple To allow selecting multiple files.
     * @param int    $size The size of the dropdown.
     * @return string.
     */
    function CreateFileDropdown($id,$name,$selectedfile='',$dir = '',$extensions = '',$allownone = '',$allowmultiple = false,$size = 3)
    {
        trigger_error('Deprecated usage of '.__METHOD__);
        $config = $this->config;
        if( $dir == '' ) {
            $dir = $config['uploads_path'];
        }
        else {
            while( startswith($dir,'/') && $dir != '' ) $dir = substr($dir,1);
            $dir = $config['uploads_path'].$dir;
        }

        $tmp = cge_dir::get_file_list($dir,$extensions);
        $tmp2 = [];
        if( !empty($allownone) ) {
            $cge = $this->get_cge();
            $tmp2[$cge->Lang('none')] = '';
        }
        $filelist = array_merge($tmp2,$tmp);

        if( $allowmultiple ) {
            if( !endswith($name,'[]') ) $name .= '[]';
            return $this->CreateInputSelectList($id,$name,$filelist,[],$size);
        }
        return $this->CreateInputDropdown($id,$name,$filelist,-1,$selectedfile);
    }


    /**
     * A convenience function to create a color selection dropdown
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name the name for the dropdown.
     * @param string $selectedvalue The initial value for the input field.
     * @return string
     */
    function CreateColorDropdown($id,$name,$selectedvalue='')
    {
	trigger_error('Deprecated usage of '.__METHOD__);
        $this->_load_form();
        $cgextensions = $this->get_cge();
        return cge_CreateColorDropdown($cgextensions,$id,$name,$selectedvalue);
    }

    /* ======================================== */
    /* IMAGE FUNCTIONS                         */
    /* ======================================== */

    /**
     * @ignore
     */
    function TransformImage($srcSpec,$destSpec,$size='')
    {
        return cge_image::transform_image($srcSpec,$destSpec,$size);
    }

    /**
     * A convenience method to create an image tag.
     * This method will automatically search through added image dirs for frontend and admin requests
     * and through the admin theme directories for admin requests.
     *
     * @deprecated
     * @see cge_tags::create_image
     * @see AddImageDir.
     * @param string $id The module action id
     * @param string $alt The alt attribute for the tag
     * @param int $width Width in pixels
     * @param int $height Height in pixels
     * @param string $class Value for the class attribute
     * @param string $addtext Additional text for the img tag.
     * @return string
     */
    function CreateImageTag(string $id,string $alt='',int $width=null,int $height=null,string $class='', string $addtext='')
    {
	trigger_error('Deprecated usage of '.__METHOD__);
        return $this->get_utils()->CreateImageTag($id,$alt,$width,$height,$class,$addtext);
    }


    /**
     * A convenience method to display an image.
     * This method will automatically search through added image dirs for frontend and admin requests
     * and through the admin theme directories for admin requests.
     *
     * @deprecated
     * @see cge_tags::create_image
     * @see AddImageDir.
     * @param string $image The basename for the desired image.
     * @param string $alt The alt attribute for the tag
     * @param string $class Value for the class attribute
     * @param int $width Width in pixels
     * @param int $height Height in pixels
     * @return string
     */
    function DisplayImage(string $image,string $alt='',string $class='',int $width=null, $height=null)
    {
        return $this->get_utils()->DisplayImage($this, $image,$alt,$class,$width,$height);
    }


    /**
     * A convenience method to create a link to a module action containing an image and optionally some text.
     *
     * This method will automatically search through added image dirs for frontend and admin requests
     * and through the admin theme directories for admin requests.
     *
     * @deprecated
     * @see CreateLink
     * @see CreateURL())
     * @see cge_tags::create_image
     * @see AddImageDir())
     * @see DisplayImage()
     * @param string $id The module action id
     * @param string $action The name of the destination action
     * @param int $returnid The page for the destination of the request.  Empty for admin requests.
     * @param string $contents The text content of the image.
     * @param string $image The basename of the image to display.
     * @param array  $params Additional link parameters
     * @param string $classname Class for the img tag.
     * @param string $warn_message An optional confirmation message
     * @param bool   $imageonly Wether the contents (if specified) should be ignored.
     * @param bool $inline
     * @param string $addtext
     * @param bool $targetcontentonly
     * @param string $prettyurl An optional pretty url slug.
     * @return string
     */
    function CreateImageLink($id,$action,$returnid,$contents,$image, $params=array(),$classname='',
                             $warn_message='',$imageonly=true, $inline=false,
                             $addtext='',$targetcontentonly=false,$prettyurl='')
    {
        @trigger_error('Deprecated usage of '.__METHOD__);
        return $this->get_utils()->CreateImageLink($this, $id,$action,$returnid,$contents,$image, $params,$classname,$warn_message,
                                                   $imageonly,$inline,$addtext, $targetcontentonly,$prettyurl);
    }



    /**
     * Add a directory to the list of searchable directories
     *
     * @param string $dir A directory relative to this modules installation directory.
     */
    function AddImageDir($dir)
    {
        if( strpos('/',$dir) !== 0 ) {
            $dir = $this->GetModuleUrlPath().'/'.$dir;
            $dir = str_replace($this->config['root_url'],'',$dir);
        }
        $this->_image_directories[] = $dir;
    }


    /**
     * List all templates stored with this module that begin with the same prefix.
     *
     * @deprecated
     * @see cge_template_utils::get_templates_by_prefix()
     * @param string $prefix The optional prefix
     * @param bool $trim
     * @return array
     */
    function ListTemplatesWithPrefix(string $prefix='',bool $trim = false )
    {
	trigger_error('Deprecated usage of '.__METHOD__);
        return cge_template_utils::get_templates_by_prefix($this,$prefix,$trim);
    }


    /**
     * Create a dropdown of all templates beginning with the specified prefix
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name The name for the input element.
     * @param string $prefix The optional prefix
     * @param string $selectedvalue The default value for the input element
     * @param string $addtext
     * @return string
     */
    function CreateTemplateDropdown($id,$name,$prefix='',$selectedvalue=-1,$addtext='')
    {
	trigger_error('Deprecated usage of '.__METHOD__);
        return cge_template_utils::create_template_dropdown($id,$name,$prefix,$selectedvalue,$addtext);
    }


    /**
     * Part of the multiple database template functionality
     * this function provides an interface for adding, editing,
     * deleting and marking active all templates that match
     * a prefix.
     *
     * @deprecated Use the CmsLayoutTemplate class(es) in 2.0 capable modules.
     * @param string $id The module action id (pass in the value from doaction)
     * @param int $returnid The page id to use on subsequent forms and links.
     * @param string $prefix The template prefix
     * @param string $defaulttemplatepref The name of the template containing the system default template.  This can either be the name of a database template or a filename ending with .tpl.
     * @param string $active_tab The tab to return to
     * @param string $defaultprefname The name of the preference that contains the name of the current default template.  If empty string then there will be no possibility to set a default template for this list.
     * @param string $title Title text to display in the add/edit template form
     * @param string $info Information text to display in the add/edit template form
     * @param string $destaction The action to return to.
     */
    function ShowTemplateList($id,$returnid,$prefix, $defaulttemplatepref,$active_tab, $defaultprefname,
                              $title,$info = '',$destaction = 'defaultadmin')
    {
        $cgextensions = $this->get_cge();
        return $cgextensions->_DisplayTemplateList($this,$id,$returnid,$prefix, $defaulttemplatepref,$active_tab,
                                                   $defaultprefname,$title,$info,$destaction);
    }


    /**
     * @ignore
     */
    function _DisplayTemplateList(&$module,$id,$returnid,$prefix,	$defaulttemplatepref,$active_tab,$defaultprefname,
                                  $title, $info = '',$destaction = 'defaultadmin')
    {
        return $this->get_utils()->_DisplayTemplateList($module,$id,$returnid,$prefix,$defaulttemplatepref,$active_tab,
                                                         $defaultprefname,$title,$info,$destaction);
    }



    /**
     * GetDefaultTemplateForm.
     * A function to return a form suitable for editing a single template.
     *
     * @deprecated (this functionality is irrelevant in CMSMS 2.0)
     * @see cge_template_admin::get_start_template_form
     * @param GExtensions $module A CGExtensions derived module reference
     * @param string $id
     * @param string $returnid
     * @param string $prefname
     * @param string $action
     * @param string $active_tab
     * @param string $title
     * @param string $filename
     * @param string $info
     * @return string
     */
    function GetDefaultTemplateForm(&$module,$id,$returnid,$prefname,$action,$active_tab,$title,$filename, $info = '')
    {
        return cge_template_admin::get_start_template_form($module,$id,$returnid,$prefname,$action,$active_tab,$title,
                                                           $filename,$info);
    }


    /**
     * EditDefaultTemplateForm
     *
     * A function to return a form suitable for editing a single template.
     *
     * @deprecated (this functionality is irrelevant in CMSMS 2.0)
     * @see cge_template_admin::get_start_template_form
     * @param GExtensions $module A CGExtensions derived module reference
     * @param string $id
     * @param string $returnid
     * @param string $prefname
     * @param string $active_tab
     * @param string $title
     * @param string $filename
     * @param string $info
     * @param string $action
     * @return string
     */
    function EditDefaultTemplateForm(&$module,$id,$returnid,$prefname, $active_tab,$title,$filename,$info = '',$action = 'defaultadmin')
    {
        echo cge_template_admin::get_start_template_form($module,$id,$returnid,$prefname, $action,$active_tab,$title, $filename,$info);
    }


    /**
     * Get the username of the currently logged in admin user.
     *
     * @deprecated
     * @param int $uid
     * @return string
     */
    function GetAdminUsername(int $uid)
    {
	trigger_error('Deprecated usage of '.__METHOD__);
        $user = UserOperations::LoadUserByID($uid);
        return $user->username;
    }


    /**
     * Get a human readable error message for an upload code.
     *
     * @deprecated
     * @see cg_fileupload
     * @param string $code The upload error code.
     * @return string
     */
    function GetUploadErrorMessage(string $code)
    {
        $cgextensions = $this->get_cge();
        return $cgextensions->Lang($code);
    }


    /**
     * @ignore
     */
    function is_alias(string $str)
    {
        if( !preg_match('/^[\-\_\w]+$/', $str) ) return false;
        return true;
    }


    /**
     * @ignore
     */
    final protected function set_action_id(string $id = null) { $this->_actionid = $id; }

    /**
     * @ignore
     */
    final protected function set_action_name(string $name) { $this->_actionname = $name; }

    /**
     * @ignore
     */
    final protected function get_action_name() { return $this->_actionname; }

    /**
     * Return the current action id
     *
     * @depcreated
     * @internal
     * @return int
     */
    protected function get_action_id()
    {
        if( $this->_actionid ) return $this->_actionid;
        if( !$this->cms->is_frontend_request() ) return 'm1_';
    }


    /**
     * Return the current action id
     *
     * @depcreated
     * @internal
     * @return int
     */
    function GetActionId()
    {
        if( !method_exists($this,'get_action_id') && $this->GetName() != MOD_CGEXTENSIONS ) {
            die('FATAL ERROR: A module derived from CGExtensions is not handling the get_action_id method');
        }
        return $this->get_action_id();
    }


    /**
     * Get a form for adding or editing a single template.
     *
     * @deprecated
     * @see cge_template_admin::get_single_template_form
     * @param CGExtensions $module A CGExtensions module reference
     * @param string $id
     * @param int $returnid
     * @param string $tmplname The name of the template to edit
     * @param string $active_tab
     * @param string $title
     * @param string $filename The name of the file (in the module's template directory) containing the system default template.
     * @param string $info
     * @param string $destaction
     * @param int $simple
     */
    function GetSingleTemplateForm(&$module,$id,$returnid,$tmplname,$active_tab,$title,$filename, $info = '',$destaction='defaultadmin',$simple = 0)
    {
        return cge_template_admin::get_single_template_form($module,$id,$returnid,$tmplname,$active_tab,$title,$filename,
                                                            $info,$destaction,$simple);
    }


    /**
     * Retrieve a human readable string for any error generated during watermarking.
     *
     * @deprecated
     * @param string $error the watermarking error code
     * @return string
     */
    function GetWatermarkError(string $error)
    {
        if( empty($error) || $error === 0 ) return '';
        $mod = $this->get_cge();
        return $mod->Lang('watermarkerror_'.$error);
    }


    /**
     * A convenience method to clear any session data associated with this module.
     *
     * @param string $key If not specified clear all session data relative to this module."
     */
    function session_clear(string $key = '')
    {
        $pkey = 'c'.md5(__FILE__.get_class($this));
        if( empty($key) ) {
            unset($_SESSION[$pkey]);
        }
        else {
            unset($_SESSION[$pkey][$key]);
        }
    }

    /**
     * A convenience method to store some session data associated with this module.
     *
     * @param string $key The variable key.
     * @param mixed $value The data to store.
     */
    function session_put(string $key,$value)
    {
        $pkey = 'c'.md5(__FILE__.get_class($this));
        if( !isset($_SESSION[$pkey]) ) $_SESSION[$pkey] = [];
        $_SESSION[$pkey][$key] = $value;
    }

    /**
     * A convenience method to retrieve some session data associated with this module.
     *
     * @param string $key The variable key.
     * @param mixed $dfltvalue "The default value to return if the specified data does not exist."
     * @return mixed.
     */
    function session_get(string $key,$dfltvalue = '')
    {
        $pkey = 'c'.md5(__FILE__.get_class($this));
        if( !isset($_SESSION[$pkey]) ) return $dfltvalue;
        if( !isset($_SESSION[$pkey][$key]) ) return $dfltvalue;
        return $_SESSION[$pkey][$key];
    }


    /**
     * Return data identified by a key either from the supplied parameters, or from session.
     *
     * @param array $params Input parameters
     * @param string $key The data key
     * @param string $defaultvalue  The data to return if the specified data does not exist in the session or in the input parameters.
     * @return mixed.
     */
    function param_session_get(&$params,$key,$defaultvalue='')
    {
        if( isset($params[$key]) ) return $params[$key];
        return $this->session_get($key,$defaultvalue);
    }


    /**
     * Given a page alias resolve it to a page id.
     *
     * @param mixed $txt The page alias to resolve.  If an integer page id is passed in that is acceptable as well.
     * @param int $dflt The default page id to return if no match can be found
     * @return int
     */
    function resolve_alias_or_id($txt,int $dflt = null)
    {
        $txt = trim((string)$txt);
        if( !$txt ) return $dflt;
        $manager = $this->cms->GetHierarchyManager();
        $node = null;
        if( is_numeric($txt) && (int) $txt > 0 ) {
            $node = $manager->find_by_tag('id',(int)$txt);
        }
        else {
            $node = $manager->find_by_tag('alias',$txt);
        }
        if( $node ) return (int)$node->get_tag('id');
        return $dflt;
    }


    /**
     * Perform an HTTP post request.
     *
     * @deprecated
     * @see cge_http
     * @param string $URL the url to post to
     * @param array $data The array to post.
     * @param string $referer An optional referrer string.
     * @return string
     */
    function http_post($URL,$data = '',$referer='')
    {
        return cge_http::post($URL,$data,$referer);
    }


    /**
     * Perform an HTTP GET request.
     *
     * @deprecated
     * @see cge_http
     * @param string $URL the url to post to
     * @param string $referer An optional referrer string.
     * @return string
     */
    function http_get(string $URL,string $referer='')
    {
        return cge_http::get($URL,$referer);
    }

    /**
     * Similar to GetPreference except the default value is used even if the preference exists, but is blank.
     *
     * @param string $pref_name The preference name
     * @param string $dflt_value The default value for the preference if not set (or empty)
     * @param bool $allow_empty Wether the default value should be used if the preference exists, but is empty.
     * @return string.
     */
    public function CGGetPreference($pref_name,$dflt_value = null,$allow_empty = FALSE)
    {
        $tmp = trim($this->GetPreference($pref_name,$dflt_value));
        if( !empty($tmp) || is_numeric($tmp) ) return $tmp;
        if( $allow_empty ) return $tmp;
        return $dflt_value;
    }

    /**
     * A wrapper to get a module specific user preference.
     * this method only applies to admin users.
     *
     * @param string $pref_name The preference name
     * @param string $dflt_value The default value for the preference if not set (or empty)
     * @param bool $allow_empty Wether the default value should be used if the preference exists, but is empty.
     * @return string.
     */
    public function CGGetUserPreference(string $pref_name,string $dflt_value = null,bool $allow_empty = FALSE)
    {
        $key = '__'.$this->GetName().'_'.$pref_name;
        $tmp = cms_userprefs::get($key,$dflt_value);
        if( !empty($tmp) || is_numeric($tmp) ) return $tmp;
        if( $allow_empty ) return $tmp;
        return $dflt_value;
    }

    /**
     * A wrapper to set a user preference that is module specific.
     * this method only applies to admin users.
     *
     * @param string $pref_name The preference name
     * @param string $value The preference value.
     */
    public function CGSetUserPreference(string $pref_name,string $value = '')
    {
        $key = '__'.$this->GetName().'_'.$pref_name;
        return cms_userprefs::set($key,$value);
    }

    /**
     * A wrapper to remove a user preference that is module specific.
     * this method only applies to admin users.
     *
     * @param string $pref_name The preference name
     * @param string $value The preference value.
     */
    public function CGRemoveUserPreference(string $pref_name)
    {
        $key = '__'.$this->GetName().'_'.$pref_name;
        return cms_userprefs::remove($key,$value);
    }

    /**
     * Get the prefererred email storage mechanism
     *
     * @since 1.59
     * @example sending_an_email.php
     * @return IEmailStorage
     */
    public function get_email_storage() : IEmailStorage
    {
        static $_obj;
        if( !$this->_obj ) $_obj = new FileEmailStorage( $this );
        return $_obj;
    }

    /**
     * Get a mailer filled with some defaults
     *
     * @since 1.63
     * @return cms_mailer
     */
    public function create_new_mailer() : cms_mailer
    {
        return new cms_mailer();
    }

    /**
     * Get a new email processor
     *
     * @since 1.63
     * @example sending_an_email.php
     * @param Email $eml The email that will be processed.
     * @return IEmailProcessor
     */
    public function create_new_mailprocessor( Email $eml ) : EmailProcessor
    {
        return new SimpleEmailProcessor( $eml, $this->create_new_mailer() );
    }

    /**
     * Find a file for this module
     * looks in module_custom, and in the module directory
     *
     * Example:
     * <pre><code>$fn = $this->find_module_file('images/icon.png');</code></pre>
     *
     * @param string $filename The file to search for
     * @return string|null If found returns the complete file specification to the found file.
     */
    public function find_module_file(string $filename)
    {
        if( startswith($filename,'/') ) $filename = substr($filename, 1);
        if( !$filename ) return;
        $tmp = realpath($filename);
        if( $tmp ) return; // absolute paths not accepted.

        $config = $this->config;
        $dirlist = [];
        if( version_compare(CMS_VERSION,'2.2-beta1') >= 0 && $config['assets_path'] ) {
            $dirlist[] = $config['assets_path']."/module_custom/".$this->GetName();
        }
        $dirlist[] = CMS_ROOT_PATH."/module_custom/".$this->GetName();
        $dirlist[] = $this->GetModulePath();
        foreach( $dirlist as $dir ) {
            $fn = "$dir/$filename";
            if( is_file($fn) ) return $fn;
        }
    }

    /**
     * Get a list of module files matching a specified pattern in a specified module subdirectory.
     * This method can be used for finding a list of files matching a pattern (i.e a list of classes, or even a list of templates).
     * This method will search for files in a matching directory in the module_custom directory (if one exists) and in the module directory.
     * i.e: $this->get_module_files('templates','summary*tpl');
     *
     * @param string $dirname The directory name (relative to the module directory) to search in.
     * @param string $pattern An optional pattern, if no pattern is specified, *.* is assumed.
     * @return string[]|null
     */
    public function get_module_files(string $dirname,string $pattern = null)
    {
        if( !$dirname ) return;
        $tmp = realpath($dirname);
        if( file_exists($dirname) ) return; // absolute paths not accepted.
        if( !$pattern ) $pattern = '*.*';

        $files = [];
        $dirlist = [];
        $dirlist[] = CMS_ROOT_PATH."/module_custom/".$this->GetName();
        $dirlist[] = $this->GetModulePath();
        foreach( $dirlist as $dir ) {
            $fn = "$dir/$dirname";
            if( !is_dir($fn) ) continue;

            $_list = glob("$fn/$pattern");
            if( !count($_list) ) continue;
            $files = array_merge($files,$_list);
        }
        if( count($files) ) return $files;
    }

    /**
     * Given a filename, search for it in the module_custom and module's templates directory.
     * i.e.: $this->find_template_file('somereport.tpl');
     *
     * @param string $filename The template filename (only the filename) to search for
     * @return string The absolute path to the filename.
     */
    public function find_template_file(string $filename)
    {
        if( !$filename ) return;
        $filename = "templates/".basename($filename);
        return $this->find_module_file($filename);
    }

    /**
     * A convenience method to generate a new smarty template object given a resource string,
     * and a prefix.  This method will also automatically assign a few common smarty variables
     * to the new scope.
     *
     * Note: the parent smarty scope depends on how this function is called.  If called directly from a module action for the same module
     *  the parent will be the current smarty scope.  If called from any method that is using a different module than the
     *  action module, then the parent scope will be the global smarty scope.
     *
     * @param string $template_name The desired template name.
     * @param string $prefix an optional prefix for database templates.
     * @param string $cache_id An optional smarty cache id.
     * @param string $compile_id An optional smarty compile id.
     * @param object $parent An optional parent template object.
     * @return object
     */
    public function CreateSmartyTemplate(string $template_name,string $prefix = null,string $cache_id = null,string $compile_id = null,$parent = null)
    {
        $smarty = null;
        if( $parent ) $smarty = $parent;
        if( !$smarty ) $smarty = $this->GetActionTemplateObject();
        if( !$smarty ) $smarty = $this->cms->GetSmarty();

        $tpl = $smarty->createTemplate($this->CGGetTemplateResource($template_name,$prefix),$cache_id,$compile_id,$smarty);

        // for convenience, I assign a few smarty variables.
        $tpl->assign('module',$this->GetName());
        $tpl->assign($this->GetName(),$this);
        $tpl->assign('mod',$this);
        $tpl->assign('actionid',$this->get_action_id());
        if( ($actionparams = $smarty->getTemplateVars('actionparams')) ) $tpl->assign('actionparams',$actionparams);
        return $tpl;
    }

    /**
     * Given a template name/resource and some data return the results.
     *
     * @param string $tpl_name
     * @param array $data
     * @see CGExtensions::CreateSmartyTemplate
     * @return string
     */
    public function ProcessSimpleTemplate(string $tpl_rsrc, array $data = null) : string
    {
        $tpl = $this->CreateSmartyTemplate($tpl_rsrc);
        foreach( $data as $key => $val ) {
            $tpl->assign($key, $val);
        }
        return $tpl->fetch();
    }

    /**
     * A convenience method to generate a smarty resource string given a template name and an optional prefix.
     * if the supplied template name begins with :: then we assume a 'cms_template' resource.
     * if the supplied template name appears to be a smarty resource name we don't do anything.
     * if the supplied template name ends with .tpl then a file template is assumed.
     *
     * @param string $template_name The desired template name
     * @param string $prefix an optional prefix for database templates.
     * @return string
     */
    public function CGGetTemplateResource(string $template_name,string $prefix = null)
    {
        $template_name = trim($template_name);
        if( startswith($template_name,'string:') || startswith($template_name,'eval:') || startswith($template_name,'extends:') ) {
            throw new \LogicException('Invalid resource name passed to '.__METHOD__);
        }
        if( startswith($template_name,'::') ) {
            // assume design manager template
            $template_name = substr($template_name,2);
            $template_name = 'cms_template:'.$template_name;
        }
        else if( !strpos($template_name,':') ) {
            // it's not a resource.
            if( endswith($template_name,'.tpl') ) return $this->GetFileResource($template_name);
            // deprecated
            return $this->GetDatabaseResource($prefix.$template_name);
        }
        return $template_name;
    }

    /**
     * An advanced method to process either a file, or database template for this module
     * through smarty
     *
     * @deprecated
     * @param string $template_name  The template name.  If the value of this parameter ends with .tpl then a file template is assumed.  Otherwise a database template is assumed.
     * @param string $prefix  For database templates, optionally prefix thie template name with this value.
     * @return string The output from the processed smarty template.
     */
    public function CGProcessTemplate(string $template_name,string $prefix = null)
    {
        trigger_error('Deprecated usage of '.__METHOD__);
        $rsrc = $this->CGGetTemplateResource($template_name,$prefix);
        $smarty = $this->GetActionTemplateObject();
        if( !$smarty ) $smarty = $this->cms->GetSmarty();
        return $smarty->fetch($rsrc);
    }

    /**
     * Get the name of the module that the current action is for.
     * (only works with modules derived from CGExtensions).
     * This method is useful to find the module action that was used to send an event.
     *
     * @return string
     */
    public function GetActionModule()
    {
        return cge_tmpdata::get('module');
    }

    /**
     * Create a url to an action that will show a message.
     *
     * This method is capable of using the \cge_message class to extract longer messages.
     *
     * @param int|string $page A page id or alias to display the message on
     * @param string $msg The message to display.  Or the message key name.
     * @param bool $is_key If true, it indicates that the $msg parameter is a key to extract from \cge_message.
     * @param bool $is_error Indicates if the message should be displayed as an error.
     * @see CGExtensions::DisplayErrorMessage();
     * @return string The URL to the action.
     */
    public function GetShowMessageURL(string $page,string $msg,bool $is_key = false,bool $is_error = false)
    {
        $page = $this->resolve_alias_or_id($page);
        if( !$page ) throw new \LogicException('Invalid page passed to '.__METHOD__);

        $parms = [];
        if( $is_key ) {
            $parms['cge_msgkey'] = trim($msg);
        }
        else {
            $parms['cge_msg'] = trim($msg);
        }
        $parms['cge_error'] = ($is_error) ? 1 : 0;
        $mod = $this->get_cge();
        return $mod->create_url('cntnt01','showmessage',$page,$parms);
    }


    /**
     * Show a message on the frontend of the website.
     * suitable for displaying errors and brief messages.
     *
     * @param string $msg The message to display
     * @param bool $is_error whether or not the msg is an error
     * @param string $title If not an error, optionally display a title to the message.
     */
    public function ShowFormattedMessage(string $msg,bool $is_error = FALSE, string $title = null)
    {
        if( $is_error ) {
            echo $this->DisplayErrorMessage($msg);
        }
        else {
            // todo... need a template for this.
            echo '<div class="cge_message">';
            if( $title ) echo '<div class="cge_msgtitle">'.$title.'</div>';
            echo '<div class="cge_msgbody">'.$msg.'</div>';
            echo '</div>';
        }
    }

    /**
     * Find and return a module that can do notifications.
     *
     * @return CMSModule|null
     */
    protected function get_notifier_module()
    {
        static $_obj;
        if( !$_obj ) {
            $list = $this->GetModulesWithCapability('notifications');
            if( empty($list) ) return;
            $module_name = $list[0];
            if( !method_exists($module_name,'send_message') ) return;
            $_obj = $this->GetModuleInstance($module_name);
        }
        return $_obj;
    }

    /**
     * Create a new notification message
     *
     * @param array $opts An associative array of options.
     * @return notification_message
     */
    protected function create_notification_message(array $opts = null) : notification_message
    {
        return new notification_message;
    }

    /**
     * Given an email message create a notification message.
     *
     * This method attempts to resolve the to_group and to fields of the notification message
     * but will NOT throw an exception if nothing can be resolved.  The caller may wish to check these properties
     * or override them.
     *
     * @param Email $eml
     * @return notification_message
     */
    protected function create_notification_message_from_email(Email $eml) : notification_message
    {
        $resolve_group = function(Email $eml) {
            if( isset($eml->to_admin_groups[0]) ) {
                $first = $eml->to_admin_groups[0];
                if( is_string($first) && (int) $first == 0 ) {
                    return (int) cge_userops::get_groupid($first) * -1;
                } else if( $first > 0 ) {
                    return $first * -1;
                }
                throw new \LogicException('Could not resolve admin group '.$first);
            }

            if( isset($eml->to_feu_groups[0]) ) {
                $first = $eml->to_feu_groups[0];
                if( is_string($first) && (int) $first == 0 ) {
                    $feu = $this->GetModuleInstance('FrontEndUsers');
                    if( $feu ) {
                        $uid = $feu->GetUsername($first);
                        if( $uid > 0 ) return $uid;
                    }
                } else if( $first > 0 ) {
                    return $first;
                }
            }
        };

        $resolve_user = function(Email $eml) {
            $feu = $this->GetModuleInstance('FrontEndUsers');
            if( $eml->to_current_admin ) {
                if( ($uid = get_userid(false)) ) return $uid * -1;
            }
            else if( $eml->to_current_feu ) {
                if( $feu && ($uid = $feu->LoggedInId()) ) return $uid;
            }
            else if( $eml->to_feu_users ) {
                if( ($uid = $eml->to_feu_users[0]) ) return $uid;
            }
        };

        $proc = $this->create_new_mailprocessor($eml);
        $msg = $this->create_notification_message();
        $msg->html = true;
        $msg->subject = $proc->get_email_subject($eml);
        $msg->body = $proc->get_email_body($eml);
        $msg->to_group = $resolve_group($eml);
        if( !$msg->to_group ) $msg->to = $resolve_user($eml);
        return $msg;
    }

  /**
   * A function to intelligently determine which template to use given parameters, a parameter name, and a type name.
   * This method will either return a template name (string) or null if nothing can be found.
   *
   * This method uses the LayoutTemplate API
   *
   * @deprecated
   * @param array $params Input module action parameters array.
   * @param strng $paramname The param name that may hold a template name.
   * @param string $typename The complete template TypeName i.e:  Uploads::Summary
   * @return string|null
   */
  public function find_layout_template(array $params, string $paramname, string $typename)
  {
      trigger_error('Deprecated usage of '.__METHOD__);
      $thetemplate = null;
      if( !is_array($params) || !($thetemplate = cge_param::get_string($params,$paramname)) ) {
          $tpl = CmsLayoutTemplate::load_dflt_by_type($typename);
          if( !is_object($tpl) ) {
              audit('',$this->GetName(),'No default '.$typename.' template found');
              return;
          }
          $thetemplate = $tpl->get_name();
          unset($tpl);
      }
      return $thetemplate;
  }

  /**
   * @ignore
   */
  public function onContentPostRender( array $params )
  {
      if( $this->config['debug'] ) return;
      $pretty_html = cge_param::get_bool($this->config,'cge_prettyhtml',false);
      $min_html = cge_param::get_bool($this->config,'cge_minhtml',false);
      if( !$pretty_html && !$min_html ) return;

      include_once(__DIR__.'/event.Core.ContentPostRender.php');
  }

  /**
   * Get the shared instance of the jsloader.
   *
   * Used by the cgjs_ plugins
   *
   * @param string $key a unique key.  If an instance by that key does not exist, it will be created.
   */
  public function get_jsloader(string $key = '_primary') : jsloader
  {
      $key = trim($key);
      if( !$key ) $key = '_primary';
      static $_obj = [];
      if( !isset($_obj[$key]) ) {
          $_obj[$key] = new jsloader( $key, $this->config );
      }
      return $_obj[$key];
  }

  /**
   * A function to get a handle to an alternate database connection that supports better error handling.
   *
   * @return Database
   */
  public function get_extended_db() : Database
  {
      static $_obj;
      if( !$_obj ) {
          $_error_handler = function(Database $conn,$errtype, $error_number, $error_message) {
              throw new \cg_sql_error($error_message.' -- '.$conn->ErrorMsg(),$error_number);
          };
          $_obj = db_compatibility::init($this->config);
          $_obj->SetErrorHandler($_error_handler);
      }
      return $_obj;
  }

  /**
   * Create a new cached file object.
   * This is a factory method
   *
   * @param string $url The URL to cache
   */
   public function cache_file(string $url) : cge_cached_remote_file
   {
      return new cge_cached_remote_file($url);
   }

  /**
   * @ignore
   */
  protected function get_internals() : internals
  {
      static $_obj;
      if( !$_obj ) $_obj = new internals( $this->get_extended_db() );
      return $_obj;
  }

  /**
   * @ignore
   */
  public function get_content_list_builder() : content_list_builder
  {
      static $_obj;
      if( !$_obj ) $_obj = new content_list_builder(
          $this->cms->GetHierarchyManager(),
          $this->cms->GetContentOperations());
      return $_obj;
  }
} // class

/**
 * @ignore
 */
function cgex_lang()
{
    $mod = cms_utils::get_module(MOD_CGEXTENSIONS);
    $args = func_get_args();
    return call_user_func_array(array($mod,'Lang'),$args);
}

// EOF
