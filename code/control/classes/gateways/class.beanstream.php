<?php

class mmGateway extends db {

  // Response and transaction type globals for Beanstream..See api docs..
  private $responseType = 'R';
  private $trnType      = 'P';

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params = $this->params();
    // Hash comparison..
    $hash   = 'merchant_id=' . $params['merchant-id'] . '&responseType=' . $this->responseType . '&trnType=' . $this->trnType . '&trnOrderNumber=' . $_POST['trnOrderNumber'] . '&trnAmount=' . $_POST['trnAmount'] . $params['hash-value'];
    $this->log($this->order['id'], 'Create SHA1 Hash Digest from the following string:' . mswDefineNewline() . $hash);
    $hash  = sha1($hash);
    $hash2 = (isset($_POST['ref2']) ? $_POST['ref2'] : '');
    $this->log($this->order['id'], 'Hash check comparison to validate. Must match:' . mswDefineNewline() . 'Received: ' . strtoupper($hash2) . mswDefineNewline() . 'Calculated: ' . strtoupper($hash));
    return (strtoupper($hash) == strtoupper($hash2) ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $arr = array(
      'trans-id' => (isset($_POST['trnId']) ? $_POST['trnId'] : ''),
      'amount' => (isset($_POST['trnAmount']) ? $this->num_format($_POST['trnAmount']) : ''),
      'refund-amount' => '',
      'currency' => $this->settings->currency,
      'code-id' => (isset($_POST['ref1']) ? $_POST['ref1'] : ''),
      'pay-status' => (isset($_POST['trnApproved']) ? $_POST['trnApproved'] : '0'),
      'message' => (isset($_POST['avsId']) ? mmGateway::message($_POST['avsId']) : ''),
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
    $total   = $this->saletotal($order);
    $arr     = array(
      'merchant_id' => $params['merchant-id'],
      'responseType' => $this->responseType,
      'trnType' => $this->trnType,
      'trnOrderNumber' => $ID,
      'trnAmount' => $total,
      'hashValue' => sha1('merchant_id=' . $params['merchant-id'] . '&responseType=' . $this->responseType . '&trnType=' . $this->trnType . '&trnOrderNumber=' . $ID . '&trnAmount=' . $total . $params['hash-value']),
      'ordName' => $this->stripchars($order->name),
      'ordAddress1' => $this->stripchars($_POST['address1']),
      'ordAddress2' => $this->stripchars($_POST['address2']),
      'ordCity' => $this->stripchars($_POST['city']),
      'ordProvince' => (in_array($_POST['country'], array(
        184,
        31
      )) ? $this->stripchars($_POST['county']) : '--'),
      'ordCountry' => $country->iso2,
      'ordPostalCode' => $this->stripchars($_POST['postcode']),
      'ordEmailAddress' => $this->stripchars($order->email),
      'errorPage' => $url . 'callback/beanstream.php',
      'approvedPage' => $url . 'callback/lib/beanstream-app.php',
      'declinedPage' => $url . 'callback/lib/beanstream-dec.php',
      'shipEmailAddress' => $this->stripchars($order->email),
      'shipAddress1' => $this->stripchars($_POST['address1']),
      'shipAddress2' => $this->stripchars($_POST['address2']),
      'shipCity' => $this->stripchars($_POST['city']),
      'shipProvince' => (in_array($_POST['country'], array(
        184,
        31
      )) ? $this->stripchars($_POST['county']) : '--'),
      'shipPostalCode' => $this->stripchars($_POST['postcode']),
      'shipCountry' => $country->iso2,
      'trnLanguage' => $params['language'],
      'ref1' => $BUYCODE . '-' . $ID . '-mswmusic',
      'ref2' => sha1('merchant_id=' . $params['merchant-id'] . '&responseType=' . $this->responseType . '&trnType=' . $this->trnType . '&trnOrderNumber=' . $ID . '&trnAmount=' . $total . $params['hash-value'])
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

  // Response messages..
  public function message($code) {
    $arr = array(
      '0' => 'Address Verification not performed for this transaction.',
      '5' => 'Invalid AVS Response.',
      '9' => 'Address Verification Data contains edit error.',
      'A' => 'Street address matches, Postal/ZIP does not match.',
      'B' => 'Street address matches, Postal/ZIP not verified.',
      'C' => 'Street address and Postal/ZIP not verified.',
      'D' => 'Street address and Postal/ZIP match.',
      'E' => 'Transaction ineligible.',
      'G' => 'Non AVS participant. Information not verified.',
      'I' => 'Address information not verified for international transaction.',
      'M' => 'Street address and Postal/ZIP match.',
      'N' => 'Street address and Postal/ZIP do not match.',
      'P' => 'Postal/ZIP matches. Street address not verified.',
      'R' => 'System unavailable or timeout.',
      'S' => 'AVS not supported at this time.',
      'U' => 'Address information is unavailable.',
      'W' => 'Postal/ZIP matches, street address does not match.',
      'X' => 'Street address and Postal/ZIP match.',
      'Y' => 'Street address and Postal/ZIP match.',
      'Z' => 'Postal/ZIP matches, street address does not match.'
    );
    return (isset($arr[$code]) ? $arr[$code] : 'Unknown');
  }

}

?>