<?php

if (!defined('PARENT') || !defined('LOGGED_IN')) {
  mswEcode($gblang[4], '403');
}

if (LOGGED_IN == 'no') {
  header("Location: " . BASE_HREF . $SEO->url('login', array(), 'yes'));
  exit;
}

$pluginLoader[] = 'mmusic';

$ORDER = $BUILDER->load('order');

if (!isset($ORDER->id) || $ORDER->account != $systemAcc['id']) {
  header("Location: " . BASE_HREF . $SEO->url('account', array(), 'yes'));
  exit;
}

if ($systemAcc['enabled'] == 'no') {
  header("Location: " . BASE_HREF . $SEO->url('account', array(), 'yes'));
  exit;
}

if ($ORDER->locked == 'yes') {
  header("Location: index.php?msg=6&id=" . $ORDER->id);
  exit;
}

$title = mswSafeDisplay($pbaccount[6]);

include(PATH . 'control/system/header.php');

$tpl = new Savant3();
$tpl->assign('TXT', array(
  $pbaccount[6],
  $pborders[7],
  $pborders[8],
  $pborders[9],
  $pborders[10],
  $pborders[11],
  $pborders[12],
  $pborders[13],
  $pborders[5],
  $pborders[17],
  $pborders[18],
  $pborders[19],
  $pborders[20],
  $pborders[21],
  $pborders[22],
  $pborders[23],
  $pborders[24],
  $pborders[26]
));
$tpl->assign('URL', array(
  BASE_HREF . $SEO->url('orders', array(), 'yes')
));
$tpl->assign('INVOICE_NO', mswSaleInvoiceNumber($ORDER->invoice));
$tpl->assign('ORDER', $ORDER);

$ship = ($ORDER->shipping > 0 ? $ORDER->shipping : '0.00');
$tax  = ($ORDER->tax > 0 ? $ORDER->tax : '0.00');
$tax2 = ($ORDER->tax2 > 0 ? $ORDER->tax2 : '0.00');
$taxT = mswFormatPrice($tax + $tax2);
if ($ORDER->coupon) {
  $cp = mswCleanData(unserialize($ORDER->coupon));
  if (isset($cp[0], $cp[1]) && $cp[1] > 0) {
    $discount = $cp[1];
  }
  $tot = ($ORDER->saleTotal > 0 ? mswFormatPrice($ORDER->saleTotal - $discount) : '0.00');
} else {
  $tot = ($ORDER->saleTotal > 0 ? $ORDER->saleTotal : '0.00');
}

$orderDataT = $CART->music($BUILDER, $ORDER->id, 'shipped', $pborders);
$orderDataD = $CART->music($BUILDER, $ORDER->id, 'download', $pborders);

$tpl->assign('ORDER_DETAIL', $orderDataD[0]);
$tpl->assign('ORDER_DETAIL2', $orderDataT[0]);
$tpl->assign('INFO', array(
  'date' => $DT->dateTimeDisplay($ORDER->ts, $SETTINGS->dateformat),
  'method' => mswSafeDisplay($ORDER->paymentMethod),
  'sub' => mswCurrencyFormat($ORDER->saleTotal, $SETTINGS->curdisplay),
  'shipping' => mswCurrencyFormat($ship, $SETTINGS->curdisplay),
  'taxrate' => ($ORDER->taxRate > 0 ? $ORDER->taxRate : ''),
  'taxrate2' => ($ORDER->taxRate2 > 0 ? $ORDER->taxRate2 : ''),
  'tax' => mswCurrencyFormat($tax, $SETTINGS->curdisplay),
  'tax2' => mswCurrencyFormat($tax2, $SETTINGS->curdisplay),
  'total' => mswCurrencyFormat(($tot + $ship + $taxT), $SETTINGS->curdisplay),
  'coupon' => (isset($discount) ? '-' . mswCurrencyFormat($discount, $SETTINGS->curdisplay) : mswCurrencyFormat('0.00', $SETTINGS->curdisplay)),
  'itemcnt' => array(
    $orderDataT[1][0],
    $orderDataD[1][1]
  )
));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/' . THEME . '/order-view.tpl.php');

include(PATH . 'control/system/footer.php');


?>