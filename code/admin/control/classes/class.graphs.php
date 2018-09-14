<?php

class graphs extends db {

  public $settings;
  public $lang;
  public $dates;

  // Settings..
  public function settings() {
    $data = (isset($_POST['graph']) ? mswSafeString(serialize($_POST['graph']), $this) : '');
    db::db_query("UPDATE `" . DB_PREFIX . "settings` SET
    `statistics` = '{$data}'
    ");
  }

  // Data for yearly..
  public function yearly() {
    $y1 = (isset($_GET['y1']) && $_GET['y1'] && @checkdate(01, 31, $_GET['y1']) ? $_GET['y1'] : '');
    $y2 = (isset($_GET['y2']) && $_GET['y2'] && @checkdate(01, 31, $_GET['y2']) ? $_GET['y2'] : '');
    // Are defaults set?
    if ($y1 == '' || $y2 == '') {
      $y1   = date('Y');
      $y2   = date('Y', strtotime('-1 year'));
      $gset = ($this->settings->statistics ? unserialize($this->settings->statistics) : array());
      if (isset($gset['years'])) {
        $chop = explode(',', $gset['years']);
        if (isset($chop[0], $chop[1])) {
          $y1 = (int) $chop[0];
          $y2 = (int) $chop[1];
        }
      }
    }
    // First year..
    $d = array(
      array(),
      array()
    );
    for ($i = 0; $i < 12; $i++) {
      $mt     = ($i + 1);
      $Q      = db::db_query("SELECT
                count(*) AS `saleCount`
                FROM `" . DB_PREFIX . "sales`
                WHERE YEAR(FROM_UNIXTIME(`ts`)) = '{$y1}'
                AND MONTH(FROM_UNIXTIME(`ts`))  = '{$mt}'
                AND `enabled`                   = 'yes'
                ");
      $T      = db::db_object($Q);
      $d[0][] = array(
        $this->dates[0][$i],
        (isset($T->saleCount) ? $T->saleCount : '0')
      );
    }
    // Second year..
    for ($i = 0; $i < 12; $i++) {
      $mt     = ($i + 1);
      $QT2    = db::db_query("SELECT
                count(*) AS `saleCount`
                FROM `" . DB_PREFIX . "sales`
                WHERE YEAR(FROM_UNIXTIME(`ts`)) = '{$y2}'
                AND MONTH(FROM_UNIXTIME(`ts`))  = '{$mt}'
                AND `enabled`                   = 'yes'
                ");
      $T2     = db::db_object($QT2);
      $d[1][] = array(
        $this->dates[0][$i],
        (isset($T2->saleCount) ? $T2->saleCount : '0')
      );
    }
    return array(
      'data' => $d[0],
      'label' => $y1,
      'data2' => $d[1],
      'label2' => $y2
    );
  }

  // Data for revenue..
  public function revenue() {
    $m = (isset($_GET['d']) ? $_GET['d'] : '');
    // Are defaults set?
    if ($m == '') {
      $gset = ($this->settings->statistics ? unserialize($this->settings->statistics) : array());
      if (isset($gset['month'])) {
        switch ($gset['month']) {
          case 'this':
            $m = date('m-Y');
            break;
          case 'last':
            $m = date('m-Y', strtotime('-1 month'));
            break;
        }
      } else {
        $m = date('m-Y');
      }
    }
    $n = date('m-Y');
    $l = date('m-Y', strtotime('-1 month'));
    $c = explode('-', $m);
    if (!isset($c[0], $c[1])) {
      $m = date('m-Y');
      $c = explode('-', $m);
    }
    $dt = (date('n', strtotime((int) $c[1] . '-' . (int) $c[0] . '-01')) - 1);
    if ($m == $n) {
      $t = $this->lang[14];
    } elseif ($m == $l) {
      $t = $this->lang[15];
    } else {
      $t = $this->dates[0][$dt] . ' ' . (int) $c[1];
    }
    $dim = date('t', strtotime((int) $c[1] . '-' . (int) $c[0] . '-01'));
    $d   = array();
    for ($i = 0; $i < $dim; $i++) {
      $incr  = ($i + 1);
      $c[1]  = (int) $c[1];
      $c[0]  = (int) $c[0];
      $Q     = db::db_query("SELECT
               ROUND(SUM(`" . DB_PREFIX . "sales_items`.`cost`),2) AS `saleTotal`
               FROM `" . DB_PREFIX . "sales_items`
               LEFT JOIN `" . DB_PREFIX . "sales`
               ON `" . DB_PREFIX . "sales`.`id` = `" . DB_PREFIX . "sales_items`.`sale`
               WHERE YEAR(FROM_UNIXTIME(`" . DB_PREFIX . "sales`.`ts`))   = '{$c[1]}'
               AND MONTH(FROM_UNIXTIME(`" . DB_PREFIX . "sales`.`ts`))    = '{$c[0]}'
               AND DAY(FROM_UNIXTIME(`" . DB_PREFIX . "sales`.`ts`))      = '{$incr}'
               AND `" . DB_PREFIX . "sales`.`enabled`                     = 'yes'
               ");
      $T     = db::db_object($Q);
      $d[$i] = array(
        $incr,
        (isset($T->saleTotal) ? @number_format($T->saleTotal,2,'.','') : '0.00')
      );
    }
    return array(
      'data' => $d,
      'label' => $t
    );
  }

  // Data for revenue..
  public function month() {
    $m = (isset($_GET['d']) ? $_GET['d'] : '');
    // Are defaults set?
    if ($m == '') {
      $gset = ($this->settings->statistics ? unserialize($this->settings->statistics) : array());
      if (isset($gset['month'])) {
        switch ($gset['month']) {
          case 'this':
            $m = date('m-Y');
            break;
          case 'last':
            $m = date('m-Y', strtotime('-1 month'));
            break;
        }
      } else {
        $m = date('m-Y');
      }
    }
    $n = date('m-Y');
    $l = date('m-Y', strtotime('-1 month'));
    $c = explode('-', $m);
    if (!isset($c[0], $c[1])) {
      $m = date('m-Y');
      $c = explode('-', $m);
    }
    $dt = (date('n', strtotime((int) $c[1] . '-' . (int) $c[0] . '-01')) - 1);
    if ($m == $n) {
      $t = $this->lang[14];
    } elseif ($m == $l) {
      $t = $this->lang[15];
    } else {
      $t = $this->dates[0][$dt] . ' ' . (int) $c[1];
    }
    $dim = date('t', strtotime((int) $c[1] . '-' . (int) $c[0] . '-01'));
    $d   = array();
    for ($i = 0; $i < $dim; $i++) {
      $incr  = ($i + 1);
      $c[1]  = (int) $c[1];
      $c[0]  = (int) $c[0];
      $Q     = db::db_query("SELECT
               count(*) AS `saleCount`
               FROM `" . DB_PREFIX . "sales`
               WHERE YEAR(FROM_UNIXTIME(`ts`)) = '{$c[1]}'
               AND MONTH(FROM_UNIXTIME(`ts`))  = '{$c[0]}'
               AND DAY(FROM_UNIXTIME(`ts`))    = '{$incr}'
               AND `enabled`                   = 'yes'
               ");
      $T     = db::db_object($Q);
      $d[$i] = array(
        $incr,
        (isset($T->saleCount) ? $T->saleCount : '0')
      );
    }
    return array(
      'data' => $d,
      'label' => $t
    );
  }

  // Data for top tracks..
  public function tracks() {
    $gset  = ($this->settings->statistics ? unserialize($this->settings->statistics) : array());
    $limit = (isset($_GET['c']) && (int) $_GET['c'] > 0 ? (int) $_GET['c'] : $gset['best']);
    $html  = array();
    $sltn  = file_get_contents(PATH . 'templates/html/table-skeleton.htm');
    $rows  = array();
    $Q     = db::db_query("SELECT
             `" . DB_PREFIX . "music`.`title` AS `trackTitle`,
             `" . DB_PREFIX . "collections`.`name` AS `colName`,
             count(*) AS `saleCount`
             FROM `" . DB_PREFIX . "sales_items`
             LEFT JOIN `" . DB_PREFIX . "music`
             ON `" . DB_PREFIX . "sales_items`.`item` = `" . DB_PREFIX . "music`.`id`
             LEFT JOIN `" . DB_PREFIX . "collections`
             ON `" . DB_PREFIX . "sales_items`.`collection` = `" . DB_PREFIX . "collections`.`id`
             WHERE `" . DB_PREFIX . "sales_items`.`type`    = 'track'
             GROUP BY `" . DB_PREFIX . "sales_items`.`item`
             ORDER BY `saleCount` DESC
             LIMIT $limit
             ");
    while ($T = db::db_object($Q)) {
      $rows[] = str_replace(array(
        '{title}',
        '{info}',
        '{sales}'
      ), array(
        mswCleanData($T->trackTitle),
        mswCleanData($T->colName),
        @number_format($T->saleCount)
      ), file_get_contents(PATH . 'templates/html/table-row.htm'));
    }
    if (!empty($rows)) {
      return array(
        'data' => str_replace('{rows}', implode(mswDefineNewline(), $rows), $sltn)
      );
    }
    return array(
      'data' => ''
    );
  }

  // Data for top collections..
  public function collections() {
    $gset  = ($this->settings->statistics ? unserialize($this->settings->statistics) : array());
    $limit = (isset($_GET['c']) && (int) $_GET['c'] > 0 ? (int) $_GET['c'] : $gset['best']);
    $html  = array();
    $sltn  = file_get_contents(PATH . 'templates/html/table-skeleton.htm');
    $rows  = array();
    $Q     = db::db_query("SELECT
             `" . DB_PREFIX . "collections`.`name` AS `colName`,
             count(*) AS `saleCount`,
             (SELECT count(*) FROM `" . DB_PREFIX . "music` WHERE `collection` = `" . DB_PREFIX . "collections`.`id`) AS `trackCount`
             FROM `" . DB_PREFIX . "sales_items`
             LEFT JOIN `" . DB_PREFIX . "collections`
             ON `" . DB_PREFIX . "sales_items`.`collection` = `" . DB_PREFIX . "collections`.`id`
             WHERE `" . DB_PREFIX . "sales_items`.`type`    = 'collection'
             GROUP BY `" . DB_PREFIX . "sales_items`.`item`
             ORDER BY `saleCount` DESC
             LIMIT $limit
             ");
    while ($T = db::db_object($Q)) {
      $rows[] = str_replace(array(
        '{title}',
        '{info}',
        '{sales}'
      ), array(
        mswCleanData($T->colName),
        $this->lang[6] . mswCleanData($T->trackCount),
        @number_format($T->saleCount)
      ), file_get_contents(PATH . 'templates/html/table-row.htm'));
    }
    if (!empty($rows)) {
      return array(
        'data' => str_replace('{rows}', implode(mswDefineNewline(), $rows), $sltn)
      );
    }
    return array(
      'data' => ''
    );
  }

  // Data for Countries..
  public function countries() {
    $html  = array();
    $d     = array();
    $Q     = db::db_query("SELECT
             `" . DB_PREFIX . "countries`.`name` AS `cntName`,
             count(*) AS `cntCount`
             FROM `" . DB_PREFIX . "sales`
             LEFT JOIN `" . DB_PREFIX . "countries`
             ON `" . DB_PREFIX . "sales`.`iso` = LOWER(`" . DB_PREFIX . "countries`.`iso2`)
             WHERE `" . DB_PREFIX . "sales`.`enabled` = 'yes'
             AND `" . DB_PREFIX . "countries`.`display` = 'yes'
             GROUP BY `" . DB_PREFIX . "sales`.`iso`
             ORDER BY `" . DB_PREFIX . "countries`.`name`
             ");
    while ($C = db::db_object($Q)) {
      $d[] = array(
        'label' => $C->cntName,
        'data' => (isset($C->cntCount) ? $C->cntCount : '0')
      );
    }
    return array(
      'data' => $d
    );
  }

  // Data for Gateways..
  public function gateways() {
    $html  = array();
    $d     = array();
    $Q     = db::db_query("SELECT
             `" . DB_PREFIX . "gateways`.`display` AS `gtwName`,
             count(*) AS `gtwCount`
             FROM `" . DB_PREFIX . "sales`
             LEFT JOIN `" . DB_PREFIX . "gateways`
             ON `" . DB_PREFIX . "sales`.`gateway` = `" . DB_PREFIX . "gateways`.`id`
             WHERE `" . DB_PREFIX . "sales`.`enabled` = 'yes'
             AND `" . DB_PREFIX . "gateways`.`status` = 'yes'
             GROUP BY `" . DB_PREFIX . "sales`.`gateway`
             ORDER BY `" . DB_PREFIX . "gateways`.`display`
             ");
    while ($C = db::db_object($Q)) {
      $d[] = array(
        'label' => $C->gtwName,
        'data' => (isset($C->gtwCount) ? $C->gtwCount : '0')
      );
    }
    return array(
      'data' => $d
    );
  }

}

?>