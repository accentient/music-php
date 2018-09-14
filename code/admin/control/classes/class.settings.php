<?php

class mmSystem extends db {

  public $settings;
  public $datetime;

  public function featuredOrder() {
    $f = array();
    if (!empty($_POST['col'])) {
      foreach ($_POST['col'] AS $k => $id) {
        $f[] = $id;
      }
    }
    $Q = db::db_query("UPDATE `" . DB_PREFIX . "settings` SET
         `featured` = '" . (!empty($f) ? mswSafeString(serialize($f), $this) : '') . "'
	       ");
    return 'OK';
  }

  public function featured() {
    $ID = (int) $_GET['id'];
    if ($ID > 0) {
      $f = array();
      if ($this->settings->featured) {
        $f = unserialize($this->settings->featured);
      }
      if (!in_array($ID, $f)) {
        $f[] = $ID;
        $Q   = db::db_query("UPDATE `" . DB_PREFIX . "settings` SET
             `featured` = '" . mswSafeString(serialize($f), $this) . "'
	           ");
        return 'OK';
      }
    }
    return 'ERR';
  }

  public function featuredRemove() {
    $ID = (int) $_GET['id'];
    if ($ID > 0) {
      $f = array();
      $n = array();
      if ($this->settings->featured) {
        $f = unserialize($this->settings->featured);
      }
      if (!empty($f)) {
        foreach ($f AS $ids) {
          if ($ids != $ID) {
            $n[] = $ids;
          }
        }
      }
      $Q = db::db_query("UPDATE `" . DB_PREFIX . "settings` SET
           `featured` = '" . (!empty($n) ? mswSafeString(serialize($n), $this) : '') . "'
           ");
    }
    return true;
  }

  public function pageOrdering() {
    if (!empty($_POST['pages'])) {
      foreach ($_POST['pages'] AS $k => $v) {
        $order = ($k + 1);
        db::db_query("UPDATE `" . DB_PREFIX . "pages` SET
        `orderby`  = '{$order}'
        WHERE `id` = '{$v}'
        ");
      }
    }
  }

  public function pageManagement() {
    // Filter post data for insert..
    $_POST   = mswSafeImport($_POST, $this);
    // Pre-Checks..
    $enabled = (isset($_POST['enabled']) && in_array($_POST['enabled'], array(
      'yes',
      'no'
    )) ? $_POST['enabled'] : 'no');
    $landing = (isset($_POST['landing']) && in_array($_POST['landing'], array(
      'yes',
      'no'
    )) ? $_POST['landing'] : 'no');
    // Edit or add..
    if (isset($_POST['edit'])) {
      $ID = (int) $_POST['edit'];
      $Q  = db::db_query("UPDATE `" . DB_PREFIX . "pages` SET
            `name`         = '{$_POST['name']}',
            `info`         = '{$_POST['info']}',
            `keys`         = '{$_POST['keys']}',
            `desc`         = '{$_POST['desc']}',
            `title`        = '{$_POST['title']}',
            `template`     = '{$_POST['template']}',
            `landing`      = '{$landing}',
            `slug`         = '{$_POST['slug']}',
            `enabled`      = '{$enabled}'
            WHERE `id`     = '{$ID}'
            ");
    } else {
      $Q  = db::db_query("INSERT INTO `" . DB_PREFIX . "pages` (
           `name`,
           `info`,
           `keys`,
           `desc`,
           `title`,
           `template`,
           `landing`,
           `slug`,
           `enabled`
           ) VALUES (
           '{$_POST['name']}',
           '{$_POST['info']}',
           '{$_POST['keys']}',
           '{$_POST['desc']}',
           '{$_POST['title']}',
           '{$_POST['template']}',
           '{$landing}',
           '{$_POST['slug']}',
           '{$enabled}'
           )");
      $ID = db::db_last_insert_id();
    }
    // If landing page was previously set, clear it..
    if ($landing == 'yes') {
      db::db_query("UPDATE `" . DB_PREFIX . "pages` SET
      `landing`      = 'no'
      WHERE `id`    != '{$ID}'
      AND `landing`  = 'yes'
      ");
    }
    return $Q;
  }

  public function addEditCountry() {
    // Filter post data for insert..
    $_POST   = mswSafeImport($_POST, $this);
    $display = (isset($_POST['display']) && in_array($_POST['display'], array(
      'yes',
      'no'
    )) ? $_POST['display'] : 'no');
    $iso     = substr($_POST['iso'], 0, 3);
    $iso2    = substr($_POST['iso2'], 0, 2);
    $iso4217 = substr((int) $_POST['iso4217'], 0, 3);
    $tax     = substr($_POST['tax'], 0, 2);
    $tax2    = substr($_POST['tax2'], 0, 2);
    $eu      = (isset($_POST['eu']) && in_array($_POST['eu'], array(
      'yes',
      'no'
    )) ? $_POST['eu'] : 'no');
    // Edit or add..
    if (isset($_POST['edit'])) {
      $ID = (int) $_POST['edit'];
      $Q  = db::db_query("UPDATE `" . DB_PREFIX . "countries` SET
            `name`         = '{$_POST['name']}',
            `iso`          = '{$iso}',
            `iso2`         = '{$iso2}',
            `iso4217`      = '{$iso4217}',
            `tax`          = '{$tax}',
            `tax2`         = '{$tax2}',
            `display`      = '{$display}',
            `eu`           = '{$eu}'
            WHERE `id`     = '{$ID}'
            ");
    } else {
      $Q  = db::db_query("INSERT INTO `" . DB_PREFIX . "countries` (
            `name`,
            `iso`,
            `iso2`,
            `iso4217`,
            `tax`,
            `tax2`,
            `display`,
            `eu`
            ) VALUES (
            '{$_POST['name']}',
            '{$iso}',
            '{$iso2}',
            '{$iso4217}',
            '{$tax}',
            '{$tax2}',
            '{$display}',
            '{$eu}'
            )");
      $ID = db::db_last_insert_id();
    }
    return $Q;
  }

  public function offers() {
    // Filter post data for insert..
    $_POST   = mswSafeImport($_POST, $this);
    // Pre-Checks..
    $enabled = (isset($_POST['enabled']) && in_array($_POST['enabled'], array(
      'yes',
      'no'
    )) ? $_POST['enabled'] : 'yes');
    $expiry  = ($_POST['expiry'] ? $this->datetime->dateToTS($_POST['expiry']) : '0');
    $type    = (in_array($_POST['type'], array(
      'all',
      'collections',
      'tracks',
      'cd'
    )) ? $_POST['type'] : 'all');
    $cols    = (!empty($_POST['cols']) ? implode(',', $_POST['cols']) : '');
    // Edit or add..
    if (isset($_POST['edit'])) {
      $ID = (int) $_POST['edit'];
      $Q  = db::db_query("UPDATE `" . DB_PREFIX . "offers` SET
            `discount`     = '{$_POST['discount']}',
            `expiry`       = '{$expiry}',
            `type`         = '{$type}',
            `collections`  = '{$cols}',
            `enabled`      = '{$enabled}'
            WHERE `id`     = '{$ID}'
            ");
      $ID = $_POST['edit'];
    } else {
      $Q  = db::db_query("INSERT INTO `" . DB_PREFIX . "offers` (
            `discount`,
            `expiry`,
            `type`,
            `collections`,
            `enabled`
            ) VALUES (
            '{$_POST['discount']}',
            '{$expiry}',
            '{$type}',
            '{$cols}',
            '{$enabled}'
            )");
      $ID = db::db_last_insert_id();
    }
    return $Q;
  }

  public function coupons() {
    // Filter post data for insert..
    $_POST   = mswSafeImport($_POST, $this);
    // Pre-Checks..
    $enabled = (isset($_POST['enabled']) && in_array($_POST['enabled'], array(
      'yes',
      'no'
    )) ? $_POST['enabled'] : 'yes');
    $expiry  = ($_POST['expiry'] ? $this->datetime->dateToTS($_POST['expiry']) : '0');
    $accounts = (!empty($_POST['accounts']) ? implode(',', $_POST['accounts']) : '');
    // Edit or add..
    if (isset($_POST['edit'])) {
      $ID = (int) $_POST['edit'];
      $Q  = db::db_query("UPDATE `" . DB_PREFIX . "coupons` SET
            `code`         = '{$_POST['code']}',
            `discount`     = '{$_POST['discount']}',
            `expiry`       = '{$expiry}',
            `enabled`      = '{$enabled}',
            `accounts`     = '{$accounts}'
            WHERE `id`     = '{$ID}'
            ");
      $ID = $_POST['edit'];
    } else {
      $Q  = db::db_query("INSERT INTO `" . DB_PREFIX . "coupons` (
            `code`,
            `discount`,
            `expiry`,
            `enabled`,
            `accounts`
            ) VALUES (
            '{$_POST['code']}',
            '{$_POST['discount']}',
            '{$expiry}',
            '{$enabled}',
            '{$accounts}'
            )");
      $ID = db::db_last_insert_id();
    }
    return $Q;
  }

  public function delete() {
    $ID = (int) $_GET['id'];
    if ($ID > 0) {
      switch ($_GET['table']) {
        // Collections..
        case 'collections':
          db::db_query("DELETE FROM `" . DB_PREFIX . "collections` WHERE `id` = '{$ID}'");
          db::db_query("DELETE FROM `" . DB_PREFIX . "collection_styles` WHERE `collection` = '{$ID}'");
          db::db_query("DELETE FROM `" . DB_PREFIX . "sales_clipboard` WHERE `type` = 'collection' AND `trackcol` = '{$ID}'");
          db::db_query("DELETE FROM `" . DB_PREFIX . "offers` WHERE `type` = 'collections' AND `collections` = '{$ID}'");
          db::db_query("DELETE FROM `" . DB_PREFIX . "music` WHERE `collection` = '{$ID}'");
          // Rebuild offers where collection was a part of group..
          $Q = db::db_query("SELECT `id`,`collections` FROM `" . DB_PREFIX . "offers` WHERE `type` = 'collections' AND FIND_IN_SET('{$ID}',`collections`) > 0");
          while ($OF = db::db_object($Q)) {
            $split = explode(',', $OF->collections);
            $flip  = array_flip($split);
            if (isset($flip[$ID])) {
              unset($flip[$ID]);
            }
            db::db_query("UPDATE `" . DB_PREFIX . "offers` SET
            `collections` = '" . implode(',', array_keys($flip)) . "'
            WHERE `id`  = '{$OF->id}'
            ");
          }
          mswTableTruncationRoutine(array(
            'collections',
            'collection_styles',
            'sales_clipboard',
            'offers',
            'music'
          ), $this);
          break;
        // Styles..
        case 'styles':
          db::db_query("DELETE FROM `" . DB_PREFIX . "music_styles` WHERE `id` = '{$ID}'");
          db::db_query("DELETE FROM `" . DB_PREFIX . "collection_styles` WHERE `style` = '{$ID}'");
          mswTableTruncationRoutine(array(
            'music_styles',
            'collection_styles'
          ), $this);
          break;
        // Sales..
        case 'sales':
          db::db_query("DELETE FROM `" . DB_PREFIX . "sales` WHERE `id` = '{$ID}'");
          db::db_query("DELETE FROM `" . DB_PREFIX . "sales_click` WHERE `sale` = '{$ID}'");
          db::db_query("DELETE FROM `" . DB_PREFIX . "sales_items` WHERE `sale` = '{$ID}'");
          mswTableTruncationRoutine(array(
            'sales',
            'sales_click',
            'sales_items'
          ), $this);
          break;
        // Accounts..
        case 'accounts':
          db::db_query("DELETE FROM `" . DB_PREFIX . "accounts` WHERE `id` = '{$ID}'");
          db::db_query("DELETE FROM `" . DB_PREFIX . "accounts_addr` WHERE `account` = '{$ID}'");
          // Delete all account sales..
          $sl = array();
          $Q  = db::db_query("SELECT `id` FROM `" . DB_PREFIX . "sales` WHERE `account` = '{$ID}'");
          while ($SL = db::db_object($Q)) {
            $sl[] = $SL->id;
          }
          if (!empty($sl)) {
            db::db_query("DELETE FROM `" . DB_PREFIX . "sales` WHERE `id` IN(" . implode(',', $sl) . ")");
            db::db_query("DELETE FROM `" . DB_PREFIX . "sales_click` WHERE `sale` IN(" . implode(',', $sl) . ")");
            db::db_query("DELETE FROM `" . DB_PREFIX . "sales_items` WHERE `sale` IN(" . implode(',', $sl) . ")");
          }
          mswTableTruncationRoutine(array(
            'accounts',
            'accounts_addr',
            'sales',
            'sales_click',
            'sales_items'
          ), $this);
          break;
        // Pages..
        case 'pages':
          db::db_query("DELETE FROM `" . DB_PREFIX . "pages` WHERE `id` = '{$ID}'");
          mswTableTruncationRoutine(array(
            'pages'
          ), $this);
          break;
        // Offers..
        case 'offers':
          db::db_query("DELETE FROM `" . DB_PREFIX . "offers` WHERE `id` = '{$ID}'");
          mswTableTruncationRoutine(array(
            'offers'
          ), $this);
          break;
        // Coupons..
        case 'coupons':
          db::db_query("DELETE FROM `" . DB_PREFIX . "coupons` WHERE `id` = '{$ID}'");
          mswTableTruncationRoutine(array(
            'coupons'
          ), $this);
          break;
        // Countries..
        case 'countries':
          db::db_query("DELETE FROM `" . DB_PREFIX . "countries` WHERE `id` = '{$ID}'");
          mswTableTruncationRoutine(array(
            'countries'
          ), $this);
          break;
        // Tracks..
        case 'tracks':
          db::db_query("DELETE FROM `" . DB_PREFIX . "music` WHERE `id` = '{$ID}'");
          db::db_query("DELETE FROM `" . DB_PREFIX . "sales_clipboard` WHERE `type` = 'track' AND `trackcol` = '{$ID}'");
          mswTableTruncationRoutine(array(
            'music',
            'sales_clipboard'
          ), $this);
          break;
        // Login history..
        case 'loghistory':
          db::db_query("DELETE FROM `" . DB_PREFIX . "accounts_login` WHERE `id` = '{$ID}'");
          mswTableTruncationRoutine(array(
            'accounts_login'
          ), $this);
          break;
      }
    }
    return 'OK';
  }

  public function update() {
    // Pre-checks..
    $access      = array();
    $weekstart   = (isset($_POST['weekstart']) && in_array($_POST['weekstart'], array(
      'sun',
      'mon'
    )) ? $_POST['weekstart'] : 'sun');
    $zip         = (isset($_POST['zip']) && in_array($_POST['zip'], array(
      'yes',
      'no'
    )) ? $_POST['zip'] : 'no');
    $rewrite     = (isset($_POST['rewrite']) && in_array($_POST['rewrite'], array(
      'yes',
      'no'
    )) ? $_POST['rewrite'] : 'no');
    $paymode     = (isset($_POST['paymode']) && in_array($_POST['paymode'], array(
      'live',
      'test'
    )) ? $_POST['paymode'] : 'test');
    $responselog = (isset($_POST['responselog']) && in_array($_POST['responselog'], array(
      'yes',
      'no'
    )) ? $_POST['responselog'] : 'no');
    $propend     = (isset($_POST['propend']) && in_array($_POST['propend'], array(
      'yes',
      'no'
    )) ? $_POST['propend'] : 'no');
    $sysstatus   = (isset($_POST['sysstatus']) && in_array($_POST['sysstatus'], array(
      'yes',
      'no'
    )) ? $_POST['sysstatus'] : 'yes');
    $autoenable  = ($_POST['autoenable'] ? $this->datetime->dateToTS($_POST['autoenable']) : '0');
    $invoice     = (isset($_POST['invoice']) ? (int) ltrim($_POST['invoice'], '0') : '0');
    $minpass     = (isset($_POST['minpass']) ? (int) $_POST['minpass'] : '8');
    $notify      = (!empty($_POST['notify']) ? mswSafeString(serialize($_POST['notify']), $this) : '');
    $tax         = (isset($_POST['deftax']) && $_POST['deftax'] > 0 ? (int) $_POST['deftax'] : '0');
    $tax2        = (isset($_POST['deftax2']) && $_POST['deftax2'] > 0 ? (int) $_POST['deftax2'] : '0');
    $defC        = (isset($_POST['defCountry']) && $_POST['defCountry'] > 0 ? (int) $_POST['defCountry'] : '0');
    $defC2       = (isset($_POST['defCountry2']) && $_POST['defCountry2'] > 0 ? (int) $_POST['defCountry2'] : '0');
    $facebook    = (isset($_POST['facebook']) && in_array($_POST['facebook'], array(
      'yes',
      'no'
    )) ? $_POST['facebook'] : 'no');
    $social      = (!empty($_POST['social']) ? mswSafeString(serialize($_POST['social']), $this) : '');
    $licenable   = (isset($_POST['licenable']) && in_array($_POST['licenable'], array(
      'yes',
      'no'
    )) ? $_POST['licenable'] : 'no');
    // Access array..
    $access      = array(
      (int) $_POST['access'][0],
      $_POST['access'][1],
      (int) $_POST['access'][2],
      (isset($_POST['access'][3]) && in_array($_POST['access'][3], array(
        'yes',
        'no'
      )) ? $_POST['access'][3] : 'no'),
      (isset($_POST['access'][4]) && in_array($_POST['access'][4], array(
        'yes',
        'no'
      )) ? $_POST['access'][4] : 'no'),
      (int) $_POST['access'][5],
      (isset($_POST['access'][6]) && in_array($_POST['access'][6], array(
        'yes',
        'no'
      )) ? $_POST['access'][6] : 'no'),
      (isset($_POST['access'][7]) && in_array($_POST['access'][7], array(
        'tmp',
        'log'
      )) ? $_POST['access'][7] : 'tmp')
    );
    $geoip  = (isset($_POST['geoip']) && in_array($_POST['geoip'], array(
      'yes',
      'no'
    )) ? $_POST['geoip'] : 'no');
    $cdpur  = (isset($_POST['cdpur']) && in_array($_POST['cdpur'], array(
      'yes',
      'no'
    )) ? $_POST['cdpur'] : 'no');
    $rss  = (isset($_POST['rss']) && in_array($_POST['rss'], array(
      'yes',
      'no'
    )) ? $_POST['rss'] : 'no');
    $hideparams  = (isset($_POST['hideparams']) && in_array($_POST['hideparams'], array(
      'yes',
      'no'
    )) ? $_POST['hideparams'] : 'no');
    $acclogin  = (isset($_POST['acclogin']) && in_array($_POST['acclogin'], array(
      'yes',
      'no'
    )) ? $_POST['acclogin'] : 'yes');
    $accloginflag  = (isset($_POST['accloginflag']) ? (int) $_POST['accloginflag'] : '0');
    $termsenable   = (isset($_POST['termsenable']) && in_array($_POST['termsenable'], array(
      'yes',
      'no'
    )) ? $_POST['termsenable'] : 'yes');
    // Check currency display and make sure placeholder hasn`t been removed..
    $_POST['curdisplay'] = (strpos($_POST['curdisplay'], '{AMOUNT}') !== false ? $_POST['curdisplay'] : $_POST['curdisplay'].'{AMOUNT}');
    // Update..
    $Q = db::db_query("UPDATE `" . DB_PREFIX . "settings` SET
         `website`       = '" . mswSafeString($_POST['website'], $this) . "',
         `email`         = '" . mswSafeString($_POST['email'], $this) . "',
         `httppath`      = '" . mswSafeString($_POST['httppath'], $this) . "',
         `secfolder`     = '" . mswSafeString($_POST['secfolder'], $this) . "',
         `dateformat`    = '" . mswSafeString($_POST['dateformat'], $this) . "',
         `timeformat`    = '" . mswSafeString($_POST['timeformat'], $this) . "',
         `timezone`      = '" . mswSafeString($_POST['timezone'], $this) . "',
         `jsformat`      = '" . mswSafeString($_POST['jsformat'], $this) . "',
         `weekstart`     = '{$weekstart}',
         `zip`           = '{$zip}',
         `rewrite`       = '{$rewrite}',
         `paymode`       = '{$paymode}',
         `responselog`   = '{$responselog}',
         `propend`       = '{$propend}',
         `smtp_port`     = '" . mswSafeString($_POST['smtp_port'], $this) . "',
         `smtp_host`     = '" . mswSafeString($_POST['smtp_host'], $this) . "',
         `smtp_user`     = '" . mswSafeString($_POST['smtp_user'], $this) . "',
         `smtp_pass`     = '" . mswSafeString($_POST['smtp_pass'], $this) . "',
         `smtp_from`     = '" . mswSafeString($_POST['smtp_from'], $this) . "',
         `smtp_email`    = '" . mswSafeString($_POST['smtp_email'], $this) . "',
         `smtp_security` = '" . mswSafeString($_POST['smtp_security'], $this) . "',
         `smtp_other`    = '" . mswSafeString($_POST['smtp_other'], $this) . "',
         `sysstatus`     = '{$sysstatus}',
         `autoenable`    = '{$autoenable}',
         `reason`        = '" . mswSafeString($_POST['reason'], $this) . "',
         `allowip`       = '" . mswSafeString($_POST['allowip'], $this) . "',
         `currency`      = '" . mswSafeString($_POST['currency'], $this) . "',
         `invoice`       = '{$invoice}',
         `curdisplay`    = '" . mswSafeString($_POST['curdisplay'], $this) . "',
         `access`        = '" . mswSafeString(serialize($access), $this) . "',
         `afoot`         = '" . (isset($_POST['afoot']) ? mswSafeString($_POST['afoot'], $this) : ''). "',
         `pfoot`         = '" . (isset($_POST['pfoot']) ? mswSafeString($_POST['pfoot'], $this) : '') . "',
         `theme`         = '" . mswSafeString($_POST['theme'], $this) . "',
         `metakeys`      = '" . mswSafeString($_POST['metakeys'], $this) . "',
         `metadesc`      = '" . mswSafeString($_POST['metadesc'], $this) . "',
         `minpass`       = '{$minpass}',
         `emnotify`      = '{$notify}',
         `deftax`        = '{$tax}',
         `deftax2`       = '{$tax2}',
         `defCountry`    = '{$defC}',
         `defCountry2`   = '{$defC2}',
         `facebook`      = '{$facebook}',
         `social`        = '{$social}',
         `licsubj`       = '" . mswSafeString($_POST['licsubj'], $this) . "',
         `licmsg`        = '" . mswSafeString(strip_tags($_POST['licmsg']), $this) . "',
         `licenable`     = '{$licenable}',
         `geoip`         = '{$geoip}',
         `minpurchase`   = '" . mswSafeString($_POST['minpurchase'], $this) . "',
         `cdpur`         = '{$cdpur}',
         `rss`           = '{$rss}',
         `hideparams`    = '{$hideparams}',
         `acclogin`      = '{$acclogin}',
         `accloginflag`  = '{$accloginflag}',
         `termsmsg`      = '" . (isset($_POST['termsmsg']) ? mswSafeString($_POST['termsmsg'], $this) : '') . "',
         `termsenable`   = '{$termsenable}'
         ");
    // Shipping?
    mmSystem::shipping();
    // Maxmind..
    $m_ipv4 = array(
      (isset($_FILES['maxmind']['tmp_name']['ipv4']) ? $_FILES['maxmind']['tmp_name']['ipv4'] : ''),
      (isset($_FILES['maxmind']['name']['ipv4']) ? $_FILES['maxmind']['name']['ipv4'] : ''),
      (isset($_FILES['maxmind']['error']['ipv4']) ? $_FILES['maxmind']['error']['ipv4'] : 'FAIL')
    );
    $m_ipv6 = array(
      (isset($_FILES['maxmind']['tmp_name']['ipv6']) ? $_FILES['maxmind']['tmp_name']['ipv6'] : ''),
      (isset($_FILES['maxmind']['name']['ipv6']) ? $_FILES['maxmind']['name']['ipv6'] : ''),
      (isset($_FILES['maxmind']['error']['ipv6']) ? $_FILES['maxmind']['error']['ipv6'] : 'FAIL')
    );
    // Lets log the error upload codes..
    if (!in_array($m_ipv4[2], array(0, 'UPLOAD_ERR_OK'))) {
      @file_put_contents(ERR_HANDLER_PATH . ERR_HANDLER_LOG_FOLDER . '/' . FILE_ERR_LOG, 'IPV4 (' . $m_ipv4[0] . ') Error Code: ' . $m_ipv4[2]);
    }
    if (!in_array($m_ipv6[2], array(0, 'UPLOAD_ERR_OK'))) {
      @file_put_contents(ERR_HANDLER_PATH . ERR_HANDLER_LOG_FOLDER . '/' . FILE_ERR_LOG, 'IPV6 (' . $m_ipv6[0] . ') Error Code: ' . $m_ipv6[2]);
    }
    if ($m_ipv4[2] == 'UPLOAD_ERR_OK' && $m_ipv6[2] == 'UPLOAD_ERR_OK' && $m_ipv4[0] && $m_ipv6[0]
        && strrchr(strtolower($m_ipv4[1]),'.') == '.csv' && strrchr(strtolower($m_ipv6[1]),'.') == '.csv') {
      mswTableTruncationRoutine(array(
        'geo_ipv4',
        'geo_ipv6'
      ), $this, true);
      // IPv4..
      $li1 = db::db_query("LOAD DATA LOCAL INFILE '" . mswSafeString($m_ipv4[0], $this) . "' INTO TABLE `" . DB_PREFIX . "geo_ipv4`
             FIELDS TERMINATED BY ','
             OPTIONALLY ENCLOSED BY '\"'
             ESCAPED BY '\"'
             LINES TERMINATED BY '\n'
             IGNORE 0 LINES (`from_ip`, `to_ip`, `loc_start`, `loc_end`, `country_iso`, `country`)
             ", true);
      if ($li1 == 'err' && defined('ERR_HANDLER_PATH')) {
        @file_put_contents(ERR_HANDLER_PATH . ERR_HANDLER_LOG_FOLDER . '/' . FILE_ERR_LOG, db::db_error($li1));
      }
      // IPv6..
      // If file has a space between columns, mysql throws a wobbly, so lets check if something
      //  was added by allowing the space and if not, try a standard import
      $li2 = db::db_query("LOAD DATA LOCAL INFILE '" . mswSafeString($m_ipv6[0], $this) . "' INTO TABLE `" . DB_PREFIX . "geo_ipv6`
             FIELDS TERMINATED BY ', '
             OPTIONALLY ENCLOSED BY '\"'
             ESCAPED BY '\"'
             LINES TERMINATED BY '\n'
             IGNORE 0 LINES (`from_ip`, `to_ip`, `loc_start`, `loc_end`, `country_iso`, `country`)
             ", true);
      if ($li2 == 'err' && defined('ERR_HANDLER_PATH')) {
        @file_put_contents(ERR_HANDLER_PATH . ERR_HANDLER_LOG_FOLDER . '/' . FILE_ERR_LOG, db::db_error($li2));
      }
      if (db::db_rowcount('geo_ipv6') == 0) {
        $li3 = db::db_query("LOAD DATA LOCAL INFILE '" . mswSafeString($m_ipv6[0], $this) . "' INTO TABLE `" . DB_PREFIX . "geo_ipv6`
               FIELDS TERMINATED BY ','
               OPTIONALLY ENCLOSED BY '\"'
               ESCAPED BY '\"'
               LINES TERMINATED BY '\n'
               IGNORE 0 LINES (`from_ip`, `to_ip`, `loc_start`, `loc_end`, `country_iso`, `country`)
               ", true);
        if ($li3 == 'err' && defined('ERR_HANDLER_PATH')) {
          @file_put_contents(ERR_HANDLER_PATH . ERR_HANDLER_LOG_FOLDER . '/' . FILE_ERR_LOG, db::db_error($li3));
        }
      }
      // Remove temporary files..
      if ($m_ipv4[0] && file_exists($m_ipv4[0])) {
        @unlink($m_ipv4[0]);
      }
      if ($m_ipv6[0] && file_exists($m_ipv6[0])) {
        @unlink($m_ipv6[0]);
      }
      db::db_query("UPDATE `" . DB_PREFIX . "settings` SET
      `maxupdate` = '" . $this->datetime->utcTime() . "'
      ");
    }
    // API fields..
    if (!empty($_POST['api'])) {
      foreach (array_keys($_POST['api']) AS $k) {
        foreach ($_POST['api'][$k] AS $apiK => $apiV) {
          $QP     = db::db_query("SELECT `id`
                    FROM `" . DB_PREFIX . "api`
                    WHERE `desc` = '{$k}'
                    AND `param` = '{$apiK}'
                    LIMIT 1
                    ");
          $PAR = db::db_object($QP);
          if (isset($PAR->id)) {
            db::db_query("UPDATE `" . DB_PREFIX . "api` SET
            `value`    = '" . mswSafeString($apiV, $this) . "'
            WHERE `id` = '{$PAR->id}'
            ");
          } else {
            db::db_query("INSERT INTO `" . DB_PREFIX . "api` (
            `desc`,
            `param`,
            `value`
            ) VALUES (
            '" . mswSafeString($k, $this) . "',
            '" . mswSafeString($apiK, $this) . "',
            '" . mswSafeString($apiV, $this) . "'
            )");
          }
        }
      }
    }
    return $Q;
  }

  // Shipping..
  public function shipping() {
    $cnt = 0;
    // If the ID is empty, always clear..
    // This prevents duplicates on the initial screen..
    if (empty($_POST['zID'])) {
      mswTableTruncationRoutine(array(
        'shipping'
      ), $this);
    }
    if (!empty($_POST['zname'])) {
      for ($i = 0; $i < count($_POST['zname']); $i++) {
        if (trim($_POST['zname'][$i])) {
          ++$cnt;
          if (isset($_POST['zID'][$i])) {
            $id = (int) $_POST['zID'][$i];
            db::db_query("UPDATE `" . DB_PREFIX . "shipping` SET
            `name`     = '{$_POST['zname'][$i]}',
            `cost`     = '{$_POST['zcost'][$i]}'
            WHERE `id` = '{$id}'
            ");
          } else {
            db::db_query("INSERT INTO `" . DB_PREFIX . "shipping` (
            `name`,
            `cost`
            ) VALUES (
            '{$_POST['zname'][$i]}',
            '{$_POST['zcost'][$i]}'
            )");
          }
        } else {
          if (isset($_POST['zID'][$i])) {
            $id = (int) $_POST['zID'][$i];
            db::db_query("DELETE FROM `" . DB_PREFIX . "shipping` WHERE `id` = '{$id}'");
            db::db_query("UPDATE `" . DB_PREFIX . "accounts` SET `shipping` = '0' WHERE `shipping` = '{$id}'");
            mswTableTruncationRoutine(array(
              'shipping'
            ), $this);
          }
        }
      }
    }
    // If nothing, truncate to clear..
    if ($cnt == 0) {
      mswTableTruncationRoutine(array(
        'shipping'
      ), $this);
    }
  }

  // Check for new version..
  public function version() {
    $url = 'http://www.maianscriptworld.co.uk/version-check.php?id=' . SCRIPT_ID;
    $str = '';
    if (function_exists('curl_init')) {
      $ch = @curl_init();
      @curl_setopt($ch, CURLOPT_URL, $url);
      @curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $result = @curl_exec($ch);
      @curl_close($ch);
      if ($result) {
        if ($result != $this->settings->version) {
          $str = 'Installed Version: ' . $this->settings->version . mswDefineNewline();
          $str .= 'Current Version: ' . $result . mswDefineNewline() . mswDefineNewline();
          $str .= '<i class="fa fa-times fa-fw"></i> Your version is out of date.' . mswDefineNewline() . mswDefineNewline();
          $str .= 'Download new version at:' . mswDefineNewline();
          $str .= '<a href="http://www.' . SCRIPT_URL . '/download.html" onclick="window.open(this);return false">www.' . SCRIPT_URL . '</a>';
        } else {
          $str = 'Current Version: ' . $this->settings->version . mswDefineNewline() . mswDefineNewline() . '<i class="fa fa-check fa-fw"></i> You are currently using the latest version';
        }
      }
    } else {
      if (@ini_get('allow_url_fopen') == '1') {
        $result = @file_get_contents($url);
        if ($result) {
          if ($result != $this->settings->version) {
            $str = 'Installed Version: ' . $this->settings->version . mswDefineNewline();
            $str .= 'Current Version: ' . $result . mswDefineNewline() . mswDefineNewline();
            $str .= '<i class="fa fa-times fa-fw"></i> Your version is out of date.' . mswDefineNewline() . mswDefineNewline();
            $str .= 'Download new version at:' . mswDefineNewline();
            $str .= '<a href="http://www.' . SCRIPT_URL . '/download.html" onclick="window.open(this);return false">www.' . SCRIPT_URL . '</a>';
          } else {
            $str = 'Current Version: ' . $this->settings->version . mswDefineNewline() . mswDefineNewline() . '<i class="fa fa-check fa-fw"></i> You are currently using the latest version';
          }
        }
      }
    }
    // Nothing?
    if ($str == '') {
      $str = 'Server check functions not available.' . mswDefineNewline() . mswDefineNewline();
      $str .= 'Please visit <a href="http://www.' . SCRIPT_URL . '/download.html" onclick="window.open(this);return false">www.' . SCRIPT_URL . '</a> to check for updates';
    }
    return $str;
  }

}

?>
