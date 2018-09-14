<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params = $this->params();
    // Check the hash to verify data came from Paypoint..
    $flds   = 'trans_id=' . $_POST['trans_id'] . '&amount=' . $_POST['amount'] . '&custom=' . $this->order['code'] . '-' . $this->order['id'] . '&' . $params['pass-remote'];
    $md5    = md5($flds);
    $this->log($this->order['id'], 'Hash check comparison to validate. Must match:' . mswDefineNewline() . 'Paypoint Hash: ' . strtoupper($_POST['hash']) . mswDefineNewline() . 'Calculated: ' . strtoupper($md5));
    return (isset($_POST['hash']) && strtoupper($_POST['hash']) == strtoupper($md5) ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    if (isset($_GET['custom'])) {
      $_GET  = array_map('urldecode', $_GET);
      $_POST = $_GET;
    }
    $arr   = array(
      'trans-id' => (isset($_POST['auth_code']) ? $_POST['auth_code'] : ''),
      'amount' => (isset($_POST['amount']) ? $this->num_format($_POST['amount']) : ''),
      'refund-amount' => '',
      'currency' => $this->settings->currency,
      'code-id' => (isset($_POST['custom']) ? $_POST['custom'] : ''),
      'pay-status' => (isset($_POST['code']) ? $_POST['code'] : ''),
      'response-code' => (isset($_POST['resp_code']) ? mmGateway::codes($_POST['resp_code']) : ''),
      'message' => (isset($_POST['message']) ? $_POST['message'] : ''),
      'pending-reason' => '',
      'inv-status' => '',
      'fraud-status' => ''
    );
    return $arr;
  }

  // Post fields to gateway..
  public function fields() {
    $ID             = $this->order['id'];
    $BUYCODE        = $this->order['code'];
    $url            = (mswSSL() == 'yes' ? str_replace('http://', 'https://', BASE_HREF) : BASE_HREF);
    $order          = $this->getsale($ID, $BUYCODE);
    $country        = $this->country($_POST['country']);
    $params         = $this->params();
    $total          = $this->saletotal($order);
    $arr            = array(
      'merchant' => $params['merchant-id'].'-redir',
      'amount' => $total,
      'trans_id' => 'sale-' . $ID,
      'currency' => $this->settings->currency,
      'callback' => $url . 'callback/paypoint.php',
      'backcallback' => urlencode($url . $this->seo->url('cancel', array(), 'yes')),
      'show_back' => 'submit',
      'bill_name' => $this->stripchars($order->name),
      'bill_addr_1' => $this->stripchars($_POST['address1']),
      'bill_addr_2' => $this->stripchars($_POST['address2']),
      'bill_city' => $this->stripchars($_POST['city']),
      'bill_state' => $this->stripchars($_POST['county']),
      'bill_post_code' => $this->stripchars($_POST['postcode']),
      'bill_country' => $this->stripchars($country->name),
      'bill_tel' => '',
      'bill_email' => $this->stripchars($order->email),
      'options' => 'test_status=' . ($this->settings->paymode == 'live' ? 'false' : 'true') . ',md_flds=trans_id:amount:custom,dups=' . ($this->settings->paymode == 'test' ? 'false' : 'true') . (mswSSL() == 'yes' ? ',ssl_cb=true' : ''),
      'digest' => md5('sale-' . $ID . $total . $params['pass-remote']),
      'order' => 'prod=' . $this->stripchars(str_replace('{store}', $this->settings->website, $this->lang[2])) . ',item_amount=' . $total . 'x1',
      'custom' => $BUYCODE . '-' . $ID
    );
    // We want the custom field on the callback..
    $arr['options'] = $arr['options'] . ',cb_flds=custom';
    // Is logo to be included?
    if (isset($params['logo']) && $params['logo'] == 'yes') {
      $arr['options'] = $arr['options'] . ',merchant_logo=<img src=\'https://www.secpay.com/users/' . $params['merchant-id'] . '/logo.jpg\' height=\'100px\' width=\'750px\' class=\'floatright\'>';
    }
    // Other test vars..
    if ($this->settings->paymode == 'test') {
      $arr['amex'] = 'true';
    }
    return $arr;
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

  // Response codes..
  public function codes($code) {
    $arr = array(
      'N' => 'Transaction not authorised. Failure message text available to merchant.',
      'C' => 'Communication problem. Trying again later may well work.',
      'F' => 'The PayPoint.net system has detected a fraud condition and rejected the transaction.',
      'P:A' => 'Pre-bank checks. Amount not supplied or invalid.',
      'P:X' => 'Pre-bank checks. Not all mandatory parameters supplied.',
      'P:P' => 'Pre-bank checks. Same payment presented twice.',
      'P:S' => 'Pre-bank checks. Start date invalid.',
      'P:E' => 'Pre-bank checks. Expiry date invalid.',
      'P:I' => 'Pre-bank checks. Issue number invalid.',
      'P:C' => 'Pre-bank checks. Card number fails LUHN check (the card number is wrong).',
      'P:T' => 'Pre-bank checks. Card type invalid - i.e. does not match card number prefix.',
      'P:N' => 'Pre-bank checks. Customer name not supplied.',
      'P:M' => 'Pre-bank checks. Merchant does not exist or not registered yet.',
      'P:B' => 'Pre-bank checks. Merchant account for card type does not exist.',
      'P:D' => 'Pre-bank checks. Merchant account for this currency does not exist.',
      'P:V' => 'Pre-bank checks. CV2 security code mandatory and not supplied / invalid Pre-bank checks.',
      'P:R' => 'Pre-bank checks. Transaction timed out awaiting a virtual circuit. Merchant may not have enough virtual circuits for the volume of business.',
      'P:#' => 'Pre-bank checks. No MD5 hash / token key set up against account.'
    );
    return (isset($arr[$code]) ? $arr[$code] : 'N/A');
  }

}

?>