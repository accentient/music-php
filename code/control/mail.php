<?php

//--------------------------------------------------------------------
// MAIL OPERATIONS
// See notes below where applicable. DO NOT change this file unless
//  you know what you are doing.
//--------------------------------------------------------------------

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

if (!defined('BASE_HREF')) {
  define('BASE_HREF', $SETTINGS->httppath);
}

//------------
// SMTP
//------------

$smtp             =  array(
 'smtp_host'      => $SETTINGS->smtp_host,
 'smtp_port'      => $SETTINGS->smtp_port,
 'smtp_user'      => $SETTINGS->smtp_user,
 'smtp_pass'      => $SETTINGS->smtp_pass,
 'smtp_security'  => $SETTINGS->smtp_security,
 'alive'          => (isset($alive) ? $alive : 'no')
);

//------------------------------------------------
// MAIL HEADERS (All Emails)
//------------------------------------------------
//
// Key => Value pairs. Key must start X-
// Example:
//
// $headers = array('X-Header' => 'Value');
//
//------------------------------------------------

$headers          = array();

//------------------------------------------------
// MAIL ATTACHMENTS (All Emails)
//------------------------------------------------
//
// Value = full attachment path
// Example:
//
// $attachments = array('c:\windows\attachment.jpg','c:\windows\attachment2.jpg');
//
//------------------------------------------------

$attachments      = array();

//------------------------------------------------
// LOAD CLASS AND DEFAULT TAGS
//------------------------------------------------

include(MM_BASE_PATH.'control/classes/class.mail.php');
$mmMail           =  new mailingSystem($smtp,$headers,$attachments);
$f_r              =  array(
 '{WEBSITE}'      => $SETTINGS->website,
 '{WEBSITE_URL}'  => BASE_HREF,
 '{ADMIN_FOLDER}' => MM_ADMIN_FOLDER
);

?>