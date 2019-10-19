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
 * A simple class for doing secure encryption and decryption of data.
 * This class relies on the php mcrypt module.
 *
 * @package CGExtensions
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

declare(strict_types=1);

/**
 * A simple class for doing secure encryption and decryption of data.
 * This class relies on the php mcrypt module.
 */
final class cge_encrypt
{
    /**
     * @ignore
     */
    protected function __construct() {}

    /**
     * Encrypt some data
     *
     * @param string $key The encryption key.  The longer and more unique this string is the more secure the encrypted data is.  The key should also be kept in a secure location.
     * @param string $data The data to encrypt.
     * @return string The encrypted data, or FALSE
     */
    static public function encrypt(string $key,string $data)
    {
        return self::openssl_encrypt( $key, $data );
    }

    /**
     * Encrypt some data using openssl libraries
     *
     * @param string $key
     * @param string $data
     * @return string
     */
    static protected function openssl_encrypt(string $key,string $data)
    {
        if( !function_exists('openssl_encrypt') ) return FALSE;

        $cipher = 'aes-256-cbc';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        return $encrypted.'::'.$iv;
    }

    /**
     * Decrypt previously encrypted data
     *
     * @param string $key The key used for encrypting the data.
     * @param string $encdata The encrypted data"
     * @return string the decrypted data.  or FALSE
     */
    static public function decrypt(string $key,string $encdata)
    {
        // use openssl and see if we get any data
        // if openssl fails, try mcrypt.
        return self::openssl_decrypt( $key, $encdata );
    }

    /**
     * Decrypt some data using openssl
     *
     * @param string $key
     * @param string $encdata
     * @return string
     */
    static protected function openssl_decrypt(string $key,string $encdata)
    {
        list( $enc, $iv ) = explode('::', $encdata );
        return openssl_decrypt($enc, 'aes-256-cbc', $key, 0, $iv);
    }

} // end of class
