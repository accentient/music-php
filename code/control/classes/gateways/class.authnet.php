<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params = $this->params();
    $hash   = mmGateway::responsehash($params, $this->order['id']);
    $phash  = (isset($_POST['x_MD5_Hash']) ? strtolower($_POST['x_MD5_Hash']) : 'XX');
    $this->log($this->order['id'], 'Hash check comparison to validate. Must match:' . mswDefineNewline() . 'Callback: ' . strtoupper($phash) . mswDefineNewline() . 'Calculated: ' . strtoupper($hash));
    return (strtoupper($hash) == strtoupper($phash) ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $order = $this->getsale((isset($_POST['x_invoice_num']) ? (int) $_POST['x_invoice_num'] : '0'));
    $arr   = array(
      'trans-id' => (isset($_POST['x_trans_id']) ? $_POST['x_trans_id'] : ''),
      'amount' => (isset($_POST['x_amount']) ? $this->num_format($_POST['x_amount']) : ''),
      'refund-amount' => '',
      'currency' => $this->settings->currency,
      'code-id' => (isset($order->saleID) ? $order->code . '-' . $order->saleID : '0-0'),
      'pay-status' => (isset($_POST['x_response_code']) ? $_POST['x_response_code'] : ''),
      'message' => (isset($_POST['x_response_reason_text']) ? '[' . $_POST['x_response_reason_code'] . '] ' . $_POST['x_response_reason_text'] : ''),
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
    $name      = $this->firstLastName($order->name);
    $country   = $this->country($_POST['country']);
    $amount    = $this->saletotal($order);
    $arr       = array(
      'x_login' => $params['login-id'],
      'x_amount' => $amount,
      'x_description' => $this->stripchars($this->lang[2]),
      'x_invoice_num' => $ID,
      'x_fp_sequence' => $ID,
      'x_fp_timestamp' => $timestamp,
      'x_fp_hash' => mmGateway::submissionhash($timestamp, $params, $ID, $amount),
      'x_test_request' => ($this->settings->paymode == 'live' ? 'false' : 'true'),
      'x_show_form' => 'PAYMENT_FORM',
      'x_type' => 'AUTH_CAPTURE',
      'x_first_name' => $this->stripchars($name['first-name']),
      'x_last_name' => $this->stripchars($name['last-name']),
      'x_address' => $this->stripchars($_POST['address1'] . ($_POST['address2'] ? ', ' . $_POST['address2'] : '')),
      'x_email' => $this->stripchars($order->email),
      'x_city' => $this->stripchars($_POST['city']),
      'x_state' => $this->stripchars($_POST['county']),
      'x_zip' => $this->stripchars($_POST['postcode']),
      'x_country' => $this->stripchars($country->name),
      'x_ship_to_first_name' => $this->stripchars($name['first-name']),
      'x_ship_to_last_name' => $this->stripchars($name['last-name']),
      'x_ship_to_address' => $this->stripchars($_POST['address1'] . ($_POST['address2'] ? ', ' . $_POST['address2'] : '')),
      'x_ship_to_city' => $this->stripchars($_POST['city']),
      'x_ship_to_state' => $this->stripchars($_POST['county']),
      'x_ship_to_zip' => $this->stripchars($_POST['postcode']),
      'x_ship_to_country' => $this->stripchars($country->name),
      'x_relay_response' => 'false',
      'x_cancel_url' => $url . $this->seo->url('cancel', array(), 'yes'),
      'x_receipt_method' => 'POST',
      'x_receipt_link_text' => $this->stripchars(str_replace('{store}', $this->settings->website, $this->lang[5])),
      'x_receipt_link_url' => $url . 'index.php?gw=' . $ID . '-' . $BUYCODE
    );
    // Only include currency code for live server..
    // Seems to throw errors for test server..
    // If this throws (99) errors on live, uncomment..
    if ($this->settings->paymode == 'live') {
      $arr['x_currency_code'] = (in_array($this->settings->currency, array(
        'USD',
        'GBP',
        'CAD',
        'EUR'
      )) ? $this->settings->currency : 'USD');
    }
    return $arr;
  }

  // Hashes..
  public function submissionhash($timestamp, $params, $id, $amount) {
    if (function_exists('hash_hmac')) {
      $this->log($id, 'Create MD5 (Hash_Hmac) Digest from the following string (with key appended):' . mswDefineNewline() . $params['login-id'] . '^' . $id . '^' . $timestamp . '^' . $amount . '^');
      return hash_hmac('md5', $params['login-id'] . '^' . $id . '^' . $timestamp . '^' . $amount . '^', $params['transaction-key']);
    } else {
      $this->log($id, 'Create MD5 (Bin2Hex/Mhash) Digest from the following string (with key appended):' . mswDefineNewline() . $params['login-id'] . '^' . $id . '^' . $timestamp . '^' . $amount . '^');
      return bin2hex(mhash(MHASH_MD5, $params['login-id'] . '^' . $id . '^' . $timestamp . '^' . $amount . '^', $params['transaction-key']));
    }
  }

  public function responsehash($params, $id) {
    $code = $params['response-key'] . $params['login-id'] . $_POST['x_trans_id'] . $_POST['x_amount'];
    $this->log($id, 'Creating callback MD5 Hash Digest from the following string:' . mswDefineNewline() . $code);
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