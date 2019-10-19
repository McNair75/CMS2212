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
 * A factory class to return objects setup with some internal defaults
 * or some preferences.
 *
 * @package CGExtensions
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

use CGExtensions\watermarker;
use CGExtensions\internal\settings;

/**
 * A factory class to return objects setup with some internal defaults
 * or some preferences.
 *
 * @package CGExtensions
 */
final class cge_setup
{
    /**
     * @ignore
     */
    private static $_instance;

    /**
     * @ignore
     */
    private $settings;


    /**
     * @ignore
     */
    private $config;

    /**
     * @ignore
     */
    private $module_path;

    /**
     * @ignore
     */
    public function __construct(cms_config $config, settings $settings, string $module_path)
    {
        if( self::$_instance ) throw new \LogicException('Only one instance of '.__CLASS__.' is permitted');
        self::$_instance = $this;

        $this->config = $config;
        $this->settings = $settings;
        $this->module_path = $module_path;
    }

    /**
     * Get a new watermarker object filled with module preferences.
     *
     * @deprecated
     * @return cge_watermark
     */
    public static function get_watermarker() : CGExtensions\watermarker
    {
        return self::$_instance->create_watermarker();
    }

    /**
     * Get a new watermarker object filled with module preferences.
     *
     * @return cge_watermark
     */
    public function create_watermarker() : CGExtensions\watermarker
    {
        $txt = $this->settings->watermark_text;
        $img = $this->settings->watermark_file;

        $obj = new \CGExtensions\watermarker;
        if( !empty($img) ) {
            $fn = $this->config['uploads_path'].'/'.$img;
            if( !is_file($fn) ) $fn = $this->config['assets_path'].'/'.$img;
            if( !is_file($fn) ) throw new \LogicException('Could not find watermark image '.$img);
            $obj->set_watermark_image($fn);
        }
        else if( !empty($txt) ) {
            $font_paths = null;
            $font_paths[] = $this->config['assets_path'].'/fonts/'.$this->settings->watermark_font;
            $font_paths[] = $this->config['assets_path'].'/'.$this->settings->watermark_font;
            $font_paths[] = $this->module_path.'/fonts/'.$this->settings->watermark_font;
            $fnd = null;
            foreach( $font_paths as $one ) {
                if( is_file($one) ) {
                    $obj->set_font($one);
                    $fnd = $one;
                    break;
                }
            }
            $obj->set_watermark_text($txt);
            $obj->set_text_size($this->settings->watermark_textsize);
            $obj->set_text_angle($this->settings->watermark_textangle);
            $tmp = $this->settings->watermark_textcolor;
            $r = hexdec(substr($tmp,1,2)); $g = hexdec(substr($tmp,3,2)); $b = hexdec(substr($tmp,5,2));
            $obj->set_text_color($r,$g,$b);
            $tmp = $this->settings->watermark_bgcolor;
            $r = hexdec(substr($tmp,1,2)); $g = hexdec(substr($tmp,3,2)); $b = hexdec(substr($tmp,5,2));
            $obj->set_background_color($r, $g, $b, $this->settings->watermark_transparent);
        }

        $obj->set_alignment($this->settings->watermark_alignment);
        $obj->set_translucency($this->settings->watermark_translucency);
        return $obj;
    }

    /**
     * Get a new object for handling uploads filled with settings defined in
     * the module admin panel.
     *
     * @deprecated
     * @param string $prefix An optional prefix for all upload file keys
     * @param string $destdir The optional destination directory.  If specified this overrides the default destination directory.
     */
    static public function get_uploader(string $prefix = '', string $destdir = '') : cge_uploader
    {
        return self::$_instance->create_uploader($prefix, $destdir);
    }

    /**
     * Get a new object for handling uploads filled with settings defined in
     * the module admin panel.
     *
     * @param string $prefix An optional prefix for all upload file keys
     * @param string $destdir The optional destination directory.  If specified this overrides the default destination directory.
     */
    static public function create_uploader(string $prefix = '',string $destdir = '') : cge_uploader
    {
        $inst = self::$_instance;
        $watermarker = null;
        if( $inst->settings->allow_watermarking ) $watermarker = $inst->create_watermarker();
        $obj = new cge_uploader($prefix, $destdir, $watermarker);
        $obj->set_accepted_filetypes($inst->settings->upload_allowed_filetypes);
        $obj->set_accepted_imagetypes($inst->settings->imagextensions);
        $obj->set_preview($inst->settings->allow_resizing);
        $obj->set_preview_size($inst->settings->resizeimage);
        $obj->set_thumbnail($inst->settings->allow_thumbnailing);
        $obj->set_thumbnail_size($inst->settings->thumbnailsize);
        $obj->set_delete_orig($inst->settings->delete_orig_image);
        return $obj;
    }
} // end of class

#
# EOF
#
