<?php

class salesPublic extends db {

  public $settings;
  public $datetime;
  public $cart;
  public $countries;

  public function clearOldZips($start) {
    $dead = array();
    $dir  = opendir(PATH . 'logs');
    while (false !== ($read = readdir($dir))) {
      if (substr(strtolower($read), -4) == '.zip' && substr(strtolower($read), 0, strlen($start)) == strtolower($start)) {
        $dead[] = PATH . 'logs/' . $read;
      }
    }
    closedir($dir);
    if (!empty($dead)) {
      foreach ($dead AS $df) {
        @unlink($df);
      }
    }
  }

  public function lockSale($ID) {
    db::db_query("UPDATE `" . DB_PREFIX . "sales` SET
    `locked`     = 'yes'
    WHERE `id`   = '{$ID}'
    ");
  }

  public function downloadItem($data) {
    $tracks = array();
    $info   = array();
    if ($data->physical == 'no' && $data->type == 'collection') {
      $Q = db::db_query("SELECT `name`,`coverart`,`coverartother` FROM `" . DB_PREFIX . "collections` WHERE `id` = '{$data->collection}'");
      $C = db::db_object($Q);
      if (isset($C->name)) {
        // Tracks..
        $QTS = db::db_query("SELECT `mp3file` FROM `" . DB_PREFIX . "music` WHERE `collection` = '{$data->collection}' ORDER BY `order`");
        while ($CT = db::db_object($QTS)) {
          $tracks[] = $CT->mp3file;
        }
        $info = array(
          'name' => mswCleanData($C->name),
          'tname' => '',
          'cover' => mswCleanData($C->coverart),
          'covero' => ($C->coverartother ? unserialize($C->coverartother) : array()),
          'mp3' => '',
          'tracks' => $tracks
        );
      }
    } else {
      if ($data->type == 'track') {
        $Q = db::db_query("SELECT `name`,`coverart`,`coverartother` FROM `" . DB_PREFIX . "collections` WHERE `id` = '{$data->collection}'");
        $C = db::db_object($Q);
        if (isset($C->name)) {
          $Q2 = db::db_query("SELECT `title`,`mp3file` FROM `" . DB_PREFIX . "music` WHERE `id` = '{$data->item}'");
          $T  = db::db_object($Q2);
          if (isset($T->mp3file)) {
            $info = array(
              'name' => mswCleanData($C->name),
              'tname' => mswCleanData($T->title),
              'cover' => mswCleanData($C->coverart),
              'covero' => ($C->coverartother ? unserialize($C->coverartother) : array()),
              'mp3' => $T->mp3file,
              'tracks' => array()
            );
          }
        }
      }
    }
    return $info;
  }

  public function getSaleFromToken($ID, $token) {
    $Q = db::db_query("SELECT *,
         `" . DB_PREFIX . "sales`.`id` AS `saleID`,
         `" . DB_PREFIX . "sales`.`ip` AS `saleIP`
         FROM `" . DB_PREFIX . "sales_items`
         LEFT JOIN `" . DB_PREFIX . "sales`
         ON `" . DB_PREFIX . "sales_items`.`sale`     = `" . DB_PREFIX . "sales`.`id`
         WHERE `" . DB_PREFIX . "sales_items`.`token` = '{$token}'
         AND `" . DB_PREFIX . "sales_items`.`id`      = '{$ID}'
         ");
    $S = db::db_object($Q);
    return (isset($S->saleID) ? $S : 'fail');
  }

  public function getSaleItem($ID) {
    $Q = db::db_query("SELECT *,
         `" . DB_PREFIX . "sales`.`id` AS `saleID`,
         `" . DB_PREFIX . "sales`.`ip` AS `saleIP`
         FROM `" . DB_PREFIX . "sales_items`
         LEFT JOIN `" . DB_PREFIX . "sales`
         ON `" . DB_PREFIX . "sales_items`.`sale`        = `" . DB_PREFIX . "sales`.`id`
         WHERE `" . DB_PREFIX . "sales_items`.`id`       = '{$ID}'
         AND `" . DB_PREFIX . "sales_items`.`physical`   = 'no'
         AND `" . DB_PREFIX . "sales`.`enabled`          = 'yes'
         AND `" . DB_PREFIX . "sales`.`locked`           = 'no'
         ");
    $S = db::db_object($Q);
    return (isset($S->saleID) ? $S : 'fail');
  }

  public function getSaleItemClicks($ID, $sale) {
    $ar = array();
    $Q  = db::db_query("SELECT `ip`,`ts`,`country` FROM `" . DB_PREFIX . "sales_click`
          WHERE `sale`   = '{$sale}'
          AND `trackcol` = '{$ID}'
          AND `type`     = 'visitor'
          GROUP BY `ip`
          ORDER BY `id`
          ");
    while ($ITEMS = db::db_object($Q)) {
      $ar[$ITEMS->ip] = array(
        $ITEMS->ts,
        $ITEMS->country
      );
    }
    return $ar;
  }

  public function ipReport($ar) {
    $str = array();
    if (!empty($ar)) {
      foreach ($ar AS $ipK => $ipV) {
        $str[] = '[' . $ipK . ' - ' . $ipV[1] . '] - ' . $this->datetime->dateTimeDisplay($ipV[0], $this->settings->dateformat) . ' / ' . $this->datetime->dateTimeDisplay($ipV[0], $this->settings->timeformat);
      }
    }
    return (!empty($str) ? implode(mswDefineNewline(), $str) : 'N/A');
  }

  public function addClick($ID) {
    db::db_query("UPDATE `" . DB_PREFIX . "sales_items` SET
    `clicks`   = (`clicks`+1)
    WHERE `id` = '{$ID}'
    ");
  }

  public function addToken($ID, $token) {
    db::db_query("UPDATE `" . DB_PREFIX . "sales_items` SET
    `token`    = '{$token}'
    WHERE `id` = '{$ID}'
    ");
  }

  public function statusChange($status, $id, $msg = '') {
    $status = mswSafeString($status,$this);
    $msg    = mswSafeString($msg,$this);
    $msg2   = mswDefineNewline() . mswDefineNewline() . $msg;
    db::db_query("UPDATE `" . DB_PREFIX . "sales` SET
    `enabled`     = 'no',
    `status`      = '{$status}',
    `notes`       = IF(`notes`,CONCAT(`notes`,'{$msg2}'),'{$msg}')
    WHERE `id`    = '{$id}'
    ");
  }

  public function getCol($id) {
    $Q = db::db_query("SELECT `collection` FROM `" . DB_PREFIX . "music` WHERE `id` = '{$id}'");
    $C = db::db_object($Q);
    return (isset($C->collection) ? $C->collection : '0');
  }

  public function addOrder($a, $t, $g) {
    $code = salesPublic::code($a['id'], $a['email']);
    $ip   = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
    $sp   = (isset($_POST['method']) ? (int) $_POST['method'] : '0');
    $gw   = (isset($_POST['payment']) ? (int) $_POST['payment'] : '0');
    $ts   = $this->datetime->utcTime();
    $sa   = mswSafeString(salesPublic::shipAddr(), $this);
    $nt   = mswSafeString($_POST['notes'], $this);
    $cp   = (isset($t['coupon'][0]) ? mswSafeString(serialize($t['coupon']), $this) : '');
    // Sale..
    db::db_query("INSERT INTO `" . DB_PREFIX . "sales` (
    `account`,
    `ip`,
    `iso`,
    `ts`,
    `gateway`,
    `subtotal`,
    `shipping`,
    `tax`,
    `tax2`,
    `taxRate`,
    `taxRate2`,
    `taxCountry`,
    `taxCountry2`,
    `shipID`,
    `shippingAddr`,
    `enabled`,
    `code`,
    `notes`,
    `coupon`
    ) VALUES (
    '{$a['id']}',
    '{$ip}',
    '{$g['iso']}',
    '{$ts}',
    '{$gw}',
    '{$t['sub']}',
    '{$t['ship']}',
    '{$t['tax']}',
    '{$t['tax2']}',
    '{$t['tax-rate']}',
    '{$t['tax-rate2']}',
    '{$t['tax-country']}',
    '{$t['tax-country2']}',
    '{$sp}',
    '{$sa}',
    'no',
    '{$code}',
    '{$nt}',
    '{$cp}'
    )");
    $id = db::db_last_insert_id();
    // Sale items..
    for ($i = 0; $i < count($_SESSION['cartItems']); $i++) {
      if ($_SESSION['cartItems'][$i]['void'] == 'no') {
        $phys  = ($_SESSION['cartItems'][$i]['type'] == 'CD' ? 'yes' : 'no');
        $cost  = ($_SESSION['cartItems'][$i]['discount'] != 'no' ? $_SESSION['cartItems'][$i]['discount'] : $_SESSION['cartItems'][$i]['cost']);
        $item  = $_SESSION['cartItems'][$i]['collection'];
        $type  = (!empty($_SESSION['cartItems'][$i]['tracks']) ? 'track' : 'collection');
        // Is this item for tracks or collection?
        if (!empty($_SESSION['cartItems'][$i]['tracks'])) {
          foreach ($_SESSION['cartItems'][$i]['tracks'] AS $tK => $tV) {
            $colID = salesPublic::getCol($tK);
            db::db_query("INSERT INTO `" . DB_PREFIX . "sales_items` (
            `sale`,
            `item`,
            `collection`,
            `type`,
            `physical`,
            `cost`
            ) VALUES (
            '{$id}',
            '{$tK}',
            '{$colID}',
            '{$type}',
            'no',
            '{$tV}'
            )");
          }
        } else {
          db::db_query("INSERT INTO `" . DB_PREFIX . "sales_items` (
          `sale`,
          `item`,
          `collection`,
          `type`,
          `physical`,
          `cost`
          ) VALUES (
          '{$id}',
          '{$item}',
          '{$item}',
          '{$type}',
          '{$phys}',
          '{$cost}'
          )");
        }
      }
    }
    // Clear cart data..
    //$this->cart->clear();
    return array(
      $id,
      $code
    );
  }

  public function activate($data=array()) {
    $inv   = salesPublic::invoice();
    $trans = mswSafeString($data['trans'],$this);
    $total = mswSafeString($data['total'],$this);
    $id    = (int) $data['sale'];
    db::db_query("UPDATE `" . DB_PREFIX . "sales` SET
    `invoice`     = '{$inv}',
    `transaction` = '{$trans}',
    `paytotal`    = '{$total}',
    `enabled`     = 'yes',
    `status`      = 'Completed',
    `refcode`     = ''
    WHERE `id`    = '{$id}'
    ");
    // Update expiry time on sales items..
    db::db_query("UPDATE `" . DB_PREFIX . "sales_items` SET
    `expiry`     = '" . mswDLExpiryTime($this->settings, $this->datetime) . "'
    WHERE `sale` = '{$id}'
    ");
    // Activate account if it hasn`t already been activated..
    db::db_query("UPDATE `" . DB_PREFIX . "accounts` SET
    `system1` = '',
    `system2` = '',
    `enabled` = 'yes'
    WHERE `id` = '{$data['account']}'
    ");
    return $inv;
  }

  public function invoice($update = true) {
    // Are we starting at specific number..
    $number = ($this->settings->invoice > 0 ? $this->settings->invoice : '1');
    $next   = ($number + 1);
    if ($update) {
      db::db_query("UPDATE `" . DB_PREFIX . "settings` SET `invoice`  = '{$next}'");
    }
    return $number;
  }

  public function shipAddr() {
    $arr = array();
    if ($this->cart->isShipping() == 'yes') {
      foreach (array(
        'address1',
        'address2',
        'city',
        'county',
        'postcode',
        'country'
      ) AS $k) {
        if (isset($_POST[$k]) && $_POST[$k]) {
          switch($k) {
            case 'country':
              if (isset($this->countries[$_POST[$k]])) {
                $arr[] = $this->countries[$_POST[$k]];
              }
              break;
            default:
              $arr[] = $_POST[$k];
              break;
          }
        }
      }
    }
    return (!empty($arr) ? implode(mswDefineNewline(), $arr) : '');
  }

  public function code($uni, $uni2) {
    $k = $uni . date('YmdHis') . $uni2;
    return sha1($k);
  }

}

?>