<?php

class mmGateway extends db {

  // Validate gateway payment..
  // Create your own code to validate gateway based on gateway API.
  // Function must return 'ok' for valid response and 'err' for invalid.
  public function validate() {
    // $order                  =  $this->getsale($ID);
    // $params                 =  $this->params();
    return 'ok';
  }

  // Variables created on callback..
  // Assigned on the callback. DO NOT change the key names, but enter the correct values based on the gateway callback API POST vars
  // Not all are required. It depends what is required by the API
  public function callback() {
    $arr = array(
      'trans-id' => '',
      'amount' => '',
      'refund-amount' => '',
      'currency' => '',
      'code-id' => (isset($_POST['custom']) ? $_POST['custom'] : ''),
      'pay-status' => '',
      'pending-reason' => '',
      'inv-status' => '',
      'fraud-status' => ''
    );
    return $arr;
  }

  // Post fields to gateway..
  public function fields() {
    $url    = (mswSSL() == 'yes' ? str_replace('http://', 'https://', BASE_HREF) : BASE_HREF);
    $order  = $this->getsale($this->order['id'], $this->order['code']);
    $params = $this->params();
    $arr    = array(
      // Other vars here..
      'custom' => $this->order['code'] . '-' . $this->order['id'] . '-mswmusic'
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