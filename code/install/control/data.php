<?php

if (!defined('PARENT')) {
  header('HTTP/1.0 403 Forbidden');
  header('Content-type: text/plain; charset=utf-8');
  echo 'Permission Denied';
  exit;
}

$dataE = array();

//=========================
// INSTALL SETTINGS
//=========================

$DB->db_query("TRUNCATE TABLE `".DB_PREFIX."settings`");
$thisLast  = date('Y',strtotime('-1 year')).','.date('Y');
$downloads = mswSafeString('a:8:{i:0;i:24;i:1;s:3:"hrs";i:2;i:5;i:3;s:3:"yes";i:4;s:2:"no";i:5;i:5;i:6;s:3:"yes";i:7;s:3:"tmp";}',$DB);
$notify    = mswSafeString('a:8:{s:7:"salecus";s:1:"1";s:7:"saleweb";s:1:"1";s:10:"salecuspen";s:1:"1";s:10:"salewebpen";s:1:"1";s:7:"cusprof";s:1:"1";s:7:"webprof";s:1:"1";s:5:"cuscr";s:1:"1";s:5:"webcr";s:1:"1";}',$DB);
$social    = mswSafeString('a:8:{s:2:"fb";s:0:"";s:2:"gg";s:0:"";s:2:"tw";s:0:"";s:2:"li";s:0:"";s:2:"yt";s:0:"";s:2:"sc";s:0:"";s:2:"sp";s:0:"";s:2:"fm";s:0:"";}',$DB);
$stats     = mswSafeString('a:4:{s:5:"years";s:9:"'.$thisLast.'";s:4:"best";s:2:"20";s:5:"month";s:4:"this";s:6:"legacy";s:2:"15";}',$DB);
$root      = 'http://www.example.com/music-store';
$now       = strtotime(date('Y-m-d H:i:s',$DT->utcTime()));
$zone      = 'Europe/London';
if (isset($_SERVER['HTTP_HOST'],$_SERVER['PHP_SELF'])) {
  $root  = 'http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's' : '').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],'install')-1).'/';
}
if (function_exists('date_default_timezone_get')) {
  $zone = date_default_timezone_get();
}
if ($zone == '' & @ini_get('date.timezone')) {
  $zone = @ini_get('date.timezone');
}
$query   = $DB->db_query("INSERT INTO `".DB_PREFIX."settings` (
`id`, `website`, `email`, `httppath`, `secfolder`, `dateformat`, `timeformat`, `jsformat`, `timezone`, `weekstart`,
`zip`, `rewrite`, `paymode`, `responselog`, `propend`, `version`, `prodkey`, `smtp_host`, `smtp_user`, `smtp_pass`,
`smtp_port`, `smtp_from`, `smtp_email`, `smtp_security`, `smtp_other`, `sysstatus`, `autoenable`, `reason`, `allowip`,
`currency`, `invoice`, `curdisplay`, `access`, `afoot`, `pfoot`, `theme`, `featured`, `metakeys`, `metadesc`, `minpass`,
`emnotify`, `deftax`, `resip`, `facebook`, `social`, `licsubj`, `licmsg`, `licenable`, `maxupdate`, `geoip`, `statistics`,
`minpurchase`, `cdpur`, `rss`, `hideparams`
) VALUES (
1, 'Music Store', 'email@example.com', '{$root}', '', 'j M Y', 'H:i:s', 'DD-MM-YYYY', '{$zone}', 'sun', 'no', 'no',
'test', 'yes', 'no', '".SCRIPT_VERSION."', '{$prodKey}', '', '', '', '587', '', '', '', '', 'yes', 0, '', '', 'GBP', 0, '&pound;{AMOUNT}',
'{$downloads}', '', '', '_theme_default', '', '', '', 8, '{$notify}', 0, 'no',
'yes', '{$social}', '[Music Store] License Agreement - Please Read', '', 'yes', '{$now}', 'yes', '{$stats}', '', 'no', 'yes', 'no')",true);

if ($query==='err') {
  $dataE[]  = DB_PREFIX.'settings';
  $ERR      = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'settings',$ERR[1],$ERR[0],__LINE__,__FILE__,'Insert');
}

//=========================
// INSTALL COUNTRIES
//=========================

$DB->db_query("TRUNCATE TABLE `".DB_PREFIX."countries`");
$data   = str_replace('{prefix}', DB_PREFIX, file_get_contents(PATH.'control/sql/countries.sql'));
$query  = $DB->db_query("{$data}",true);
if ($query==='err') {
  $dataE[]  = DB_PREFIX.'countries';
  $ERR      = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'countries',$ERR[1],$ERR[0],__LINE__,__FILE__,'Insert');
} else {
  include(PATH.'control/sql/eu-countries.php');
}

//=========================
// INSTALL STYLES
//=========================

if (isset($_POST['styles'])) {
  $DB->db_query("TRUNCATE TABLE `".DB_PREFIX."music_styles`");
  $query  = $DB->db_query("INSERT INTO `".DB_PREFIX."music_styles` (`id`, `name`, `slug`, `enabled`, `type`, `orderby`) VALUES
	(1, 'Electronic', '', 'yes', '0', '01'),
	(2, 'Instrumental', '', 'yes', '0', '02'),
	(3, 'Classical', '', 'yes', '0', '03'),
	(4, 'Country', '', 'yes', '0', '04'),
	(5, 'World Music', '', 'yes', '0', '05'),
	(6, 'Easy Listening', '', 'yes', '0', '06'),
	(7, 'Singer/Songwriter', '', 'yes', '6', '01'),
	(8, 'House Music', '', 'yes', '1', '01'),
	(9, 'New Age', '', 'yes', '2', '01'),
	(10, 'Pop Music', '', 'yes', '1', '02'),
	(11, 'Soundtracks', '', 'yes', '2', '02'),
	(12, 'Japanese Pop', '', 'yes', '5', '01'),
	(13, 'Traditional', '', 'yes', '4', '01'),
	(14, 'Techno/Trance', '', 'yes', '1', '03'),
	(15, 'Korean Pop', '', 'yes', '5', '02'),
  (16, '80s Electro', '', 'yes', '1', '03'),
  (17, 'Chinese Pop', '', 'yes', '5', '03'),
  (18, 'Ambient', '', 'yes', '2', '03')",true);
  if ($query==='err') {
    $dataE[]  = DB_PREFIX.'music_styles';
    $ERR      = $DB->db_error(true);
    ins_logDBError(DB_PREFIX.'music_styles',$ERR[1],$ERR[0],__LINE__,__FILE__,'Insert');
  }
}

//=========================
// INSTALL GATEWAYS
//=========================

$DB->db_query("TRUNCATE TABLE `".DB_PREFIX."gateways`");
$data   = str_replace('{prefix}',DB_PREFIX,file_get_contents(PATH.'control/sql/gateways.sql'));
$query  = $DB->db_query("{$data}",true);
if ($query==='err') {
  $dataE[]  = DB_PREFIX.'gateways';
  $ERR      = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'gateways',$ERR[1],$ERR[0],__LINE__,__FILE__,'Insert');
}

//=========================
// INSTALL GATEWAY PARAMS
//=========================

$DB->db_query("TRUNCATE TABLE `".DB_PREFIX."gateways_params`");
$data   = str_replace('{prefix}',DB_PREFIX,file_get_contents(PATH.'control/sql/gateways-params.sql'));
$query  = $DB->db_query("{$data}",true);
if ($query==='err') {
  $dataE[]  = DB_PREFIX.'gateways_params';
  $ERR      = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'gateways_params',$ERR[1],$ERR[0],__LINE__,__FILE__,'Insert');
}

//=========================
// INSTALL GEO IPV4
//=========================

$DB->db_query("TRUNCATE TABLE `".DB_PREFIX."geo_ipv4`");
$ipvfile = PATH.'control/sql/geoipv4.csv';
$query   = $DB->db_query("LOAD DATA LOCAL INFILE '" . mswSafeString($ipvfile, $DB) . "' INTO TABLE `" . DB_PREFIX . "geo_ipv4`
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '\"'
ESCAPED BY '\"'
LINES TERMINATED BY '\n'
IGNORE 0 LINES (`from_ip`, `to_ip`, `loc_start`, `loc_end`, `country_iso`, `country`)
",true);
if ($query==='err') {
  //$dataE[]  = DB_PREFIX.'geoipv4';
  $ERR      = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'geoipv4',$ERR[1],$ERR[0],__LINE__,__FILE__,'Insert');
}

//=========================
// INSTALL GEO IPV6
//=========================

$DB->db_query("TRUNCATE TABLE `".DB_PREFIX."geo_ipv6`");
$ipv6file  = PATH.'control/sql/geoipv6.csv';
$query   = $DB->db_query("LOAD DATA LOCAL INFILE '" . mswSafeString($ipv6file, $DB) . "' INTO TABLE `" . DB_PREFIX . "geo_ipv6`
FIELDS TERMINATED BY ', '
OPTIONALLY ENCLOSED BY '\"'
ESCAPED BY '\"'
LINES TERMINATED BY '\n'
IGNORE 0 LINES (`from_ip`, `to_ip`, `loc_start`, `loc_end`, `country_iso`, `country`)
",true);
if ($query==='err') {
  //$dataE[]  = DB_PREFIX.'geoipv6';
  $ERR      = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'geoipv6',$ERR[1],$ERR[0],__LINE__,__FILE__,'Insert');
}

//=========================
// INSTALL PAGES
//=========================

$DB->db_query("TRUNCATE TABLE `".DB_PREFIX."pages`");
$query  = $DB->db_query("INSERT INTO `".DB_PREFIX."pages` (`id`, `name`, `info`, `keys`, `desc`, `title`, `orderby`, `template`, `landing`, `slug`, `enabled`
) VALUES (
1, 'About Our Music', 'This is an additional page. Add your own text, edit or delete.', '', '', '', 1, '', 'no', 'about-page', 'yes'),
(2, 'Licence', 'This is an additional page. Add your own text, edit or delete.', '', '', '', 4, '', 'no', 'licence', 'yes'),
(3, 'Company Info', 'This is an additional page. Add your own text, edit or delete.', '', '', '', 3, '', 'no', 'company-info', 'yes'),
(4, 'Contact Us', '', '', '', 'How to Contact Us', 2, 'contact.tpl.php', 'no', 'contact', 'yes')",true);
if ($query==='err') {
  $dataE[]  = DB_PREFIX.'pages';
  $ERR      = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'pages',$ERR[1],$ERR[0],__LINE__,__FILE__,'Insert');
}

//=============================
// INSTALL SHIP ZONE EXAMPLES
//=============================

$DB->db_query("TRUNCATE TABLE `".DB_PREFIX."shipping`");
$query  = $DB->db_query("INSERT INTO `".DB_PREFIX."shipping` (`id`, `name`, `cost`) VALUES
(1, 'Europe', '1.00'),
(2, 'America', '10%'),
(3, 'Asia', '5.00')",true);
if ($query==='err') {
  $dataE[]  = DB_PREFIX.'shipping';
  $ERR      = $DB->db_error(true);
  ins_logDBError(DB_PREFIX.'shipping',$ERR[1],$ERR[0],__LINE__,__FILE__,'Insert');
}

?>
