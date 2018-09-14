<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params     = $this->params();
    $merchantid = (isset($_POST['MERCHANT_ID']) ? $_POST['MERCHANT_ID'] : '');
    $timestamp  = (isset($_POST['TIMESTAMP']) ? $_POST['TIMESTAMP'] : '');
    $result     = (isset($_POST['RESULT']) ? $_POST['RESULT'] : '');
    $orderid    = (isset($_POST['ORDER_ID']) ? $_POST['ORDER_ID'] : '');
    $message    = (isset($_POST['MESSAGE']) ? $_POST['MESSAGE'] : '');
    $authcode   = (isset($_POST['AUTHCODE']) ? $_POST['AUTHCODE'] : '');
    $pasref     = (isset($_POST['PASREF']) ? $_POST['PASREF'] : '');
    $realexmd5  = (isset($_POST['MD5HASH']) ? $_POST['MD5HASH'] : '');
    $tmp        = "$timestamp.$merchantid.$orderid.$result.$message.$pasref.$authcode";
    $this->log($this->order['id'], 'Creating callback MD5 Hash Digest from the following string:' . mswDefineNewline() . $tmp);
    $hashTemp = md5($tmp);
    $key      = $params['secret-key'];
    $keyTemp  = "$hashTemp.$key";
    $this->log($this->order['id'], 'Append key and create MD5 Hash Digest from the following string:' . mswDefineNewline() . $keyTemp);
    $received = md5($keyTemp);
    // Validate..
    $this->log($this->order['id'], 'MD5 hash check to validate. Must match:' . mswDefineNewline() . 'Callback: ' . strtoupper($realexmd5) . mswDefineNewline() . 'Calculated: ' . strtoupper($received));
    // Check result..
    $this->log($this->order['id'], 'Checking Result: ' . $this->gatewayCodes($result));
    return (strtoupper($received) == strtoupper($realexmd5) && $result == '00' ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $order = $this->getsale((isset($_POST['ORDER_ID']) ? (int) $_POST['ORDER_ID'] : '0'));
    $arr   = array(
      'trans-id' => (isset($_POST['AUTHCODE']) ? $_POST['AUTHCODE'] : ''),
      'amount' => (isset($order->saleID) ? $this->saletotal($order) : '0.00'),
      'refund-amount' => '',
      'currency' => $this->settings->currency,
      'code-id' => (isset($order->saleID) ? $order->code . '-' . $order->saleID : '0-0'),
      'pay-status' => (isset($order->saleID) ? 'OK' : ''),
      'pending-reason' => '',
      'inv-status' => '',
      'fraud-status' => ''
    );
    return $arr;
  }

  // Post fields to gateway..
  public function fields() {
    $ID       = $this->order['id'];
    $BUYCODE  = $this->order['code'];
    $url      = (mswSSL() == 'yes' ? str_replace('http://', 'https://', BASE_HREF) : BASE_HREF);
    $order    = $this->getsale($ID, $BUYCODE);
    $params   = $this->params();
    $ts       = strftime("%Y%m%d%H%M%S");
    $country  = $this->country($_POST['country']);
    $total    = $this->saletotal($order);
    $arr      = array(
      'MERCHANT_ID' => $params['merchant-id'],
      'ORDER_ID' => $ID,
      'ACCOUNT' => ($params['sub-account'] ? $params['sub-account'] : 'internet'),
      'CURRENCY' => $this->settings->currency,
      'AMOUNT' => str_replace('.', '', $total),
      'TIMESTAMP' => $ts,
      'AUTO_SETTLE_FLAG' => ($this->settings->propend == 'yes' ? '0' : '1'),
      'COMMENT1' => $this->stripchars(str_replace('{store}', $this->settings->website, $this->lang[2]) . ' (' . SCRIPT_NAME . ')'),
      'VAR_REF' => $this->stripchars($order->email),
      'CUSTOM' => $BUYCODE . '-' . $ID . '-mswmusic',
      'BILLING_CODE' => preg_replace('/[^0-9]/', '', $_POST['postcode']) . '|' . preg_replace('/[^0-9]/', '', $_POST['address1']),
      'BILLING_CO' => $country->iso2,
      'SHIPPING_CODE' => preg_replace('/[^0-9]/', '', $_POST['postcode']) . '|' . preg_replace('/[^0-9]/', '', $_POST['address1']),
      'SHIPPING_CO' => $country->iso2
    );
    // Calculate hash..
    // Concatenation MUST be quoted or else Global Iris will fail..
    $merchant = $params['merchant-id'];
    $key      = $params['secret-key'];
    $amt      = str_replace('.', '', $total);
    $cur      = $this->settings->currency;
    $tmp      = "$ts.$merchant.$ID.$amt.$cur";
    $this->log($ID, 'Create MD5 Hash Digest from the following string:' . mswDefineNewline() . $tmp);
    $hashTemp = md5($tmp);
    $keyTemp  = "$hashTemp.$key";
    $this->log($ID, 'Append key and create MD5 Hash Digest from the following string:' . mswDefineNewline() . $keyTemp);
    $arr['MD5HASH'] = md5($keyTemp);
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

  // Error codes..
  public function gatewayCodes($result) {
    switch (substr($result, 0, 1)) {
      case 2:
        $result = '2xx';
        break;
      case 3:
        $result = '3xx';
        break;
      case 5:
        $result = '5xx';
        break;
    }
    $codes = array(
      '00' => 'Successful',
      '101' => 'Declined by Bank',
      '102' => 'Referral by Bank (treat as decline in automated system such as internet)',
      '103' => 'Card reported lost or stolen',
      '2xx' => 'Error with bank systems',
      '3xx' => 'Error with Realex Payments systems',
      '5xx' => 'Incorrect XML message formation or content',
      '666' => 'Client deactivated.'
    );
    return (isset($codes[$result]) ? $codes[$result] : 'N/A');
  }

}

?>