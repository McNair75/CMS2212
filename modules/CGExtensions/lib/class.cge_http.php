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
 * A set of utility functions for performing http requests.
 *
 * @package CGExtensions
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

/**
 * A set of utility functions for performing http requests.
 *
 * This class uses an embedded http object, which in turn relies on curl (or attempts to use fsockupeon if all else fails) to perform http requests.
 *
 * @package CGExtensions
 */
final class cge_http
{
    /**
     * @ignore
     */
    static private $_http;

    /**
     * @ignore
     */
    private function __construct() {}

    /**
     * Reset the http object state to default values.
     */
    static public function reset()
    {
        require_once(dirname(__FILE__).'/http/class.http.php');
        self::$_http = new Http(array());
        self::$_http->setTimeout(60); // todo, make this a preference.
    }


    /**
     * Get the internal HTTP object
     *
     * @return http
     */
    static public function get_http()
    {
        if( is_null(self::$_http) )	self::reset();
        return self::$_http;
    }


    /**
     * Send a POST request to the specified URL
     *
     * @param string $URL The specified data
     * @param array $data Data to associate with the URL
     * @param string $referer An optional referer string.  If not specified, a preset value is used.
     * @return string The HTML or other data returned from the request."
     */
    static public function post(string $URL,array $data = null,string $referer = null)
    {
        if( startswith($URL,'//') ) {
            if( cmsms()->is_https_request() ) {
                $URL = 'https:' . $URL;
            } else {
                $URL = 'http:' . $URL;
            }
        }
        self::reset();
        $http = self::get_http();
        $http->setMethod('POST');
        if( $data )	$http->setParams($data);
        return $http->execute($URL);
    }


    /**
     * Send a GET request to the specified URL.
     * This method will use file_get_contents to read the URL if curl is not available, or use_curl is set to false.
     *
     * @param string $URL
     * @param string $referer An optional referer string
     * @param bool $use_curl
     * @return string
     */
    static public function get(string $URL,string $referer = '',bool $use_curl = TRUE)
    {
        if( startswith($URL,'//') ) {
            if( cmsms()->is_https_request() ) {
                $URL = 'https:' . $URL;
            } else {
                $URL = 'http:' . $URL;
            }
        }
        if( $use_curl == FALSE || !in_array('curl',get_loaded_extensions()) ) {
            return file_get_contents($URL);
        }
        else {
            self::reset();
            $http = self::get_http();
            $http->setMethod('GET');
            $tmp = $http->execute($URL);
            return $tmp;
        }
    }


    /**
     * Get the status of the last http request
     *
     * @return int
     */
    static public function status()
    {
        $http = self::get_http();
        return $http->getStatus();
    }

    /**
     * Get the result of the last http request
     *
     * @return string
     */
    static public function result()
    {
        $http = self::get_http();
        return $http->getResult();
    }
} // class

#
# EOF
#
