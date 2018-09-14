<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params = $this->params();
    return (isset($_POST['vendor']) && $_POST['vendor'] == sha1($params['vendor']) ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $custom = (isset($_POST['custom']) ? (int) $_POST['custom'] : '0');
    $order  = $this->getsale($custom);
    $arr    = array(
      'trans-id' => (isset($_POST['txn_id']) ? $_POST['txn_id'] : ''),
      'amount' => (isset($_POST['amount']) ? $this->num_format($_POST['amount']) : ''),
      'refund-amount' => '',
      'currency' => $this->settings->currency,
      'code-id' => (isset($order->id) ? $order->code.'-'.$order->saleID : ''),
      'pay-status' => (isset($_POST['status']) ? $_POST['status'] : ''),
      'message' => (isset($_POST['message']) ? $_POST['message'] : ''),
      'pending-reason' => (isset($_POST['message']) ? $_POST['message'] : ''),
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
    $name    = $this->firstLastName($order->name);
    $country = $this->country($_POST['country']);
    // Sage pay throws errors is address fields are blank, so lets add something if they are blank..
    if ($_POST['address1'] == '') {
      $_POST['address1'] = 'x';
      $_POST['address2'] = 'x';
      $_POST['city']     = 'x';
      $_POST['county']   = 'xxx';
      $_POST['postcode'] = 'x';
    }
    $encrypt = array(
      'VendorTxCode' => $ID,
      'Amount' => $this->saletotal($order),
      'Currency' => $this->settings->currency,
      'Description' => $this->stripchars($this->lang[2]),
      'SuccessURL' => $url . 'index.php?gw=' . $ID . '-' . $BUYCODE,
      'FailureURL' => $url . 'index.php?msg=2',
      'SendEMail' => '2',
      'CustomerName' => $this->stripchars($order->name),
      'CustomerEMail' => $this->stripchars($order->email),
      'VendorEMail' => $this->settings->email,
      'BillingFirstnames' => $this->stripchars($name['first-name']),
      'BillingSurname' => $this->stripchars($name['last-name']),
      'BillingAddress1' => $this->stripchars($_POST['address1']),
      'BillingAddress2' => $this->stripchars($_POST['address2']),
      'BillingCity' => $this->stripchars($_POST['city']),
      'BillingState' => $this->stripchars($_POST['county']),
      'BillingPostCode' => $this->stripchars($_POST['postcode']),
      'BillingCountry' => $country->iso2,
      'DeliveryFirstnames' => $this->stripchars($name['first-name']),
      'DeliverySurname' => $this->stripchars($name['last-name']),
      'DeliveryAddress1' => $this->stripchars($_POST['address1']),
      'DeliveryAddress2' => $this->stripchars($_POST['address2']),
      'DeliveryCity' => $this->stripchars($_POST['city']),
      'DeliveryState' => $this->stripchars($_POST['county']),
      'DeliveryPostCode' => $this->stripchars($_POST['postcode']),
      'DeliveryCountry' => $country->iso2,
      'AllowGiftAid' => '0',
      'ApplyAVSCV2' => '0',
      'Apply3DSecure' => '0'
    );
    // If none US addresses, remove state fields..
    // Sagepay allows this. If we include it, payment page fails..
    if ($country->iso2 != 'US') {
      unset($encrypt['BillingState']);
    }
    if ($country->iso2 != 'US') {
      unset($encrypt['DeliveryState']);
    }
    $this->log($ID, 'Form data for SagePay: ' . print_r($encrypt, true));
    $data = '';
    foreach ($encrypt AS $k => $v) {
      $data .= ($k != 'VendorTxCode' ? '&' : '') . $k . '=' . $v;
    }
    $arr = array(
      'VPSProtocol' => ($this->settings->paymode == 'test' ? '2.23' : '3.0'),
      'TxType' => 'PAYMENT',
      'Vendor' => $params['vendor'],
      'Crypt' => $this->encoder($data, $params)
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

  // Pings handler on successful return..
  // This will only occur once..
  public function pingcallback($order) {
    $params   = $this->params();
    $log      = array();
    $incoming = $this->decoder($_GET['crypt'], $params);
    // Log..
    $this->log($this->order['id'], 'Decoding payment data: ' . $incoming);
    // Build data to post..
    $post = explode('&', $incoming);
    // Log incoming vars..
    foreach ($post AS $k) {
      $split          = explode('=', $k);
      if (isset($split[1])) {
        $log[$split[0]] = $split[1];
      }
    }
    $this->log($this->order['id'], 'Post data sent from SagePay: ' . print_r($log, true));
    $build  = 'txn-id=' . (isset($log['TxAuthNo']) ? $log['TxAuthNo'] : (isset($log['VPSTxId']) ? $log['VPSTxId'] : ''));
    $build .= '&status=' . (isset($log['Status']) ? $log['Status'] : 'INV');
    $build .= '&vendor=' . sha1($params['vendor']);
    $build .= '&amount=' . (isset($log['Amount']) ? $log['Amount'] : '0.00');
    $build .= '&custom=' . (isset($log['VendorTxCode']) ? $log['VendorTxCode'] : '');
    $build .= '&message=' . (isset($log['StatusDetail']) ? $log['StatusDetail'] : '');
    // Ping handler..
    if (isset($log['Status']) && strtoupper($log['Status']) == 'OK') {
      // Log..
      $this->log($this->order['id'], 'Status OK from Sagepay...sending ping to ' . SCRIPT_NAME . ' handler with the following: ' . $build);
      // Ping..
      $r = $this->transmit(BASE_HREF . 'callback/sagepay.php', $build);
    } else {
      // Log..
      $this->log($this->order['id'], 'Status NOT OK from SagePay..stop gateway processing. Check SagePay account for more details. Data: ' . $build);
    }
  }

  //----------------------------------------------
  // ENCRYPTION FUNCTIONS
  // AES encryption or XOR encryption
  // Taken from Sage Pay Dev Kit
  //----------------------------------------------

  public function encoder($data, $params) {
    switch ($params['encryption']) {
      // XOR encryption..
      case 'xor':
        return $this->base64Encode($this->simpleXor($data, $params['xor-password']));
        break;
      // AES - default
      default:
        $strIn = $this->addPKCS5Padding($data);
        $crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $params['xor-password'], $strIn, MCRYPT_MODE_CBC, $params['xor-password']);
        return '@' . bin2hex($crypt);
        break;
    }
  }

  public function decoder($data, $params) {
    if (substr($data, 0, 1) == '@') {
      $strIn = substr($data, 1);
      $strIn = pack('H*', $strIn);
      return $this->removePKCS5Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $params['xor-password'], $strIn, MCRYPT_MODE_CBC, $params['xor-password']));
    } else {
      return $this->simpleXor($this->base64Decode($data), $params['xor-password']);
    }
  }

  public function base64Encode($plain) {
    return base64_encode($plain);
  }

  public function base64Decode($scrambled) {
    $scrambled = str_replace(" ", "+", $scrambled);
    return base64_decode($scrambled);
  }

  public function simpleXor($InString, $Key) {
    $KeyList = array();
    $output  = '';
    for ($i = 0; $i < strlen($Key); $i++) {
      $KeyList[$i] = ord(substr($Key, $i, 1));
    }
    for ($i = 0; $i < strlen($InString); $i++) {
      $output .= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
    }
    return $output;
  }

  public function removePKCS5Padding($decrypted) {
    $padChar = ord($decrypted[strlen($decrypted) - 1]);
    return substr($decrypted, 0, -$padChar);
  }

  public function addPKCS5Padding($input) {
    $blocksize = 16;
    $padding   = '';
    $padlength = $blocksize - (strlen($input) % $blocksize);
    for ($i = 1; $i <= $padlength; $i++) {
      $padding .= chr($padlength);
    }
    return $input . $padding;
  }

}

?>