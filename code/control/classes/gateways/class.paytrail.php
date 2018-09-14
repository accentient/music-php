<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params = $this->params();
    $hash   = $this->responseHash($params);
    $phash  = (isset($_POST['RETURN_AUTHCODE']) ? $_POST['RETURN_AUTHCODE'] : 'XX');
    $this->log($this->order['id'], 'Hash check comparison to validate. Must match:' . mswDefineNewline() . 'Callback: ' . strtoupper($phash) . mswDefineNewline() . 'Calculated: ' . strtoupper($hash));
    return (strtoupper($hash) == strtoupper($phash) ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $order = $this->getsale((isset($_POST['ORDER_NUMBER']) ? (int) $_POST['ORDER_NUMBER'] : '0'));
    $arr   = array(
      'amount' => (isset($order->saleID) ? $this->saletotal($order) : '0.00'),
      'trans-id' => (isset($_POST['PAID']) ? $_POST['PAID'] : ''),
      'refund-amount' => '',
      'currency' => $this->settings->currency,
      'code-id' => (isset($order->code) ? $order->code . '-' . $order->saleID : '0-0'),
      'pay-status' => (isset($_POST['PAID']) && $_POST['PAID'] ? 'OK' : ''),
      'pending-reason' => '',
      'inv-status' => '',
      'fraud-status' => ''
    );
    return $arr;
  }

  // Post fields to gateway..
  public function fields() {
    $ID              = $this->order['id'];
    $BUYCODE         = $this->order['code'];
    $url             = (mswSSL() == 'yes' ? str_replace('http://', 'https://', BASE_HREF) : BASE_HREF);
    $order           = $this->getsale($ID, $BUYCODE);
    $params          = $this->params();
    $country         = $this->country($_POST['country']);
    $name            = $this->firstLastName($order->name);
    $arr             = array(
      'MERCHANT_ID' => $params['merchant-id'],
      'AMOUNT' => $this->saletotal($order),
      'ORDER_NUMBER' => $ID,
      'REFERENCE_NUMBER' => '',
      'ORDER_DESCRIPTION' => $this->stripchars($this->lang[2]),
      'CURRENCY' => 'EUR',
      'RETURN_ADDRESS' => $url . 'index.php?gw=' . $ID . '-' . $BUYCODE,
      'CANCEL_ADDRESS' => $url . $this->seo->url('cancel', array(), 'yes'),
      'PENDING_ADDRESS' => $url . 'index.php?msg=5',
      'NOTIFY_ADDRESS' => $url . 'callback/paytrail.php',
      'TYPE' => 'S1',
      'CULTURE' => (in_array($params['language'], array('fi_FI','sv_SE','en_US')) ? $params['language'] : 'fi_FI'),
      'PRESELECTED_METHOD' => (isset($params['pre-select-method']) ? $params['pre-select-method'] : '9'),
      'MODE' => '1',
      'VISIBLE_METHODS' => '',
      'GROUP' => ''
    );
    // Calculate hash..
    $arr['AUTHCODE'] = $this->submissionHash($params, $arr, $ID);
    return $arr;
  }

  // Hashes..
  public function submissionHash($params, $arr, $id) {
    $string = $params['auth-hash'] . '|';
    foreach ($arr AS $k => $v) {
      $string .= $v . ($k != 'GROUP' ? '|' : '');
    }
    $this->log($id, 'Create MD5 Hash Digest from the following string:' . mswDefineNewline() . $string);
    $hash = md5($string);
    $this->log($id, 'Hash created was ' . strtoupper($hash));
    return strtoupper($hash);
  }

  public function responseHash($params) {
    $string = (isset($_POST['ORDER_NUMBER']) ? $_POST['ORDER_NUMBER'] : 'XX') . '|' . (isset($_POST['TIMESTAMP']) ? $_POST['TIMESTAMP'] : 'XX') . '|' . (isset($_POST['PAID']) ? $_POST['PAID'] : 'XX') . '|' . (isset($_POST['METHOD']) ? $_POST['METHOD'] : 'XX') . '|' . $params['auth-hash'];
    $this->log($this->order['id'], 'Creating callback MD5 Hash Digest from the following string:' . mswDefineNewline() . $string);
    return strtoupper(md5($string));
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