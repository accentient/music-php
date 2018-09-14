<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $postRet = http_build_query($_POST) . '&cmd=_notify-validate';
    $this->log($this->order['id'], 'Sending data back to Paypal to validate: ' . $postRet);
    $r = $this->transmit($this->payserver(), $postRet);
    $this->log($this->order['id'], 'Paypal responded with: ' . $r);
    return (strpos(strtolower($r), 'verified') === true || strpos(strtolower($r), 'verified') > 0 ? 'ok' : 'err');
  }

  // Variables created on callback..
  public function callback() {
    $arr = array(
      'trans-id' => (isset($_POST['txn_id']) ? $_POST['txn_id'] : ''),
      'amount' => (isset($_POST['mc_gross']) ? $this->num_format($_POST['mc_gross']) : ''),
      'refund-amount' => (isset($_POST['mc_gross']) ? $this->num_format($_POST['mc_gross']) : ''),
      'currency' => (isset($_POST['mc_currency']) ? $_POST['mc_currency'] : ''),
      'code-id' => (isset($_POST['custom']) ? $_POST['custom'] : ''),
      'pay-status' => (isset($_POST['payment_status']) ? $_POST['payment_status'] : ''),
      'pending-reason' => (isset($_POST['pending_reason']) ? $this->reasons($_POST['pending_reason']) : ''),
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
      'rm' => '2',
      'cmd' => '_xclick',
      'business' => $params['email'],
      'item_name' => $this->stripchars($this->lang[2]),
      'quantity' => '1',
      'notify_url' => $url . 'callback/paypal.php',
      'cancel_return' => $url . $this->seo->url('cancel', array(), 'yes'),
      'return' => $url . 'index.php?gw=' . $ID . '-' . $BUYCODE,
      'amount' => $this->saletotal($order),
      'currency_code' => $this->settings->currency,
      'no_shipping' => '1',
      'custom' => $BUYCODE . '-' . $ID . '-mswmusic'
    );
    // Are we in test mode?
    if ($this->settings->paymode == 'test') {
      $arr['test_ipn'] = '1';
    }
    // Add locale if set..
    if (isset($params['locale']) && $params['locale']) {
      $arr['lc'] = $params['locale'];
    }
    // Only show page style field if one is set, otherwise paypal throws an error..
    if (isset($params['pagestyle']) && $params['pagestyle']) {
      $arr['page_style'] = $params['pagestyle'];
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

  // Pending reasons..
  public function reasons($code) {
    $arr = array(
      'address' => 'The payment is pending because your customer did not include a confirmed shipping address and your Payment Receiving Preferences is set to allow you to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile.',
      'authorization' => 'You set the payment action to Authorization and have not yet captured funds.',
      'echeck' => 'The payment is pending because it was made by an eCheck that has not yet cleared.',
      'intl' => 'The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview.',
      'multi-currency' => 'You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment.',
      'order' => 'You set the payment action to Order and have not yet captured funds.',
      'paymentreview' => 'The payment is pending while it is being reviewed by PayPal for risk.',
      'unilateral' => 'The payment is pending because it was made to an email address that is not yet registered or confirmed.',
      'upgrade' => 'The payment is pending because it was made via credit card and you must upgrade your account to Business or Premier status in order to receive the funds. upgrade can also mean that you have reached the monthly limit for transactions on your account.',
      'verify' => 'The payment is pending because you are not yet verified. You must verify your account before you can accept this payment.',
      'other' => 'The payment is pending for a reason other than the standard reasons. For more information, contact PayPal Customer Service.'
    );
    return (isset($arr[$code]) ? $arr[$code] : 'N/A');
  }

}

?>