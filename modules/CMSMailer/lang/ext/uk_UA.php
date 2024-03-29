<?php
$lang['friendlyname'] = 'CMSMailer';
$lang['help'] = '<h3>Для чого цей модуль?</h3>
<p><strong>Примітка:</strong> Цей модуль є таким, використання якого не рекомендується (deprecated) у версії CMSMS 2.0. Тому переклад довідки не робився.</p>
	<p>This module provides no end user functionality.  It is designed to be integrated into other modules to provide email capabilities.  That\'s it, nothing more.</p>
	<h3>How Do I Use It</h3>
	<p>This module provides a simple wrapper around all of the methods and variables of phpmailer.  It is designed for use by other module developers, below is an example, and a brief API reference.  Please read the PHPMailer documentation included for more information.</p>
	<h3>An Example</h3>
	<pre>
	  $cmsmailer = $this->GetModuleInstance(\'CMSMailer\');
	  $cmsmailer->AddAddress(\'calguy1000@hotmail.com\',\'calguy\');
	  $cmsmailer->SetBody(\'<h4>This is a test message</h4>\');
	  $cmsmailer->IsHTML(true);
	  $cmsmailer->SetSubject(\'Test message\');
	  $cmsmailer->Send();
	</pre>
	<h3>API</h3>
	<ul>
	<li><p><b>void reset()</b></p>
	<p>Reset the object back to the values specified in the Admin panel</p>
	</li>
	<li><p><b>string GetAltBody()</b></p>
	<p>Return the alternate body of the email</p>
	</li>
	<li><p><b>void SetAltBody( $string )</b></p>
	<p>Set the alternate body of the email</p>
	</li>
	<li><p><b>string GetBody()</b></p>
	<p>Return the primary body of the email</p>
	</li>
	<li><p><b>void SetBody( $string )</b></p>
	<p>Set the primary body of the email</p>
	</li>
	<li><p><b>string GetCharSet()</b></p>
	<p>Default: iso-8859-1</p>
	<p>Return the mailer character set</p>
	</li>
	<li><p><b>void SetCharSet( $string )</b></p>
	<p>Set the mailer character set</p>
	</li>
	<li><p><b>string GetConfirmReadingTo()</b></p>
	<p>Return the address confirmed reading email flag</p>
	</li>
	<li><p><b>void SetConfirmReadingTo( $address )</b></p>
	<p>Set or unset the confirm reading address</p>
	</li>
	<li><p><b>string GetContentType()</b></p>
	<p>Default: text/plain</p>
	<p>Return the content type</p>
	</li>
	<li><p><b>void SetContentType()</b></p>
	<p>Set the content type</p>
	</li>
	<li><p><b>string GetEncoding()</b></p>
	<p>Return the encoding</p>
	</li>
	<li><p><b>void SetEncoding( $encoding )</b></p>
	<p>Set the encoding</p>
	<p>Options are: 8bit, 7bit, binary, base64, quoted-printable</p>
	</li>
	<li><p><b>string GetErrorInfo()</b></p>
	<p>Return any error information</p>
	</li>
	<li><p><b>string GetFrom()</b></p>
	<p>Return the current originating address</p>
	</li>
	<li><p><b>void SetFrom( $address )</b></p>
	<p>Set the originating address</p>
	</li>
	<li><p><b>string GetFromName()</b></p>
	<p>Return the current originating name</p>
	</li>
	<li><p><b>SetFromName( $name )</b></p>
	<p>Set the originating name</p>
	</li>
	<li><p><b>string GetHelo()</b></p>
	<p>Return the HELO string</p>
	</li>
	<li><p><b>SetHelo( $string )</b></p>
	<p>Set the HELO string</p>
	<p>Default value: $hostname</p>
	</li>
	<li><p><b>string GetHost()</b></p>
	<p>Return the SMTPs host separated by semicolon</p>
	</li>
	<li><p><b>void SetHost( $string )</b></p>
	<p>Set the hosts</p>
	</li>
	<li><p><b>string GetHostName()</b></p>
	<p>Return the hostname used for SMTP Helo</p>
	</li>
	<li><p><b>void SetHostName( $hostname )</b></p>
	<p>Set the hostname used for SMTP Helo</p>
	</li>
	<li><p><b>string GetMailer()</b></p>
	<p>Return the mailer</p>
	</li>
	<li><p><b>void SetMailer( $mailer )</b></p>
	<p>Set the mailer, either sendmail,mail, or smtp</p>
	</li>
	<li><p><b>string GetPassword()</b></p>
	<p>Return the password for smtp auth</p>
	</li>
	<li><p><b>void SetPassword( $string )</b></p>
	<p>Set the password for smtp auth</p>
	</li>
	<li><p><b>int GetPort()</b></p>
	<p>Return the port number for smtp connections</p>
	</li>
	<li><p><b>void SetPort( $int )</b></p>
	<p>Set the port for smtp connections</p>
	</li>
	<li><p><b>int GetPriority()</b></p>
	<p>Return the message priority</p>
	</li>
	<li><p><b>void SetPriority( int )</b></p>
	<p>Set the message priority</p>
	<p>Values are 1=High, 3 = Normal, 5 = Low</p>
	</li>
	<li><p><b>string GetSender()</b></p>
	<p>Return the sender email (return path) string</p>
	</li>
	<li><p><b>void SetSender( $address )</b></p>
	<p>Set the sender string</p>
	</li>
	<li><p><b>string GetSendmail()</b></p>
	<p>Return the sendmail path</p>
	</li>
	<li><p><b>void SetSendmail( $path )</b></p>
	<p>Set the sendmail path</p>
	</li>
	<li><p><b>bool GetSMTPAuth()</b></p>
	<p>Return the current value of the smtp auth flag</p>
	</li>
	<li><p><b>SetSMTPAuth( $bool )</b></p>
	<p>Set the smtp auth flag</p>
	</li>
	<li><p><b>bool GetSMTPDebug()</b></p>
	<p>Return the value of the SMTP debug flag</p>
	</li>
	<li><p><b>void SetSMTPDebug( $bool )</b></p>
	<p>Set the SMTP debug flag</p>
	</li>
	<li><p><b>bool GetSMTPKeepAlive()</b></p>
	<p>Return the value of the SMTP keep alive flag</p>
	</li>
	<li><p><b>SetSMTPKeepAlive( $bool )</b></p>
	<p>Set the SMTP keep alive flag</p>
	</li>
	<li><p><b>string GetSubject()</b></p>
	<p>Return the current subject string</p>
	</li>
	<li><p><b>void SetSubject( $string )</b></p>
	<p>Set the subject string</p>
	</li>
	<li><p><b>int GetTimeout()</b></p>
	<p>Return the timeout value</p>
	</li>
	<li><p><b>void SetTimeout( $seconds )</b></p>
	<p>Set the timeout value</p>
	</li>
	<li><p><b>string GetUsername()</b></p>
	<p>Return the smtp auth username</p>
	</li>
	<li><p><b>void SetUsername( $string )</b></p>
	<p>Set the smtp auth username</p>
	</li>
	<li><p><b>int GetWordWrap()</b></p>
	<p>Return the wordwrap value</p>
	</li>
	<li><p><b>void SetWordWrap( $int )</b></p>
	<p>Return the wordwrap value</p>
	</li>
	<li><p><b>AddAddress( $address, $name = \'\' )</b></p>
	<p>Add a destination address</p>
	</li>
	<li><p><b>AddAttachment( $path, $name = \'\', $encoding = \'base64\', $type = \'application/octent-stream\' )</b></p>
	<p>Add a file attachment</p>
	</li>
	<li><p><b>AddBCC( $address, $name = \'\' )</b></p>
	<p>Add a BCC\'d destination address</p>
	</li>
	<li><p><b>AddCC( $address, $name = \'\' )</b></p>
	<p>Add a CC\'d destination address</p>
	</li>
	<li><p><b>AddCustomHeader( $txt )</b></p>
	<p>Add a custom header to the email</p>
	</li>
	<li><p><b>AddEmbeddedImage( $path, $cid, $name = \'\', $encoding = \'base64\', $type = \'application/octent-stream\' )</b></p>
	<p>Add an embedded image</p>
	</li>
	<li><p><b>AddReplyTo( $address, $name = \'\' )</b></p>
	<p>Add a reply to address</p>
	</li>
	<li><p><b>AddStringAttachment( $string, $filename, $encoding = \'base64\', $type = \'application/octent-stream\' )</b></p>
	<p>Add a file attachment</p>
	</li>
	<li><p><b>ClearAddresses()</b></p>
	<p>Clear all addresses</p>
	</li>
	<li><p><b>ClearAllRecipients()</b></p>
	<p>Clear all recipients</p>
	</li>
	<li><p><b>ClearAttachments()</b></p>
	<p>Clear all attachments</p>
	</li>
	<li><p><b>ClearBCCs()</b></p>
	<p>Clear all BCC addresses</p>
	</li>
	<li><p><b>ClearCCs()</b></p>
	<p>Clear all CC addresses</p>
	</li>
	<li><p><b>ClearCustomHeaders()</b></p>
	<p>Clear all custom headers</p>
	</li>
	<li><p><b>ClearReplyto()</b></p>
	<p>Clear reply to address</p>
	</li>
	<li><p><b>IsError()</b></p>
	<p>Check for an error condition</p>
	</li>
	<li><p><b>bool IsHTML( $bool )</b></p>
	<p>Set the html flag</p>
	<p><i>Note</i> possibly this should be a get and set method</p>
	</li>
	<li><p><b>bool IsMail()</b></p>
	<p>Check whether we are using mail</p>
	</li>
	<li><p><b>bool IsQmail()</b></p>
	<p>Check whether we are using qmail</p>
	</li>
	<li><p><b>IsSendmail()</b></p>
	<p>Check whether we are using sendmail</p>
	</li>
	<li><p><b>IsSMTP()</b></p>
	<p>Check whether we are using smtp</p>
	</li>
	<li><p><b>Send()</b></p>
	<p>Send the currently prepared email</p>
	</li>
	<li><p><b>SetLanguage( $lang_type, $lang_path = \'\' )</b></p>
	<p>Set the current language and <em>(optional)</em> language path</p>
	</li>
	<li><p><b>SmtpClose()</b></p>
	<p>Close the smtp connection</p>
	</li>
	</ul>
	<h3>Support</h3>
	<p>This module does not include commercial support. However, there are a number of resources available to help you with it:</p>
	<ul>
	<li>For the latest version of this module, FAQs, or to file a Bug Report or buy commercial support, please visit calguys homepage at <a href=\'http://techcom.dyndns.org\'>techcom.dyndns.org</a>.</li>
	<li>Additional discussion of this module may also be found in the <a href=\'https://forum.cmsmadesimple.org\'>CMS Made Simple Forums</a>.</li>
	<li>The author, calguy1000, can often be found in the <a href=\'irc://irc.freenode.net/#cms\'>CMS IRC Channel</a>.</li>
	<li>Lastly, you may have some success emailing the author directly.</li>  
	</ul>
	<p>As per the GPL, this software is provided as-is. Please read the text
	of the license for the full disclaimer.</p>

	<h3>Copyright and License</h3>
	<p>Copyright © 2005, Robert Campbell <a href=\'mailto:calguy1000@hotmail.com\'><calguy1000@hotmail.com></a>. All Rights Are Reserved.</p>
	<p>This module has been released under the <a href=\'http://www.gnu.org/licenses/licenses.html#GPL\'>GNU Public License</a>. You must agree to this license before using the module.</p>';
$lang['moddescription'] = 'Це просто обгортка для PHPMailer, вона моє ідентичні API (функція до функції) та простий інтерфейс для деяких параметрів за замовчуванням. Цей модуль є таким, використання якого не рекомендується (deprecated) у версії CMSMS 2.0';
$lang['postinstall'] = 'Модуль CMSMailer успішно встановлено';
$lang['postuninstall'] = 'Модуль CMSMailer було деінстальовано';
?>