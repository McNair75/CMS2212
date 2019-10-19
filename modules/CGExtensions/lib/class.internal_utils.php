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
declare(strict_types=1);
namespace CGExtensions;
use CGExtensions;
use cge_template_utils;
use cms_utils;
use StdClass;

final class internal_utils
{
    private $mod;

    function __construct(CGExtensions $mod)
    {
        if( get_class($mod) != MOD_CGEXTENSIONS ) throw new \LogicException('Invalid module passed to '.__METHOD__);
        $this->mod = $mod;
    }

    /*
     * A Convenience function to redirect to an admin tab in the
     * defaultadmin action
     *
     * See Also:  SetCurrentTab
     */
    function RedirectToTab(string $id,string $tab = '', $params = '',string $action = '')
    {
        $parms = array();
        if( is_array( $params ) ) $parms = $params;
        if( $tab == '' ) {
            if( $this->mod->_current_tab ) $tab = $this->mod->_current_tab;
        }
        if( $tab != '' ) $parms['cg_activetab'] = $tab;
        if( is_array($this->mod->_errormsg) && count($this->mod->_errormsg) ) {
            $parms['cg_error'] = implode(':err:',$this->mod->_errormsg);
        }
        if( is_array($this->mod->_messages) && count($this->mod->_messages) ) {
            $parms['cg_message'] = implode(':err:',$this->mod->_messages);
        }

        if( !$action && $this->mod->_current_action ) $action = $this->mod->_current_action;
        if( !$action ) $action = 'defaultadmin';
        $this->mod->Redirect( $id, $action, '', $parms, true );
    }

    /*
     * A convenience function for creating an <img> tag.
     * @deprecated
     */
    protected function CreateImageTag(string $image,string $alt='',int $width=null,int $height=null,string $class='',string $addtext='')
    {
        $txt = "<img src=\"$image\"";
        $txt .= " alt=\"$alt\"";
        if( $alt != '' ) {
            $txt .= " title=\"$alt\"";
        }
        if( $width != '' ) $txt .= " width=\"$width\"";
        if( $height != '' )	$txt .= " height=\"$height\"";
        if( $class != '' ) $txt .= " class=\"$class\"";
        if( $addtext != '' ) $txt .= " $addtext";
        $txt .= " />";
        return $txt;
    }


    /*
     * A convenience function to search for an image in certain preset
     * directories
     */
    function DisplayImage(CGExtensions $mod, string $image, string $alt='', string $class='', int $width =null, int $height=null, string $id='')
    {
        $config = $this->mod->config;
        if( !$class && $this->mod->cms->is_frontend_request() ) $class = 'systemicon';

        $img1 = basename($image);

        // check image_directories first
        if( isset($mod->_image_directories) && !empty($mod->_image_directories)) {
            foreach( $mod->_image_directories as $dir ) {
                $url = "$dir/$img1";
                $path = cms_join_path(CMS_ROOT_PATH,$url);
                if( is_readable($path) ) {
                    if( !$this->mod->cms->is_frontend_request() ) {
                        $url = "../$url";
                    }
                    else {
                        $url = $config['root_url']."/$url";
                    }
                    return $this->CreateImageTag($url,$alt,$width,$height,$class);
                }
            }
        }

        $theme = cms_utils::get_theme_object();
        if( is_object($theme) ) {
            // we're in the admin
            $txt = $theme->DisplayImage($image,$alt,$width,$height,$class);
        }
        else {
            // frontend
            $txt = $this->CreateImageTag($image,$alt,$width,$height,$class);
        }
        return $txt;
    }


    /*
     * A convenience function for creating a link with an image
     */
    function CreateImageLink(CGExtensions $mod, string $id, string $action,$returnid,$contents,$image,
                             $params=array(),$classname='',
                             $warn_message='',$imageonly=true,
                             $inline=false,
                             $addtext='',$targetcontentonly=false,$prettyurl='')
    {
        if( $classname == '' ) $classname = 'systemicon';

        $txt = $this->DisplayImage($mod, $image, $contents, $classname);
        if( $imageonly !== true ) {
            $txt .= '&nbsp';
            $txt .= $contents;
        }
        return $mod->CreateLink( $id, $action, $returnid, $txt, $params, $warn_message, $inline, $addtext, $targetcontentonly, $prettyurl);
    }


    /*
     * An overridable function for creating a pretty link
     *
     * @deprecated
     */
    function __CreatePrettyLink(CGExtensions $mod, $id, $action, $returnid='', $contents='',
                                $params=array(), $warn_message='',
                                $onlyhref=false, $inline=false, $addtext='',
                                $targetcontentonly=false, $prettyurl='')
    {
        $config = $this->mod->config;
        $pretty = false;
        if( $config['assume_mod_rewrite'] === true || $config['internal_pretty_urls'] === true ) $pretty = true;

        $method_exists = method_exists($this->mod,'CreatePrettyLink');
        if( $pretty && ($returnid != '') && $method_exists ) {
            // pretty urls are configured, we're not in an admin action
            // and the CreatePrettyLink method has been found.
            return $mod->CreatePrettyLink($id,$action,$returnid,
                                                    $contents,$params,
                                                    $warn_message,
                                                    $onlyhref,$inline,$addtext,
                                                    $targetcontentonly,$prettyurl);
        }
        else {
            return $mod->CreateLink($id,$action,$returnid,$contents,$params,$warn_message,
                                              $onlyhref,$inline,$addtext,$targetcontentonly,$prettyurl);
        }
    }


    function _DisplayTemplateList( &$module, $id, $returnid, $prefix,
                                   $defaulttemplatepref,
                                   $active_tab, $defaultprefname,
                                   $title, $info = '',$destaction = 'defaultadmin')
    {
        // we're gonna allow multiple templates here
        // but we're gonna prefix them all with something

        $theme = cms_utils::get_theme_object();
        $falseimage1 = $theme->DisplayImage('icons/system/false.gif','make default','','','systemicon');
        $trueimage1 = $theme->DisplayImage('icons/system/true.gif','default','','','systemicon');
        $alltemplates = cge_template_utils::get_templates_by_prefix($module,$prefix,true);
        $rowarray = array();
        $rowclass = 'row1';

        foreach( $alltemplates as $onetemplate ) {
            if( $prefix.$onetemplate == $defaulttemplatepref ) continue; // don't show the system default.

            $tmp = $onetemplate;
            $row = new StdClass;
            $row->name = $this->mod->CreateLink( $id, 'edittemplate', $returnid,
                                                     $tmp, array('template' => $tmp,
                                                                 'destaction' => $destaction,
                                                                 'activetab' => $active_tab,
                                                                 'title'=>$title,
                                                                 'info'=>$info,
                                                                 'prefix'=>$prefix,
                                                                 'modname'=>$module->GetName(),
                                                                 'moddesc'=>$module->GetFriendlyName(),
                                                                 'defaulttemplatepref'=>$defaulttemplatepref,
                                                                 'defaultprefname'=>$defaultprefname,
                                                                 'mode'=>'edit'));
            $row->rowclass = $rowclass;

            $row->default = null;
            if( $defaultprefname ) {
                $default = ($module->GetPreference($defaultprefname) == $tmp) ? true : false;
                if( $default ) {
                    $row->default = $trueimage1;
                }
                else {
                    $row->default = $this->mod->CreateLink( $id, 'makedefaulttemplate', $returnid,
                                                                $falseimage1,
                                                                array('template'=>$tmp,
                                                                      'destaction'=>$destaction,
                                                                      'defaultprefname'=>$defaultprefname,
                                                                      'modname'=>$module->GetName(),
                                                                      'cg_activetab' => $active_tab));
                }
            }

            $row->editlink = $this->mod->CreateImageLink( $id,'edittemplate',$returnid,
                                                              $this->mod->Lang('prompt_edittemplate'),
                                                              'icons/system/edit.gif',
                                                              array ('template' => $tmp,
                                                                     'destaction'=>$destaction,
                                                                     'activetab' => $active_tab,
                                                                     'prefix'=>$prefix,
                                                                     'title'=>$title,
                                                                     'info'=>$info,
                                                                     'modname'=>$module->GetName(),
                                                                     'moddesc'=>$module->GetFriendlyName(),
                                                                     'defaulttemplatepref'=>$defaulttemplatepref,
                                                                     'defaultprefname'=>$defaultprefname,
                                                                     'mode'=>'edit'));

            if( $defaultprefname && $default ) {
                $row->deletelink = '&nbsp;';
            }
            else {
                $row->deletelink = $this->mod->CreateImageLink( $id, 'deletetemplate', $returnid,
                                                                    $this->mod->Lang('prompt_deletetemplate'),
                                                                    'icons/system/delete.gif',
                                                                    array ('template' => $onetemplate,
                                                                           'prefix'=>$prefix,
                                                                           'modname'=>$module->GetName(),
                                                                           'destaction'=>$destaction,
                                                                           'cg_activetab' => $active_tab),
                                                                    '',
                                                                    $this->mod->Lang('areyousure'));
            }

            $rowarray[] = $row;
            ($rowclass == "row1" ? $rowclass = "row2" : $rowclass = "row1");
        }

        $tpl = $this->mod->CreateSmartyTemplate('listtemplates.tpl');
        $tpl->assign('parent_module_name',$module->GetFriendlyName());
        $tpl->assign('items', $rowarray );
        $tpl->assign('nameprompt', $this->mod->Lang('prompt_name'));
        $tpl->assign('defaultprompt', $this->mod->Lang('prompt_default'));
        $tpl->assign('newtemplatelink',
                     $this->mod->CreateImageLink( $id, 'edittemplate', $returnid,
                                                  $this->mod->Lang('prompt_newtemplate'),
                                                  'icons/system/newobject.gif',
                                                  array('prefix' => $prefix,
                                                        'destaction' => $destaction,
                                                        'activetab' => $active_tab,
                                                        'modname' => $module->GetName(),
                                                        'moddesc'=>$module->GetFriendlyName(),
                                                        'title'=>$title,
                                                        'info'=>$info,
                                                        'mode' => 'add',
                                                        'defaulttemplatepref' => $defaulttemplatepref,
                                                        'defaultprefname'=>$defaultprefname
                                                      ),'','',false));
        return $tpl->fetch();
    }

    public function cge_CreateInputYesNoDropdown(string $id,string $name,$selectedvalue='',string $addtext='')
    {
        $items = [ $this->mod->Lang('yes')=>1,$this->mod->Lang('no')=>0 ];
        return $this->mod->CreateInputDropdown($id,$name,$items,-1,$selectedvalue,$addtext);
    }

} // class

#
# EOF
#
