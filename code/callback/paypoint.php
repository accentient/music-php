<?php

// SET PATH TO ROOT FOLDER..
$basePath = pathinfo(dirname(__FILE__));
define('PATH', $basePath['dirname'].'/');
define('PARENT', 1);

// Load defined admin options..
include(PATH . 'control/defined.php');

// SET GATEWAY FLAG
$thisGateway = substr(basename(__file__), 0, -4);

// ERROR REPORTING..
define('GW_ERR_LOG', $thisGateway);
include(PATH . 'control/classes/class.errors.php');
if (ERR_HANDLER_ENABLED) {
  register_shutdown_function('msFatalErr');
  set_error_handler('msErrorhandler');
}

// INIT/LANG..
include(PATH . 'control/init.php');

// GATEWAY DATA..
$Q  = $DB->db_query("SELECT * FROM `" . DB_PREFIX . "gateways` WHERE `class` = 'class.{$thisGateway}.php'");
$GW = $DB->db_object($Q);

if (!isset($GW->id) || !file_exists(PATH . 'control/classes/gateways/' . $GW->class)) {
  die('Gateway files and/or info not found, callback cancelled.');
  exit;
}

// CLASS..
include(PATH . 'control/classes/gateways/' . $GW->class);
include(PATH . 'control/classes/gateways/class.controller.php');

// INITIATE GATEWAY CLASS..
$GATEWAY = new mmGatewayController(array(
  'gwID' => $GW->id,
  'gwname' => $GW->display,
  'server' => $GW->liveserver,
  'sandbox' => $GW->sandboxserver,
  'webpage' => $GW->webpage,
  'settings' => $SETTINGS,
  'account' => '',
  'order' => array(
    'id' => '',
    'code' => ''
  ),
  'lang' => $checklang,
  'seo' => $SEO
));

// LOAD INCOMING POST DATA..
$CALLBACK = $GATEWAY->callback();

// HANDLE CALLBACK..
if ($CALLBACK['code-id']) {

  // GET BUY/SALE CODE AND ID, ALONG WITH MARKER..
  // Marker determines if sale came from Maian Music..
  $DATA      = explode('-', $CALLBACK['code-id']);
  $SALE_CODE = (isset($DATA[0]) && ctype_alnum($DATA[0]) ? $DATA[0] : '0');
  $SALE_ID   = (isset($DATA[1]) && (int) $DATA[1] > 0 ? $DATA[1] : '0');
  $MARKER    = (isset($DATA[2]) ? $DATA[2] : 'mswmusic');

  // DEBUG..
  $GATEWAY->log($SALE_ID, 'Received post callback from ' . $GW->display . ' payment server..writing post log..');
  $GATEWAY->log($SALE_ID, print_r($_POST, true));

  // GET SALE / ORDER INFO..
  $SALE_ORDER = $GATEWAY->getsale($SALE_ID, $SALE_CODE);

  // START PROCESSING..
  if (isset($SALE_ORDER->saleID) && $MARKER == 'mswmusic') {

    // ACCOUNT/ORDER PARAMS TO CLASS..
    $GATEWAY->account       = $SALE_ORDER->account;
    $GATEWAY->order['id']   = $SALE_ID;
    $GATEWAY->order['code'] = $SALE_CODE;

    // SALE TOTAL
    $saleTotal = $GATEWAY->saletotal($SALE_ORDER);

    // DEBUG..
    if (isset($SALE_ORDER->pass)) {
      $SALE_ORDER->pass = '[Hidden for security]';
    }
    $GATEWAY->log($SALE_ID, 'Sale found in database. '.print_r($SALE_ORDER,true));

    // GLOBAL MAIL TAGS..
    include(PATH . 'control/mail.php');
    $f_r['{GATEWAY_NAME}'] = $GW->display;
    $f_r['{GATEWAY_URL}']  = $GW->webpage;
    $f_r['{ORDER_IP}']     = $SALE_ORDER->ip;
    $f_r['{NAME}']         = mswCleanData($SALE_ORDER->name);

    // LOAD MAIL TEMPLATE FILE PREFERENCES..
    $MTEMP = $GATEWAY->mailtemplates();

    // ORDER ADDRESSES..
    $ORDER_ADDR = mswShippingAddress($SALE_ID, $DB);

    // VALIDATE PAYMENT..
    if ($GATEWAY->validate() == 'ok') {

      // GET PAYMENT STATUS..
      $paymentStatus = strtolower($CALLBACK['pay-status']);

      // DEBUG..
      $GATEWAY->log($SALE_ID, 'Sale validated by ' . $GW->display . ' gateway. Payment status: ' . $paymentStatus);

      // ARE PENDING SALES TO BE HANDLED AS COMPLETED..
      if ($SETTINGS->propend == 'yes' && $paymentStatus == 'pending') {
        $paymentStatus = 'completed';
      }

      // ADJUST FOR REFUNDED..
      if ($paymentStatus == 'refunded') {
        $SALE_ORDER->saleEnabled = 'no';
      }

      // ADJUST FOR COMPLETED PAYMENT..
      if ($paymentStatus == 'a') {
        $paymentStatus = 'completed';
      }

      // UPDATE SALE / ORDER..
      if ($SALE_ORDER->saleEnabled == 'no') {

        switch ($paymentStatus) {

          //==========================================
          // GATEWAY CALLBACK => COMPLETED PAYMENT
          //==========================================

          case 'completed':
            if (($CALLBACK['amount'] >= $saleTotal) && ($CALLBACK['currency'] == $SETTINGS->currency)) {

              // LOAD CALLBACK TEMPLATE..
              include(PATH . 'callback/ops/completed.php');

              // CUSTOM OPS..
              include(PATH . 'callback/ops/custom.php');

            } else {

              // DEBUG..
              $GATEWAY->log($SALE_ID, 'Currency and/or price amount did not match values in database. Possible fraud. Database (' . $saleTotal . ',' . $SETTINGS->currency . '), Received (' . $CALLBACK['amount'] . ',' . $CALLBACK['currency'] . '). If not fraud, check tax is not enabled in gateway settings.');

            }
            break;

          //==========================================
          // GATEWAY CALLBACK => PENDING PAYMENT
          // NOT currently supported by Paypoint
          //==========================================

          case 'pending':

            // LOAD CALLBACK TEMPLATE..
            include(PATH . 'callback/ops/pending.php');

            break;

          //==========================================
          // GATEWAY CALLBACK => REFUNDED PAYMENT
          // NOT currently supported by Paypoint
          //==========================================

          case 'refunded':

            // LOAD CALLBACK TEMPLATE..
            include(PATH . 'callback/ops/refunded.php');

            break;

          //==========================================
          // GATEWAY CALLBACK => OTHER OPTIONS
          // May be added in future versions
          //==========================================

          default:
            // DEBUG..
            $GATEWAY->log($SALE_ID, 'Received action not currently supported. (' . $paymentStatus . ')');
            if ($_POST['message']) {
              $GATEWAY->log($SALE_ID, 'Gateway message: ' . $_POST['message']);
              if ($_POST['response-code']) {
                $GATEWAY->log($SALE_ID, 'Response Code: ' . $_POST['response-code']);
              }
            }
            break;
        }
      } else {

        // DEBUG..
        $GATEWAY->log($SALE_ID, 'Received callback for sale already processed.');

      }

      // Paypoint requires an html rendering in the browser..
      $_GET['msg'] = '9';
      include(PATH . 'control/system/message.php');
      exit;

    } else {

      // DEBUG..
      $GATEWAY->log($SALE_ID, 'Sale not validated by gateway. Check debug log for post data received..');

    }

  } else {

    // DEBUG..
    $GATEWAY->log($SALE_ID, 'Received callback from IPN transmission from another system. Ignored.');

  }

  // DEBUG..
  $GATEWAY->log($SALE_ID, 'Callback processing completed. No further actions.');

}

// Show message..
$_GET['msg'] = '1';
include(PATH . 'control/system/message.php');

?>