<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params = $this->params();
    // Checksum..
    $sum = $params['encryption-code'] . '|' . $_POST['Merchant'] . '|' . $_POST['Status'] . '|' . $_POST['StatusCode'] . '|' . $_POST['OrderID'] . '|' . $_POST['PaymentID'] . '|' . $_POST['Reference'] . '|' . $_POST['TransactionID'] . '|' . $_POST['Amount'] . '|' . $_POST['Currency'] . '|' . $_POST['Duration'] . '|' . $_POST['ConsumerIPAddress'];
    $this->log($this->order['id'], 'Create callback SHA1 Hash Digest from the following string:' . mswDefineNewline() . $sum);
    $sum = sha1($sum);
    $this->log($this->order['id'], 'Hash check comparison to validate. Must match:' . mswDefineNewline() . 'Hash Sent: ' . strtoupper($_POST['Checksum']) . mswDefineNewline() . 'Calculated: ' . strtoupper($sum));
    return (isset($_POST['Checksum']) && strtoupper($sum) == strtoupper($_POST['Checksum']) ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $arr = array(
      'trans-id' => (isset($_POST['paymentID']) ? $_POST['paymentID'] : ''),
      'amount' => (isset($_POST['Amount']) ? $_POST['Amount'] : ''),
      'refund-amount' => (isset($_POST['Amount']) ? $_POST['Amount'] : ''),
      'currency' => (isset($_POST['Currency']) ? $_POST['Currency'] : ''),
      'code-id' => (isset($_POST['Reference']) ? $_POST['Reference'] : ''),
      'pay-status' => (isset($_POST['Status']) ? $_POST['Status'] : ''),
      'message' => (isset($_POST['StatusCode']) ? mmGateway::codetext($_POST['StatusCode']) : ''),
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
    $arr     = array(
      'IC_PaymentMethod' => 'CREDITCARD',
      'IC_Issuer' => 'VISA',
      'IC_Merchant' => $params['merchant-id'],
      'IC_Amount' => str_replace('.', '', $this->saletotal($order)),
      'IC_Currency' => $this->settings->currency,
      'IC_Language' => $params['language'],
      'IC_Country' => '00',
      'IC_OrderID' => $ID,
      'IC_Reference' => $BUYCODE . '-' . $ID,
      'IC_Description' => $this->stripchars($this->lang[2]),
      'IC_URLCompleted' => $url . 'index.php?gw=' . $ID . '-' . $BUYCODE,
      'IC_URLError' => $url . '?msg=1',
      'IC_ResponseType' => 'REDIRECT'
    );
    // Generate checksum..
    $this->log($ID, 'Create SHA1 Hash Digest from the following string:' . mswDefineNewline() . $params['encryption-code'] . '|' . $params['merchant-id'] . '|' . $arr['IC_Amount'] . '|' . $arr['IC_Currency'] . '|' . $arr['IC_OrderID'] . '|' . $arr['IC_PaymentMethod'] . '|' . $arr['IC_Issuer']);
    $arr['IC_CheckSum'] = sha1($params['encryption-code'] . '|' . $params['merchant-id'] . '|' . $arr['IC_Amount'] . '|' . $arr['IC_Currency'] . '|' . $arr['IC_OrderID'] . '|' . $arr['IC_PaymentMethod'] . '|' . $arr['IC_Issuer']);
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

  // Icepay statuses..
  public function codetext($code) {
    $arr = array(
      'OK' => 'The payment has been completed.',
      'OPEN' => 'The payment is not yet completed (pending). After some time you will receive a Postback Notification which contains the OK or ERR status. The time varies depending on the payment method that was used.',
      'ERR' => 'The payment was not completed successfully or expired. It cannot change into anything else.',
      'REFUND' => 'A payment has been successfully refunded. You will receive a different PaymentID parameter but all the other parameters remain the same.',
      'CBACK' => 'The consumer has filed a chargeback via their issuing bank.',
      'VALIDATE' => 'The payment is awaiting validation by the consumer by means of a validation code returned by ICEPAY. Currently, this status is only used by SMS payments. You can safely ignore postbacks with this status if you have integrated ICEPAY using the Checkout.aspx method.You should ignore all other statuses. If a new status is introduced, you will be notified by your account manager.'
    );
    return (isset($arr[$code]) ? $arr[$code] : 'Unknown');
  }

}

?>