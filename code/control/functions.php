<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4], '403');
}

//---------------------------------
// Query string management
//---------------------------------

function qStringBuilder($skip = array()) {
  $qstring = array();
  if (!empty($_GET)) {
    foreach ($_GET AS $k => $v) {
      if (!in_array($k, $skip)) {
        $qstring[] = $k . '=' . mswSafeDisplay(urlencode($v));
      }
    }
  }
  return (!empty($qstring) ? '&amp;' . implode('&amp;', $qstring) : '');
}

function qStringSelector($param,$value) {
  return (isset($_GET[$param]) && $_GET[$param] == $value ? ' selected="selected"' : '');
}

//-----------------------------------
// Accepted params
//-----------------------------------

function mswAcceptedParams() {
  return array(
    'home',
    'category',
    'product',
    'latest',
    'popular',
    'specials',
    'search',
    'account',
    'basket',
    'style',
    '404',
    'profile',
    'page',
    'collection',
    'orders',
    'logout',
    'view-order',
    'login',
    'cancel',
    'success',
    'create',
    'check',
    'ajax',
    'pg',
    'verify',
    'download',
    'message',
    'response'
  );
}

//-----------------------------------
// Overrides for certain params
//-----------------------------------

function mswParamOverRide($cmd, $seo) {
  $sef = array_flip($seo->converter('', true));
  if (isset($_GET['style'])) {
    return 'style';
  }
  if (isset($_GET['404'])) {
    return '404';
  }
  if (isset($_GET['ajax'])) {
    return 'ajax';
  }
  if (isset($_GET['collection'])) {
    $cmd = 'collection';
  }
  if (isset($_GET['style'])) {
    $cmd = 'style';
  }
  if (isset($_GET['view-order'])) {
    $cmd = 'view-order';
  }
  if (isset($_GET['search'])) {
    $cmd = 'search';
  }
  if (isset($_GET['pg'])) {
    $cmd = 'pg';
  }
  if (isset($_GET['ve'])) {
    $cmd = 'verify';
  }
  if (isset($_GET['dmf'])) {
    $cmd = 'download';
  }
  if (isset($_GET['msg'])) {
    $cmd = 'message';
  }
  if (isset($_GET['gw'])) {
    $cmd = 'response';
  }
  if (isset($_GET['orders'])) {
    $cmd = 'orders';
  }
  if (substr($cmd,0,3) == 'rss') {
    $cmd = 'rss';
  }
  // Must be last, checks for url name overrides..
  if (isset($sef[$cmd])) {
    $cmd = $sef[$cmd];
  }
  return $cmd;
}

//-----------------------------------
// Secure folder files
//-----------------------------------

function mswFolderFiles($path, $flag = '*') {
  if ($path && is_dir($path)) {
    $files = glob($path . $flag, GLOB_NOSORT);
    for ($i = 0; $i < count($files); $i++) {
      $add   = glob($files[$i] . '/' . $flag, GLOB_NOSORT);
      if (is_array($add)) {
        $files = array_merge($files, $add);
      }
    }
    return $files;
  }
  return array();
}

//-----------------------------------
// Folder file scanner
//-----------------------------------

function mswFolderFileScanner($path, $supported) {
  if ($path && is_dir($path)) {
    $ok    = array_map('strtolower', explode('|', $supported));
    $items = glob($path . '/*', GLOB_NOSORT);
    $files = array();
    for ($i = 0; $i < count($items); $i++) {
      $ext = substr(strrchr(strtolower($items[$i]), '.'), 1);
      if (in_array(strtolower($ext), $ok)) {
        $files[] = $items[$i];
      }
    }
    return $files;
  }
  return array();
}

//-----------------------------------
// Folder directory scanner..
//-----------------------------------

function mswFolderScanner($folder) {
  if ($folder && is_dir($folder)) {
    $items = glob($folder . '/*', GLOB_ONLYDIR);
    for ($i = 0; $i < count($items); $i++) {
      if (is_dir($items[$i])) {
        $add   = glob($items[$i] . '/*', GLOB_ONLYDIR);
        if (is_array($add)) {
          $items = array_merge($items, $add);
        }
      }
    }
    return $items;
  }
  return array();
}

//-----------------------------------
// Check valid email..
//-----------------------------------

function mswIsValidEmail($em) {
  if ($em == '') {
    return 'no';
  } else {
    if (function_exists('filter_var') && !filter_var($em, FILTER_VALIDATE_EMAIL)) {
      return 'no';
    } else {
      if (!preg_match("/^[_.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z.-]+.)+[a-zA-Z]{2,6}$/i", $em)) {
        return 'no';
      }
    }
  }
  return 'ok';
}

//--------------------------
// Trim Time Display
//--------------------------

function mswTrimTime($t) {
  $l = (substr($t, 0, 3) == '00:' ? ltrim(substr($t, 3), '0') : ltrim($t, '0'));
  if (substr($l, 0, 1) == ':') {
    return '0' . $l;
  }
  return $l;
}

//--------------------------
// Load cover art
//--------------------------

function mswCoverArtLoader($art, $path, $area = 'admin') {
  switch ($area) {
    case 'admin':
      if ($art && file_exists(MM_BASE_PATH . COVER_ART_FOLDER . '/' . $art)) {
        return $path . COVER_ART_FOLDER . '/' . $art;
      } else {
        return 'templates/images/tempart.png';
      }
      break;
    default:
      break;
  }
}

//---------------------------------------------------
// Get status
//---------------------------------------------------

function mswGetStatus($flag, $l) {
  return ($flag == 'yes' ? '<i class="fa fa-check-square fa-fw mm_green" title="' . mswSafeDisplay($l[36]) . '"></i>' : '<i class="fa fa-times-circle fa-fw mm_red" title="' . mswSafeDisplay($l[37]) . '"></i>');
}

//----------------------------
// DB Schema..
//----------------------------

function mswDBSchemaArray($db) {
  $tbl = array();
  if (strlen(DB_PREFIX) > 0) {
    $q = $db->db_query("SHOW TABLES WHERE SUBSTRING(`Tables_in_" . DB_NAME . "`,1," . strlen(DB_PREFIX) . ") = '" . DB_PREFIX . "'");
  } else {
    $q = $db->db_query("SHOW TABLES");
  }
  while ($TABLES = $db->db_object($q)) {
    $field = 'Tables_in_' . DB_NAME;
    $tbl[] = $TABLES->{$field};
  }
  return $tbl;
}

//---------------------------------------------------
// Shipping Address
//---------------------------------------------------

function mswShippingAddress($id, $db_obj) {
  $ship = 'N/A';
  $Q    = $db_obj->db_query("SELECT count(*) AS `cd_count` FROM `" . DB_PREFIX . "sales_items`
          WHERE `sale`   = '{$id}'
          AND `physical` = 'yes'
		      ");
  $SI   = $db_obj->db_object($Q);
  if (isset($SI->cd_count) && $SI->cd_count > 0) {
    $Q2   = $db_obj->db_query("SELECT `shippingAddr` FROM `" . DB_PREFIX . "sales`
            WHERE `id` = '{$id}'
            ");
    $SL   = $db_obj->db_object($Q2);
    $ship = mswCleanData($SL->shippingAddr);
  }
  return $ship;
}

//---------------------------------------------------
// Get sale order
//---------------------------------------------------

function mswGetSaleOrderTotals($id, $db_obj) {
  $dc   = '0.00';
  $dcc  = '';
  $Q    = $db_obj->db_query("SELECT ROUND(SUM(`cost`),2) AS `sumSale` FROM `" . DB_PREFIX . "sales_items`
		       WHERE `sale` = '{$id}'
		       ");
  $SI   = $db_obj->db_object($Q);
  $Q2   = $db_obj->db_query("SELECT `shipping`,`tax`,`tax2`,`taxRate`,`taxRate2`,`coupon` FROM `" . DB_PREFIX . "sales`
		       WHERE `id` = '{$id}'
		       ");
  $SL   = $db_obj->db_object($Q2);
  $Q3   = $db_obj->db_query("SELECT count(*) AS `iCount` FROM `" . DB_PREFIX . "sales_items`
		       WHERE `sale`   = '{$id}'
           AND `physical` = 'yes'
		       ");
  $SIC1 = $db_obj->db_object($Q3);
  $Q4   = $db_obj->db_query("SELECT count(*) AS `iCount` FROM `" . DB_PREFIX . "sales_items`
		       WHERE `sale`   = '{$id}'
           AND `physical` = 'no'
		       ");
  $SIC2 = $db_obj->db_object($Q4);
  $sub  = (isset($SI->sumSale) ? mswFormatPrice($SI->sumSale) : '0.00');
  $ship = (isset($SL->shipping) ? mswFormatPrice($SL->shipping) : '0.00');
  $tax  = (isset($SL->tax) ? mswFormatPrice($SL->tax) : '0.00');
  $tax2 = (isset($SL->tax2) ? mswFormatPrice($SL->tax2) : '0.00');
  $cp   = mswCleanData(unserialize($SL->coupon));
  $tsub = $sub;
  if (isset($cp[0],$cp[1]) && $cp[1] > 0) {
    $dc   = $cp[1];
    $dcc  = $cp[0];
    $tsub = mswFormatPrice($sub-$dc);
  }
  return array(
    'sub' => $sub,
    'ship' => $ship,
    'tax' => $tax,
    'rate' => ($SL->taxRate > 0 ? $SL->taxRate : '0'),
    'tax2' => $tax2,
    'rate2' => ($SL->taxRate2 > 0 ? $SL->taxRate2 : '0'),
    'total' => mswFormatPrice(($tsub + $ship + $tax + $tax2)),
    'coupon' => $dc,
    'couponcode' => $dcc,
    'counts' => array(
      (isset($SIC1->iCount) ? $SIC1->iCount : '0'),
      (isset($SIC2->iCount) ? $SIC2->iCount : '0')
    )
  );
}

function mswGetSaleOrder($id, $db_obj, $emvars, $reset = array()) {
  $sale = array(
    'dl' => array(),
    'cd' => array()
  );
  $Q    = $db_obj->db_query("SELECT * FROM `" . DB_PREFIX . "sales_items`
          WHERE `sale` = '{$id}'
          " . (!empty($reset) ? 'AND `id` IN(' . implode(',', $reset) . ')' : '') . "
          ORDER BY `type`,`id`
          ");
  while ($ITEMS = $db_obj->db_object($Q)) {
    switch ($ITEMS->type) {
      case 'collection':
        $Q_C                                             = $db_obj->db_query("SELECT * FROM `" . DB_PREFIX . "collections` WHERE `id` = '{$ITEMS->item}'");
        $CTION                                           = $db_obj->db_object($Q_C);
        $name                                            = mswCleanData($CTION->name);
        $sale[$ITEMS->physical == 'yes' ? 'cd' : 'dl'][] = str_replace(array(
          '{catalogue}',
          '{collection}',
          '{cost}'
        ), array(
          $CTION->catnumber,
          $name,
          $ITEMS->cost
        ), (!empty($reset) ? $emvars[2] : $emvars[0]));
        break;
      case 'track':
        $Q_T          = $db_obj->db_query("SELECT * FROM `" . DB_PREFIX . "music` WHERE `id` = '{$ITEMS->item}'");
        $CTK          = $db_obj->db_object($Q_T);
        $Q_C          = $db_obj->db_query("SELECT * FROM `" . DB_PREFIX . "collections` WHERE `id` = '{$CTK->collection}'");
        $CTION        = $db_obj->db_object($Q_C);
        $name         = mswCleanData($CTION->name);
        $track        = mswCleanData($CTK->title);
        $sale['dl'][] = str_replace(array(
          '{catalogue}',
          '{collection}',
          '{track}',
          '{cost}'
        ), array(
          $CTION->catnumber,
          $name,
          $track,
          $ITEMS->cost
        ), (!empty($reset) ? $emvars[3] : $emvars[1]));
        break;
    }
  }
  return $sale;
}

function mswGetSaleOrderAgreement($id, $db_obj, $emvars) {
  $sale = array(
    'dl' => array(),
    'cd' => array(),
    'cdd' => array()
  );
  $Q    = $db_obj->db_query("SELECT * FROM `" . DB_PREFIX . "sales_items`
          WHERE `sale` = '{$id}'
          ORDER BY `type`,`id`
          ");
  while ($ITEMS = $db_obj->db_object($Q)) {
    switch ($ITEMS->type) {
      case 'collection':
        $Q_C                                             = $db_obj->db_query("SELECT * FROM `" . DB_PREFIX . "collections` WHERE `id` = '{$ITEMS->item}'");
        $CTION                                           = $db_obj->db_object($Q_C);
        $name                                            = mswCleanData($CTION->name);
        $sale[$ITEMS->physical == 'yes' ? 'cd' : 'cdd'][] = str_replace(array(
          '{catalogue}',
          '{collection}',
          '{cost}'
        ), array(
          $CTION->catnumber,
          $name,
          $ITEMS->cost
        ), $emvars[2]);
        break;
      case 'track':
        $Q_T          = $db_obj->db_query("SELECT * FROM `" . DB_PREFIX . "music` WHERE `id` = '{$ITEMS->item}'");
        $CTK          = $db_obj->db_object($Q_T);
        $Q_C          = $db_obj->db_query("SELECT * FROM `" . DB_PREFIX . "collections` WHERE `id` = '{$CTK->collection}'");
        $CTION        = $db_obj->db_object($Q_C);
        $name         = mswCleanData($CTION->name);
        $track        = mswCleanData($CTK->title);
        $sale['dl'][] = str_replace(array(
          '{catalogue}',
          '{collection}',
          '{track}',
          '{cost}'
        ), array(
          $CTION->catnumber,
          $name,
          $track,
          $ITEMS->cost
        ), $emvars[3]);
        break;
    }
  }
  return $sale;
}

//-----------------------------------
// Determine which doc topic to load
//-----------------------------------

function mswDocTopic($cmd) {
  switch ($cmd) {
    case 'purchase':
      return 'index';
      break;
    case 'msg':
      return ($_GET['code'] == 'test' ? 'ad-test' : 'index');
      break;
    case 'gateways':
      return 'gateways';
      break;
    case 'new-gateway':
      if (defined('GW_HELP')) {
        return 'gw-' . GW_HELP;
      } else {
        return 'ad-new-gateway';
      }
      break;
    default:
      return 'ad-' . $cmd;
      break;
  }
}

function mswDLExpiryTime($S, $DT) {
  $ts     = '0';
  $access = ($S->access ? unserialize($S->access) : array());
  if (isset($access[0], $access[1])) {
    $time = (int) $access[0];
    $apd  = ($time > 1 ? 's' : '');
    if ($time > 0) {
      $cur = $DT->utcTime();
      switch ($access[1]) {
        case 'min':
          $ts = strtotime(date('Y-m-d H:i:s', strtotime('+' . $time . ' minute' . $apd, $cur)));
          break;
        case 'hrs':
          $ts = strtotime(date('Y-m-d H:i:s', strtotime('+' . $time . ' hour' . $apd, $cur)));
          break;
        case 'day':
          $ts = strtotime(date('Y-m-d H:i:s', strtotime('+' . $time . ' day' . $apd, $cur)));
          break;
        case 'week':
          $ts = strtotime(date('Y-m-d H:i:s', strtotime('+' . $time . ' week' . $apd, $cur)));
          break;
        case 'month':
          $ts = strtotime(date('Y-m-d H:i:s', strtotime('+' . $time . ' month' . $apd, $cur)));
          break;
      }
    }
  }
  return $ts;
}

//---------------------------------
// New line to break..
//---------------------------------

function mswNL2BR($text) {
  // Second param added in 5.3.0, else its not available..
  if (version_compare(phpversion(), '5.3.0', '<')) {
    return str_replace(mswDefineNewline(), '<br>', $text);
  }
  return nl2br($text, false);
}

//-----------------------------------
// Round up bit rate
//-----------------------------------

function mswBitRate($rate) {
  $r = round($rate);
  return (strlen($r) > 3 ? substr($r, 0, -3) : $r);
}

//----------------------------------------------
// Get single country
//----------------------------------------------

function mswGetCountry($id,$db_obj) {
  $Q    = $db_obj->db_query("SELECT * FROM `" . DB_PREFIX . "countries`
          WHERE `id`   = '{$id}'
          ");
  return $db_obj->db_object($Q);
}

//---------------------------------------------------
// History logger
//---------------------------------------------------

function mswHistoryLog($data = array(), $obj) {
  $data = mswSafeImport($data, $obj);
  $obj->db_query("INSERT INTO `" . DB_PREFIX . "sales_click` (
  `sale`,
  `trackcol`,
  `ip`,
  `ts`,
  `action`,
  `type`,
  `iso`,
  `country`
  ) VALUES (
  '{$data['sale']}',
  '{$data['trackcol']}',
  '" . $_SERVER['REMOTE_ADDR'] . "',
  UNIX_TIMESTAMP(UTC_TIMESTAMP),
  '{$data['action']}',
  '{$data['type']}',
  '{$data['iso']}',
  '{$data['country']}'
  )");
}

//---------------------------------------------------
// Invoice number
//---------------------------------------------------

function mswSaleInvoiceNumber($num) {
  $zeros = '';
  if (MIN_INVOICE_DIGITS > 0 && MIN_INVOICE_DIGITS > strlen($num)) {
    for ($i = 0; $i < MIN_INVOICE_DIGITS - strlen($num); $i++) {
      $zeros .= 0;
    }
  }
  return ($zeros . $num);
}

//-----------------------------------
// Table truncation routine..
//-----------------------------------

function mswTableTruncationRoutine($tables = array(), $DB, $force = false) {
  if (!empty($tables)) {
    foreach ($tables AS $t) {
      if ($DB->db_rowcount($t) == 0 || $force) {
        $DB->db_query("TRUNCATE TABLE `" . DB_PREFIX . $t . "`");
      }
    }
  }
}

//---------------------------------------------------
// JS Data Filters
//---------------------------------------------------

function mswJSFilters($data) {
  return str_replace("'", "\'", $data);
}

//---------------------------------------------------
// Cleans output data..
//---------------------------------------------------

function mswCleanData($data) {
  if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    $sybase = strtolower(@ini_get('magic_quotes_sybase'));
    if (empty($sybase) || $sybase == 'off') {
      // Fixes issue of new line chars not parsing between single quotes..
      $data = str_replace('\n', '\\\n', $data);
      return stripslashes($data);
    }
  }
  return $data;
}

//---------------------------------------------------
// Re-enable site
//---------------------------------------------------

function mswSiteOnline($db_obj) {
  $db_obj->db_query("UPDATE `" . DB_PREFIX . "settings` SET
  `sysstatus`  = 'yes',
  `autoenable` = '0'
  ");
}

//---------------------------------------------------
// Cleans output data with character entities..
//---------------------------------------------------

function mswSafeDisplay($data) {
  $data = htmlspecialchars($data);
  $data = str_replace('&amp;#', '&#', $data);
  $data = str_replace('&amp;amp;', '&amp;', $data);
  return mswCleanData($data);
}

//---------------------------------------------------
// Clean CSV
//---------------------------------------------------

function mswCleanCSV($data, $del) {
  return '"' . mswCleanData($data) . '"';
}

//---------------------------------------------------
// Prepare CSV Array
//---------------------------------------------------

function mswPrepCSV($data) {
  return "'{$data}'";
}

//---------------------------------------------------
// Cleans output data without amendments..
//---------------------------------------------------

function mswCleanRawData($data) {
  if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    $sybase = strtolower(@ini_get('magic_quotes_sybase'));
    if (empty($sybase) || $sybase == 'off') {
      // Fixes issue of new line chars not parsing between single quotes..
      $data = str_replace('\n', '\\\n', $data);
      return stripslashes($data);
    }
  }
  return $data;
}

//---------------------------------------------------
// Format currency display..
//---------------------------------------------------

function mswCurrencyFormat($p, $f) {
  return str_replace('{AMOUNT}', mswFormatPrice($p), $f);
}

//---------------------------------------------------
// Format price..
//---------------------------------------------------

function mswFormatPrice($price, $comma = false) {
  $sep = '';
  if (PRICE_THOUSANDS_SEPARATORS && $comma) {
    $sep = PRICE_THOUSANDS_SEPARATORS;
  }
  $price = @number_format($price, 2, '.', '');
  $price = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $price));
  if (substr($price, -3, 1) == '.') {
    $pennies = '.' . substr($price, -2);
    $price   = substr($price, 0, strlen($price) - 3);
  } elseif (substr($price, -2, 1) == '.') {
    $pennies = '.' . substr($price, -1);
    $price   = substr($price, 0, strlen($price) - 2);
  } else {
    $pennies = '.00';
  }
  $price = preg_replace("/[^0-9]/", "", $price);
  // Prevent formatting errors during imports..
  if ($price == '') {
    $price = '0';
  }
  if (rtrim($pennies, '.') == '') {
    $pennies = '.00';
  }
  return ($price . $pennies > 0 ? @number_format($price . $pennies, 2, '.', ($sep ? $sep : '')) : '0.00');
}

//---------------------------------------------------
// Generates 60 character product key..
//---------------------------------------------------

function mswGenerateProductKey() {
  $_SERVER['HTTP_HOST']   = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : uniqid(rand(), 1));
  $_SERVER['REMOTE_ADDR'] = (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : uniqid(rand(), 1));
  if (function_exists('sha1')) {
    $c1      = sha1($_SERVER['HTTP_HOST'] . date('YmdHis') . $_SERVER['REMOTE_ADDR'] . time());
    $c2      = sha1(uniqid(rand(), 1) . time());
    $prodKey = substr($c1 . $c2, 0, 60);
  } elseif (function_exists('md5')) {
    $c1      = md5($_SERVER['HTTP_POST'] . date('YmdHis') . $_SERVER['REMOTE_ADDR'] . time());
    $c2      = md5(uniqid(rand(), 1), time());
    $prodKey = substr($c1 . $c2, 0, 60);
  } else {
    $c1      = str_replace('.', '', uniqid(rand(), 1));
    $c2      = str_replace('.', '', uniqid(rand(), 1));
    $c3      = str_replace('.', '', uniqid(rand(), 1));
    $prodKey = substr($c1 . $c2 . $c3, 0, 60);
  }
  return strtoupper($prodKey);
}

//---------------------------------------------------
// Define new line per op system..
//---------------------------------------------------

function mswDefineNewline() {
  if (defined('PHP_EOL')) {
    return PHP_EOL;
  }
  if (isset($_SERVER["HTTP_USER_AGENT"]) && strstr(strtolower($_SERVER["HTTP_USER_AGENT"]), 'win')) {
    $newline = "\r\n";
  } else if (isset($_SERVER["HTTP_USER_AGENT"]) && strstr(strtolower($_SERVER["HTTP_USER_AGENT"]), 'mac')) {
    $newline = "\r";
  } else {
    $newline = "\n";
  }
  return $newline;
}

//---------------------------------------------------
// File size..
//---------------------------------------------------

function mswFileSizeConversion($size = 0, $precision = 2) {
  if ($size > 0) {
    $base     = log($size) / log(1024);
    $suffixes = array(
      'Bytes',
      'KB',
      'MB',
      'GB',
      'TB'
    );
    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
  } else {
    return '0Bytes';
  }
}

//---------------------------------------------------
// SSL Detection
//---------------------------------------------------

function mswSSL() {
  return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'yes' : 'no');
}

//---------------------------------------------------
// Gets visitor IP address..
//---------------------------------------------------

function mswIPAddr($ar = false) {
  $ip = array();
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip[] = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== FALSE) {
      $split = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
      foreach ($split AS $value) {
        $ip[] = $value;
      }
    } else {
      $ip[] = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
  } else {
    $ip[] = $_SERVER['REMOTE_ADDR'];
  }
  if ($ar) {
    return $ip;
  }
  return (!empty($ip) ? implode(',', $ip) : '');
}

//---------------------------------------------------
// Returns encrypted data..
//---------------------------------------------------

function mswEncrypt($data) {
  return (function_exists('sha1') ? sha1($data) : md5($data));
}

//---------------------------------------------------
// Safe mysql import..
//---------------------------------------------------

function mswSafeImport($data, $obj) {
  if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    $sybase = strtolower(@ini_get('magic_quotes_sybase'));
    if (empty($sybase) || $sybase == 'off') {
      $data = mswMultiDimensionalArrayMap('stripslashes', $data, $obj);
    } else {
      $data = mswMultiDimensionalArrayMap('removeDoubleApostrophes', $data, $obj);
    }
  }
  $data = mswMultiDimensionalArrayMap('escapeString', $data, $obj);
  return $data;
}

//---------------------------------------------------
// Safe import string..
//---------------------------------------------------

function mswSafeString($data, $obj) {
  if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    $sybase = strtolower(@ini_get('magic_quotes_sybase'));
    if (empty($sybase) || $sybase == 'off') {
      $data = stripslashes($data);
    } else {
      $data = mswRemoveDoubleApostrophes($data);
    }
  }
  return $obj->db_escape($data);
}

//---------------------------------------------------
// Clean double quotes
//---------------------------------------------------

function mswRemoveDoubleApostrophes($data) {
  return str_replace("''", "'", $data);
}

//---------------------------------------------------
// Recursive way of handling multi dimensional arrays..
//---------------------------------------------------

function mswMultiDimensionalArrayMap($func, $arr, $obj = '') {
  $newArr = array();
  if (!empty($arr)) {
    foreach ($arr AS $key => $value) {
      switch($func) {
        case 'escapeString':
          $newArr[$key] = (is_array($value) ? mswMultiDimensionalArrayMap($func, $value, $obj) : $obj->db_escape($value));
          break;
        default:
          $newArr[$key] = (is_array($value) ? mswMultiDimensionalArrayMap($func, $value, $obj) : $func($value));
          break;
      }
    }
  }
  return $newArr;
}

//---------------------------------------------------
// Theme loader
//---------------------------------------------------

function mswThemeLoader($folder) {
  return (is_dir(MM_BASE_PATH . 'content/' . $folder) ? $folder : '_theme_default');
}

//---------------------------------------------------
// Schema check for manual DB dump
//---------------------------------------------------

function mswSchemaCheck($path, $obj, $admin = false) {
  if ($path == 'FirstRun') {
    $root = 'http://www.example.com/music-store';
    $key  = mswGenerateProductKey();
    if (isset($_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF'])) {
      if ($admin) {
        $root  = 'http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's' : '').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],'admin')-1).'/';
      } else {
        $root  = 'http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's' : '').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,-10).'/';
      }
    }
    $obj->db_query("UPDATE `".DB_PREFIX."settings` SET
    `httppath`  = '{$root}',
    `prodkey`   = '{$key}'
    ");
    header("Location: index.php");
    exit;
  }
}

//---------------------------------------------------
// Forbidden
//---------------------------------------------------

function mswEcode($msg, $code) {
  switch ($code) {
    case '200':
      header('HTTP/1.0 200 OK');
      break;
    case '400':
      header('HTTP/1.0 400 Bad Request');
      break;
    case '403':
      header('HTTP/1.0 403 Forbidden');
      break;
    case '404':
      header('HTTP/1.0 404 Not Found');
      break;
  }
  header('content-type: text/plain; charset=utf-8');
  echo $msg;
  exit;
}

//-----------------------------------------------
// Controller
//-----------------------------------------------

function mswfileController() {
  if (!file_exists(MM_BASE_PATH . 'control/system/core/sys-controller.php')) {
    die('[FATAL ERROR] The "control/system/core/sys-controller.php" file does NOT exist in your installation. It may have been auto deleted by your anti virus software. If
    this is the case, this is a false positive. Please add the file to your anti virus whitelist, re-add and refresh page.');
  }
}

//---------------------------------------------------
// Global filtering on post and get input..
//---------------------------------------------------

$_GET  = mswMultiDimensionalArrayMap('htmlspecialchars', $_GET);
$_POST = mswMultiDimensionalArrayMap('trim', $_POST);

?>