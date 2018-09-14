<?php

class mmGateway extends db {

  // Validate gateway payment..
  public function validate() {
    // Store gateway params..
    $this->storeparams($this->order['id'], $_POST);
    $params  = $this->params();
    if (isset($_POST['JP_TRANID'],$_POST['JP_PASSWORD'])) {
      $incoming = array(
        'jp_tranid' => (isset($_POST['JP_TRANID']) ? $_POST['JP_TRANID'] : ''),
        'jp_merchant_orderid' => (isset($_POST['JP_MERCHANT_ORDERID']) ? $_POST['JP_MERCHANT_ORDERID'] : '0'),
        'jp_item_name' => (isset($_POST['JP_ITEM_NAME']) ? $_POST['JP_ITEM_NAME'] : ''),
        'jp_amount' => (isset($_POST['JP_AMOUNT']) ? $_POST['JP_AMOUNT'] : ''),
        'jp_currency' => (isset($_POST['JP_CURRENCY']) ? $_POST['JP_CURRENCY'] : ''),
        'jp_timestamp' => (isset($_POST['JP_TIMESTAMP']) ? $_POST['JP_TIMESTAMP'] : ''),
        'jp_password' => (isset($_POST['JP_PASSWORD']) ? $_POST['JP_PASSWORD'] : ''),
        'jp_channel' => (isset($_POST['JP_CHANNEL']) ? $_POST['JP_CHANNEL'] : '')
      );
      if ($incoming['jp_merchant_orderid'] > 0) {
        $str  = $incoming['jp_merchant_orderid'] . $incoming['jp_amount'] . $incoming['jp_currency'] . $params['shared-key'] . $incoming['jp_timestamp'];
        $hash = md5(utf8_encode($str));
        $this->log($incoming['jp_merchant_orderid'], 'Password hash received: ' . $incoming['jp_password'] . mswDefineNewline() . 'Calculated: ' . $hash);
        if ($hash == $incoming['jp_password']) {
          $this->log($this->order['id'], 'Password match successful, order accepted.');
          return 'ok';
        } else {
          $this->log($this->order['id'], 'Passwords do not match.');
          return 'err';
        }
      } else {
        $this->log($incoming['jp_merchant_orderid'], 'Invalid order ID');
        return 'err';
      }
    } else {
      $this->log($this->order['id'], 'No password hash was received from JamboPay');
      return 'err';
    }
  }

  // Variables created on callback..
  public function callback() {
    $order = $this->getsale((isset($_POST['JP_MERCHANT_ORDERID']) ? (int) $_POST['JP_MERCHANT_ORDERID'] : '0'));
    $arr   = array(
      'trans-id' => (isset($_POST['JP_TRANID']) ? $_POST['JP_TRANID'] : ''),
      'amount' => (isset($_POST['JP_AMOUNT']) ? $this->num_format($_POST['JP_AMOUNT']) : ''),
      'currency' => (isset($_POST['JP_CURRENCY']) ? $_POST['JP_CURRENCY'] : ''),
      'code-id' => (isset($order->code) ? $order->code . '-' . $order->saleID : '0-0'),
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
      'jp_item_type' => 'cart',
      'jp_item_name' => $this->stripchars($this->lang[2]),
      'order_id' => $ID,
      'jp_business' => $params['business-address'],
      'jp_amount_1' => $this->saletotal($order),
      'jp_amount_2' => '0',
      'jp_amount_5' => $this->saletotal($order),
      'jp_payee' => $order->email,
      'jp_shipping' => $this->stripchars($this->settings->website),
      'jp_rurl' => $url . 'index.php?gw=' . $ID . '-' . $BUYCODE,
      'jp_furl' => $url . 'index.php?msg=2',
      'jp_curl' => $url . $this->seo->url('cancel', array(), 'yes'),
    );
    return $arr;
  }

  // Pings handler on successful return..
  // This will only occur once..
  public function pingcallback($order) {
    $params   = $this->params();
    $this->log($this->order['id'], 'Post data sent from JamboPay: ' . print_r($_POST, true));
    $this->log($this->order['id'], 'Sending ping to ' . SCRIPT_NAME . ' handler with the following: ' . http_build_query($_POST));
    // Ping..
    $r = $this->transmit(BASE_HREF . 'callback/jambo.php', http_build_query($_POST));
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