<?php

if (!defined('PARENT') || !defined('LOGGED_IN')) {
  mswEcode($gblang[4], '403');
}

if (LOGGED_IN == 'no') {
  header("Location: " . BASE_HREF . $SEO->url('login', array(), 'yes'));
  exit;
}

$pluginLoader[] = 'mmusic';
$title          = mswSafeDisplay($pbaccount[16]);

include(PATH . 'control/system/header.php');

$tpl = new Savant3();
$tpl->assign('TXT', array(
  $pblang[6],
  $pbaccount[17],
  str_replace('{url}', BASE_HREF . $SEO->url('profile', array(), 'yes'), $pbaccount[18]),
  $pbaccount[19],
  $pborders[3],
  $pborders[4],
  $pborders[5],
  $pborders[6],
  $pbaccount[20]
));
$tpl->assign('URL', array(
  BASE_HREF . $SEO->url('orders', array(), 'yes')
));

// Global template vars..
include(PATH . 'control/lib/global.php');

$tpl->assign('ORDERS', $CART->orders($BUILDER, $systemAcc['id'], ORDER_LIMIT_ACCOUNT_HOMESCREEN));

// Load template..
$tpl->display('content/' . THEME . '/account.tpl.php');

include(PATH . 'control/system/footer.php');

?>