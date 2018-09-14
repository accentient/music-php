<?php

class mmGateway extends db {

  // MNS verification handlers..
  private $mnshandlers = array(
   'live' => 'https://my.fetchpayments.co.nz/webpayments/MNSHandler.aspx',
   'demo' => 'https://demo.fetchpayments.co.nz/webpayments/MNSHandler.aspx'
  );

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params  = $this->params();
    $postRet = str_replace('+','%20',http_build_query($_POST)) . '&cmd=_xverify-transaction';
    $this->log($this->order['id'], 'Sending data back to Fetch to validate: ' . $postRet . mswDefineNewline() . mswDefineNewline() . 'Ping handler is: ' . ($this->settings->paymode == 'live' ? $this->mnshandlers['live'] : $this->mnshandlers['demo']));
    $r = $this->transmit(($this->settings->paymode == 'live' ? $this->mnshandlers['live'] : $this->mnshandlers['demo']), $postRet);
    $this->log($this->order['id'], 'Fetch responded with: ' . $r);
    return (strpos(strtolower($r), 'verified') === true || strpos(strtolower($r), 'verified') > 0 ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $chop  = (isset($_POST['custom_data']) ? explode('-',$_POST['custom_data']) : array(0,0));
    $order = $this->getsale($chop[1], $chop[0]);
    $arr   = array(
      'trans-id' => (isset($_POST['transaction_id']) ? $_POST['transaction_id'] : ''),
      'amount' => (isset($order->paytotal) ? $this->saletotal($order) : '0.00'),
      'currency' => $this->settings->currency,
      'code-id' => (isset($_POST['custom_data']) ? $_POST['custom_data'] : ''),
      'pay-status' => (isset($_POST['transaction_status']) ? $_POST['transaction_status'] : ''),
      'pending-reason' => '',
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
    $arr     = array(
      'cmd' => '_xclick',
      'account_id' => $params['account-id'],
      'amount' => $this->saletotal($order),
      'item_name' => str_replace(' ','%20',$this->stripchars($this->lang[2])),
      'return_url' => $url . 'index.php?gw=' . $ID . '-' . $BUYCODE,
      'notification_url' => $url . 'callback/fetch.php',
      'custom_data' => $BUYCODE . '-' . $ID . '-mswmusic',
      'display_customer_email' => '1'
    );
    // Calculate the hash
    $arr['merchant_verifier'] = mmGateway::sechash($arr, $params);
    return $arr;
  }

  // Calculate hash..
  public function sechash($arr, $params) {
    $string = $params['account-id'] . $arr['amount'] . $arr['return_url'] . $arr['notification_url'] . $arr['custom_data'] . $params['secret-key'];
    $hash   = mb_convert_encoding($string, 'utf-8');
    $b64    = base64_encode(sha1($hash, true));
    $this->log($this->order['id'], 'Calculating security hash for merchant verifier: ' . $b64);
    return $b64;
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

  // Error codes..
  public function errorcodes($code) {
    $arr = array(
      'W2P001' => 'Invalid cmd field.',
      'W2P002' => 'Invalid account id field.',
      'W2P003' => 'Invalid amount field.',
      'W2P004' => 'Invalid item name field.',
      'W2P005' => 'Invalid reference field.',
      'W2P006' => 'Invalid particular field.',
      'W2P007' => 'Invalid return url field.',
      'W2P008' => 'Invalid notification url field.',
      'W2P009' => 'Invalid update_url field.',
      'W2P0l0' => 'Invalid header_image field.',
      'W2P011' => 'Invalid header_bottom_border field.',
      'W2P012' => 'Invalid header_background_colour field.',
      'W2P013' => 'Invalid page background colour field.',
      'W2P014' => 'Invalid shopping cart items field.',
      'W2P015' => 'Invalid shopping cart item_price field or item_qty field.',
      'W2P016' => 'Invalid shopping cart item_name field.',
      'W2P017' => 'Invalid shopping cart item_code field.',
      'W2P018' => 'Invalid session id field.',
      'W2P019' => 'Invalid checkout_id field.',
      'W2P020' => 'Invalid customer email field.',
      'W2P021' => 'Invalid card_type field.',
      'W2P022' => 'Your IP address is not in our acceptable range.',
      'W2P023' => 'An error has occurred while processing your payment.',
      'W2P024' => 'The payment server is not available right now. Please try again later.',
      'W2P025' => '3 party mode 2 is not enabled for your account.',
      'W2P026' => 'Transaction is blocked.',
      'W2P027' => 'Fetch web payments is not enabled for your account.',
      'W2P028' => 'Payment gateway setup is not correct, please contact Fetch.',
      'W2P029' => 'Client account setup is not correct, please contact Fetch.',
      'W2P030' => 'Invalid custom_data field.',
      'W2P042' => 'Service is not enabled',
      'W2P043' => 'Invalid store card field. Must be 0 or 1.',
      'W2P044' => 'CSS file not found.',
      'W2P045' => 'Page display type undetermined. Must be 1 or 2.',
      'W2P046' => 'Merchant is not active.',
      'W2P047' => 'Invalid csc required field. Must be 0 or 1.',
      'W2P048' => 'Invalid client_id field.',
      'W2P049' => 'Invalid unique reference field.',
      'W2P050' => 'Card details are invalid or the one dollar authorisation has failed.',
      'W2P051' => 'Invalid register return url value.',
      'W2P054' => 'Invalid day of week value. Please consult integration guide.',
      'W2P055' => '3DSecure result - card not enrolled.',
      'W2P056' => '3DSecure result - cardholder not registered.',
      'W2P057' => '3DSecure result - cardholder registered but verification not successful.',
      'W2P058' => 'Invalid Merchant Transaction ID.',
      'W2P059' => 'Invalid Mobile Device ID.',
      'W2P060' => 'Invalid Mobile Device Description.',
      'W2P061' => 'Invalid Latitude.',
      'W2P062' => 'Invalid Longitude.',
      'W2P063' => 'Invalid Customer Mobile Number.',
      'W2P064' => 'Invalid Customer Data 1.',
      'W2P065' => 'Invalid Customer Data 2.',
      'W2P066' => 'Invalid Customer Data 3.',
      'W2P067' => 'Invalid Customer Data 4.',
      'W2P068' => 'Invalid Customer Data 5.',
      'W2P069' => 'Error reading Web2Pay URI.',
      'W2P070' => 'The value provided for payment method is invalid.',
      'W2P071' => 'Merchant account is not configured for the selected payment method.',
      'W2P072' => 'Invalid Mobile Request.',
      'W2P073' => 'Mobile Request already has been processed.',
      'W2P074' => 'Merchant verifier does not match with the details provided.',
      'W2P075' => 'Xero integration disconnected by the merchant.'
    );
    return (isset($arr[$code]) ? $arr[$code] : 'N/A');
  }

}

?>