<?php

if (!defined('GW_ERR_LOG')) {
  mswEcode($gblang[4], '403');
}

// CURRENCIES
include(PATH . 'control/currencies.php');

// ACTIVATE SALE CLASS..
include(PATH . 'control/classes/class.sales.php');
$SALE           = new salesPublic();
$SALE->settings = $SETTINGS;
$SALE->datetime = $DT;
$SALE->cart     = $CART;

// DEBUG..
$GATEWAY->log($SALE_ID, 'Processing ' . $paymentStatus . ' actions..');

// UPDATE ORDER..
$SALE->statusChange($paymentStatus, $SALE_ID, str_replace(array(
  '{status}',
  '{date}',
  '{time}'
), array(
  $paymentStatus,
  $DT->dateTimeDisplay($DT->utcTime(), $SETTINGS->dateformat, $SETTINGS->timezone),
  $DT->dateTimeDisplay($DT->utcTime(), $SETTINGS->timeformat, $SETTINGS->timezone)
), $checklang[11]));

$GATEWAY->log($SALE_ID, 'Sale updated to ' . $paymentStatus . '..');

?>