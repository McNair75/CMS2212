<?php
#BEGIN_LICENSE
#-------------------------------------------------------------------------
# Module: CGExtensions (c) 2008-2018 by Robert Campbell
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
 * A class to act as a processor for Email objects.
 *
 * @package CGExtensions\Email
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2018 by Robert Campbell
 */

namespace CGExtensions\Email;
use cms_mailer;

/**
 * A class to act as a processor for Email objects.
 *
 * When this object sends messages it tries to use some intelligence so that the same email
 * is not sent to the same user twice.
 *
 * This class has no mechanism for sending messages in batches.
 *
 * @package CGExtensions
 * @since 1.59
 */
class SimpleEmailProcessor implements EmailProcessor
{

    /**
     * @ignore
     */
    private $_eml;

    /**
     * @ignore
     */
    private $_mailer;

    /**
     * @ignore
     */
    private $_before_send;

    /**
     * @ignore
     */
    private $_after_send;

    /**
     * @ignore
     */
    private $_on_error;

    /**
     * @ignore
     */
    private $_tpl;

    /**
     * Constructor
     *
     * @param Email $eml The email object that will be used to send messaage.
     * @param cms_mailer $mailer The mailer object to use.
     */
    public function __construct( Email $eml, cms_mailer $mailer )
    {
        if( !$eml->has_addresses() ) throw new AddressException('Cannot send email... no addresses');

        $this->_eml = $eml;
        $this->_mailer = $mailer;
    }

    /**
     * Set a callback to be executed before each message is compiled and sent.
     *
     * @param callable $c
     * @return void
     */
    public function before_send( callable $c )
    {
        $this->_before_send = $c;
    }

    /**
     * Set a callback to be executed after each message is compiled and successfuly sent.
     *
     * @param callable $c
     * @return void
     */
    public function after_send( callable $c )
    {
        $this->_after_send = $c;
    }

    /**
     * Set a callback to be executed when sending a message fails.
     *
     * @param callable $c
     * @return void
     */
    public function on_error( callable $c )
    {
        $this->_on_error = $c;
    }

    /**
     * Resolve admin groups into an array of email addresses
     *
     * @internal
     * @return EmailDestination[]
     */
    protected function resolve_admin_groups()
    {
        $out = [];
        $list = $this->_eml->to_admin_groups;
        if( count( $list ) ) {
            foreach( $list as $one ) {
                $one = trim($one);
                $gid = 0;
                if( ! (is_numeric($one) && (int) $one > 0) ) {
                    // get did from name
                    $gid = \cge_userops::get_groupid( $one );
                } else {
                    $gid = (int) $one;
                }
                if( $gid > 0 ) {
                    $tmp = [];
                    $ops = \UserOperations::get_instance();
                    $ulist = $ops->LoadUsersInGroup( $gid );
                    if( $ulist && count($ulist) ) {
                        foreach( $ulist as $user ) {
                            if( !$user->active ) continue;
                            if( !$user->email ) continue;

                            $obj = new EmailDestination;
                            $obj->addr = $user->email;
                            $obj->name = $user->firstname;
                            $obj->admin_gid = $gid;
                            $obj->admin_uid = $user->id;
                            $tmp[] = $obj;
                        }
                        $out = array_merge( $out, $tmp );
                    }
                }
            }
        }
        if( count($out) ) return $out;
    }

    /**
     * Resolve an array of FEU uids into an array of email addresses.
     *
     * @internal
     * @return EmailDestination[]
     */
    protected function resolve_feu_uids()
    {
        $feu = cmsms()->GetModuleInstance('FrontEndUsers');
        if( !$feu ) return;

        $out = [];
        if( count( $this->_eml->to_feu_users ) ) {
            $qry = new \feu_user_query;
            $qry->add_and_opt( \feu_user_query_opt::MATCH_USERLIST, $this->_eml->to_feu_users );
            // todo: notexpired, notdisabled??
            $qry->set_pagelimit( 500 ); // arbitrary limit?
            $rs = $qry->execute();
            while( !$rs->EOF() ) {
                $flds = $rs->fields;

                $obj = new EmailDestination;
                $obj->addr = $flds['email'];
                $obj->feu_uid = $flds['id'];
                $out[] = $obj;

                $rs->MoveNext();
            }
        }
        if( count($out) ) return $out;
    }

    /**
     * Resolve an array of feu group names/ids into an array of email addresses.
     *
     * @internal
     * @return EmailDestination[]
     */
    protected function resolve_feu_groups()
    {
        $feu = cmsms()->GetModuleInstance('FrontEndUsers');
        if( !$feu ) return;

        $out = [];
        $list = $this->_eml->to_feu_groups;
        if( count($list) ) {
            foreach( $list as $one ) {
                $one = trim($one);
                $gid = 0;
                if( ! (is_numeric($one) && (int) $one > 0 ) ) {
                    $gid = $feu->GetGroupID( $one );
                } else {
                    $gid = (int) $one;
                }
                if( $gid > 0 ) {
                    // get the emails for all members of this group
                    $tmp = null;
                    $qry = new \feu_user_query;
                    $qry->add_and_opt( \feu_user_query_opt::MATCH_GROUPID, $gid );
                    $qry->set_pagelimit( 500 );
                    $rs = $qry->execute();
                    while( !$rs->EOF() ) {
                        $flds = $rs->fields;

                        $obj = new EmailDestination;
                        $obj->addr = $flds['email'];
                        $obj->feu_gid = $gid;
                        $obj->feu_uid = $flds['id'];
                        $tmp[] = $obj;

                        $rs->MoveNext();
                    }
                    if( $tmp ) $out = array_merge( $out, $tmp );
                }
            }
        }
        if( count($out) ) return $out;
    }

    /**
     * Get info about the currently logged in administrator (for admin requests)
     *
     * @internal
     * @return EmailDestination
     */
    protected function resolve_current_admin()
    {
        if( ! $this->_eml->to_current_admin ) return;
        $uid = get_userid( FALSE );
        if( $uid < 1 ) return;

        // todo: set name here
        $obj = new EmailDestination;
        $obj->addr = \cge_userops::get_uid_email();
        $obj->admin_uid = $uid;
        return $obj;
    }

    /**
     * Get info about the currently logged in FEU user (for frontend requests)
     *
     * @internal
     * @return EmailDestination
     */
    protected function resolve_current_feu()
    {
        if( ! $this->_eml->to_current_feu ) return;
        $feu = cmsms()->GetModuleInstance('FrontEndUsers');
        if( !$feu ) return;
        $uid = $feu->LoggedInId();
        if( $uid < 1 ) return;

        $obj = new EmailDestination;
        $obj->addr = $feu->LoggedInEmail();
        $obj->feu_uid = $uid;
        return $obj;
    }

    /**
     * Resolve all of the to_addresses into Email messages
     *
     * @internal
     * @return EmailDestination[]
     */
    protected function resolve_to_addr()
    {
        $out = [];
        $list = $this->_eml->to_addr;
        if( count($list) ) {
            foreach( $list as $one ) {
                $one = trim($one);
                if( !is_email($one) ) continue;
                $obj = new EmailDestination;
                $obj->addr = $one;
                $obj->name = $this->_eml->get_email_name($one);
                $out[] = $obj;
            }
        }
        if( count($out) ) return $out;
    }


    /**
     * Get a list of all addresses that the current email should be sent to.
     *
     * @return EmailDestination[]
     */
    protected function resolve_dest_addresses()
    {
        $pre = [];
        $tmp = $this->resolve_admin_groups();
        if( $tmp && count($tmp) ) $pre = array_merge( $tmp, $pre );
        $tmp = $this->resolve_feu_uids();
        if( $tmp && count($tmp) ) $pre = array_merge( $tmp, $pre );
        $tmp = $this->resolve_feu_groups();
        if( $tmp && count($tmp) ) $pre = array_merge( $tmp, $pre );
        $tmp = $this->resolve_current_admin();
        if( $tmp ) $pre[] = $tmp;
        $tmp = $this->resolve_current_feu();
        if( $tmp ) $pre[] = $tmp;
        $tmp = $this->resolve_to_addr();
        if( $tmp && count($tmp) ) $pre = array_merge( $tmp, $pre );

        // now reduce this list to unique addresses
        $out = array_unique( $pre, SORT_REGULAR );
        return $out;
    }

    /**
     * Get a list of all addresses that the current email should be CC'd to
     *
     * @return EmailDestination[]
     */
    protected function resolve_cc_emails()
    {
        $list = $this->_eml->cc_addr;
        if( !$list || !is_array($list) || !count($list) ) return;
        $list = array_unique($list);

        $out = null;
        foreach( $list as $one ) {
            if( is_email($one) && !in_array( $one, $out ) ) {
                $obj = new EmailDestionation;
                $obj->addr = $one;
                $obj->name = $this->_eml->get_email_name($one);
                $out[] = $obj;
            }
        }
        return $out;
    }

    /**
     * Get a list of all addresses that the current email should be BCC'd to
     *
     * @return EmailDestination[]
     */
    protected function resolve_bcc_emails()
    {
        $list = $this->_eml->bcc_addr;
        if( !$list || !is_array($list) || !count($list) ) return;
        $list = array_unique($list);

        $out = null;
        foreach( $list as $one ) {
            if( is_email($one) && !in_array( $one, $out ) ) {
                $obj = new EmailDestionation;
                $obj->addr = $one;
                $obj->name = $this->_eml->get_email_name($one);
                $out[] = $obj;
            }
        }
        return $out;
    }

    /**
     * Execute a callback and provide the email and EmailDestination objects.
     *
     * @internal
     * @param callable $c The callback
     * @param Email $eml  The email
     * @param EmailDestination $dest
     * @return Email
     */
    protected function do_callback( callable $c = null, Email $eml, EmailDestination $dest )
    {
        if( $c && is_callable( $c ) ) {
            $eml = call_user_func_array( $c, [ $eml, $dest ] );
        }
        return $eml;
    }

    /**
     * Given an email object, get the rendered subject.
     *
     * @param Email $eml
     * @return string
     */
    public function get_email_subject(Email $eml) : string
    {
        $subject = '';
        $tpl = $this->get_template($eml);
        if( $eml->subj_tpl ) {
            $subject = $tpl->fetch( 'string:'.$eml->subj_tpl );
            $subject = trim(strip_tags($subject));
        }
        if( $subject && $this->_eml->encode_subject ) {
            $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
        }
        return $subject;
    }

    /**
     * Given an email obbject, get the processed body.
     *
     * @param Email $eml
     * @return string
     */
    public function get_email_body(Email $eml) : string
    {
        $body = '';
        $tpl = $this->get_template($eml);
        if( $eml->body_tpl ) $body = $tpl->fetch( 'string:'.$eml->body_tpl );
        return $body;
    }

    /**
     * Given an email object, get a smarty template to allow processing it.
     * This method assigns all data in the email object to smarty variables.
     *
     * @param Email $eml
     * @return smarty_template  A smarty template object
     */
    protected function get_template(Email $eml)
    {
        if( !$this->_tpl ) {
            $smarty = cmsms()->GetSmarty();
            $this->_tpl = $smarty->CreateTemplate( 'string:' );
            $data = $eml->data;
            if( $data && count($data) ) {
                foreach( $data as $key => $val ) {
                    $this->_tpl->assign( $key, $val );
                }
            }
        }
        return $this->_tpl;
    }

    /**
     * Given an email object, and a single destionation... send the email to the destination.
     *
     * @param Email $eml
     * @param EmailDestination $dest
     */
    protected function process_one_email(Email $eml, EmailDestination $dest)
    {
        $subject = $body = null;
        $mailer = $this->_mailer;

        // process template for subject and body
        $subject = $this->get_email_subject($eml);
        $body = $this->get_email_body($eml);

        if( !$body ) return;
        if( !preg_match('/^(\<html|\<body)/', $body) ) $body = '<body>'.$body.'</body>';

        // setup mailer
        $mailer->reset();
        $mailer->SetPriority( $eml->priority );
        $mailer->SetSubject( $subject );
        $mailer->SetBody( $body );
        $mailer->IsHTML( TRUE );
        if( $eml->attachments ) {
            foreach( $eml->attachments as $file ) {
                $mailer->AddAttachment( $file );
            }
        }
        $mailer->AddAddress( $dest->addr, $dest->name );

        $cc_addresses = $this->resolve_cc_emails();
        $bcc_addresses = $this->resolve_bcc_emails();
        if( $cc_addresses ) {
            foreach( $cc_addresses as $one_cc ) {
                $mailer->AddCC( $one_cc->addr, $one_cc->name );
            }
        }
        if( $bcc_addresses ) {
            foreach( $bcc_addresses as $one_bcc ) {
                $mailer->AddBCC( $one_bcc->addr, $one_bcc->name );
            }
        }

        // send and handle result
        $res = $mailer->Send();
        if( !$res ) {
            $err = $mailer->GetErrorInfo();
            throw new MailTransportError($err);
        }
    }

    /**
     * Process and send the email
     *
     * @return void
     */
    public function send()
    {
        // note to allow for user info expansion, and privace
        // each destination receives a unique email....
        // therefore CC and BCC addresses can receive multiple emails if there are multiple destiantions.

        $to_addresses = $this->resolve_dest_addresses();
        if( !$to_addresses || !count($to_addresses) ) throw new AddressException('No destination addresses for email');

        $n_sent = 0;
        $eml = $this->_eml;

        foreach( $to_addresses as $dest ) {
            // before send
            $eml = $this->do_callback( $this->_before_send, $eml, $dest );
            try {
                $this->process_one_email($eml, $dest);
                $n_sent++;
                $eml = $this->do_callback( $this->_after_send, $eml, $dest );
            }
            catch( \RuntimeException $e ) {
                // if we have an onerror callback we call.  otherwise, just throw the exception again.
                if( $this->_on_error && is_callable( $this->_on_error ) ) {
                    $err = $e->GetMessage();
                    call_user_func_array( $this->_on_error, [ $dest, $err ] );
                }
                else {
                    throw $e;
                }
            }
        }
    }
} // class
