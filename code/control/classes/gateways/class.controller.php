<?php

class mmGatewayController extends mmGateway {

  public function mmGatewayController($info = array()) {
    $this->gwID     = $info['gwID'];
    $this->gwname   = $info['gwname'];
    $this->server   = $info['server'];
    $this->webpage  = $info['webpage'];
    $this->sandbox  = $info['sandbox'];
    $this->settings = $info['settings'];
    $this->account  = $info['account'];
    $this->lang     = $info['lang'];
    $this->iso4217  = (isset($info['iso4217']) ? $info['iso4217'] : array());
    $this->order    = array(
      'id' => $info['order']['id'],
      'code' => $info['order']['code'],
      'rcode' => (isset($info['order']['rcode']) ? $info['order']['rcode'] : '')
    );
    $this->seo      = $info['seo'];
  }

  public function payserver($type = '') {
    // Specific call..
    switch ($type) {
      case 'live':
        return $this->server;
        break;
      case 'sandbox':
        return $this->sandbox;
        break;
    }
    return ($this->settings->paymode == 'live' ? $this->server : $this->sandbox);
  }

  public function params() {
    $p = array();
    $q = db::db_query("SELECT * FROM `" . DB_PREFIX . "gateways_params`
         WHERE `gateway` = '{$this->gwID}'
         ORDER BY `id`
	   ");
    while ($PR = db::db_object($q)) {
      $p[$PR->param] = mswCleanData($PR->value);
    }
    return new ArrayObject($p);
  }

  public function log($id = 0, $debug = '') {
    if ($this->settings->responselog == 'yes' && is_dir(PATH . GW_LOG_FOLDER_NAME) && is_writeable(PATH . GW_LOG_FOLDER_NAME)) {
      $message = strtoupper(SCRIPT_NAME) . ' LOG @ ' . date("j F Y H:i:s") . mswDefineNewline();
      $message .= 'Database ID: ' . $id . mswDefineNewline();
      $message .= 'Action/Info: ' . $debug . mswDefineNewline();
      $message .= mswDefineNewline() . '= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =' . mswDefineNewline() . mswDefineNewline();
      // Attempt to create log directory if it doesn`t exist..
      if (!is_dir(PATH . GW_LOG_FOLDER_NAME)) {
        $oldumask = @umask(0);
        @mkdir(PATH . GW_LOG_FOLDER_NAME, 0777);
        @umask($oldumask);
      }
      // If it exists, write to it..
      if (is_dir(PATH . GW_LOG_FOLDER_NAME)) {
        $gate = preg_replace('/[^\w-]/', '', $this->gwname);
        $gate = str_replace(' ', '-', strtolower($gate));
        @file_put_contents(PATH . GW_LOG_FOLDER_NAME . '/' . $gate . '-' . $id . '.log', $message, FILE_APPEND);
      }
    }
  }

  // Clean post string and strip problematic characters..
  public function stripchars($string) {
    $s = array(
      '#',
      '\\',
      '>',
      '<',
      '"',
      '[',
      ']',
      '|'
    );
    return str_replace($s, '', $string);
  }

  // Transmits data back to gateway via curl..
  public function transmit($url, $fields = '', $gw = '', $return = true, $header = true) {
    switch ($gw) {
      case 'eway':
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        break;
      default:
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; www.' . SCRIPT_URL . '; ' . SCRIPT_NAME . ' Handler)');
        break;
    }
    curl_setopt($ch, CURLOPT_HEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, $return);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
  }

  public function getsale($id = 0, $code = '', $refcode = '') {
    $q    = db::db_query("SELECT *,
            (SELECT count(*) FROM `" . DB_PREFIX . "sales_items` WHERE `sale` = `" . DB_PREFIX . "sales`.`id` AND `type` = 'track') AS `trackCount`,
            (SELECT count(*) FROM `" . DB_PREFIX . "sales_items` WHERE `sale` = `" . DB_PREFIX . "sales`.`id` AND `type` = 'collection') AS `collectionCount`,
            (SELECT ROUND(SUM(`cost`),2) FROM `" . DB_PREFIX . "sales_items` WHERE `sale` = `" . DB_PREFIX . "sales`.`id`) AS `saleTotal`,
            `" . DB_PREFIX . "sales`.`id` AS `saleID`,
            `" . DB_PREFIX . "sales`.`shipping` AS `saleShipping`,
            `" . DB_PREFIX . "sales`.`enabled` AS `saleEnabled`
            FROM `" . DB_PREFIX . "sales`
            LEFT JOIN `" . DB_PREFIX . "accounts`
            ON  `" . DB_PREFIX . "accounts`.`id` = `" . DB_PREFIX . "sales`.`account`
            " . ($id > 0 ? 'WHERE `' . DB_PREFIX . 'sales`.`id` = \'' . $id . '\'' : '') . "
            " . ($code ? 'AND `code` = \'' . $code . '\'' : '') . "
            " . ($refcode ? 'WHERE `' . DB_PREFIX . 'sales`.`refcode` = \'' . $refcode . '\'' : ''));
    $SALE = db::db_object($q);
    return (isset($SALE->saleID) ? $SALE : '');
  }

  public function firstLastName($input) {
    $string = explode(' ', $input);
    $other  = array();
    for ($i = 0; $i < count($string); $i++) {
      if ($i > 0) {
        $other[] = $string[$i];
      }
    }
    return array(
      'first-name' => $string[0],
      'last-name' => (!empty($other) ? implode(' ', $other) : '')
    );
  }

  public function gateAddr($country) {
    $a   = array();
    $a[] = mswCleanData($_POST['address1']);
    if ($_POST['address2']) {
      $a[] = mswCleanData($_POST['address2']);
    }
    $a[] = mswCleanData($_POST['city']);
    $a[] = mswCleanData($_POST['county']);
    $c   = mmGatewayController::country($country);
    $a[] = mswCleanData($c->name);
    return implode(mswDefineNewline(), $a);
  }

  public function country($id) {
    $id = (int) $id;
    $Q  = db::db_query("SELECT * FROM `" . DB_PREFIX . "countries` WHERE `id` = '{$id}'");
    return db::db_object($Q);
  }

  public function saletotal($sale) {
    // Was there a discount?
    if ($sale->coupon) {
      $cp = mswCleanData(unserialize($sale->coupon));
      if (isset($cp[0], $cp[1]) && $cp[1] > 0) {
        $discount = $cp[1];
      }
      $tot = ($sale->saleTotal > 0 ? mswFormatPrice($sale->saleTotal - $discount) : '0.00');
    } else {
      $tot = ($sale->saleTotal > 0 ? $sale->saleTotal : '0.00');
    }
    $tots  = ($sale->saleShipping > 0 ? $sale->saleShipping : '0.00');
    $totv  = ($sale->tax > 0 ? $sale->tax : '0.00');
    $totv2 = ($sale->tax2 > 0 ? $sale->tax2 : '0.00');
    $taxT  = mswFormatPrice($totv + $totv2);
    return $this->num_format(($tot + $tots + $taxT));
  }

  public function num_format($num) {
    return @number_format($num, 2, '.', '');
  }

  public function storeref($ref, $id) {
    db::db_query("UPDATE `" . DB_PREFIX . "sales` SET
    `refcode`    = '{$ref}'
    WHERE `id`   = '{$id}'
    ");
  }

  public function sysvalue($val, $id, $ref = '1') {
    $field = 'sys'.$ref;
    db::db_query("UPDATE `" . DB_PREFIX . "sales` SET
    `".$field."` = '".mswSafeString($val, $this)."'
    WHERE `id`   = '{$id}'
    ");
  }

  // Log gateway parameters..
  public function storeparams($id, $arr) {
    $p = array();
    if (!empty($arr)) {
      foreach ($arr AS $k => $v) {
        if (is_array($v)) {
          foreach ($v AS $k2 => $v2) {
            $p[] = urldecode($k2) . '=>' . urldecode($v2);
          }
        } else {
          $p[] = urldecode($k) . '=>' . urldecode($v);
        }
      }
      if (!empty($p)) {
        db::db_query("UPDATE `" . DB_PREFIX . "sales` SET
        `gateparams`  = '" . mswSafeString(implode('<-->', $p), $this) . "'
        WHERE `id`    = '{$id}'
        AND (`gateparams` is null OR `gateparams` = '')
      ");
      }
    }
  }

}


?>