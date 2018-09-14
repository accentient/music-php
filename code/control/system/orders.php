<?php

if (!defined('PARENT') || !defined('LOGGED_IN')) {
  mswEcode($gblang[4], '403');
}

if (LOGGED_IN == 'no') {
  header("Location: " . BASE_HREF . $SEO->url('login', array(), 'yes'));
  exit;
}

// Pagination..
if ($SETTINGS->rewrite == 'yes') {
  $sef = $SEO->rewriteElements();
  if (isset($sef[1]) && $sef[1] > 1) {
    $page = (int) $sef[1];
  }
} else {
  if (isset($_GET['orders']) && $_GET['orders'] > 1) {
    $page = (int) $_GET['orders'];
  }
}
$totalRows = $CART->orders($BUILDER, $systemAcc['id'], 0, array(), true);
$limit     = $page * ORDERS_PER_PAGE - (ORDERS_PER_PAGE);
$url       = array(
  'seo' => array(
    '/'
  ),
  'standard' => array(
    'next' => '#'
  )
);
$PTION     = new pagination(array(
  $totalRows,
  $gblang[15],
  $page
), $SEO->url('orders', $url));
$sPages    = $PTION->display();

$title = mswSafeDisplay($pbaccount[5]);

include(PATH . 'control/system/header.php');

$tpl = new Savant3();
$tpl->assign('TXT', array(
  $pbaccount[5],
  $pborders[3],
  $pborders[4],
  $pborders[5],
  $pborders[6]
));

// Global template vars..
include(PATH . 'control/lib/global.php');

$tpl->assign('ORDERS', $CART->orders($BUILDER, $systemAcc['id'], 0, array(
  ($page <= 1 ? 0 : $page),
  ORDERS_PER_PAGE
)));
$tpl->assign('PAGINATION', $sPages);

// Load template..
$tpl->display('content/' . THEME . '/orders.tpl.php');

include(PATH . 'control/system/footer.php');

?>