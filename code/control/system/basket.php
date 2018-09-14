<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4], '403');
}

include(PATH . 'control/countries.php');
include(PATH . 'control/currencies.php');

$title = $pblang[15];

$pluginLoader[] = 'mmusic';
$pluginLoader[] = 'music-ops';

// Are we processing checkout?
if (isset($_POST['process'])) {
  // Load sales class..
  include(PATH . 'control/classes/class.sales.php');
  $SALE            = new salesPublic();
  $SALE->settings  = $SETTINGS;
  $SALE->datetime  = $DT;
  $SALE->cart      = $CART;
  $SALE->countries = $countries;
  // Load GeoIP class..
  include(PATH . 'control/classes/class.ip.php');
  $IPGEO           = new geoIP();
  $IPGEO->settings = $SETTINGS;
  $lookup          = $IPGEO->lookup($_SERVER['REMOTE_ADDR'],$gblang[19]);
  $ID              = (int) $_POST['payment'];
  $Q               = $DB->db_query("SELECT * FROM `" . DB_PREFIX . "gateways` WHERE `id` = '{$ID}'");
  $GW              = $DB->db_object($Q);
  if (isset($GW->class) && file_exists(PATH . 'control/classes/gateways/' . $GW->class)) {
    // Gateway class..
    include(PATH . 'control/classes/gateways/' . $GW->class);
    include(PATH . 'control/classes/gateways/class.controller.php');
    // Total vars..
    $tVars = array(
      'sub' => (isset($_SESSION['basketHidden'][0]) ? mswFormatPrice($_SESSION['basketHidden'][0]) : '0.00'),
      'ship' => (isset($_SESSION['basketHidden'][1]) ? mswFormatPrice($_SESSION['basketHidden'][1]) : '0.00'),
      'tax' => (isset($_SESSION['basketHidden'][2]) ? mswFormatPrice($_SESSION['basketHidden'][2]) : '0.00'),
      'tax-rate' => (isset($_SESSION['basketHidden'][3]) ? (int) $_SESSION['basketHidden'][3] : '0'),
      'tax2' => (isset($_SESSION['basketHidden'][4]) ? mswFormatPrice($_SESSION['basketHidden'][4]) : '0.00'),
      'tax-rate2' => (isset($_SESSION['basketHidden'][5]) ? (int) $_SESSION['basketHidden'][5] : '0'),
      'total' => (isset($_SESSION['basketHidden'][6]) ? mswFormatPrice($_SESSION['basketHidden'][6]) : '0.00'),
      'tax-country' => (isset($_SESSION['basketHidden'][7]) ? (int) $_SESSION['basketHidden'][7] : '0'),
      'coupon' => (isset($_SESSION['basketHidden'][8]) ? $_SESSION['basketHidden'][8] : array()),
      'tax-country2' => (isset($_SESSION['basketHidden'][9]) ? (int) $_SESSION['basketHidden'][9] : '0')
    );
    // Address post vars..
    // Check if set..
    if (!isset($_POST['address1'])) {
      $_POST['address1'] = '';
      $_POST['address2'] = '';
      $_POST['city']     = '';
      $_POST['county']   = '';
      $_POST['postcode'] = '';
      $_POST['country']  = (isset($systemAcc['addCountry']) ? $systemAcc['addCountry'] : '0');
    }
    // Process..
    if ($GW->status == 'yes' && isset($systemAcc['id']) && !empty($_SESSION['cartItems'])) {
      include(PATH . 'control/mail.php');
      // Add order to database..
      $ORID    = $SALE->addOrder($systemAcc, $tVars, $lookup);
      // Controller class..
      $GATEWAY = new mmGatewayController(array(
        'gwID' => $ID,
        'gwname' => $GW->display,
        'server' => $GW->liveserver,
        'sandbox' => $GW->sandboxserver,
        'webpage' => $GW->webpage,
        'settings' => $SETTINGS,
        'account' => $systemAcc,
        'iso4217' => $iso4217_conversion,
        'order' => array(
          'id' => $ORID[0],
          'code' => $ORID[1]
        ),
        'lang' => $checklang,
        'seo' => $SEO
      ));
      // If this order is free (0.00) we are done..
      if ($tVars['total'] == '0.00') {
        $GATEWAY->log($ORID[0], 'Free order processed for: ' . $systemAcc['name']);
        $invoice  = $SALE->activate(array(
         'sale' => $ORID[0],
         'trans' => $checklang[10],
         'total' => '0.00',
         'account' => $systemAcc['id'],
         'sub' => $tVars['sub']
        ));
        $title              = mswSafeDisplay($checklang[3]);
        // Url for redirect..
        $url                = array(
          'seo' => array(
            mswSaleInvoiceNumber($ORID[0])
          ),
          'standard' => array(
            '#' => mswSaleInvoiceNumber($ORID[0])
          )
        );
        // CURRENCIES
        include(PATH . 'control/currencies.php');
        // Emails..
        $oData              = mswGetSaleOrder($ORID[0], $DB, $emvars);
        $oTotals            = mswGetSaleOrderTotals($ORID[0], $DB);
        // Mail tags..
        $f_r['{ACC_NAME}']  = $systemAcc['name'];
        $f_r['{C1}']        = count($oData['dl']);
        $f_r['{C2}']        = count($oData['cd']);
        $f_r['{IP}']        = mswIPAddr();
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
        $f_r['{SHIP_ADDR}'] = mswShippingAddress($ORID[0], $DB);
        $f_r['{TOTAL}']     = $oTotals['total'];
        $f_r['{CURRENCY}']  = $currencies[$SETTINGS->currency];
        $msg                = strtr(file_get_contents(PATH . 'content/language/email-templates/account-new-sale.txt'), $f_r);
        $sbj                = str_replace('{website}', $SETTINGS->website, $emlang[12]);
        $msg2               = strtr(file_get_contents(PATH . 'content/language/email-templates/wm-account-new-sale.txt'), $f_r);
        $sbj2               = str_replace('{website}', $SETTINGS->website, $emlang[13]);
        // Customer..
        if (isset($notify['salecus'])) {
          $GATEWAY->log($ORID[0], 'Customer email enabled (Admin > Settings > Store), sending order confirmation email to: ' . $systemAcc['name']);
          $mmMail->sendMail(array(
            'to_name' => $systemAcc['name'],
            'to_email' => $systemAcc['email'],
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
        if (isset($notify['saleweb'])) {
          $GATEWAY->log($ORID[0], 'Webmaster email enabled (Admin > Settings > Store), sending order confirmation email to store owner');
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
        // Clear basket..
        $CART->clear();
        $GATEWAY->log($ORID[0], 'Free order completed. Invoice number: #' . mswSaleInvoiceNumber($invoice));
        define('CHECK_RDR', $SEO->url('view-order', $url));
        include(PATH . 'control/system/header.php');
        $tpl = new Savant3();
        $tpl->assign('TXT', array(
          $checklang[3],
          $checklang[4] . $checklang[9]
        ));
        $tpl->display('content/' . THEME . '/order-success.tpl.php');
        include(PATH . 'control/system/footer.php');
        exit;
      }
      // Controller class..
      $GATEWAY = new mmGatewayController(array(
        'gwID' => $ID,
        'gwname' => $GW->display,
        'server' => $GW->liveserver,
        'sandbox' => $GW->sandboxserver,
        'webpage' => $GW->webpage,
        'settings' => $SETTINGS,
        'account' => $systemAcc,
        'iso4217' => $iso4217_conversion,
        'order' => array(
          'id' => $ORID[0],
          'code' => $ORID[1]
        ),
        'lang' => $checklang,
        'seo' => $SEO
      ));
      // Send fields to gateway..
      $f       = $GATEWAY->fields();
      // Separate ops based on certain gateways..
      switch ($ID) {
        // For eWay redirect to token page..
        case '7':
          header("Location: " . $f);
          exit;
          break;
        default:
          // Nothing here, script continues..
          break;
      }
      // Clear basket..
      $CART->clear();
      // Start log..
      $GATEWAY->log($ORID[0], 'Sending post data to "' . $GW->display . '" gateway @ '.$GATEWAY->payserver() . ':' . mswDefineNewline() . mswDefineNewline() . print_r($f, true) . mswDefineNewline() . mswDefineNewline() . 'Waiting for gateway response.. . ');
      $tpl = new Savant3();
      $tpl->assign('CHARSET', $gblang[0]);
      $tpl->assign('LANG', $gblang[2]);
      $tpl->assign('TITLE', mswSafeDisplay($checklang[0]));
      $tpl->assign('TXT', array(
        $checklang[1]
      ));
      $tpl->assign('SERVER', $GATEWAY->payserver());
      $tpl->assign('FIELDS', (is_array($f) ? $f : array()));
      if (!is_array($f) && substr($f, 0, 4) == 'http') {
        $tpl->assign('META_REFRESH', $f);
      }
      $tpl->display('content/' . THEME . '/gateway-load.tpl.php');
      exit;
    }
  }
  die('<b>Error</b> - Possible reasons are:<br><br>Gateway class "<b>control/gateways/classes/' . $GW->class . '</b>" does NOT exist.<br>
  Gateway is NOT enabled.<br>
  Account isn`t found.<br>
  Basket is empty as the system sessions terminated unexpected (could be from page refresh) . ');
  exit;
}

include(PATH . 'control/system/header.php');

$ship = (isset($systemAcc['shipping']) ? $CART->getShipping(CART_TOTAL, $systemAcc['shipping']) : '0.00');
$tax  = $CART->getTax($BUILDER, (isset($systemAcc['accCountry']) ? $systemAcc['accCountry'] : $SETTINGS->defCountry), 'tangible', $ship, array('no','no'));
$tax2 = $CART->getTax($BUILDER, (isset($systemAcc['accCountry']) ? $systemAcc['accCountry'] : $SETTINGS->defCountry2), 'digital', $ship, array('no','no'));

// Set total session vars..page load only..
$_SESSION['basketHidden'] = array(
  CART_TOTAL,
  ($ship > 0 ? mswFormatPrice($ship) : '0.00'),
  ($tax[0] > 0 ? mswFormatPrice($tax[0]) : '0.00'),
  substr($tax[1], 0, -1),
  ($tax2[0] > 0 ? mswFormatPrice($tax2[0]) : '0.00'),
  substr($tax2[1], 0, -1),
  mswFormatPrice(CART_TOTAL + $ship + mswFormatPrice($tax[0] + $tax2[1])),
  0,
  array(),
  0
);

$tpl = new Savant3();
$tpl->assign('TXT', array(
  $pblang[15],
  $pbbasket[0],
  $pbbasket[1],
  $pbbasket[2],
  $pbbasket[3],
  $pbbasket[4],
  $pbbasket[5],
  $pbbasket[6],
  $pbbasket[7],
  $pbbasket[8],
  $pbbasket[9],
  $pbprofile[6],
  $pbprofile[7],
  $pbprofile[8],
  $pbprofile[9],
  $pbprofile[10],
  $pbprofile[29],
  $pbprofile[13],
  $pbbasket[10],
  $pbbasket[11],
  $pbbasket[1],
  $pbbasket[12],
  str_replace('{rate}', $tax[1], $pbbasket[13]),
  $pbbasket[14],
  $pbbasket[22],
  $pbbasket[23],
  $pbbasket[24],
  $pbbasket[32],
  $pbbasket[33],
  str_replace("'","\'",$jslang[3])
));
$tpl->assign('URL', array(
  BASE_HREF . $SEO->url('basket', array(), 'yes') //checkout
));
$tpl->assign('CHARGES', array(
  'sub' => mswCurrencyFormat(CART_TOTAL, $SETTINGS->curdisplay),
  'ship' => mswCurrencyFormat($ship, $SETTINGS->curdisplay),
  'tax' => mswCurrencyFormat(mswFormatPrice($tax[0] + $tax2[0]), $SETTINGS->curdisplay),
  'total' => mswCurrencyFormat(mswFormatPrice(CART_TOTAL + $ship + mswFormatPrice($tax[0] + $tax2[0])), $SETTINGS->curdisplay)
));
$tpl->assign('CART_COUNT', CART_COUNT);
$tpl->assign('BASKET_ITEMS', $CART->basketItems($BUILDER, $pbcatlang));
$tpl->assign('IS_SHIPPING', $CART->isShipping());
$tpl->assign('COUNTRIES', $countries);
$tpl->assign('RATES', $BUILDER->rates($pbprofile));
$tpl->assign('METHODS', $BUILDER->methods());
$tpl->assign('ACCOUNT_LOGIN', $BUILDER->basketAcc($systemAcc, $pbbasket, $countries));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/' . THEME . '/basket.tpl.php');

include(PATH . 'control/system/footer.php');

?>