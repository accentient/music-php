<?php

define('ADMIN_PANEL', 1);

if (!defined('PARENT')) {
  header('HTTP/1.0 403 Forbidden');
  header('Content-type: text/plain; charset=utf-8');
  echo 'Forbidden';
  exit;
}

//---------------------------------------------------
// Load language files..
//---------------------------------------------------

include(REL_PATH . 'content/language/global.php');
include(REL_PATH . 'content/language/js.php');
include(REL_PATH . 'content/language/admin.php');

//---------------------------------------------------
// Load include files..
//---------------------------------------------------

include(REL_PATH . 'control/constants.php');
include(REL_PATH . 'control/functions.php');
include(REL_PATH . 'control/timezones.php');

//---------------------------------------------------
// Database connection..
//---------------------------------------------------

include(REL_PATH . 'control/connect.php');
include(REL_PATH . 'control/classes/class.db.php');

$DB = new db();
$DB->db_conn();

//---------------------------------------------------
// Load class files..
//---------------------------------------------------

mswfileController();
include(REL_PATH . 'control/system/core/sys-controller.php');
include(REL_PATH . 'control/classes/class.datetime.php');
include(REL_PATH . 'control/classes/class.page.php');
include(PATH . 'control/classes/class.settings.php');
include(PATH . 'control/classes/class.gateways.php');
include(PATH . 'control/classes/class.music.php');
include(PATH . 'control/classes/class.sales.php');
include(PATH . 'control/classes/class.accounts.php');
include(REL_PATH . 'control/classes/class.json.php');
include(REL_PATH . 'control/classes/class.store-builder.php');

//---------------------------------------------------
// Declare and initialise..
//---------------------------------------------------

$SYS  = new mmSystem();
$MSC  = new music();
$JSON = new jsonHandler();
$PAY  = new gateways();
$SLS  = new sales();
$ACC  = new accounts();
$DT   = new mmDateTime();
$SBDR = new storeBuilder();

//---------------------------------------------------
// Load settings..
// Pass class objects to other classes..
//---------------------------------------------------

$Q = $DB->db_query("SELECT * FROM `" . DB_PREFIX . "settings`", false);

if ($Q == 'err') {
  header("Location: ../install/index.php");
  exit;
}

$SETTINGS      = $DB->db_object($Q);
$SYS->settings = $SETTINGS;
$SYS->datetime = $DT;
$MSC->settings = $SETTINGS;
$MSC->datetime = $DT;
$PAY->settings = $SETTINGS;
$SLS->settings = $SETTINGS;
$SLS->datetime = $DT;
$ACC->settings = $SETTINGS;
$ACC->datetime = $DT;
$DT->settings  = $SETTINGS;

//---------------------------------------------------
// Schema reload
//---------------------------------------------------

mswSchemaCheck($SETTINGS->httppath, $DB, true);

//---------------------------------------------------
// Theme loader
//---------------------------------------------------

define('THEME', mswThemeLoader($SETTINGS->theme));

//---------------------------------------------------
// Set timezone / timestamp
//---------------------------------------------------

$DT->setTimeZone($SETTINGS->timezone, $timezones);

//---------------------------------------------------
// Default vars..
//---------------------------------------------------

$cmd      = (isset($_GET['p']) ? $_GET['p'] : 'home');
$page     = (isset($_GET['next']) && (int) $_GET['next'] > 0 ? $_GET['next'] : '1');
$limit    = $page * PER_PAGE - (PER_PAGE);
$eString  = array();
$tabIndex = 0;

//---------------------------------------------------
// Check login
//---------------------------------------------------

if (isset($_GET['ajax']) && $_GET['ajax'] == 'login') {
  $loginBypass = true;
}

if (!isset($_SESSION['mm_access_' . mswEncrypt(SECRET_KEY)]) && !isset($loginBypass)) {
  include(PATH . 'control/system/login.php');
}

//---------------------------------------------------
// Override
//---------------------------------------------------

if (isset($_GET['ajax'])) {
  $cmd = 'ajax';
}

?>