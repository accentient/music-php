<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4],'403');
}

$pluginLoader[] = 'mmusic';

$title = mswSafeDisplay($pbaccount[7]);

include(PATH . 'control/system/header.php');

$tpl  = new Savant3();
$tpl->assign('TXT',
 array(
  $pbaccount[7],
  $pbaccount[8],
  $pbaccount[9],
  $pbaccount[10],
  $pbaccount[11],
  $pbaccount[12],
  $pbaccount[13]
 )
);
$tpl->assign('CREATE_URL', BASE_HREF.$SEO->url('create',array(),'yes'));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/'.THEME . '/login.tpl.php');

include(PATH . 'control/system/footer.php');


?>