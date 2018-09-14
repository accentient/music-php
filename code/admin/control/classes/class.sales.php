<?php

class sales extends db {

  public $settings;
  public $datetime;
  public $dl;

  public function exportRevenue($head,$d) {
    $file        = PATH . 'backup/revenue.csv';
    $bld         = array();
    $del         = ',';
    $currentYear = date('Y');
    $SQL         = 'WHERE `enabled` = \'yes\'';
    if (isset($_POST['q']) && strlen($_POST['q'])==4) {
      $filterYear  = (int) $_POST['q'];
      if (@checkdate(12, 31, $filterYear)) {
        $currentYear = $filterYear;
      }
      if (isset($_POST['country']) && (int) $_POST['country'] > 0) {
        if (isset($_POST['pref']) && in_array($_POST['pref'],array('tangible','digital'))) {
          switch($_POST['pref']) {
            case 'tangible':
              $SQL .= ' AND `taxCountry` = \''.(int) $_POST['country'].'\'';
              break;
            case 'digital':
              $SQL .= ' AND `taxCountry2` = \''.(int) $_POST['country'].'\'';
              break;
          }
        }
      }
    }
    $SQL .= ' AND YEAR(FROM_UNIXTIME(`ts`)) = \''.$currentYear.'\'';
    foreach (range(0,11) AS $months) {
      $thisMonth = ($months + 1);
      $Q  = db::db_query("SELECT
            ROUND(SUM(`paytotal`), 2) AS `pTotal`,
            ROUND(SUM(`subtotal`), 2) AS `sTotal`,
            ROUND(SUM(`tax`), 2) AS `tTotal`,
            ROUND(SUM(`tax2`), 2) AS `tTotal2`,
            ROUND(SUM(`shipping`), 2) AS `shTotal`
            FROM `".DB_PREFIX."sales`
            $SQL
            AND MONTH(FROM_UNIXTIME(`ts`)) = '{$thisMonth}'
      ");
      $RV    = db::db_object($Q);
      $csv   = '';
      $csv  .= $d[1][$months].' '.$currentYear . $del;
      $csv  .= ($RV->sTotal > 0 ? $RV->sTotal : '0.00') . $del;
      $csv  .= ($RV->shTotal > 0 ? $RV->shTotal : '0.00') . $del;
      $csv  .= ($RV->tTotal > 0 ? $RV->tTotal : '0.00') . $del;
      $csv  .= ($RV->tTotal2 > 0 ? $RV->tTotal2 : '0.00') . $del;
      $csv  .= ($RV->pTotal > 0 ? $RV->pTotal  : '0.00');
      $bld[] = $csv;
    }
    if (!empty($bld)) {
      // Save file to server and download..
      $this->dl->write($file, $head . mswDefineNewline() . implode(mswDefineNewline(), $bld));
      if (file_exists($file)) {
        $this->dl->dl($file, 'text/csv');
      }
    }
    header("Location: index.php?p=revenue");
    exit;
  }

  public function updateLockReason($id) {
    // Update sale..
    db::db_query("UPDATE `" . DB_PREFIX . "sales` SET
    `lockreason` = '" . mswSafeString($_POST['reason'], $this) . "'
    WHERE `id`   = '{$id}'
    ");
  }

  public function getStatuses() {
    $st = array();
    $q  = db::db_query("SELECT `status` FROM `" . DB_PREFIX . "sales`
          WHERE `enabled`  = 'yes'
          GROUP BY `status`
          ORDER BY `status`
          ");
    while ($S = db::db_object($q)) {
      $st[] = mswSafeDisplay($S->status);
    }
    return $st;
  }

  public function updateLock($id) {
    // Update sale..
    db::db_query("UPDATE `" . DB_PREFIX . "sales` SET
    `locked`   = '" . ($_GET['st'] == 'lock' ? 'yes' : 'no') . "'
    WHERE `id` = '{$id}'
    ");
  }

  public function resetDownloads($id, $lang, $lang2) {
    // Update downloads..
    db::db_query("UPDATE `" . DB_PREFIX . "sales_items` SET
    `expiry`    = '" . mswDLExpiryTime($this->settings, $this->datetime) . "',
    `clicks`    = '0',
    `token`     = ''
    WHERE `id` IN(" . implode(',', $_POST['rem']) . ")
    ");
    // Update sale..
    db::db_query("UPDATE `" . DB_PREFIX . "sales` SET
    `locked`   = 'no',
    `enabled`  = 'yes'
    WHERE `id` = '{$id}'
    ");
    // Write history for each item reset..
    include(REL_PATH . 'control/classes/class.ip.php');
    $IPGEO           = new geoIP();
    $IPGEO->settings = $this->settings;
    $lookup          = $IPGEO->lookup($_SERVER['REMOTE_ADDR'],$lang2);
    foreach ($_POST['rem'] AS $rID) {
      mswHistoryLog(array(
        'sale' => $id,
        'trackcol' => $rID,
        'action' => $lang[58],
        'type' => 'admin',
        'iso' => strtolower($lookup['iso']),
        'country' => $lookup['country']
      ), $this);
    }
  }

  public function getCol($id) {
    $Q = db::db_query("SELECT `collection` FROM `" . DB_PREFIX . "music` WHERE `id` = '{$id}'");
    $C = db::db_object($Q);
    return (isset($C->collection) ? $C->collection : '0');
  }

  public function addEditSale($ac_obj, $nextInvoiceNo) {
    // Filter post data for insert..
    $_POST       = mswSafeImport($_POST, $this);
    // Pre-Checks..
    $_POST['ts'] = ($_POST['ts'] ? $this->datetime->dateToTS($_POST['ts']) : '0');
    $pass        = '';
    $account     = 0;
    $tax         = (isset($_POST['taxRate']) ? (int) $_POST['taxRate'] : '0');
    $tax2        = (isset($_POST['taxRate2']) ? (int) $_POST['taxRate2'] : '0');
    $taxC        = (isset($_POST['taxCountry']) ? (int) $_POST['taxCountry'] : '0');
    $taxC2       = (isset($_POST['taxCountry2']) ? (int) $_POST['taxCountry2'] : '0');
    $invoice     = ($_POST['invoice'] > 0 ? (int) ltrim($_POST['invoice'], '0') : $nextInvoiceNo);
    // Check a sale doesn`t already exist with this number?
    // If it does, we take the next value always..
    $QCI = db::db_query("SELECT `id` FROM `" . DB_PREFIX . "sales` WHERE `invoice` = '{$invoice}' LIMIT 1");
    $SCI = db::db_object($QCI);
    if (isset($SCI->id)) {
      $invoice = $nextInvoiceNo;
    }
    // Edit or add..
    if (isset($_POST['edit'])) {
      $ID = (int) $_POST['edit'];
      $Q  = db::db_query("UPDATE `" . DB_PREFIX . "sales` SET
            `invoice`      = '{$invoice}',
            `ip`           = '{$_POST['ip']}',
            `ts`           = '{$_POST['ts']}',
            `gateway`      = '{$_POST['gateway']}',
            `transaction`  = '{$_POST['transaction']}',
            `status`       = '{$_POST['status']}',
            `notes`        = '{$_POST['notes']}',
            `taxRate`      = '{$tax}',
            `taxRate2`     = '{$tax2}',
            `taxCountry`   = '{$taxC}',
            `taxCountry2`  = '{$taxC2}'
            WHERE `id`     = '{$ID}'
            ");
      $ID = $_POST['edit'];
    } else {
      // Does account exist?
      $QA = db::db_query("SELECT `id` FROM `" . DB_PREFIX . "accounts` WHERE `email` = '{$_POST['email']}'");
      $A  = db::db_object($QA);
      if (isset($A->id)) {
        $account = $A->id;
      } else {
        $pass    = $ac_obj->password();
        $enc     = sha1(SECRET_KEY . $pass);
        $Q       = db::db_query("INSERT INTO `" . DB_PREFIX . "accounts` (
                   `name`,
                   `email`,
                   `pass`,
                   `ip`,
                   `ts`,
                   `enabled`,
                   `timezone`,
                   `shippingAddr`
                   ) VALUES (
                   '{$_POST['name']}',
                   '{$_POST['email']}',
                   '{$enc}',
                   '{$_POST['ip']}',
                   '{$_POST['ts']}',
                   'yes',
                   '{$this->settings->timezone}',
                   '{$_POST['shippingAddr']}'
                   )");
        $account = db::db_last_insert_id();
      }
      $Q  = db::db_query("INSERT INTO `" . DB_PREFIX . "sales` (
            `invoice`,
            `account`,
            `ip`,
            `ts`,
            `gateway`,
            `transaction`,
            `enabled`,
            `status`,
            `notes`,
            `taxRate`,
            `taxRate2`,
            `taxCountry`,
            `taxCountry2`,
            `shippingAddr`
            ) VALUES (
            '{$invoice}',
            '{$account}',
            '{$_POST['ip']}',
            '{$_POST['ts']}',
            '{$_POST['gateway']}',
            '{$_POST['transaction']}',
            'yes',
            '{$_POST['status']}',
            '{$_POST['notes']}',
            '{$tax}',
            '{$tax2}',
            '{$taxC}',
            '{$taxC2}',
            '{$_POST['shippingAddr']}'
            )");
      $ID = db::db_last_insert_id();
    }
    // Remove downloads from sale..
    if (!empty($_POST['rem'])) {
      db::db_query("DELETE FROM `" . DB_PREFIX . "sales_items` WHERE `id` IN(" . implode(',', $_POST['rem']) . ")");
      mswTableTruncationRoutine(array(
        'sales_items'
      ), $this);
    }
    // Remove cds from sale..
    if (!empty($_POST['remcd'])) {
      db::db_query("DELETE FROM `" . DB_PREFIX . "sales_items` WHERE `id` IN(" . implode(',', $_POST['remcd']) . ")");
      mswTableTruncationRoutine(array(
        'sales_items'
      ), $this);
    }
    // Clipboard..
    $clips = array();
    $pcn   = 0;
    if (!empty($_POST['include'])) {
      foreach ($_POST['include'] AS $newSaleItem) {
        $chop = explode('-', $newSaleItem);
        if ($chop[0] == 'cd') {
          $phys = 'yes';
          ++$pcn;
        } else {
          $phys = 'no';
        }
        if (isset($_POST['cbcheck_' . $chop[1]])) {
          $cost = '0.00';
        } else {
          $cost = $chop[3];
        }
        $colID = ($chop[2] == 'collection' ? $chop[1] : sales::getCol($chop[1]));
        db::db_query("INSERT INTO `" . DB_PREFIX . "sales_items` (
        `sale`,
        `item`,
        `collection`,
        `type`,
        `physical`,
        `expiry`,
        `cost`
        ) VALUES (
        '{$ID}',
        '{$chop[1]}',
        '{$colID}',
        '{$chop[2]}',
        '{$phys}',
        '" . mswDLExpiryTime($this->settings, $this->datetime) . "',
        '{$cost}'
        )");
        // Clear clipboard..
        if (isset($_POST['clear'])) {
          $clips[] = $chop[4];
        }
      }
      // Clear clipboard items..
      if (isset($_POST['clear']) && $_POST['clear'] == 'yes' && !empty($clips)) {
        db::db_query("DELETE FROM `" . DB_PREFIX . "sales_clipboard` WHERE `id` IN(" . implode(',', $clips) . ")");
        mswTableTruncationRoutine(array(
          'sales_clipboard'
        ), $this);
      }
    }
    // Update existing if any prices were changed..
    if (!empty($_POST['saleItemID'])) {
      foreach ($_POST['saleItemID'] AS $siID) {
        $icost = mswFormatPrice($_POST['price'][$siID]);
        db::db_query("UPDATE `" . DB_PREFIX . "sales_items` SET
        `cost`     = '{$icost}'
        WHERE `id` = '{$siID}'
        ");
      }
    }
    // Update sub total
    $QS           = db::db_query("SELECT ROUND(SUM(`cost`),2) AS `subTotal` FROM `" . DB_PREFIX . "sales_items` WHERE `sale` = '{$ID}'");
    $SUB          = db::db_object($QS);
    $saleSubTotal = (isset($SUB->subTotal) ? $SUB->subTotal : '0.00');
    db::db_query("UPDATE `" . DB_PREFIX . "sales` SET `subtotal` = '{$saleSubTotal}' WHERE `id` = '{$ID}'");
    // Update invoice number..
    if (!isset($_POST['edit'])) {
      $next = ($invoice + 1);
      db::db_query("UPDATE `" . DB_PREFIX . "settings` SET `invoice` = '{$next}'");
    }
    // Update shipping..
    sales::shippingUpdate($ID, $pcn, $tax, $tax2, $saleSubTotal);
    return array(
      $Q,
      $ID,
      $pass
    );
  }

  public function shippingUpdate($id, $cds, $tax, $tax2, $sub) {
    $sid = (int) $_POST['shipping'];
    if ($sid == '0') {
      $cost = '0.00';
    } else {
      $Q = db::db_query("SELECT `cost` FROM `" . DB_PREFIX . "shipping` WHERE `id` = '{$sid}'");
      $S = db::db_object($Q);
      if ($S->cost == '0.00') {
        $cost = '0.00';
      } else {
        if (substr($S->cost, -1) == '%') {
          $QS = db::db_query("SELECT ROUND(SUM(`cost`),2) AS `sumSale` FROM `" . DB_PREFIX . "sales_items`
                WHERE `sale`   = '{$id}'
                AND `type`     = 'collection'
                AND `physical` = 'yes'
                ");
          $SL = db::db_object($QS);
          if ($SL->sumSale == '0.00') {
            $cost = '0.00';
          } else {
            $cost = mswFormatPrice((substr($S->cost, 0, -1) * $SL->sumSale / 100));
          }
        } else {
          $cost = mswFormatPrice($S->cost);
        }
      }
    }
    // Calculate tax
    $vRate = array('0.00','0.00');
    // Tangible goods tax (inc shipping)..
    if ($tax > 0) {
      $QS    = db::db_query("SELECT ROUND(SUM(`cost`),2) AS `sumSale` FROM `" . DB_PREFIX . "sales_items`
               WHERE `sale`   = '{$id}'
               AND `physical` = 'yes'
               ");
      $SL       = db::db_object($QS);
      $icost    = (isset($SL->sumSale) ? $SL->sumSale : '0.00');
      $vRate[0] = mswFormatPrice(($tax * mswFormatPrice(($icost + $cost)) / 100));
    }
    // Digital goods tax..
    if ($tax2 > 0) {
      $QS    = db::db_query("SELECT ROUND(SUM(`cost`),2) AS `sumSale` FROM `" . DB_PREFIX . "sales_items`
               WHERE `sale`   = '{$id}'
               AND `physical` = 'no'
               ");
      $SL       = db::db_object($QS);
      $icost    = (isset($SL->sumSale) ? $SL->sumSale : '0.00');
      $vRate[1] = mswFormatPrice(($tax2 * mswFormatPrice($icost) / 100));
    }
    $payTotal = mswFormatPrice($sub + $cost + $vRate[0] + $vRate[1]);
    db::db_query("UPDATE `" . DB_PREFIX . "sales` SET
    `paytotal` = '{$payTotal}',
    `shipping` = '{$cost}',
    `shipID`   = '{$sid}',
    `tax`      = '{$vRate[0]}',
    `tax2`     = '{$vRate[1]}'
    WHERE `id` = '{$id}'
    ");
  }

  public function addToClipBoard() {
    $type = (substr($_GET['clipBoard'], 0, 1) == 'c' ? 'collection' : 'track');
    $clip = (int) substr($_GET['clipBoard'], 1);
    if ($clip > 0) {
      db::db_query("INSERT INTO `" . DB_PREFIX . "sales_clipboard` (`trackcol`,`type`,`physical`) VALUES ('{$clip}','{$type}','no')");
    }
  }

  public function updateClipBoard() {
    $ID = (int) $_GET['id'];
    if ($ID > 0 && isset($_GET['choice']) && in_array($_GET['choice'], array(
      'yes',
      'no'
    ))) {
      db::db_query("UPDATE `" . DB_PREFIX . "sales_clipboard` SET `physical` = '{$_GET['choice']}' WHERE `id` = '{$ID}'");
    }
    return 'ok';
  }

  public function clearClipBoard() {
    $ID = (int) $_GET['id'];
    if ($ID > 0) {
      db::db_query("DELETE FROM `" . DB_PREFIX . "sales_clipboard` WHERE `id` = '{$ID}'");
      mswTableTruncationRoutine(array(
        'sales_clipboard'
      ), $this);
      return db::db_rowcount('sales_clipboard');
    } else {
      db::db_query("DELETE FROM `" . DB_PREFIX . "sales_clipboard`");
      mswTableTruncationRoutine(array(
        'sales_clipboard'
      ), $this);
      return '0';
    }
  }

  public function exportHistory($l) {
    $csv        = '';
    $del        = ',';
    $dhistory   = (int) $_GET['dhistory'];
    $saleOption = (int) $_GET['saleItem'];
    $file       = PATH . 'backup/export-history-' . $dhistory . '-' . $saleOption . '.csv';
    $Q          = db::db_query("SELECT * FROM `" . DB_PREFIX . "sales_click`
                  WHERE `sale`    = '{$dhistory}'
                  AND `trackcol`  = '{$saleOption}'
                  ORDER BY `ts` DESC
                  ");
    while ($H = db::db_object($Q)) {
      $csv[] = mswCleanCSV($this->datetime->dateTimeDisplay($H->ts, $this->settings->dateformat), $del) . $del . mswCleanCSV($this->datetime->dateTimeDisplay($H->ts, $this->settings->timeformat), $del) . $del . mswCleanCSV($H->ip, $del) . $del . mswCleanCSV(mswCleanData($H->action), $del);
    }
    if ($csv) {
      // Save file to server and download..
      $this->dl->write($file, $l . mswDefineNewline() . implode(mswDefineNewline(), $csv));
      if (file_exists($file)) {
        $this->dl->dl($file, 'text/csv');
      }
    }
    // If nothing found, just go back to sale edit screen..
    header("Location: index.php?p=new-sale&edit=" . $dhistory);
    exit;
  }

  public function clearHistory() {
    $ID   = $_GET['id'];
    $sale = (int) $_GET['sale'];
    if (substr($ID, 0, 3) == 'all') {
      $item = (int) substr($ID, 3);
      db::db_query("DELETE FROM `" . DB_PREFIX . "sales_click` WHERE `sale` = '{$sale}' AND `trackcol` = '{$item}'");
      mswTableTruncationRoutine(array(
        'sales_click'
      ), $this);
      return 'all';
    } else {
      db::db_query("DELETE FROM `" . DB_PREFIX . "sales_click` WHERE `id` = '" . (int) $ID . "'");
      mswTableTruncationRoutine(array(
        'sales_click'
      ), $this);
      return 'item';
    }
  }

  public function exportSales($head) {
    $SQL  = 'WHERE `' . DB_PREFIX . 'sales`.`enabled` = \'yes\' ';
    $file = PATH . 'backup/sales.csv';
    $bld  = array();
    $del  = ',';
    if ($_POST['from'] && $_POST['to']) {
      $from = $this->datetime->dateToTS($_POST['from']);
      $to   = $this->datetime->dateToTS($_POST['to']);
      if ($from > 0 && $to > 0) {
        $SQL .= 'AND (DATE(FROM_UNIXTIME(`' . DB_PREFIX . 'sales`.`ts`)) BETWEEN \'' . date('Y-m-d', $from) . '\' AND \'' . date('Y-m-d', $to) . '\') ';
      }
    }
    if (!empty($_POST['gateway'])) {
      $gw = array();
      for ($i = 0; $i < count($_POST['gateway']); $i++) {
        $gw[] = (int) $_POST['gateway'][$i];
      }
      $SQL .= 'AND `' . DB_PREFIX . 'sales`.`gateway` IN(' . implode(',', $gw) . ') ';
    }
    if (!empty($_POST['status'])) {
      $st = array();
      for ($i = 0; $i < count($_POST['status']); $i++) {
        $st[] = "'" . mswSafeString($_POST['status'][$i], $this) . "'";
      }
      $SQL .= 'AND `' . DB_PREFIX . 'sales`.`status` IN(' . implode(',', $st) . ') ';
    }
    if ($_POST['account']) {
      $em = substr($_POST['account'], strpos($_POST['account'], '(') + 1);
      $em = mswSafeString(trim(substr($em, 0, -1)), $this);
      $Q  = db::db_query("SELECT `id` FROM `" . DB_PREFIX . "accounts` WHERE `email` = '{$em}'");
      $AC = db::db_object($Q);
      if (isset($AC->id)) {
        $SQL .= 'AND `' . DB_PREFIX . 'sales`.`account` = \'' . $AC->id . '\' ';
      }
    }
    if ($_POST['taxCountry'] > 0) {
      $SQL .= 'AND `' . DB_PREFIX . 'sales`.`taxCountry` = \'' . (int) $_POST['taxCountry'] . '\'';
    }
    if ($_POST['taxCountry2'] > 0) {
      $SQL .= 'AND `' . DB_PREFIX . 'sales`.`taxCountry2` = \'' . (int) $_POST['taxCountry2'] . '\'';
    }
    if ($SQL) {
      $Q = db::db_query("SELECT *,
           (SELECT ROUND(SUM(`cost`),2) FROM `" . DB_PREFIX . "sales_items` WHERE `sale` = `" . DB_PREFIX . "sales`.`id`) AS `saleTotal`,
           (SELECT `display` FROM `" . DB_PREFIX . "gateways` WHERE `id` = `" . DB_PREFIX . "sales`.`gateway`) AS `saleGateway`,
           (SELECT `name` FROM `" . DB_PREFIX . "countries` WHERE `id` = `" . DB_PREFIX . "sales`.`taxCountry`) AS `tangibleTaxCountry`,
           (SELECT `name` FROM `" . DB_PREFIX . "countries` WHERE `id` = `" . DB_PREFIX . "sales`.`taxCountry2`) AS `digitalTaxCountry`,
           `" . DB_PREFIX . "sales`.`ip` AS `salesIP`,
           `" . DB_PREFIX . "sales`.`shipping` AS `saleShipping`,
           `" . DB_PREFIX . "sales`.`ts` AS `saleTS`,
           `" . DB_PREFIX . "sales`.`id` AS `saleID`
           FROM `" . DB_PREFIX . "sales`
           LEFT JOIN `" . DB_PREFIX . "accounts`
           ON `" . DB_PREFIX . "sales`.`account` = `" . DB_PREFIX . "accounts`.`id`
           $SQL
           ORDER BY `" . DB_PREFIX . "sales`.`ts`
           ");
      while ($S = db::db_object($Q)) {
        $cpn = array(
          '0.00',
          ''
        );
        if ($S->coupon) {
          $cp = mswCleanData(unserialize($S->coupon));
          if (isset($cp[0], $cp[1]) && $cp[1] > 0) {
            $cpn = array(
              $cp[1],
              $cp[0]
            );
          }
        }
        $data = array();
        $csv  = '';
        $csv .= mswSaleInvoiceNumber($S->invoice) . $del;
        $csv .= mswCleanCSV(mswCleanData($S->name), $del) . $del;
        $csv .= mswCleanCSV(mswCleanData($S->email), $del) . $del;
        $csv .= mswCleanCSV(mswCleanData(str_replace('"', "'", $S->shippingAddr)), $del) . $del;
        $csv .= mswFormatPrice($S->saleTotal) . $del;
        $csv .= ($cpn[0] > 0 ? '-' . mswFormatPrice($cpn[0]) : '0.00') . $del;
        $csv .= mswFormatPrice($S->saleShipping) . $del;
        $csv .= mswFormatPrice($S->tax) . $del;
        $csv .= mswFormatPrice($S->tax2) . $del;
        $csv .= $S->taxRate . '%' . $del;
        $csv .= $S->taxRate2 . '%' . $del;
        $csv .= (isset($S->tangibleTaxCountry) ? $S->tangibleTaxCountry : 'N/A') . $del;
        $csv .= (isset($S->digitalTaxCountry) ? $S->digitalTaxCountry : 'N/A') . $del;
        $csv .= mswFormatPrice((($S->saleTotal - $cpn[0]) + $S->saleShipping + mswFormatPrice($S->tax + $S->tax2))) . $del;
        $csv .= $S->salesIP . $del;
        $csv .= mswCleanCSV(mswCleanData($S->transaction), $del) . $del;
        $csv .= (isset($S->saleGateway) ? mswCleanCSV(mswCleanData($S->saleGateway), $del) : '') . $del;
        $csv .= mswCleanCSV($this->datetime->dateTimeDisplay($S->saleTS, $this->settings->dateformat), $del) . $del;
        $csv .= mswCleanCSV(mswCleanData($S->status), $del) . $del;
        $csv .= mswCleanCSV(mswCleanData($cpn[1]), $del);
        // Music sales..
        if (isset($_POST['music']) && $_POST['music'] == 'yes') {
          $Q2 = db::db_query("SELECT * FROM `" . DB_PREFIX . "sales_items`
                WHERE `sale` = '{$S->saleID}'
                ORDER BY `type`,`id`
                ");
          while ($ITEMS = db::db_object($Q2)) {
            $name = '';
            switch ($ITEMS->type) {
              case 'collection':
                $Q_C   = db::db_query("SELECT `name`,`catnumber` FROM `" . DB_PREFIX . "collections` WHERE `id` = '{$ITEMS->item}'");
                $CTION = db::db_object($Q_C);
                $name  = str_replace('"', "'", mswCleanData($CTION->name));
                $track = '';
                $cd    = ($ITEMS->physical == 'yes' ? '[CD]' : '');
                break;
              case 'track':
                $Q_T   = db::db_query("SELECT `title`,`collection` FROM `" . DB_PREFIX . "music` WHERE `id` = '{$ITEMS->item}'");
                $CTK   = db::db_object($Q_T);
                $Q_C   = db::db_query("SELECT `name`,`catnumber` FROM `" . DB_PREFIX . "collections` WHERE `id` = '{$CTK->collection}'");
                $CTION = db::db_object($Q_C);
                $name  = str_replace('"', "'", mswCleanData($CTION->name));
                $track = str_replace('"', "'", mswCleanData($CTK->title));
                $cd    = '';
                break;
            }
            if ($name) {
              $data[] = $cd . '[' . mswCleanData($CTION->catnumber) . '] - ' . mswCleanData($name) . ($track ? ' - ' . mswCleanData($track) : '');
            }
          }
        }
        if (!empty($data)) {
          $csv .= $del . mswCleanCSV(trim(implode(mswDefineNewline(), $data)), $del);
        }
        $bld[] = $csv;
      }
      if (!empty($bld)) {
        // Save file to server and download..
        $this->dl->write($file, $head . mswDefineNewline() . implode(mswDefineNewline(), $bld));
        if (file_exists($file)) {
          $this->dl->dl($file, 'text/csv');
        }
      }
    }
    header("Location: index.php?p=export-sales");
    exit;
  }

  public function exportMoss($head) {
    $file = PATH . 'backup/moss-report.csv';
    $bld  = array();
    $del  = ',';
    $fromTo = array('','');
    $dispFT = array('','');
    $SQL    = '';
    if (isset($_GET['fr'],$_GET['to'])) {
      if ($_GET['fr'] && $_GET['to']) {
        $from = $this->datetime->dateToTS($_GET['fr']);
        $to   = $this->datetime->dateToTS($_GET['to']);
        if ($from > 0 && $to > 0) {
          $fromTo[0] = $_GET['fr'];
          $fromTo[1] = $_GET['to'];
          $dispFT[0] = $this->datetime->dateTimeDisplay($from,$this->settings->dateformat);
          $dispFT[1] = $this->datetime->dateTimeDisplay($to,$this->settings->dateformat);
          $SQL      .= ' AND (DATE(FROM_UNIXTIME(`'.DB_PREFIX.'sales`.`ts`)) BETWEEN \'' . date('Y-m-d', $from) . '\' AND \'' . date('Y-m-d', $to) . '\')';
        }
      }
    }
    if ($fromTo[0] == '') {
      $fr        = strtotime(date('Y-m') . '-01');
      $to        = strtotime(date('Y-m') . '-' . date('t'));
      $fromTo[0] = date(str_replace(array('dd','mm','yy'),array('d','m','y'),$DT->jsFormat()), $fr);
      $fromTo[1] = date(str_replace(array('dd','mm','yy'),array('d','m','y'),$DT->jsFormat()), $to);
      $SQL      .= ' AND (DATE(FROM_UNIXTIME(`'.DB_PREFIX.'sales`.`ts`)) BETWEEN \'' . date('Y-m-d', $fr) . '\' AND \'' . date('Y-m-d', $to) . '\')';
      $dispFT[0] = $this->datetime->dateTimeDisplay($fr,$this->settings->dateformat);
      $dispFT[1] = $this->datetime->dateTimeDisplay($to,$this->settings->dateformat);
    }
    $moss = array();
    $Q    = db::db_query("SELECT *,
            `" . DB_PREFIX . "countries`.`name` AS `countryName`,
            `" . DB_PREFIX . "sales`.`id` AS `saleID`
            FROM `" . DB_PREFIX . "sales`
            LEFT JOIN `" . DB_PREFIX . "countries`
            ON  `" . DB_PREFIX . "countries`.`id` = `" . DB_PREFIX . "sales`.`taxCountry2`
            WHERE `" . DB_PREFIX . "sales`.`enabled` = 'yes'
            AND `" . DB_PREFIX . "sales`.`status` = 'Completed'
            AND `" . DB_PREFIX . "sales`.`taxCountry2` > 0
            AND `" . DB_PREFIX . "sales`.`tax2` > 0
            AND `" . DB_PREFIX . "countries`.`eu` = 'yes'
            $SQL
            ORDER BY `" . DB_PREFIX . "countries`.`name`
            ");
    while ($CALC = db::db_object($Q)) {
      if (!isset($moss[$CALC->taxCountry2 . '-' . $CALC->taxRate2])) {
        $moss[$CALC->taxCountry2 . '-' . $CALC->taxRate2]    = array();
        $moss[$CALC->taxCountry2 . '-' . $CALC->taxRate2][0] = $CALC->countryName;
        $moss[$CALC->taxCountry2 . '-' . $CALC->taxRate2][2] = array($CALC->iso,$CALC->iso2,$CALC->iso4217);
      }
      $moss[$CALC->taxCountry2 . '-' . $CALC->taxRate2][1][] = $CALC->saleID;
    }
    if (!empty($moss)) {
      foreach (array_keys($moss) AS $mossKey) {
        if (!empty($moss[$mossKey][1])) {
          $split  = explode('-', $mossKey);
          $Q      = db::db_query("SELECT ROUND(SUM(`cost`),2) AS `sumSale`
                    FROM `" . DB_PREFIX . "sales_items`
                    WHERE `sale` IN(" . implode(',', $moss[$mossKey][1]) . ")
                    AND `physical` = 'no'
                    ");
          $TL     = db::db_object($Q);
          $total  = (isset($TL->sumSale) ? $TL->sumSale : '0.00');
          $sum    = @number_format(($split[1] * $total) / 100, 2, '.', '');
          $cost   = @number_format(($total + $sum), 2, '.', '');
          $csv    = '';
          $csv   .= mswCleanCSV($moss[$mossKey][0], $del) . $del;
          $csv   .= mswCleanCSV($moss[$mossKey][2][0], $del) . $del;
          $csv   .= mswCleanCSV($moss[$mossKey][2][1], $del) . $del;
          $csv   .= mswCleanCSV($moss[$mossKey][2][2], $del) . $del;
          $csv   .= mswCleanCSV(@number_format($cost,2,'.',''), $del) . $del;
          $csv   .= mswCleanCSV($split[1] . '%', $del) . $del;
          $csv   .= mswCleanCSV(@number_format($total,2,'.',''), $del) . $del;
          $csv   .= mswCleanCSV(@number_format($sum,2,'.',''), $del) . $del;
          $bld[]  = $csv;
        }
			}
      if (!empty($bld)) {
        // Save file to server and download..
        $this->dl->write($file, $head . mswDefineNewline() . implode(mswDefineNewline(), $bld));
        if (file_exists($file)) {
          $this->dl->dl($file, 'text/csv');
        }
      }
    }
    header("Location: index.php?p=moss");
    exit;
  }

}

?>