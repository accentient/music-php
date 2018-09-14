<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4],'403');
}

if ($SETTINGS->sysstatus == 'no') {

  // Is auto enable active?
  if ($SETTINGS->autoenable>0) {
    if (date('Y-m-d',$SETTINGS->autoenable) == $DT->dateTimeDisplay($DT->utcTime(), 'Y-m-d', $SETTINGS->timezone)) {
      mswSiteOnline($DB);
      header("Location: ".BASE_HREF);
      exit;
    }
  }

  // Check if IP addresses are allowed to view site when offline?
  $ipBypass = 'no';
  if ($SETTINGS->allowip) {
    $chopip = array_map('trim',explode(',',$SETTINGS->allowip));
    if (in_array($_SERVER['REMOTE_ADDR'],$chopip)) {
      $ipBypass = 'yes';
    }
  }

  if ($ipBypass == 'no') {
    $title = mswSafeDisplay($gblang[48]);

    include(PATH . 'control/system/header.php');

    $tpl = new Savant3();
    $tpl->assign('TXT',
     array(
      $gblang[48],
      mswNL2BR($SETTINGS->reason)
     )
    );

    // Global template vars..
    include(PATH . 'control/lib/global.php');

    // Load template..
    $tpl->display('content/'.THEME . '/message.tpl.php');

    include(PATH . 'control/system/footer.php');

    exit;
  }

}

?>