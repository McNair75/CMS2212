<?php
/**
 * An example function demonstrating how to process and send a templated email from a module action.
 * Assuming that this is a module action file, in a module derived from CGExtensions
 * and a file entitled emails/foo.eml exists in the module directory.
 *
 * The foo.eml is a formatted text file that looks something line:
 *
 * encode_subject=true
 * ==== subject template ====
 * Notification from {sitename}
 * ==== body template ===
 * <h2>Hello {$my_name}</h3>
 * <p>This message was sent to you at <strong>{$smarty.now|date_format:'%x %X'}</strong></p>
 *
 * The top section of the file (above the section header separator) is in .ini file format
 * and allows specifying numerous options, and addresses
 * The second section of the file is a smarty template for the email subject.  Note that a subject cannot normally contain extended characters, and must be only one line long, unless encoded.
 * The third section of the eml file is a smarty template for the email body.  It permits HTML emails, but remember that stylesheets do not work.
 */
if( !isset($gCms) ) exit;

$email = $this->get_email_storage()->load('foo.eml');
if( $email ) {
    $email->add_data('my_name','foo bar');
    $email->add_address('somebody@localhost.localdomain');
    $this->create_new_mailprocessor($email)->send();
}