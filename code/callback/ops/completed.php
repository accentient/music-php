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
$GATEWAY->log($SALE_ID, 'Activating sale in database..');

// ACTIVATE SALE..
$invoice = $SALE->activate(array(
 'sale' => $SALE_ID,
 'trans' => $CALLBACK['trans-id'],
 'total' => $CALLBACK['amount'],
 'account' => $SALE_ORDER->account
));

// DEBUG..
$GATEWAY->log($SALE_ID, 'Sale activated. Sending emails..');

// Send mail for valid email address..
$oData              = mswGetSaleOrder($SALE_ID, $DB, $emvars);
$oTotals            = mswGetSaleOrderTotals($SALE_ID, $DB);
// Mail tags..
$f_r['{ACC_NAME}']  = $SALE_ORDER->name;
$f_r['{GATE_URL}']  = $GW->webpage;
$f_r['{GATEWAY}']   = $GW->display;
$f_r['{IP}']        = mswIPAddr();
$f_r['{C1}']        = count($oData['dl']);
$f_r['{C2}']        = count($oData['cd']);
$f_r['{DOWNLOADS}'] = (!empty($oData['dl']) ? implode(mswDefineNewline(), $oData['dl']) : 'N/A');
$f_r['{CDS}']       = (!empty($oData['cd']) ? implode(mswDefineNewline(), $oData['cd']) : 'N/A');
$f_r['{SUB}']       = $oTotals['sub'];
$f_r['{SHIP}']      = $oTotals['ship'];
$f_r['{COUPON}']    = $oTotals['coupon'];
$f_r['{CPN_CODE}']  = $oTotals['couponcode'];
$f_r['{TAX}']       = $oTotals['tax'];
$f_r['{RATE}']      = $oTotals['rate'];
$f_r['{TAX2}']      = $oTotals['tax2'];
$f_r['{RATE2}']     = $oTotals['rate2'];
$f_r['{TCOUNT}']    = $oTotals['counts'][0];
$f_r['{DCOUNT}']    = $oTotals['counts'][1];
$f_r['{SHIP_ADDR}'] = mswShippingAddress($SALE_ID, $DB);
$f_r['{TOTAL}']     = $oTotals['total'];
$f_r['{CURRENCY}']  = $currencies[$SETTINGS->currency];
$msg                = strtr(file_get_contents(PATH . 'content/language/email-templates/' . $MTEMP['completed']), $f_r);
$sbj                = str_replace('{website}', $SETTINGS->website, $emlang[12]);
$msg2               = strtr(file_get_contents(PATH . 'content/language/email-templates/' . $MTEMP['completed-wm']), $f_r);
$sbj2               = str_replace('{website}', $SETTINGS->website, $emlang[13]);

// Customer..
if (isset($notify['salecus'])) {
  $GATEWAY->log($SALE_ID, 'Customer email enabled (Admin > Settings > Store), sending order confirmation email to: ' . $SALE_ORDER->name);
  $mmMail->sendMail(array(
    'to_name' => $SALE_ORDER->name,
    'to_email' => $SALE_ORDER->email,
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

// License agreement email..
if ($SETTINGS->licenable == 'yes' && $SETTINGS->licsubj && $SETTINGS->licmsg) {
  $GATEWAY->log($SALE_ID, 'License agreement email enabled (Admin > Settings > License Agreement Email), sending license agreement to: ' . $SALE_ORDER->name);
  $saleD  = mswGetSaleOrderAgreement($SALE_ID, $DB, $emvars);
  $licmsg = str_replace(array(
    '{NAME}',
    '{EMAIL}',
    '{DATE}',
    '{INVOICE}',
    '{CD}',
    '{CDD}',
    '{TRACKS}',
    '{ID}'
  ), array(
    $SALE_ORDER->name,
    $SALE_ORDER->email,
    $DT->dateTimeDisplay($DT->utcTime(), $SETTINGS->dateformat, $SETTINGS->timezone),
    mswSaleInvoiceNumber($invoice),
    (!empty($saleD['cd']) ? implode(mswDefineNewline(), $saleD['cd']) : 'N/A'),
    (!empty($saleD['cdd']) ? implode(mswDefineNewline(), $saleD['cdd']) : 'N/A'),
    (!empty($saleD['dl']) ? implode(mswDefineNewline(), $saleD['dl']) : 'N/A'),
    $CALLBACK['trans-id']
  ), $SETTINGS->licmsg);
  $mmMail->sendMail(array(
    'to_name' => $SALE_ORDER->name,
    'to_email' => $SALE_ORDER->email,
    'from_name' => ($SETTINGS->smtp_from ? $SETTINGS->smtp_from : $SETTINGS->website),
    'from_email' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
    'subject' => $SETTINGS->licsubj,
    'msg' => $mmMail->htmlWrapper(array(
      'global' => $gblang,
      'title' => $SETTINGS->licsubj,
      'header' => $SETTINGS->licsubj,
      'content' => mswNL2BR($licmsg),
      'footer' => (!defined('LICENCE_VER') || LICENCE_VER == 'locked' ? $gblang[41] : '')
    )),
    'reply-to' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
    'plain' => $licmsg,
    'htmlWrap' => 'yes'
  ), $gblang);
  $mmMail->smtpClose();
}

// Webmaster..
if (isset($notify['saleweb'])) {
  $GATEWAY->log($SALE_ID, 'Webmaster email enabled (Admin > Settings > Store), sending order confirmation email to store owner');
  $mmMail->sendMail(array(
    'to_name' => $SETTINGS->website,
    'to_email' => $SETTINGS->email,
    'from_name' => ($SETTINGS->smtp_from ? $SETTINGS->smtp_from : $SETTINGS->website),
    'from_email' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
    'subject' => $sbj2,
    'msg' => $mmMail->htmlWrapper(array(
      'global' => $gblang,
      'title' => $sbj2,
      'header' => $sbj2,
      'content' => mswNL2BR($msg2),
      'footer' => (!defined('LICENCE_VER') || LICENCE_VER == 'locked' ? $gblang[41] : '')
    )),
    'reply-to' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
    'other' => $SETTINGS->smtp_other,
    'plain' => $msg2,
    'htmlWrap' => 'yes'
  ), $gblang);
  $mmMail->smtpClose();
}

// Is pushover enabled?
include(PATH . 'control/classes/class.apis.php');
$APIS           = new apis();
$APIS->settings = $SETTINGS;
$APIS->builder  = $BUILDER;
$GATEWAY->log($SALE_ID, 'Pushover service is enabled, attempting to ping to pushover devices (' . $APIS->urls['pushover'] . ').');
$response       = $APIS->pushover($msg2, $sbj2);
if ($response != 'disable') {
  $GATEWAY->log($SALE_ID, 'Pushover ping completed to enabled devices.');
}

$GATEWAY->log($SALE_ID, 'Order completed. Invoice number: #' . mswSaleInvoiceNumber($invoice));

?>