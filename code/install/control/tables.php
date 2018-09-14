<?php

if (!defined('PARENT')) {
  header('HTTP/1.0 403 Forbidden');
  header('Content-type: text/plain; charset=utf-8');
  echo 'Permission Denied';
  exit;
}

$v       = (isset($_POST['mysqli_version']) ? $_POST['mysqli_version'] : 'MySQL5');
$engine  = (isset($_POST['mysqli_engine']) && in_array($_POST['mysqli_engine'],array('MyISAM','InnoDB')) ? $_POST['mysqli_engine'] : 'MyISAM');
$c       = $_POST['charset'];
$tableD  = array();

switch($v) {
  case 'MySQL4':
  if ($c) {
    $split      = explode('_',$c);
    $tableType  = 'DEFAULT CHARACTER SET '.$split[0].mswDefineNewline();
    $tableType .= 'COLLATE '.$c.mswDefineNewline();
  }
  $tableType .= 'TYPE = '.$engine;
  break;
  case 'MySQL5':
  if ($c) {
    $split      = explode('_',$c);
    $tableType  = 'CHARSET = '.$split[0].mswDefineNewline();
    $tableType .= 'COLLATE '.$c.mswDefineNewline();
  }
  $tableType .= 'ENGINE = '.$engine;
  break;
}

//============================================================
// INSTALL TABLE...ACCOUNTS..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."accounts`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."accounts` (
  `id` int(10) unsigned not null auto_increment,
  `name` varchar(250) not null default '',
  `email` varchar(250) not null default '',
  `pass` varchar(40) not null default '',
  `ip` varchar(250) not null default '',
  `ts` int(30) not null default '0',
  `enabled` enum('yes','no') not null default 'yes',
  `system1` varchar(250) not null default '',
  `system2` varchar(250) not null default '',
  `notes` text default null,
  `timezone` varchar(50) not null default '',
  `country` int(5) not null default '183',
  `shipping` int(5) not null default '0',
  `token` varchar(50) not null default '',
  `bypass` enum('yes','no') not null default 'no',
  `login` enum('yes','no') not null default 'yes',
  PRIMARY KEY (`id`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'accounts';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'accounts',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...ACCOUNTS ADDRESS..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."accounts_addr`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."accounts_addr` (
  `id` int(10) unsigned not null auto_increment,
  `account` int(30) not null default '0',
  `address1` varchar(250) not null default '',
  `address2` varchar(250) not null default '',
  `city` varchar(250) not null default '',
  `county` varchar(250) not null default '',
  `postcode` varchar(250) not null default '',
  `country` int(5) not null default '183',
  `default` enum('yes','no') not null default 'yes',
  PRIMARY KEY (`id`),
  KEY `acc` (`account`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'accounts_addr';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'accounts_addr',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...ACCOUNTS LOGIN HISTORY..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."accounts_login`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."accounts_login` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`account` INT(7) NOT NULL DEFAULT '0',
	`ip` VARCHAR(250) NOT NULL DEFAULT '',
	`ts` INT(30) NOT NULL DEFAULT '0',
	`iso` CHAR(2) NOT NULL DEFAULT '',
	`country` VARCHAR(250) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`),
	KEY `accid` (`account`),
  KEY `accip` (`ip`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'accounts_login';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'accounts_login',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...API..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."api`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."api` (
  `id` INT(5) NOT NULL AUTO_INCREMENT,
  `desc` VARCHAR(50) NOT NULL DEFAULT '',
  `param` TEXT NOT NULL,
  `value` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `descK` (`desc`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'api';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'api',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...COLLECTIONS..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."collections`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."collections` (
  `id` int(7) not null auto_increment,
  `name` varchar(250) not null default '',
  `title` text default null,
  `metakeys` text default null,
  `metadesc` text default null,
  `slug` varchar(50) not null default '',
  `information` text default null,
  `searchtags` text default null,
  `social` text default null,
  `coverart` text default null,
  `coverartother` text default null,
  `enabled` enum('yes','no') not null default 'yes',
  `views` int(10) unsigned not null default '0',
  `released` int(30) not null default '0',
  `catnumber` varchar(200) not null default '',
  `cost` varchar(10) not null default '',
  `costcd` varchar(10) not null default '',
  `added` int(30) not null default '0',
  `updated` int(30) not null default '0',
  `related` text default null,
  `length` varchar(10) not null default '',
  `bitrate` varchar(100) not null default '',
  PRIMARY KEY (`id`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'collections';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'collections',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...COLLECTION STYLES..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."collection_styles`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."collection_styles` (
  `id` int(4) unsigned not null auto_increment,
  `style` int(8) not null default '0',
  `collection` int(8) not null default '0',
  PRIMARY KEY (`id`),
  KEY `style` (`style`),
  KEY `col` (`collection`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'collection_styles';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'collection_styles',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...COUNTRIES..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."countries`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."countries` (
  `id` int(4) unsigned not null auto_increment,
  `name` varchar(250) not null default '',
  `iso` varchar(3) not null default '',
  `iso2` char(2) not null default '',
  `iso4217` varchar(50) not null default '0',
  `tax` char(2) not null default '',
  `tax2` char(2) not null default '',
  `display` enum('yes','no') not null default 'yes',
  `eu` enum('yes','no') not null default 'no',
  PRIMARY KEY (`id`),
  KEY `iso` (`iso`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'countries';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'countries',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...COUPONS..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."coupons`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."coupons` (
  `id` int(10) unsigned not null auto_increment,
  `code` varchar(30) not null default '',
  `discount` varchar(10) not null default '',
  `expiry` int(30) not null default '0',
  `enabled` enum('yes','no') not null default 'yes',
  `accounts` text default null,
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'coupons';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'coupons',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...GATEWAYS..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."gateways`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."gateways` (
  `id` int(3) not null auto_increment,
  `display` varchar(100) not null default '',
  `liveserver` varchar(250) not null default '',
  `sandboxserver` varchar(250) not null default '',
  `image` varchar(100) not null default '',
  `webpage` varchar(100) not null default '',
  `status` enum('yes','no') not null default 'yes',
  `class` varchar(100) not null default '',
  `default` enum('yes','no') not null default 'no',
  PRIMARY KEY (`id`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'gateways';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'gateways',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...GATEWAY PARAMS..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."gateways_params`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."gateways_params` (
  `id` int(3) not null auto_increment,
  `gateway` int(6) not null default '0',
  `param` varchar(200) not null default '',
  `value` varchar(250) not null default '',
  PRIMARY KEY (`id`),
  KEY `mthd_index` (`gateway`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'gateways_params';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'gateways_params',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...GEO IPV4..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."geo_ipv4`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."geo_ipv4` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`from_ip` VARCHAR(100) NOT NULL DEFAULT '',
	`to_ip` VARCHAR(100) NOT NULL DEFAULT '',
	`loc_start` VARCHAR(100) NOT NULL DEFAULT '0',
	`loc_end` VARCHAR(100) NOT NULL DEFAULT '0',
	`country_iso` CHAR(2) NOT NULL DEFAULT '',
	`country` VARCHAR(100) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'geo_ipv4';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'geo_ipv4',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...GEO IPV6..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."geo_ipv6`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."geo_ipv6` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`from_ip` VARCHAR(100) NOT NULL DEFAULT '',
	`to_ip` VARCHAR(100) NOT NULL DEFAULT '',
	`loc_start` VARCHAR(100) NOT NULL DEFAULT '0',
	`loc_end` VARCHAR(100) NOT NULL DEFAULT '0',
	`country_iso` CHAR(2) NOT NULL DEFAULT '',
	`country` VARCHAR(100) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'geo_ipv6';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'geo_ipv6',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...MUSIC..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."music`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."music` (
  `id` int(9) not null auto_increment,
  `title` varchar(250) not null default '',
  `collection` int(7) not null default '0',
  `mp3file` text default null,
  `previewfile` text default null,
  `length` varchar(10) not null default '',
  `bitrate` varchar(100) not null default '',
  `samplerate` varchar(100) not null default '',
  `cost` varchar(10) not null default '',
  `order` int(9) not null default '0',
  `ts` int(30) not null default '0',
  `updated` int(30) not null default '0',
  PRIMARY KEY (`id`),
  KEY `col` (`collection`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'music';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'music',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...MUSIC STYLES..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."music_styles`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."music_styles` (
  `id` int(4) unsigned not null auto_increment,
  `name` varchar(250) not null default '',
  `slug` varchar(50) not null default '',
  `enabled` enum('yes','no') not null default 'yes',
  `type` int(10) not null default '0',
  `orderby` varchar(10) not null default '0',
  `collection` int(7) not null default '0',
  PRIMARY KEY (`id`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'music_styles';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'music_styles',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...OFFERS..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."offers`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."offers` (
  `id` int(10) unsigned not null auto_increment,
  `discount` varchar(10) not null default '',
  `expiry` int(30) not null default '0',
  `type` enum('collections','tracks','all','cd') not null default 'collections',
  `collections` text default null,
  `enabled` enum('yes','no') not null default 'yes',
  PRIMARY KEY (`id`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'offers';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'offers',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...PAGES..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."pages`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."pages` (
  `id` int(7) not null auto_increment,
  `name` varchar(250) not null default '',
  `info` text default null,
  `keys` text default null,
  `desc` text default null,
  `title` text default null,
  `orderby` int(5) not null default '0',
  `template` varchar(250) not null default '',
  `landing` enum('yes','no') not null default 'no',
  `slug` varchar(250) not null default '',
  `enabled` enum('yes','no') not null default 'no',
  PRIMARY KEY (`id`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'pages';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'pages',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...SALES..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."sales`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."sales` (
  `id` int(9) not null auto_increment,
  `invoice` varchar(64) not null default '',
  `account` int(8) not null default '0',
  `ip` text default null,
  `iso` char(2) not null default '',
  `ts` int(30) not null default '0',
  `gateway` int(11) not null default '0',
  `paytotal` varchar(20) not null default '0.00',
  `subtotal` varchar(20) not null default '0.00',
  `shipping` varchar(10) not null default '0.00',
  `tax` varchar(10) not null default '0.00',
  `tax2` varchar(10) not null default '0.00',
  `taxRate` varchar(10) not null default '0',
  `taxRate2` varchar(10) not null default '0',
  `taxCountry` int(3) not null default '0',
  `taxCountry2` int(3) not null default '0',
  `shipID` int(6) not null default '0',
  `shippingAddr` text default null,
  `transaction` varchar(250) not null default '',
  `locked` enum('yes','no') not null default 'no',
  `lockreason` text default null,
  `enabled` enum('yes','no') not null default 'no',
  `status` varchar(250) not null default '',
  `notes` text default null,
  `code` varchar(40) not null default '',
  `refcode` varchar(250) not null default '',
  `gateparams` text default null,
  `coupon` text default null,
  `sys1` varchar(200) not null default '',
  `sys2` varchar(200) not null default '',
  PRIMARY KEY (`id`),
  KEY `acc` (`account`),
  KEY `code` (`code`),
  KEY `iso` (`iso`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'sales';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'sales',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...SALES CLICK HISTORY..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."sales_click`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."sales_click` (
  `id` int(7) unsigned not null auto_increment,
  `sale` int(7) not null default '0',
  `trackcol` int(7) not null default '0',
  `ip` varchar(250) not null default '',
  `ts` int(30) not null default '0',
  `action` varchar(250) not null default '',
  `type` enum('admin','visitor') not null default 'visitor',
  `iso` char(2) not null default '',
  `country` varchar(250) not null default '',
  PRIMARY KEY (`id`),
  KEY `saleid_index` (`sale`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'sales_click';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'sales_click',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...SALES CLIPBOARD..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."sales_clipboard`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."sales_clipboard` (
  `id` int(10) unsigned not null auto_increment,
  `trackcol` int(7) not null default '0',
  `type` enum('track','collection') not null default 'track',
  `physical` enum('yes','no') not null default 'no',
  PRIMARY KEY (`id`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'sales_clipboard';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'sales_clipboard',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...SALES ITEMS..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."sales_items`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."sales_items` (
  `id` int(9) not null auto_increment,
  `sale` int(9) not null default '0',
  `item` int(9) not null default '0',
  `collection` int(9) not null default '0',
  `type` enum('collection','track','none') not null default 'none',
  `physical` enum('yes','no') not null default 'no',
  `expiry` int(30) not null default '0',
  `cost` varchar(10) not null default '0.00',
  `clicks` int(30) not null default '0',
  `token` varchar(40) not null default '',
  PRIMARY KEY (`id`),
  KEY `sale` (`sale`),
  KEY `item` (`item`),
  KEY `col` (`collection`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'sales_items';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'sales_items',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...SETTINGS..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."settings`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."settings` (
  `id` tinyint(1) unsigned not null auto_increment,
  `website` varchar(100) not null default '',
  `email` text default null,
  `httppath` varchar(250) not null default '',
  `secfolder` varchar(250) not null default '',
  `dateformat` varchar(20) not null default '',
  `timeformat` varchar(10) not null default '',
  `jsformat` varchar(15) not null default 'DD-MM-YYYY',
  `timezone` varchar(50) not null default '',
  `weekstart` enum('sun','mon') not null default 'sun',
  `zip` enum('yes','no') not null default 'yes',
  `rewrite` enum('yes','no') not null default 'no',
  `paymode` enum('test','live') not null default 'test',
  `responselog` enum('yes','no') not null default 'yes',
  `propend` enum('yes','no') not null default 'no',
  `version` varchar(10) not null default '',
  `prodkey` varchar(60) not null default '',
  `smtp_host` varchar(100) not null default 'localhost',
  `smtp_user` varchar(100) not null default '',
  `smtp_pass` varchar(100) not null default '',
  `smtp_port` varchar(10) not null default '25',
  `smtp_from` varchar(250) not null default '',
  `smtp_email` varchar(250) not null default '',
  `smtp_security` varchar(5) not null default '',
  `smtp_other` text default null,
  `sysstatus` enum('yes','no') not null default 'no',
  `autoenable` int(30) not null default '0',
  `reason` text default null,
  `allowip` text default null,
  `currency` char(3) not null default 'GBP',
  `invoice` int(8) not null default '0',
  `curdisplay` varchar(250) not null default '',
  `access` varchar(200) not null default '',
  `afoot` text default null,
  `pfoot` text default null,
  `theme` varchar(250) not null default '_theme_default',
  `featured` text default null,
  `metakeys` text default null,
  `metadesc` text default null,
  `minpass` int(5) not null default '8',
  `emnotify` text default null,
  `deftax` tinyint(2) not null default '0',
  `deftax2` tinyint(2) not null default '0',
  `defCountry` int(3) not null default '0',
  `defCountry2` int(3) not null default '0',
  `resip` enum('yes','no') not null default 'no',
  `facebook` enum('yes','no') not null default 'yes',
  `social` text default null,
  `licsubj` varchar(100) not null default '',
  `licmsg` text default null,
  `licenable` enum('yes','no') not null default 'no',
  `maxupdate` int(30) not null default '0',
  `geoip` enum('yes','no') not null default 'yes',
  `statistics` text default null,
  `minpurchase` varchar(20) not null default '',
  `cdpur` enum('yes','no') not null default 'no',
  `rss` enum('yes','no') not null default 'yes',
  `hideparams` enum('yes','no') not null default 'no',
  `acclogin` enum('yes','no') not null default 'yes',
  `accloginflag` int(5) not null default '0',
  `termsmsg` text default null,
  `termsenable` enum('yes','no') not null default 'no',
  PRIMARY KEY (`id`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'settings';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'settings',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...SHIPPING..
//============================================================

$DB->db_query("DROP TABLE IF EXISTS `".DB_PREFIX."shipping`");
$query = $DB->db_query("
CREATE TABLE `".DB_PREFIX."shipping` (
  `id` int(10) unsigned not null auto_increment,
  `name` varchar(250) not null default '',
  `cost` varchar(250) not null default '',
  PRIMARY KEY (`id`)
) $tableType",true);

if ($query==='err') {
  $tableD[]  = DB_PREFIX.'shipping';
  $ERR       = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'shipping',$ERR[1],$ERR[0],__LINE__,__FILE__);
}

?>