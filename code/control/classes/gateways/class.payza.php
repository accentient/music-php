<?php

class mmGateway extends db {

  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params = $this->params();
    // Log incoming vars..
    return (($_POST['ap_merchant'] == $params['email']) && ($_POST['ap_securitycode'] == $params['ipncode']) ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    // Url decode incoming data..
    $_POST = array_map('urldecode', $_POST);
    $arr   = array(
      'trans-id' => (isset($_POST['ap_referencenumber']) ? $_POST['ap_referencenumber'] : ''),
      'amount' => (isset($_POST['ap_amount']) ? $this->num_format($_POST['ap_amount']) : ''),
      'refund-amount' => (isset($_POST['ap_amount']) ? $this->num_format($_POST['ap_amount']) : ''),
      'currency' => (isset($_POST['ap_currency']) ? $_POST['ap_currency'] : ''),
      'code-id' => (isset($_POST['apc_1']) ? $_POST['apc_1'] : ''),
      'pay-status' => (isset($_POST['ap_status']) ? $_POST['ap_status'] : '')
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
      'ap_merchant' => $params['email'],
      'ap_itemname' => $this->stripchars($this->lang[2]),
      'ap_quantity' => '1',
      'ap_cancelurl' => $url . $this->seo->url('cancel', array(), 'yes'),
      'ap_returnurl' => $url . 'index.php?gw=' . $ID . '-' . $BUYCODE,
      'ap_amount' => $this->saletotal($order),
      'ap_purchasetype' => 'item',
      'ap_currency' => $this->settings->currency,
      'apc_1' => $BUYCODE . '-' . $ID
    );
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