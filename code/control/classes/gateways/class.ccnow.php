<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params = $this->params();
    $hash   = $this->responseHash($params, $this->order['id']);
    $phash  = (isset($_POST['x_fp_hash']) ? $_POST['x_fp_hash'] : 'XX');
    $this->log($this->order['id'], 'Hash check comparison to validate. Must match:' . mswDefineNewline() . 'Callback: ' . strtoupper($phash) . mswDefineNewline() . 'Calculated: ' . strtoupper($hash));
    return (strtoupper($hash) == strtoupper($phash) ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $order = $this->getsale((isset($_POST['x_invoice_num']) ? (int) $_POST['x_invoice_num'] : '0'));
    $arr   = array(
      'trans-id' => (isset($_POST['x_orderid']) ? $_POST['x_orderid'] : ''),
      'amount' => (isset($_POST['x_amount']) ? $this->num_format($_POST['x_amount']) : ''),
      'refund-amount' => (isset($_POST['x_refund_amount']) ? $this->num_format($_POST['x_refund_amount']) : ''),
      'currency' => (isset($_POST['x_currency_code']) ? $_POST['x_currency_code'] : ''),
      'code-id' => (isset($order->saleID) ? $order->code . '-' . $order->saleID : '0-0'),
      'pay-status' => (isset($_POST['x_status']) ? $_POST['x_status'] : ''),
      'message' => (isset($_POST['x_reason']) ? '[' . $_POST['x_reason'] . '] ' . $_POST['x_reason'] : ''),
      'inv-status' => '',
      'fraud-status' => ''
    );
    return $arr;
  }

  // Post fields to gateway..
  public function fields() {
    $ID        = $this->order['id'];
    $BUYCODE   = $this->order['code'];
    $url       = (mswSSL() == 'yes' ? str_replace('http://', 'https://', BASE_HREF) : BASE_HREF);
    $order     = $this->getsale($ID, $BUYCODE);
    $params    = $this->params();
    $timestamp = time();
    $country   = $this->country($_POST['country']);
    $total     = $this->saletotal($order);
    $arr       = array(
      'x_login' => $params['login-id'],
      'x_version' => '1.0',
      'x_fp_sequence' => $ID,
      'x_fp_arg_list' => 'x_login^x_fp_arg_list^x_fp_sequence^x_amount^x_currency_code',
      'x_fp_hash' => $this->submissionHash($params, $ID, $this->settings->currency, $total),
      'x_product_sku_1' => '1',
      'x_product_title_1' => $this->stripchars($this->lang[2]),
      'x_product_quantity_1' => '1',
      'x_product_unitprice_1' => $total,
      'x_product_url_1' => $url,
      'x_name' => $this->stripchars($order->name),
      'x_address' => $this->stripchars($_POST['address1']),
      'x_address2' => $this->stripchars($_POST['address2']),
      'x_city' => $this->stripchars($_POST['city']),
      'x_state' => $this->stripchars($_POST['county']),
      'x_zip' => $this->stripchars($_POST['postcode']),
      'x_country' => $this->stripchars($country->iso2),
      'x_email' => $this->stripchars($order->email),
      'x_ship_to_name' => $this->stripchars($order->name),
      'x_ship_to_address' => $this->stripchars($_POST['address1']),
      'x_ship_to_address2' => $this->stripchars($_POST['address2']),
      'x_ship_to_city' => $this->stripchars($_POST['city']),
      'x_ship_to_state' => $this->stripchars($_POST['county']),
      'x_ship_to_zip' => $this->stripchars($_POST['postcode']),
      'x_ship_to_country' => $this->stripchars($country->iso2),
      'x_invoice_num' => $ID,
      'x_language' => $params['language'],
      'x_currency_code' => $this->settings->currency,
      'x_method' => 'NONE',
      'x_amount' => $total
    );
    return $arr;
  }

  // Hashes..
  public function submissionHash($params, $id, $code, $amount) {
    $string = $params['login-id'] . '^x_login^x_fp_arg_list^x_fp_sequence^x_amount^x_currency_code^' . $id . '^' . $amount . '^' . $code . '^' . $params['activation-key'];
    $this->log($id, 'Creating MD5 hash from the following string:' . mswDefineNewline() . mswDefineNewline() . $string);
    return md5($string);
  }

  public function responseHash($params, $id) {
    $orderID = (isset($_POST['x_orderid']) ? $_POST['x_orderid'] : '');
    $status  = (isset($_POST['x_status']) ? $_POST['x_status'] : '');
    $date    = (isset($_POST['x_timestamp']) ? $_POST['x_timestamp'] : '');
    $key     = $params['secret-key'];
    $code    = $orderID . '^' . $status . '^' . $date . '^' . $key;
    $this->log($id, 'Create callback MD5 Hash Digest from the following string:' . mswDefineNewline() . $code);
    return md5($code);
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