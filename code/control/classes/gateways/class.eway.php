<?php

class mmGateway extends db {

  // Validate gateway payment..
  // For eway validation done via gateway. If we are here, its ok..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    return 'ok';
  }

  // Variables created on callback..
  public function callback() {
    $arr = array(
      'trans-id' => (isset($_POST['txn-id']) ? $_POST['txn-id'] : ''),
      'amount' => (isset($_POST['amount']) && $_POST['amount'] > 0 ? $this->num_format($_POST['amount']) : ''),
      'refund-amount' => '',
      'currency' => $this->settings->currency,
      'code-id' => (isset($_POST['custom']) ? $_POST['custom'] : ''),
      'pay-status' => (isset($_POST['code']) ? $_POST['code'] : ''),
      'pending-reason' => (isset($_POST['code']) ? mmGateway::codes($_POST['code']) : ''),
      'inv-status' => '',
      'fraud-status' => ''
    );
    return $arr;
  }

  // Post fields to gateway..
  public function fields() {
    $ID      = $this->order['id'];
    $BUYCODE = $this->order['code'];
    $url     = (mswSSL() == 'yes' ? str_replace('http://', 'https://', BASE_HREF) : BASE_HREF);
    $order   = $this->getsale($ID, $BUYCODE);
    $params  = $this->params();
    $country = $this->country($_POST['country']);
    $name    = $this->firstLastName($order->name);
    $arr     = array(
      'CustomerID' => ($this->settings->paymode == 'live' ? $params['customer-id'] : '87654321'),
      'UserName' => ($this->settings->paymode == 'live' ? $params['username'] : 'TestAccount'),
      'Amount' => $this->saletotal($order),
      'Currency' => $this->settings->currency,
      'PageTitle' => $this->stripchars($params['page-title']),
      'PageDescription' => $this->stripchars($params['page-desc']),
      'PageFooter' => $this->stripchars($params['page-footer']),
      'Language' => $params['language'],
      'CompanyName' => $this->stripchars($this->settings->website),
      'CustomerFirstName' => $this->stripchars($name['first-name']),
      'CustomerLastName' => $this->stripchars($name['last-name']),
      'CustomerAddress' => $this->stripchars($_POST['address1'] . (isset($_POST['address2']) ? ', ' . $_POST['address2'] : '')),
      'CustomerCity' => $this->stripchars($_POST['city']),
      'CustomerState' => $this->stripchars($_POST['county']),
      'CustomerPostCode' => $this->stripchars($_POST['postcode']),
      'CustomerCountry' => $country->iso,
      'CustomerEmail' => $this->stripchars($order->email),
      'InvoiceDescription' => $this->stripchars($this->lang[2]),
      'CancelURL' => $url . $this->seo->url('cancel', array(), 'yes'),
      'ReturnUrl' => $url . 'callback/eway.php',
      'CompanyLogo' => $params['company-logo'],
      'PageBanner' => $params['page-banner'],
      'MerchantReference' => $BUYCODE . '-' . $ID,
      'MerchantInvoice' => $ID,
      'MerchantOption1' => $BUYCODE . '-' . $ID,
      'MerchantOption2' => '',
      'MerchantOption3' => '',
      'ModifiableCustomerDetails' => 'false'
    );
    // Build string..
    $string  = '';
    foreach ($arr AS $k => $v) {
      $string .= ($k == 'CustomerID' ? '?' : '&') . $k . '=' . $v;
    }
    // Replace spaces with encoded equivalent and send to eway for unique transaction token..
    $string = str_replace(' ', '%20', $string);
    $this->log($this->order['id'], 'Sending data to eWay (' . $this->server . ') for authentication code and url: ' . mswDefineNewline() . $this->server . '/' . $string);
    $r = $this->transmit($this->server . '/' . $string, '', 'eway');
    $this->log($this->order['id'], 'eWay XML response: ' . $r);
    // Extract XML from response..
    if (strpos($r, 'TransactionRequest') > 0 || strpos($r, 'TransactionRequest') !== false) {
      $xmr = preg_match("/<TransactionRequest>(.+)<\/TransactionRequest>/si", $r, $match);
      $xmr = trim($match[1]);
      // Check mode and url from eway..
      $XML = simplexml_load_string('<TransactionRequest>' . $xmr . '</TransactionRequest>');
      // If XML ok, load form and redirect or show error..
      if (isset($XML->Result) && $XML->Result == 'True') {
        // Store value against sale..
        // This is so we can identify sale on callback..
        $boom = explode('=', $XML->URI);
        db::db_query("UPDATE `" . DB_PREFIX . "sales` SET
        `refcode`   = '" . mswSafeString($boom[1], $this) . "'
        WHERE `id`  = '{$ID}'
        AND `code`  = '{$BUYCODE}'
        ");
        $this->log($this->order['id'], 'Success, ref code ' . $boom[1] . ' stored..redirecting to: ' . mswDefineNewline() . $XML->URI);
        return $XML->URI;
      } else {
        $this->log($this->order['id'], 'Failed with valid XML response, displaying error page and message: ' . (isset($XML->Error) ? $XML->Error : 'N/A'));
        header('Location: ' . BASE_HREF . '?msg=' . (isset($XML->Error) ? urlencode($XML->Error) : ''));
      }
    } else {
      $this->log($this->order['id'], 'Failed with invalid XML response, displaying error page and message: ' . (isset($XML->Error) ? $XML->Error : 'N/A'));
      header('Location: ' . BASE_HREF . '?msg=' . (isset($XML->Error) ? urlencode($XML->Error) : ''));
    }
    exit;
  }

  // Mail templates assigned to this method..
  public function mailtemplates() {
    $arr = array(
      'completed' => 'order-completed.txt',
      'completed-wm' => 'wm-order-completed.txt',
      'pending' => 'order-pending.txt',
      'pending-wm' => 'wm-order-pending.txt'
    );
    return $arr;
  }

  // Pings gateway on successful return..
  // This will only occur once..
  public function pingcallback() {
    $return     = array(
      'err',
      'err'
    );
    $log        = array();
    $params     = $this->params();
    $SALE_ORDER = $this->getsale(0, '', $_POST['AccessPaymentCode']);
    // Log..
    if (isset($SALE_ORDER->saleID)) {
      $return = array(
        $SALE_ORDER->saleID,
        $SALE_ORDER->code
      );
      $this->log($SALE_ORDER->saleID, 'Received callback signal..Pinging eway (' . $this->sandbox . ') system based on payment code: ' . $_POST['AccessPaymentCode']);
      // Build data to post..
      $resp = '?CustomerID=' . ($this->settings->paymode == 'live' ? $params['customer-id'] : '87654321');
      $resp .= '&UserName=' . ($this->settings->paymode == 'live' ? $params['username'] : 'TestAccount');
      $resp .= '&AccessPaymentCode=' . $_POST['AccessPaymentCode'];
      // Log..
      $this->log($SALE_ORDER->saleID, 'Ping eWay to verify payment was valid: ' . $resp);
      // Transmit to eway..
      $r = $this->transmit($this->sandbox . $resp);
      if (strpos($r, 'TransactionResponse') > 0 || strpos($r, 'TransactionResponse') !== false) {
        $this->log($SALE_ORDER->saleID, 'Valid eWay response: ' . $r);
        // Get XML from response..
        $xmr = preg_match("/<TransactionResponse>(.+)<\/TransactionResponse>/si", $r, $match);
        $xmr = trim($match[1]);
        // Check mode and url from eway..
        $XML = simplexml_load_string('<TransactionResponse>' . $xmr . '</TransactionResponse>');
        $build = 'txn-id=' . (isset($XML->AuthCode) ? $XML->AuthCode : '');
        $build .= '&code=' . (isset($XML->ResponseCode) ? $XML->ResponseCode : '');
        $build .= '&amount=' . (isset($XML->ReturnAmount) ? $XML->ReturnAmount : '');
        $build .= '&custom=' . (isset($XML->MerchantOption1) ? $XML->MerchantOption1 : '');
        $build .= '&message=' . (isset($XML->ErrorMessage) ? $XML->ErrorMessage : '');
        // Did order exist..
        if (isset($XML->MerchantOption1)) {
          // Log incoming vars..
          foreach ((array) $XML AS $k => $v) {
            $log[$k] = $v;
          }
          if (!empty($log)) {
            $this->log($SALE_ORDER->saleID, print_r($log,true));
          }
        }
        // Log..
        $this->log($SALE_ORDER->saleID, 'Sending ping to ' . SCRIPT_NAME . ' callback handler (callback/eway.php) with the following: ' . $build);
        // Ping eway handler..
        $r = $this->transmit(BASE_HREF . 'callback/eway.php', $build);
        // Log..
        $this->log($SALE_ORDER->saleID, 'Ping to ' . SCRIPT_NAME . ' callback url (callback/eway.php) completed.');
        return $return;
      } else {
        $this->log($SALE_ORDER->saleID, 'Failed or invalid gateway response: ' . $r);
        header("Location: " . BASE_HREF . "index.php?msg=3");
        exit;
      }
    } else {
      header("Location: " . BASE_HREF . "index.php?msg=3");
      exit;
    }
  }

  // eWay error codes..
  public function codes($code) {
    $arr = array(
      'CX' => 'Customer Cancelled Transaction',
      '00' => 'Transaction Approved',
      '01' => 'Refer to Issuer',
      '02' => 'Refer to Issuer, special',
      '03' => 'No Merchant',
      '04' => 'Pick Up Card',
      '05' => 'Do Not Honour',
      '06' => 'Error',
      '07' => 'Pick Up Card, Special',
      '08' => 'Honour With Identification',
      '09' => 'Request In Progress',
      '10' => 'Approved For Partial Amount',
      '11' => 'Approved, VIP',
      '12' => 'Invalid Transaction',
      '13' => 'Invalid Amount',
      '14' => 'Invalid Card Number',
      '15' => 'No Issuer',
      '16' => 'Approved, Update Track 3  ',
      '19' => 'Re-enter Last Transaction',
      '21' => 'No Action Taken',
      '22' => 'Suspected Malfunction',
      '23' => 'Unacceptable Transaction Fee',
      '25' => 'Unable to Locate Record On File',
      '30' => 'Format Error',
      '31' => 'Bank Not Supported By Switch',
      '33' => 'Expired Card, Capture',
      '34' => 'Suspected Fraud, Retain Card',
      '35' => 'Card Acceptor, Contact Acquirer, Retain Card',
      '36' => 'Restricted Card, Retain Card',
      '37' => 'Contact Acquirer Security Department, Retain Card',
      '38' => 'PIN Tries Exceeded, Capture',
      '39' => 'No Credit Account',
      '40' => 'Function Not Supported',
      '41' => 'Lost Card',
      '42' => 'No Universal Account',
      '43' => 'Stolen Card',
      '44' => 'No Investment Account',
      '51' => 'Insufficient Funds',
      '52' => 'No Cheque Account',
      '53' => 'No Savings Account',
      '54' => 'Expired Card',
      '55' => 'Incorrect PIN',
      '56' => 'No Card Record',
      '57' => 'Function Not Permitted to Cardholder',
      '58' => 'Function Not Permitted to Terminal',
      '59' => 'Suspected Fraud',
      '60' => 'Acceptor Contact Acquirer',
      '61' => 'Exceeds Withdrawal Limit',
      '62' => 'Restricted Card',
      '63' => 'Security Violation',
      '64' => 'Original Amount Incorrect',
      '66' => 'Acceptor Contact Acquirer, Security',
      '67' => 'Capture Card',
      '75' => 'PIN Tries Exceeded',
      '82' => 'CVV Validation Error',
      '90' => 'Cutoff In Progress',
      '91' => 'Card Issuer Unavailable',
      '92' => 'Unable To Route Transaction',
      '93' => 'Cannot Complete, Violation Of The Law',
      '94' => 'Duplicate Transaction',
      '96' => 'System Error'
    );
    return (isset($arr[$code]) ? $arr[$code] : 'Unknown');
  }

}

?>