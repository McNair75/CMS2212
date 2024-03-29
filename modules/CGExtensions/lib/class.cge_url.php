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
 * A simple class for taking apart, modifying, and re-assembling URLS.
 *
 * @package CGExtensions
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

/**
 * A simple class for taking apart, modifying, and re-assembling URLS.
 *
 * @package CGExtensions
 */
final class cge_url
{
    /**
     * @ignore
     */
    private function __construct() {}

    /**
     * Convert a standard url into an SSL url
     *
     * @param string $url The input URL
     * @return string
     */
    public static function ssl_url(string $url)
    {
        $config = cms_config::get_instance();

        $ssl_url = '';
        if( isset($config['ssl_url']) )  $ssl_url = $config['ssl_url'];
        if( empty($ssl_url) ) $ssl_url = str_replace('http://','https://',$config['root_url']);

        if( startswith($url,$ssl_url) ) {
            return $url;
        }
        else if( startswith($url,$config['root_url']) ) {
            $url = str_replace($config['root_url'],$ssl_url,$url);
            return $url;
        }
    }

    /**
     * Attempt to determine the currently requested URL
     *
     * @deprecated
     * @return string
     */
    public static function current_url() : string
    {
        // rebuild the current url.
        $config = cms_config::get_instance();
        $url1 = new cms_url($_SERVER['REQUEST_URI']);
        $url2 = new cms_url($config['root_url']);
        $url1->set_scheme($url2->get_scheme());
        $url1->set_host($url2->get_host());
        $url1->set_port($url2->get_port());
        $url1->set_user($url2->get_user());
        $url1->set_pass($url2->get_pass());
        $out = (string) $url1;
        return $out;
    }

    /**
     * Get the URL of the currently requested page.
     * Only works for frontend requests
     *
     * @deprecated
     * @return string
     */
    public static function page_url()
    {
        $content_obj = \cms_utils::get_current_content();
        if( is_object($content_obj) ) return $content_obj->GetURL();
    }

    /**
     * Given an existing URL, add or adjust one of the parameters
     *
     * @deprecated
     * @param string $url The input url
     * @param string $key The key name
     * @param string $value The value for the variable
     * @returns string  The resulting URL
     */
    public static function set_param(string $url,string $key,string $value) 
    {
        if( !$url ) return;
        $tmp = parse_url($url);
        if( $tmp === FALSE ) return;

        $query = array();
        if( isset($tmp['query']) ) parse_str($tmp,$query);
        $query[$key] = $value;
        $tmp['query'] = http_build_query($query,'','&');
        return http_build_url($tmp);
    }

    /**
     * Given a filename, convert it to a URL
     *
     * @param string $filename The input filename
     * @return string
     */
    public static function filename_to_url(string $filename)
    {
        if( !$filename ) return;
        if( !is_readable($filename) ) return;

        if( !startswith($filename,CMS_ROOT_PATH) ) return;
        return str_replace(CMS_ROOT_PATH,CMS_ROOT_URL,$filename);
    }
}

#
# EOF
#
