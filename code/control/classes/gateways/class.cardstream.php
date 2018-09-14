<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $siggie = (isset($_POST['signature']) ? $_POST['signature'] : '');
    // Get order based on signature..
    if ($siggie && $this->order['rcode'] == $siggie) {
      $this->log($this->order['id'], 'Signature verified: ' . $siggie . '..Checking response code..');
      if (isset($_POST['responseCode']) && $_POST['responseCode'] == '0') {
        $this->log($this->order['id'], 'Sale response code 0 (Zero), sale is valid and approved');
        return 'ok';
      } else {
        $this->log($this->order['id'], 'Sale response code says sale isn`t approved');
        return 'err';
      }
    } else {
      $this->log($this->order['id'], 'Signature NOT verified. No sale found with hash code: ' . $siggie);
      return 'err';
    }
  }

  // Variables created on callback..
  public function callback() {
    $arr = array(
      'trans-id' => (isset($_POST['transactionID']) ? $_POST['transactionID'] : ''),
      'amount' => (isset($_POST['amountReceived']) ? $_POST['amountReceived'] : ''),
      'refund-amount' => '',
      'currency' => $this->settings->currency,
      'code-id' => (isset($_POST['transactionUnique']) ? $_POST['transactionUnique'] : ''),
      'signature' => (isset($_POST['signature']) ? $_POST['signature'] : ''),
      'pay-status' => (isset($_POST['responseCode']) ? $_POST['responseCode'] : ''),
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
    $country = $this->country($_POST['country']);
    $params  = $this->params();
    $total   = $this->saletotal($order);
    $arr     = array(
      'merchantID' => ($this->settings->paymode == 'live' ? $params['merchant-id'] : '0000992'),
      'amount' => str_replace('.', '', $total),
      'action' => 'SALE',
      'type' => '1',
      'countryCode' => $country->iso4217,
      'currencyCode' => $this->iso4217[$this->settings->currency],
      'transactionUnique' => $BUYCODE . '-' . $ID,
      'orderRef' => $this->stripchars($this->lang[2]),
      'redirectURL' => $url . 'index.php?gw=' . $ID . '-' . $BUYCODE,
      'callbackURL' => $url . 'callback/cardstream.php',
      'merchantData' => $BUYCODE . '-' . $ID,
      'customerName' => $this->stripchars($order->name),
      'customerAddress' => $this->stripchars($this->gateAddr($country->id)),
      'customerPostCode' => $this->stripchars($_POST['postcode']),
      'customerEmail' => $this->stripchars($order->email),
      'item1Description' => $this->stripchars($this->lang[2]),
      'item1Quantity' => '1',
      'item1GrossValue' => str_replace('.', '', $total)
    );
    // Calculate the signature and store it..
    ksort($arr);
    $arr['signature'] = hash('SHA512', http_build_query($arr) . $params['signature-key']);
    $this->storeref($arr['signature'],$ID);
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