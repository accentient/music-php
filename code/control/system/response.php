<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4], '403');
}

$title = mswSafeDisplay($checklang[8]);

// Check Order..
$DATA = explode('-', $_GET['gw']);
$OCNT = (isset($_GET['cnt']) ? (int) $_GET['cnt'] : '1');
$META = '';
if (isset($DATA[0], $DATA[1])) {
  $ID   = (int) $DATA[0];
  $CODE = (ctype_alnum($DATA[1]) ? $DATA[1] : '');
  if ($ID > 0 && $CODE) {
    $Q     = $DB->db_query("SELECT * FROM `" . DB_PREFIX . "sales`
	           WHERE `id`    = '{$ID}'
             AND `code`    = '{$CODE}'
             ");
    $ORDER = $DB->db_object($Q);
    // For some gateways like Global Iris / Realex, check ID only as code will have been cleared..
    if (!isset($ORDER->id)) {
      $Q     = $DB->db_query("SELECT * FROM `" . DB_PREFIX . "sales`
           WHERE `id` = '{$ID}'
           ");
      $ORDER = $DB->db_object($Q);
    }
    if (isset($ORDER->id)) {
      $Q  = $DB->db_query("SELECT * FROM `" . DB_PREFIX . "gateways` WHERE `id` = '{$ORDER->gateway}'");
      $GW = $DB->db_object($Q);
      if (isset($GW->id)) {
        // Controller class..
        include(PATH . 'control/classes/gateways/' . $GW->class);
        include(PATH . 'control/classes/gateways/class.controller.php');
        $GATEWAY = new mmGatewayController(array(
          'gwID' => $GW->id,
          'gwname' => $GW->display,
          'server' => $GW->liveserver,
          'sandbox' => $GW->sandboxserver,
          'webpage' => $GW->webpage,
          'settings' => $SETTINGS,
          'account' => $ORDER->account,
          'order' => array(
            'id' => $ORDER->id,
            'code' => $ORDER->code
          ),
          'lang' => $checklang,
          'seo' => $SEO
        ));
        switch ($GW->id) {
          // For SagePay, ping callback handler as they don`t send a trigger to the callback file..
          case '6':
            if (isset($_GET['crypt'])) {
              $GATEWAY->log($ORDER->id, 'Data received from Sage Pay. Preparing to ping callback handler. . ');
              $GATEWAY->pingcallback($ORDER);
            }
            break;
          // For JamboPay, ping callback handler as they don`t send a trigger to the callback file..
          case '21':
            if (isset($_POST['JP_PASSWORD'])) {
              $GATEWAY->log($ORDER->id, 'Data received from JamboPay. Preparing to ping callback handler. . ');
              $GATEWAY->pingcallback($ORDER);
            }
            break;
          // For Realex Payments and Global Iris, check response wasn`t invalid..
          // Present error if it was. This is recommended by Realex Payments..
          case '13':
          case '20':
            if ($ORDER->gateparams) {
              $params = ($ORDER->gateparams ? explode('<-->', $ORDER->gateparams) : array());
              if (!empty($params)) {
                foreach ($params AS $gp) {
                  $chop = explode('=>', $gp);
                  if (strtoupper($chop[0]) == 'RESULT' && $chop[1] && $chop[1] != '00') {
                    header("Location: " . BASE_HREF . "index.php?msg=3");
                    exit;
                  }
                }
              }
            }
            break;
        }
        // Has sale been updated?
        if ($OCNT < RESPONSE_PAGE_REFRESHES) {
          if ($ORDER->enabled == 'yes') {
            // Url for redirect..
            $url = array(
              'seo' => array(
                mswSaleInvoiceNumber($ORDER->id)
              ),
              'standard' => array(
                '#' => mswSaleInvoiceNumber($ORDER->id)
              )
            );
            // Is the buyer still logged in?
            if (isset($systemAcc['id'])) {
              $urlRdr = $SEO->url('view-order', $url);
            } else {
              $urlRdr = $SEO->url('account', array(), 'yes');
            }
            // Clear code..
            $DB->db_query("UPDATE `" . DB_PREFIX . "sales` SET
            `code`     = ''
            WHERE `id` = '{$ID}'
            ");
            // Show success page..
            define('CHECK_RDR', $urlRdr);
            include(PATH . 'control/system/header.php');
            $tpl = new Savant3();
            $tpl->assign('TXT', array(
              $checklang[3],
              $checklang[4] . $checklang[9]
            ));
            $tpl->display('content/' . THEME . '/order-success.tpl.php');
            include(PATH . 'control/system/footer.php');
            exit;
          } else {
            $META = BASE_HREF . 'index.php?gw=' . $ID . '-' . $CODE . '&amp;cnt=' . ($OCNT + 1);
          }
        } else {
          $META = BASE_HREF . 'index.php?msg=4';
        }
      }
    }
  }
}

$tpl = new Savant3();
$tpl->assign('CHARSET', $gblang[0]);
$tpl->assign('LANG', $gblang[2]);
$tpl->assign('TITLE', $title);
$tpl->assign('TXT', array(
  $checklang[8],
  $checklang[4]
));
$tpl->assign('META_REFRESH', $META);

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/' . THEME . '/order-check.tpl.php');

?>