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
 * A simple class library to provide key/value storage
 * and searching.  and to include builtin caching
 *
 * @package CGExtensions
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

declare(strict_types=1);
namespace CGExtensions;
use CMSMS\Database\Connection as Database;

/**
 * A node containing associative data.
 *
 * This class is used exclusively by the AssocData class
 *
 * @internal
 * @package CGExtensions
 */
class AssocDataNode
{
    /**
     * @ignore
     */
    private $_type;

    /**
     * @ignore
     */
    private $_data;

    /**
     * @ignore
     */
    private $_extra;

    /**
     * Construct a new AssocDataNode object.
     *
     * @param string $type (The data type.  i.e: object, int, boolean, array) (deprecated)
     * @param mixed $data  The data to store.
     */
    public function __construct(string $type,$data)
    {
        $this->_type = $type;
        $this->_data = $data;
    }

    /**
     * Return the type of data
     *
     * @return string
     */
    public function GetType()
    {
        return $this->_type;
    }

    /**
     * Set the data type for this record.
     *
     * @param string $type Data type
     */
    public function SetType(string $type)
    {
        $this->_type = $type;
    }

    /**
     * Set the data for this record
     * May be a serialized object or array
     *
     * @return mixed
     */
    public function GetData()
    {
        return $this->_data;
    }

    /**
     * Set the data for this record
     * May be a serialized object or array
     *
     * @param mixed $data
     */
    public function SetData($data)
    {
        $this->_data = $data;
    }
}; // class



/**
 * A class to manage associative data.  Particularly useful for storing data for which there are up to 3 hierarchical keys in the database
 * and retrieving that data at a later date.  This class automatically serializes and de-serializes complex types such as arrays or objects.
 *
 * @package CGExtensions
 */
class AssocData
{
    /**
     * @ignore
     */
    private $_db;

    /**
     * @ignore
     */
    private $_key1;

    /**
     * @ignore
     */
    private $_cachesize;

    /**
     * @ignore
     */
    private $_cache;

    /**
     * Constructor
     *
     * @param object $db  A reference to the adodb db object
     * @param string $key1 The first key.
     * @param integer $cachesize int Number of elements to store in the cache
     */
    public function __construct(Database $db, string $key1, int $cachesize=100)
    {
        $this->_db = $db;
        $this->_key1 = $key1;
        $this->_cachesize = (int) $cachesize;
    }


    /**
     * Clear the cache
     *
     * @ignore
     */
    private function _clearcache()
    {
        $this->_cache = array();
    }


    /**
     * Set an item into the cache
     *
     * @ignore
     * @param string Second key
     * @param string Third key
     * @param string Fourth key
     * @param object The data to store
     */
    private function _setcache(string $key2, string $key3, string $key4, AssocDataNode $node)
    {
        if( !is_array($this->_cache) ) {
            $this->_cache = array();
        }
        else {
            if( count($this->_cache) >= $this->_cachesize ) $this->_cache = array_shift($this->_cache);
        }

        $key = implode('+++',array($key2,$key3,$key4));
        $this->_cache[$key] = $node;
    }


    /**
     * Set an item into the cache
     * and into the database.
     *
     * @ignore
     * @param string $key2 Second key
     * @param string $key3 Third key
     * @param string $key4 Fourth key
     * @param AssocDataNode $node The data to store
     */
    private function _set(string $key2,string $key3,string $key4, AssocDataNode $node)
    {
        // call _setcache
        $this->_setcache($key2,$key3,$key4,$node);

        // if exists update
        $now = $this->_db->DbTimeStamp(time());
        $query = 'SELECT id FROM '.CGEXTENSIONS_TABLE_ASSOCDATA.' WHERE key1 = ? AND key2 = ? AND key3 = ? AND key4 = ? LIMIT 1';
        $id = $this->_db->GetOne($query, array($this->_key1,$key2,$key3,$key4));
        if( $id ) {
            $query = 'UPDATE '.CGEXTENSIONS_TABLE_ASSOCDATA." SET type = ?, data = ?, modified_date = $now
                WHERE key1 = ? AND key2 = ? AND key3 = ? AND key4 = ?";
            $this->_db->Execute($query, array($node->GetType(),$node->GetData(), $this->_key1,$key2,$key3,$key4));
        }
        else {
            $query = 'INSERT INTO '.CGEXTENSIONS_TABLE_ASSOCDATA." (key1,key2,key3,key4,type,data,create_date,modified_date)
                VALUES (?,?,?,?,?,?,$now,$now)";
            $this->_db->Execute($query,array($this->_key1,$key2,$key3,$key4,$node->GetType(),$node->GetData()));
        }
    }


    /**
     * Store some data
     *
     * <code>
     * $obj = new AssocData(cmsms()->GetDb(),'addresses');
     * $obj->Set('user1','123 Somewhere Street');
     * </code>
     *
     * @param string $key2 The second key
     * @param mixed  $value The data to store
     * @param string $key3 An optional third key
     * @param string $key4 An optional fourth key
     */
    public function Set(string $key2, $value, string $key3 = '', string $key4 = '')
    {
        $key2 = trim($key2);
        $key3 = trim($key3);
        $key4 = trim($key4);
        if( empty($key2) ) return FALSE;
        if( empty($key3) && !empty($key4) ) return FALSE;

        // determine type
        $type = 'simple';
        $data = $value;
        if( is_object($value) ) {
            $type = 'object';
            $data = serialize($value);
        }
        else if( is_array($value) ) {
            $type = 'array';
            $data = serialize($value);
        }

        $node = new AssocDataNode($type,$data);
        // call _set
        $this->_set($key2,$key3,$key4,$node);
    }


    /**
     * Get data from the cache
     *
     * @ignore
     * @param string $key2
     * @param string $key3
     * @param string $key4
     * @return object AssocDataNode
     */
    private function _getcache(string $key2, string $key3, string $key4)
    {
        $key = implode('+++',array($key2,$key3,$key4));
        if( !isset($this->_cache[$key]) ) return FALSE;
        return $this->_cache[$key];
    }


    /**
     * Retrieve stored data
     *
     * If not available in the cache, an attempt is made to pullthe
     * data from the database and store it in the cache.
     *
     * @ignore
     * @param string $key2
     * @param string $key3
     * @param string $key4
     * @returnobject AssocDataNode
     */
    private function _get(string $key2, string $key3, string $key4)
    {
        // call _getcache
        $tmp = $this->_getcache($key2,$key3,$key4);
        if( $tmp === FALSE ) {
            // retreive data from database
            $query = 'SELECT data,type FROM '.CGEXTENSIONS_TABLE_ASSOCDATA.' WHERE key1=? AND key2=? AND key3=? AND key4=? LIMIT 1';
            $row = $this->_db->GetRow($query, array($this->_key1,$key2,$key3,$key4));
            if( !$row ) return FALSE;
            $tmp = new AssocDataNode($row['type'],$row['data']);

            // update cache
            $this->_setcache($key2,$key3,$key4,$tmp);
        }
        return $tmp;
    }


    /**
     * Retrieve stored data
     *
     * will pull the data from the cache if possible, and if necessary
     * from the database.
     *
     * <code>
     * $obj = new AssocData(cmsms()->GetDb(),'addresses');
     * $the_address = $obj->Get('user1');
     * </code>
     *
     * @param string $key2 A second key
     * @param string $key3 An optional third key
     * @param string $key4 An optional 4th key
     * @return mixed The resulting data.  If no data is found FALSE is returned.
     */
    public function Get(string $key2, string $key3 = '', string $key4 = '')
    {
        $key2 = trim($key2);
        if( $key3 ) $key3 = trim($key3);
        if( $key4 ) $key4 = trim($key4);
        if( empty($key2) ) return FALSE;
        if( empty($key3) && !empty($key4) ) return FALSE;

        // call _get
        $node = $this->_get($key2,$key3,$key4);
        if( $node === FALSE ) return FALSE;

        // deserialize
        if( $node->GetType() == 'simple' )return $node->GetData();
        return unserialize($node->GetData());
    }


    /**
     * Retrieve data from the database, ignoring any cache
     *
     * @param string $key2 A second key
     * @param string $key3 An optional third key
     * @param string $key4 An optional 4th key
     * @return mixed The resulting data.  If no data is found FALSE is returned.
     */
    public function GetFullNoCache(string $key2, string $key3 = '', string $key4 = '')
    {
        $key2 = trim($key2);
        $key3 = trim($key3);
        $key4 = trim($key4);
        if( empty($key2) ) return FALSE;
        if( empty($key3) && !empty($key4) ) return FALSE;

        // return an associative array of all data for a particular row
        $query = 'SELECT * FROM '.CGEXTENSIONS_TABLE_ASSOCDATA.' WHERE key1 = ? AND key2 = ? AND key3 = ? AND key4 = ?';
        $row = $this->_db->GetRow($query,array($this->_key1,$key2,$key3,$key4));
    }


    /**
     * List values stored in the cache for a particular set of keys
     *
     * @param string $key2
     * @param string $key3 An optional third key
     * @return array listing all matching values.
     */
    public function GetList(string $key2, string $key3 = '')
    {
        // returns an array associations of all matching keys
        if( empty($key2) ) return FALSE;

        $qparms = array();
        $qparms[] = $this->_key1;
        $qparms[] = $key2;
        $query = 'SELECT key1,key2,key3,key4 FROM '.CGEXTENSIONS_TABLE_ASSOCDATA.' WHERE key1 = ? AND key2 = ?';
        if( !empty($key3) ) {
            $query .= ' AND key3 = ?';
            $qparms[] = $key3;
        }
        $results = $this->_db->GetArray($query,$qparms);
        if( !is_array($results) ) return FALSE;
        return $results;
    }


    /**
     * Delete a stored value.
     *
     * <code>
     * $obj = new AssocData(cmsms()->GetDb(),'addresses');
     * $obj->Delete('user1');
     * </code>
     *
     * @param string $key2
     * @param string $key3 an optional key3
     * @param string $key4 an optional key4
     * @return bool
     */
    public function Delete(string $key2, string $key3 = '', string $key4 = '')
    {
        // delete all matching keys
        $key2 = trim($key2);
        $key3 = trim($key3);
        $key4 = trim($key4);
        if( empty($key2) ) return FALSE;
        if( empty($key3) && !empty($key4) ) return FALSE;

        $this->_clearcache();

        $qparms = array();
        $qparms[] = $this->_key1;
        $qparms[] = $key2;
        $query = 'DELETE FROM '.CGEXTENSIONS_TABLE_ASSOCDATA.'
               WHERE key1 = ? AND key2 = ?';
        if( !empty($key3) ) {
            $query .= ' AND key3 = ?';
            $qparms[] = $key3;
            if( !empty($key4) ) {
                $query .= ' AND key4 = ?';
                $qparms[] = $key4;
            }
        }
        $db = $this->_db->Execute($query,$qparms);
        return TRUE;
    }


    /**
     * Find all of the entries that match
     *
     * @param mixed $key2 (optional) (specify a string for an exact match, an array for IN expressin)
     * @param string $key3 (optional)
     * @param string $key4 (optional)
     * @return array of matching key1,key2,key3,key4 values
     */
    public function find(string $key2 = '', string $key3 = '', string $key4 = '')
    {
        if( !$key1 && !$key2 && !$key3 && !$key4 ) return;
        $query = 'SELECT key1,key2,key3,key4 FROM '.CGEXTENSIONS_TABLE_ASSOCDATA;

        $where = array();
        $qparms = array();
        if( $key2 ) {
            if( is_array($key2) ) {
                for( $i = 0, $n = count($key2); $i < $n; $i++ ) {
                    $key2[$i] = "'".$key2[$i]."'";
                }
                $str = implode(',',$key2);
                $where[] = 'key1 IN ('.$str.')';
            }
            else {
                $where[] = 'key2 = ?';
                $qparms[] = $key2;
            }
        }
        if( $key3 ) {
            $where[] = 'key3 = ?';
            $qparms[] = $key3;
        }
        if( $key4 ) {
            $where[] = 'key4 = ?';
            $qparms[] = $key4;
        }
        if( !count($where) ) return;

        $query .= ' WHERE '.implode(' AND ',$where);
        $db = $this->_db;
        $dbr = $db->GetArray($query,$qparms);
        if( !is_array($dbr) ) return;
        return $dbr;
    }
} // class
