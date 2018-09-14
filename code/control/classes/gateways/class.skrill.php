<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params      = $this->params();
    $secret_word = strtoupper(md5($params['secret']));
    $md5         = $_POST['merchant_id'] . $_POST['transaction_id'] . $secret_word . $_POST['mb_amount'] . $_POST['mb_currency'] . $_POST['status'];
    $this->log($this->order['id'], 'Creating callback MD5 Hash Digest from the following string:' . mswDefineNewline() . $md5);
    $md5 = strtoupper(md5($md5));
    $this->log($this->order['id'], 'Hash check comparison to validate. Must match:' . mswDefineNewline() . 'Signature: ' . strtoupper($_POST['md5sig']) . mswDefineNewline() . 'Hash: ' . $md5);
    return (isset($_POST['md5sig']) && strtoupper($_POST['md5sig']) == $md5 ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $arr = array(
      'trans-id' => (isset($_POST['transaction_id']) ? $_POST['transaction_id'] : ''),
      'amount' => (isset($_POST['mb_amount']) ? $this->num_format($_POST['mb_amount']) : ''),
      'refund-amount' => (isset($_POST['mb_amount']) ? $this->num_format($_POST['mb_amount']) : ''),
      'currency' => (isset($_POST['mb_currency']) ? $_POST['mb_currency'] : ''),
      'code-id' => (isset($_POST['buycode']) ? $_POST['buycode'] : ''),
      'pay-status' => (isset($_POST['status']) ? $_POST['status'] : ''),
      'pending-reason' => 'N/A'
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
    $name    = $this->firstLastName($order->name);
    $arr     = array(
      'pay_to_email' => $params['email'],
      'firstname' => $this->stripchars($name['first-name']),
      'lastname' => $this->stripchars($name['last-name']),
      'pay_from_email' => $this->stripchars($order->email),
      'country' => $country->iso,
      'address' => $this->stripchars($_POST['address1']),
      'address2' => $this->stripchars($_POST['address2']),
      'city' => $this->stripchars($_POST['city']),
      'state' => $this->stripchars($_POST['county']),
      'postal_code' => $this->stripchars($_POST['postcode']),
      'detail1_description' => $this->lang[6],
      'detail1_text' => $this->stripchars($this->lang[2]),
      'transaction_id' => $ID,
      'status_url' => $url . 'callback/skrill.php',
      'cancel_url' => $url . $this->seo->url('cancel', array(), 'yes'),
      'return_url' => $url . 'index.php?gw=' . $ID . '-' . $BUYCODE,
      'return_url_text' => $this->stripchars(str_replace('{store}', $this->settings->website, $this->lang[5])),
      'amount' => rtrim($this->saletotal($order), '0'),
      'currency' => $this->settings->currency,
      'language' => $params['language'],
      'merchant_fields' => 'buycode',
      'buycode' => $BUYCODE . '-' . $ID
    );
    if (isset($params['logo']) && $params['logo'] && mswSSL() == 'yes' && substr($params['logo'], 0, 5) == 'https') {
      $arr['logo_url'] = $params['logo'];
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