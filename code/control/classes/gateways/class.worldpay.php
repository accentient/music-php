<?php

class mmGateway extends db {

  // Validate gateway payment..
  // Here we are checking that the trans status is Yes, the callback password matches and the Card Verification Value check passed..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params = $this->params();
    // Split 4 digit AVS..
    $avs = str_split($_POST['AVS']);
    // Country match and CVV checks. If live, both must match..
    // If you don`t want this check, set $val array same as test. Note that these checks are recommended by WorldPay.
    if ($this->settings->paymode == 'live') {
      $this->log($this->order['id'], 'Validating CVV (Card Verification Value) and Country Match (Both should equal 2):' . mswDefineNewline() . 'CVV: ' . $avs[0] . mswDefineNewline() . 'COUNTRY: ' . $avs[3]);
      $val = array(
        $avs[0],
        $avs[3]
      );
    } else {
      $this->log($this->order['id'], 'CVV and Country Match not checked in test mode. Passed OK.');
      $val = array(
        '2',
        '2'
      );
    }
    return (isset($_POST['transStatus']) && $_POST['transStatus'] == 'Y' && isset($_POST['callbackPW']) && $_POST['callbackPW'] == $params['callback-pw'] && $val[0] == '2' && $val[1] == '2' ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $arr = array(
      'trans-id' => (isset($_POST['transId']) ? $_POST['transId'] : ''),
      'amount' => (isset($_POST['amount']) ? $this->num_format($_POST['amount']) : ''),
      'refund-amount' => '',
      'currency' => $this->settings->currency,
      'code-id' => (isset($_POST['MC_custom']) ? $_POST['MC_custom'] : ''),
      'pay-status' => (isset($_POST['transStatus']) ? $_POST['transStatus'] : ''),
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
    $country = $this->country($_POST['country']);
    $arr     = array(
      'testMode' => ($this->settings->paymode == 'live' ? '0' : '100'),
      'instId' => $params['install-id'],
      'cartId' => 'Cart' . $ID,
      'amount' => $this->saletotal($order),
      'currency' => $this->settings->currency,
      'desc' => $this->stripchars($this->lang[2]),
      'name' => ($this->settings->paymode == 'live' ? $this->stripchars($order->name) : 'AUTHORISED'),
      'address1' => $this->stripchars($_POST['address1']),
      'address2' => $this->stripchars($_POST['address2']),
      'town' => $this->stripchars($_POST['city']),
      'region' => $this->stripchars($_POST['county']),
      'postcode' => $this->stripchars($_POST['postcode']),
      'country' => $country->iso2,
      'tel' => '',
      'email' => $this->stripchars($order->email),
      'hideCurrency' => '',
      'hideContact' => '',
      'MC_custom' => $BUYCODE . '-' . $ID,
      'MC_callback' => $url . 'callback/worldpay.php'
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