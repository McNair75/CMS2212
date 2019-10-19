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

if( version_compare(phpversion(),'7.2.1') < 0 ) {
    return "Minimum PHP version of 7.2.1 required";
}

$db = \cge_utils::get_db();
$taboptarray = array( 'mysql' => 'TYPE=MyISAM' );
$dict = NewDataDictionary($db);

$flds = "id I KEY AUTO,
         key1 C(255),
         key2 C(255),
         key3 C(255),
         key4 C(255),
         data X,
         type C(20),
         expiry C(20),
         create_date ".CMS_ADODB_DT.",
         modified_date ".CMS_ADODB_DT;
$sqlarray = $dict->CreateTableSQL(CGEXTENSIONS_TABLE_ASSOCDATA,$flds,$taboptarray);
$dict->ExecuteSQLArray($sqlarray);

$libdefn = new CGExtensions\jsloader\libdefn('cg_cmsms');
$libdefn->callback = ['cge_jshandler','load'];
$this->get_jsloader()->register($libdefn);
