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
 * A utility class to manipulate HTML headers
 *
 * @package CGExtensions
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

declare(strict_types=1);

/**
 * A utility class to manipulate HTML headers
 *
 * @package CGExtensions
 */
final class cge_headers
{
    /**
     * @ignore
     */
    private static $_headers;

    /**
     * @ignore
     */
    private function __construct() {}

    /**
     * Test if we have stored any header values
     *
     * @return bool
     */
    public static function have_headers() : bool
    {
        return is_array(self::$_headers);
    }

    /**
     * Add a specific header string to the store.
     *
     * @param string $header
     */
    public static function add_header(string $header)
    {
        if( !is_array(self::$_headers) ) self::$_headers = array();
        self::$_headers[] = $header;
    }

    /**
     * Output stored headers.
     *
     * @see header()
     * @return void
     */
    public static function output_headers()
    {
        if( !is_array(self::$_headers) ) return;

        foreach( self::$_headers as $value ) {
            header($value);
        }
    }

} // end of class
