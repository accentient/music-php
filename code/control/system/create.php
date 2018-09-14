<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4],'403');
}

include(PATH . 'control/countries.php');

$pluginLoader[] = 'mmusic';

$title = mswSafeDisplay($pbaccount[8]);

include(PATH . 'control/system/header.php');

$tpl  = new Savant3();
$tpl->assign('TXT',
 array(
  $pbaccount[8],
  $pbprofile[0],
  $pbprofile[1],
  $pbprofile[2],
  $pbprofile[3],
  $pbprofile[4],
  $pbprofile[5],
  $pbprofile[6],
  $pbprofile[7],
  $pbprofile[8],
  $pbprofile[9],
  $pbprofile[10],
  $pbprofile[11],
  $pbprofile[12],
  $pbprofile[13],
  $pbprofile[28],
  $pbprofile[29]
 )
);
$tpl->assign('TIMEZONES', $timezones);
$tpl->assign('COUNTRIES', $countries);
$tpl->assign('RATES', $BUILDER->rates($pbprofile));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/'.THEME . '/account-create.tpl.php');

include(PATH . 'control/system/footer.php');


?>