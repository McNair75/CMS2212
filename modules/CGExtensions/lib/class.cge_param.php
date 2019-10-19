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
 * A set of utilities for cleaning input parameters.
 *
 * @package CGExtensions
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2015 by Robert Campbell
 */

/**
 * A set of utilities for cleaning input parameters.
 *
 * @package CGExtensions
 */
final class cge_param
{
    /**
     * @ignore
     */
    private function __construct() {}

    /**
     * A convenience method to test if a key exists in the input array.
     *
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @return bool
     */
    public static function exists($params,string $key)
    {
        $key = trim($key);
        if( !$key ) return;
        return isset($params[$key]);
    }

    /**
     * Get safe HTML from an input parameter.
     * This method uses htmlawed to clean input HTML.
     *
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param string $dflt The default value to use if the key does not exist in the $params aray.
     * @return string
     */
    public static function get_html($params,string $key,$dflt = null)
    {
        $val = cge_utils::get_param($params,$key,$dflt);
        if( $val ) {
            $val = cge_utils::clean_input_html($val);
            $val = html_entity_decode($val);
        }
        return $val;
    }

    /**
     * Get a safe integer from an input parameter.
     *
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param int $dflt The default value to use if the key does not exist in the $params aray.
     * @return int|null
     */
    public static function get_int($params,string $key,int $dflt = null)
    {
        $res = cge_utils::get_empty_param($params,$key,$dflt);
        if( !is_null($res) ) return (int) $res;
    }

    /**
     * Get a safe boolean from an input parameter.
     * This method can accept boolean strings like yes, no, true, false, on, off.
     *
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param bool $dflt The default value to use if the key does not exist in the $params aray.
     */
    public static function get_bool($params,string $key,bool $dflt = false)
    {
        $res = cge_utils::get_param($params,$key,$dflt);
        if( !is_null($res) ) return (bool) $res;
    }

    /**
     * Get a safe string from an input parameter.
     * This is similar to the get_string method except that if the $dflt is null, and the parameter
     * does not exist, then null will be returned.
     *
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param string $dflt The default value to use if the key does not exist in the $params aray.
     */
    public static function get_empty_string($params,string $key,$dflt = null)
    {
        $val = cge_utils::get_param($params,$key,$dflt);
        if( !is_null($val) ) {
            $val = cms_html_entity_decode($val);
            $val = trim(strip_tags( $val ));
            $val = filter_var( $val, FILTER_SANITIZE_SPECIAL_CHARS ); // this will convert some crap back to entities. and strip some high/low chars.
        }
        return $val;
    }

    /**
     * Get a safe string from an input parameter.
     * The string is stripped of any html code, and high or low bytes.
     *
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param string $dflt The default value to use if the key does not exist in the $params aray.
     */
    public static function get_string($params,string $key,$dflt = null)
    {
        // from textareas, and GET params may be encoded already.  so we have to decode it first
        $val = cms_html_entity_decode(cge_utils::get_param($params,$key,$dflt));
        // now it's in HTML
        $val = trim(strip_tags( $val ));
        $val = filter_var( $val, FILTER_SANITIZE_SPECIAL_CHARS ); // this will convert some crap back to entities. and strip some high/low chars.
        // $val = cms_html_entity_decode( $val, ENT_QUOTES );
        return $val;
    }

    /**
     * Get a safe float from an input parameter.
     *
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param float $dflt The default value to use if the key does not exist in the $params aray.
     */
    public static function get_float($params,string $key,float $dflt = null)
    {
        $tmp = cge_utils::get_empty_param($params,$key,$dflt);
        if( !is_null($tmp) ) return cge_string::to_float($tmp);
    }

    /**
     * Get a safe array of strings from an input parameter that is an array.
     * keys and values are cleaned.
     *
     * @see cge_param::get_string()
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param string[] $dflt The default value to use if the key does not exist in the $params aray.
     */
    public static function get_string_array($params,string $key,$dflt = null)
    {
        $tmp = cge_utils::get_param($params,$key,$dflt);
        if( !is_array($tmp) ) $tmp = [$tmp];

        $out = array();
        foreach( $tmp as $key => $val ) {
            $tmp = (int) $key;
            if( trim($tmp) === $key ) {
                $key = $tmp;  // we have an integer key.
            }
            else {
                $key = filter_var( $key, FILTER_SANITIZE_STRING );
                $key = html_entity_decode($key);
                $key = trim(strip_tags($key));
            }

            $val = html_entity_decode($val); // it could be encoded from a text area or GET request
            $val = filter_var( $val, FILTER_SANITIZE_SPECIAL_CHARS );
            $val = html_entity_decode($val); // yes, decode again
            $val = trim(strip_tags($val));
            $out[$key] = $val;
        }
        return $out;
    }

    /**
     * Get a unix timestamp from separate month, day and year fields.
     *
     * This method is useful for converting dates a user entered into separate month day and year fields into a unix timestamp.
     * i.e: if using the smarty {html_select_date} field for gathering dates.
     *
     * This method assumes that there will be separate fields in the params array who's values are integers, and the
     * key begins with the provided prefix and ends with _Month, _Day, and _Year respectively.
     *
     * Note, if the year parameter does not exist in the params array, but month and day do... then the current year is used.
     *
     * @param array $params
     * @param string $prefix The common prefix for the separate month, day and year parameters
     * @param int $dflt The optional default value.
     * @return int A unix timestamp representing the day found, at midnight.
     */
    public static function get_separated_date($params,string $prefix,$dflt = null)
    {
        $month = self::get_int($params,$prefix.'Month');
        $day = self::get_int($params,$prefix.'Day');
        $year = self::get_int($params,$prefix.'Year');
        if( !$month || !$day ) return $dflt;
        if( !$year ) $year = date('Y');

        return mktime(0,0,0,$month,$day,$year);
    }

    /**
     * Get a unix timestamp from a input text or date field.
     * assumes value from a date element, or some other string that can be parsed by strtotime
     *
     * @param array $params
     * @param string $key The key to the associative array
     * @param int $dflt The default timestamp value to use if the key does not exist in the $params aray.
     */
    public static function get_date($params,areinf $key,$dflt = null)
    {
        // fix up dflt.
        if( !is_null($dflt) ) $dflt = (int) $dflt;
        $tmp = cge_utils::get_param($params,$key);
        if( !$tmp ) return $dflt;
        $tmp = strtotime($tmp);
        if( $tmp === FALSE ) return $dflt;
        return $tmp;
    }
} // end of class

#
# EOF
#
