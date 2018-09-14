<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params     = $this->params();
    $saleID     = $_POST['sale_id'];
    $vendorID   = $_POST['vendor_id'];
    $invoiceID  = $_POST['invoice_id'];
    $orderTotal = $_POST['invoice_list_amount'];
    if (isset($_POST['demo']) && $_POST['demo'] == 'Y') {
      $invoiceID = 1;
    }
    // Calculate md5 hash as 2co formula: md5(saleID + vendorID + invoiceID + secret_word)
    $key        = strtoupper(md5($saleID . $vendorID . $invoiceID . $params['secret']));
    $comparison = strtoupper($_POST['md5_hash']);
    $this->log($this->order['id'], 'Hash check comparison to validate. Must match:' . mswDefineNewline() . 'Key: ' . $key . mswDefineNewline() . 'Hash: ' . $comparison);
    // verify if the key is accurate
    return ($comparison == $key ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $arr = array(
      'trans-id' => (isset($_POST['invoice_id']) ? $_POST['invoice_id'] : ''),
      'amount' => (isset($_POST['invoice_list_amount']) ? $this->num_format($_POST['invoice_list_amount']) : ''),
      'refund-amount' => (isset($_POST['item_list_amount_1']) ? $this->num_format($_POST['item_list_amount_1']) : ''),
      'currency' => (isset($_POST['list_currency']) ? $_POST['list_currency'] : ''),
      'code-id' => (isset($_POST['vendor_order_id']) ? $_POST['vendor_order_id'] : ''),
      'pay-status' => (isset($_POST['message_type']) ? $_POST['message_type'] : ''),
      'inv-status' => (isset($_POST['invoice_status']) ? $_POST['invoice_status'] : ''),
      'fraud-status' => (isset($_POST['fraud_status']) ? $_POST['fraud_status'] : '')
    );
    return $arr;
  }

  // Post fields to gateway..
  public function fields() {
    $ID      = $this->order['id'];
    $BUYCODE = $this->order['code'];
    $url     = (mswSSL() == 'yes' ? str_replace('http://', 'https://', BASE_HREF) : BASE_HREF);
    $order   = $this->getsale($ID, $BUYCODE);
    $country = $this->country($_POST['country']);
    $params  = $this->params();
    $arr     = array(
      'sid' => $params['account'],
      'cart_order_id' => $ID,
      'total' => $this->saletotal($order),
      'x_Receipt_Link_URL' => $url . 'index.php?gw=' . $ID . '-' . $BUYCODE,
      'tco_currency' => $this->settings->currency,
      'merchant_order_id' => $BUYCODE . '-' . $ID,
      'lang' => strtolower($params['language']),
      'card_holder_name' => $this->stripchars($order->name),
      'street_address' => $this->stripchars($_POST['address1']),
      'street_address2' => $this->stripchars($_POST['address2']),
      'city' => $this->stripchars($_POST['city']),
      'state' => $this->stripchars($_POST['county']),
      'zip' => $this->stripchars($_POST['postcode']),
      'country' => $this->stripchars($country->name),
      'email' => $this->stripchars($order->email),
      'ship_name' => $this->stripchars($order->name),
      'ship_street_address' => $this->stripchars($_POST['address1']),
      'ship_street_address2' => $this->stripchars($_POST['address2']),
      'ship_city' => $this->stripchars($_POST['city']),
      'ship_state' => $this->stripchars($_POST['county']),
      'ship_zip' => $this->stripchars($_POST['postcode']),
      'ship_country' => $this->stripchars($country->name)
    );
    // Send var for test mode..
    if ($this->settings->paymode == 'test') {
      $arr['demo'] = 'Y';
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

}

?>