<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params    = $this->params();
    $hashcode  = 'PreSharedKey=' . $params['pre-share-key'];
    $hashcode .= '&MerchantID=' . (isset($_POST['MerchantID']) ? $_POST['MerchantID'] : '');
    $hashcode .= '&Password=' . $params['password'];
    $hashcode .= '&StatusCode=' . (isset($_POST['StatusCode']) ? $_POST['StatusCode'] : '');
    $hashcode .= '&Message=' . (isset($_POST['Message']) ? $_POST['Message'] : '');
    $hashcode .= '&PreviousStatusCode=' . (isset($_POST['PreviousStatusCode']) ? $_POST['PreviousStatusCode'] : '');
    $hashcode .= '&PreviousMessage=' . (isset($_POST['PreviousMessage']) ? $_POST['PreviousMessage'] : '');
    $hashcode .= '&CrossReference=' . (isset($_POST['CrossReference']) ? $_POST['CrossReference'] : '');
    $hashcode .= '&AddressNumericCheckResult=' . (isset($_POST['AddressNumericCheckResult']) ? $_POST['AddressNumericCheckResult'] : '');
    $hashcode .= '&PostCodeCheckResult=' . (isset($_POST['PostCodeCheckResult']) ? $_POST['PostCodeCheckResult'] : '');
    $hashcode .= '&CV2CheckResult=' . (isset($_POST['CV2CheckResult']) ? $_POST['CV2CheckResult'] : '');
    $hashcode .= '&ThreeDSecureAuthenticationCheckResult=' . (isset($_POST['ThreeDSecureAuthenticationCheckResult']) ? $_POST['ThreeDSecureAuthenticationCheckResult'] : '');
    $hashcode .= '&CardType=' . (isset($_POST['CardType']) ? $_POST['CardType'] : '');
    $hashcode .= '&CardClass=' . (isset($_POST['CardClass']) ? $_POST['CardClass'] : '');
    $hashcode .= '&CardIssuer=' . (isset($_POST['CardIssuer']) ? $_POST['CardIssuer'] : '');
    $hashcode .= '&CardIssuerCountryCode=' . (isset($_POST['CardIssuerCountryCode']) ? $_POST['CardIssuerCountryCode'] : '');
    $hashcode .= '&Amount=' . (isset($_POST['Amount']) ? $_POST['Amount'] : '');
    $hashcode .= '&CurrencyCode=' . $this->iso4217[$this->settings->currency];
    $hashcode .= '&OrderID=' . (isset($_POST['OrderID']) ? $_POST['OrderID'] : '');
    $hashcode .= '&TransactionType=' . (isset($_POST['TransactionType']) ? $_POST['TransactionType'] : '');
    $hashcode .= '&TransactionDateTime=' . (isset($_POST['TransactionDateTime']) ? $_POST['TransactionDateTime'] : '');
    $hashcode .= '&OrderDescription=' . (isset($_POST['OrderDescription']) ? $_POST['OrderDescription'] : '');
    $hashcode .= '&CustomerName=' . (isset($_POST['CustomerName']) ? $_POST['CustomerName'] : '');
    $hashcode .= '&Address1=' . (isset($_POST['Address1']) ? $_POST['Address1'] : '');
    $hashcode .= '&Address2=' . (isset($_POST['Address2']) ? $_POST['Address2'] : '');
    $hashcode .= '&Address3=' . (isset($_POST['Address3']) ? $_POST['Address3'] : '');
    $hashcode .= '&Address4=' . (isset($_POST['Address4']) ? $_POST['Address4'] : '');
    $hashcode .= '&City=' . (isset($_POST['City']) ? $_POST['City'] : '');
    $hashcode .= '&State=' . (isset($_POST['State']) ? $_POST['State'] : '');
    $hashcode .= '&PostCode=' . (isset($_POST['PostCode']) ? $_POST['PostCode'] : '');
    $hashcode .= '&CountryCode=' . (isset($_POST['CountryCode']) ? $_POST['CountryCode'] : '');
    $hashcode .= '&EmailAddress=' . (isset($_POST['EmailAddress']) ? $_POST['EmailAddress'] : '');
    $hashcode .= '&PhoneNumber=';
    $this->log($this->order['id'], 'Create callback SHA1 Hash Digest from the following string:' . mswDefineNewline() . $hashcode);
    $hashcode = sha1($hashcode);
    $this->log($this->order['id'], 'Hash check comparison to validate. Must match:' . mswDefineNewline() . 'Digest: ' . strtoupper($_POST['HashDigest']) . mswDefineNewline() . 'Hash: ' . strtoupper($hashcode));
    return (isset($_POST['HashDigest']) && strtoupper($hashcode) == strtoupper($_POST['HashDigest']) ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $arr = array(
      'trans-id' => (isset($_POST['OrderID']) ? substr($_POST['OrderID'], strpos($_POST['OrderID'], '-') + 1) : ''),
      'amount' => (isset($_POST['Amount']) ? $_POST['Amount'] : ''),
      'refund-amount' => '',
      'currency' => $this->iso4217[$this->settings->currency],
      'code-id' => (isset($_POST['OrderID']) ? $_POST['OrderID'] : ''),
      'pay-status' => (isset($_POST['StatusCode']) ? $_POST['StatusCode'] : ''),
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
      'PreSharedKey' => $params['pre-share-key'],
      'MerchantID' => $params['merchant-id'],
      'Password' => $params['password'],
      'Amount' => str_replace('.', '', $this->saletotal($order)),
      'CurrencyCode' => $this->iso4217[$this->settings->currency],
      'EchoAVSCheckResult' => 'false',
      'EchoCV2CheckResult' => 'false',
      'EchoThreeDSecureAuthenticationCheckResult' => 'false',
      'EchoCardType' => 'false',
      'OrderID' => $BUYCODE . '-' . $ID,
      'TransactionType' => 'SALE',
      'TransactionDateTime' => date('Y-m-d H:i:s P'),
      'CallbackURL' => $url . 'index.php?gw=' . $ID . '-' . $BUYCODE,
      'OrderDescription' => $this->stripchars($this->lang[2]),
      'CustomerName' => $this->stripchars($order->name),
      'Address1' => (isset($_POST['address1']) ? $this->stripchars($_POST['address1']) : ''),
      'Address2' => (isset($_POST['address2']) ? $this->stripchars($_POST['address2']) : ''),
      'Address3' => '',
      'Address4' => '',
      'City' => (isset($_POST['city']) ? $this->stripchars($_POST['city']) : ''),
      'State' => (isset($_POST['county']) ? $this->stripchars($_POST['county']) : ''),
      'PostCode' => (isset($_POST['postcode']) ? $this->stripchars($_POST['postcode']) : ''),
      'CountryCode' => $this->iso4217[$this->settings->currency],
      'EmailAddress' => $this->stripchars($order->email),
      'PhoneNumber' => '',
      'EmailAddressEditable' => 'false',
      'PhoneNumberEditable' => 'false',
      'CV2Mandatory' => 'true',
      'Address1Mandatory' => 'true',
      'CityMandatory' => 'true',
      'PostCodeMandatory' => 'true',
      'StateMandatory' => 'true',
      'CountryMandatory' => 'true',
      'ResultDeliveryMethod' => 'SERVER',
      'ServerResultURL' => $url . 'callback/iridium.php',
      'PaymentFormDisplaysResult' => 'false'
    );
    // Calculate hash..
    $hash    = '';
    foreach ($arr AS $fK => $fV) {
      $hash .= ($fK != 'PreSharedKey' ? '&' : '') . $fK . '=' . $fV;
    }
    $this->log($ID, 'Create SHA1 Hash Digest from the following string:' . mswDefineNewline() . $hash);
    $arr['HashDigest'] = sha1($hash);
    // Remove key and password from fields..
    unset($arr['PreSharedKey'], $arr['Password']);
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