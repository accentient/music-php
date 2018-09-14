<?php

if (!defined('PARENT') || !defined('BASE_HREF') || !isset($_GET['ve'])) {
  mswEcode($gblang[4], '403');
}

if (ctype_alnum($_GET['ve'])) {
  include(PATH . 'control/mail.php');
  // Does account exist with this code?
  $notify = ($SETTINGS->emnotify ? unserialize($SETTINGS->emnotify) : array());
  $code   = mswSafeString($_GET['ve'], $DB);
  $Q      = $DB->db_query("SELECT * FROM `" . DB_PREFIX . "accounts` WHERE `system1` = '{$code}' LIMIT 1");
  $A      = $DB->db_object($Q);
  if (isset($A->id)) {
    // Activate and send emails..
    $affRows = $ACC->update(array(
      'system1' => '',
      'system2' => '',
      'enabled' => 'yes'
    ), $A->id);
    if ($affRows > 0) {
      $f_r['{NAME}']  = $A->name;
      $f_r['{EMAIL}'] = $A->email;
      $f_r['{IP}']    = $A->ip;
      $f_r['{TZ}']    = $A->timezone;
      $f_r['{ID}']    = $A->id;
      // Customer..
      if (isset($notify['cuscr'])) {
        $msg = strtr(file_get_contents(PATH . 'content/language/email-templates/cus-account-activated.txt'), $f_r);
        $sbj = str_replace('{website}', $SETTINGS->website, $emlang[10]);
        $mmMail->sendMail(array(
          'to_name' => $A->name,
          'to_email' => $A->email,
          'from_name' => ($SETTINGS->smtp_from ? $SETTINGS->smtp_from : $SETTINGS->website),
          'from_email' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
          'subject' => $sbj,
          'msg' => $mmMail->htmlWrapper(array(
            'global' => $gblang,
            'title' => $sbj,
            'header' => $sbj,
            'content' => mswNL2BR($msg),
            'footer' => (!defined('LICENCE_VER') || LICENCE_VER == 'locked' ? $gblang[41] : '')
          )),
          'reply-to' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
          'plain' => $msg,
          'htmlWrap' => 'yes'
        ), $gblang);
        $mmMail->smtpClose();
      }
      // Webmaster..
      if (isset($notify['webcr'])) {
        $msg = strtr(file_get_contents(PATH . 'content/language/email-templates/wm-account-activated.txt'), $f_r);
        $sbj = str_replace('{website}', $SETTINGS->website, $emlang[11]);
        $mmMail->sendMail(array(
          'to_name' => $SETTINGS->website,
          'to_email' => $SETTINGS->email,
          'from_name' => ($SETTINGS->smtp_from ? $SETTINGS->smtp_from : $SETTINGS->website),
          'from_email' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
          'subject' => $sbj,
          'msg' => $mmMail->htmlWrapper(array(
            'global' => $gblang,
            'title' => $sbj,
            'header' => $sbj,
            'content' => mswNL2BR($msg),
            'footer' => (!defined('LICENCE_VER') || LICENCE_VER == 'locked' ? $gblang[41] : '')
          )),
          'reply-to' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
          'other' => $SETTINGS->smtp_other,
          'plain' => $msg,
          'htmlWrap' => 'yes'
        ), $gblang);
        $mmMail->smtpClose();
      }
    }
  }
}

// If we are already logged in, go to account page..
if (isset($systemAcc['email'])) {
  $pluginLoader[] = 'rdr-account';
} else {
  $pluginLoader[] = 'rdr-login';
}

$title = mswSafeDisplay($pbprofile[26]);

include(PATH . 'control/system/header.php');

$tpl = new Savant3();
$tpl->assign('TXT', array(
  $pbprofile[26],
  $pbprofile[27]
));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/' . THEME . '/verify.tpl.php');

include(PATH . 'control/system/footer.php');

?>