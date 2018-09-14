<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params  = $this->params();
    if (isset($_POST['responsesitesecurity']) && $_POST['responsesitesecurity']) {
      $this->log($this->order['id'], 'POST received from Secure Trading: ' . print_r($_POST, true));
      $string  = '';
      $keys    = array_keys($_POST);
      sort($keys);
      foreach ($keys as $pK) {
        if (!in_array($pK, array('notificationreference', 'responsesitesecurity'))) {
          $string .= $_POST[$pK];
        }
      }
      if ($string) {
        $string .= $params['notify-password'];
        $hash    = hash('sha256', $string);
        $this->log($this->order['id'], 'Hash calculated by system: ' . $hash);
        $this->log($this->order['id'], 'Hash sent by Secure Trading must match: ' . $_POST['responsesitesecurity']);
        if (strtolower($hash) == strtolower($_POST['responsesitesecurity']) && $_POST['sitereference'] == $params['site-reference']) {
          if ($_POST['errorcode'] == '0') {
            return 'ok';
          }
        }
      }
    }
    return 'err';
  }

  // Variables created on callback..
  public function callback() {
    $arr = array(
      'trans-id' => (isset($_POST['transactionreference']) ? $_POST['transactionreference'] : ''),
      'amount' => (isset($_POST['mainamount']) ? $this->num_format($_POST['mainamount']) : ''),
      'currency' => (isset($_POST['currencyiso3a']) ? $_POST['currencyiso3a'] : ''),
      'code-id' => (isset($_POST['custom-data']) ? $_POST['custom-data'] : ''),
      'pay-status' => 'completed',
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
    $arr     = array(
      'sitereference' => $params['site-reference'],
      'currencyiso3a' => $this->settings->currency,
      'mainamount' => $this->saletotal($order),
      'version' => '1',
      'orderreference' => $ID,
      'custom-data' => $BUYCODE . '-' . $ID . '-mswmusic',
      'settlestatus' => '',
      'settleduedate' => '',
      'authmethod' => ''
    );
    // Calculate the hash
    $arr['sitesecurity'] = 'g' . mmGateway::sechash($arr, $params);
    return $arr;
  }

  // Calculate hash..
  public function sechash($arr, $params) {
    $string = $arr['currencyiso3a'] . $arr['mainamount'] . $arr['sitereference'] . $arr['settlestatus'] . $arr['settleduedate'] . $arr['authmethod'] . $params['merchant-password'];
    $hash   = hash('sha256', $string);
    $this->log($this->order['id'], 'Calculating security hash for site security: ' . $hash);
    return $hash;
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