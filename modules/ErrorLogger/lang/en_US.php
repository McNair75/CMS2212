<?php
$lang['friendlyname'] = 'Error Logger';
$lang['really_uninstall'] = 'Really? Are you sure you want to uninstall '.$lang['friendlyname'].' ?';
$lang['uninstalled'] = 'Module Uninstalled.';
$lang['installed'] = 'Module version %s installed.';
$lang['upgraded'] = 'Module upgraded to version %s.';
$lang['moddescription'] = 'This module writes (system) error messages in to the database and provides the possibility to browse them.';

$lang['error'] = 'Error!';
$land['admin_title'] = 'Error Logger Admin Panel';
$lang['admindescription'] = $lang['moddescription'];
$lang['accessdenied'] = 'Access Denied. Please check your permissions.';

$lang['changelog'] = '<ul>
   <li>Version 1.0 - 31 December 2012.<br />Initial Release.</li>
   <li>Version 1.1 - 20 January 2013.<br />added download as CSV file</li>
   <li>Version 1.2 - June 2015<br />fixed reported bugs (10527, 10528, 10529, 10530)</li>
   <li>Version 1.3 - November 2015<br />prepared for CMSMS 2.x</li>
   <li>Version 1.4 - December 2015<br />added some comments to code</li>
   <li>Version 2.0 - July 2019<br />adapted module to CMSMS 2.x</li>
</ul>';
$lang['help'] = '<h3>What does this module do?</h3>
<p>Some webhoster do not offer access to the log files.<br />'.$lang['moddescription'].'</p>

<h3>How do I use it?</h3>
<p>Just install, and feel free to change some settings.</p>
<p>If your config.php is write protected, add this line by yourself:<br />
<i>require_once("'.realpath(dirname(__FILE__).'/../').'/ErrorLogger.handler.php");</i>
</p>

<h3>Support</h3>
<p>
    <b>Having trouble on upgrade?</b><br />
    Due to new directory structure of CMSMS from version 2.3 upper you may update the path to ErrorHandler in you config.php<br /><br />
    As per the GPL, this software is provided as-is. Please read the text of the license for the full disclaimer.
</p>

<h3>Copyright and License</h3>
<p>Copyright &copy; 2019, Oliver Joo&szlig;. All Rights Are Reserved.</p>

<p>This module has been released under the <a href="http://www.gnu.org/licenses/licenses.html#GPL">GNU Public License</a>. You must agree to this license before using the module.</p>';
