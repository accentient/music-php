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

$GATEWAY->log($SALE_ID, 'Sale updated to ' . $paymentStatus . '..Send emails about status..');

// Send mail for valid email address..
$oData                 = mswGetSaleOrder($SALE_ID, $DB, $emvars);
$oTotals               = mswGetSaleOrderTotals($SALE_ID, $DB);
// Mail tags..
$f_r['{ACC_NAME}']     = $SALE_ORDER->name;
$f_r['{GATE_URL}']     = $GW->webpage;
$f_r['{GATEWAY}']      = $GW->display;
$f_r['{C1}']           = count($oData['dl']);
$f_r['{C2}']           = count($oData['cd']);
$f_r['{DOWNLOADS}']    = (!empty($oData['dl']) ? implode(mswDefineNewline(), $oData['dl']) : 'N/A');
$f_r['{CDS}']          = (!empty($oData['cd']) ? implode(mswDefineNewline(), $oData['cd']) : 'N/A');
$f_r['{SUB}']          = $oTotals['sub'];
$f_r['{SHIP}']         = $oTotals['ship'];
$f_r['{COUPON}']       = $oTotals['coupon'];
$f_r['{CPN_CODE}']     = $oTotals['couponcode'];
$f_r['{TAX}']          = $oTotals['tax'];
$f_r['{RATE}']         = $oTotals['rate'];
$f_r['{TAX2}']         = $oTotals['tax2'];
$f_r['{RATE2}']        = $oTotals['rate2'];
$f_r['{TCOUNT}']       = $oTotals['counts'][0];
$f_r['{DCOUNT}']       = $oTotals['counts'][1];
$f_r['{TOTAL}']        = $oTotals['total'];
$f_r['{IP}']           = $SALE_ORDER->ip;
$f_r['{CURRENCY}']     = $currencies[$SETTINGS->currency];
$f_r['{REASON}']       = (isset($CALLBACK['pending-reason']) ? $CALLBACK['pending-reason'] : 'N/A');
$f_r['{INV_STATUS}']   = (isset($CALLBACK['inv-status']) ? $CALLBACK['inv-status'] : 'N/A');
$f_r['{FRAUD_STATUS}'] = (isset($CALLBACK['fraud-status']) ? $CALLBACK['fraud-status'] : 'N/A');
$msg                   = strtr(file_get_contents(PATH . 'content/language/email-templates/' . $MTEMP['pending']), $f_r);
$sbj                   = str_replace('{website}', $SETTINGS->website, $emlang[14]);
$msg2                  = strtr(file_get_contents(PATH . 'content/language/email-templates/' . $MTEMP['pending-wm']), $f_r);
$sbj2                  = str_replace('{website}', $SETTINGS->website, $emlang[15]);

// Customer..
if (isset($notify['salecuspen'])) {
  $GATEWAY->log($SALE_ID, 'Customer pending email enabled (Admin > Settings > Store), sending email to: ' . $SALE_ORDER->name);
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

// Webmaster..
if (isset($notify['salewebpen'])) {
  $GATEWAY->log($SALE_ID, 'Webmaster pending email enabled (Admin > Settings > Store), sending email to store owner');
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

?>