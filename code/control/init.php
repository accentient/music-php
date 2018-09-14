<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4], '403');
}

//---------------------------------------------------
// Load language files..
//---------------------------------------------------

include(PATH . 'content/language/global.php');
include(PATH . 'content/language/js.php');
include(PATH . 'content/language/public.php');
include(PATH . 'content/language/checkout.php');

//---------------------------------------------------
// Load include files..
//---------------------------------------------------

include(PATH . 'control/constants.php');
include(PATH . 'control/functions.php');

//----------------------------
// Savant template engine..
//----------------------------

include(PATH . 'control/lib/Savant3.php');

//---------------------------------------------------
// Database connection..
//---------------------------------------------------

include(PATH . 'control/connect.php');
include(PATH . 'control/classes/class.db.php');

$DB = new db();
$DB->db_conn();

//---------------------------------------------------
// Load class files..
//---------------------------------------------------

mswfileController();
include(PATH . 'control/timezones.php');
include(PATH . 'control/system/core/sys-controller.php');
include(PATH . 'control/classes/class.cost.php');
include(PATH . 'control/classes/class.datetime.php');
include(PATH . 'control/classes/class.page.php');
include(PATH . 'control/classes/class.accounts.php');
include(PATH . 'control/classes/class.cart.php');
include(PATH . 'control/classes/class.seo.php');
include(PATH . 'control/classes/class.store-builder.php');

//---------------------------------------------------
// Declare and initialise..
//---------------------------------------------------

$DT      = new mmDateTime();
$ACC     = new accPublic();
$CART    = new cart();
$SEO     = new seo();
$BUILDER = new storeBuilder();
$COSTING = new costing();

//---------------------------------------------------
// Load settings..
// Pass class objects to other classes..
//---------------------------------------------------

$Q = $DB->db_query("SELECT * FROM `" . DB_PREFIX . "settings`", true);

if ($Q == 'err') {
  header("Location: install/index.php");
  exit;
}

$siteOffers        = $COSTING->offers();
$SETTINGS          = $DB->db_object($Q);
$DT->settings      = $SETTINGS;
$SEO->settings     = $SETTINGS;
$ACC->settings     = $SETTINGS;
$CART->settings    = $SETTINGS;
$CART->seo         = $SEO;
$CART->datetime    = $DT;
$CART->costing     = $COSTING;
$BUILDER->settings = $SETTINGS;
$BUILDER->seo      = $SEO;
$BUILDER->costing  = $COSTING;
$COSTING->offers   = $siteOffers;

//---------------------------------------------------
// Schema reload
//---------------------------------------------------

mswSchemaCheck($SETTINGS->httppath, $DB);

//---------------------------------------------------
// Theme loader
//---------------------------------------------------

define('THEME', mswThemeLoader($SETTINGS->theme));

//---------------------------------------------------
// Default vars..
//---------------------------------------------------

$cmd          = ($SETTINGS->rewrite == 'yes' ? $SEO->rewriteParam() : (isset($_GET['p']) ? $_GET['p'] : 'home'));
$page         = (isset($_GET['next']) && (int) $_GET['next'] > 0 ? (int) $_GET['next'] : '1');
$limit        = $page * PER_PAGE - (PER_PAGE);
$notify       = ($SETTINGS->emnotify ? unserialize($SETTINGS->emnotify) : array());
$eString      = array();
$title        = '';
$listFilters  = array();
$metaData     = array(
  mswSafeDisplay($SETTINGS->metadesc),
  mswSafeDisplay($SETTINGS->metakeys)
);
$pluginLoader = array();
$SEO->curPage = $page;
$SEO->cmd     = $cmd;
$og           = array();

//---------------------------------------------------
// Is user logged in?
//---------------------------------------------------

$loggedInUser = $ACC->login();
$systemAcc    = (is_array($loggedInUser) && isset($loggedInUser['id']) ? $loggedInUser : array());

//---------------------------------------------------
// Set timezone / timestamp
//---------------------------------------------------

$DT->setTimeZone((isset($loggedInUser['timezone']) ? $loggedInUser['timezone'] : $SETTINGS->timezone), $timezones);

// Load global constants..
include(PATH . 'control/system/constants.php');

//---------------------------------------------------
// Parameter overrides
//---------------------------------------------------

$cmd = mswParamOverRide($cmd, $SEO);

//---------------------------------------------------
// Is system offline?
//---------------------------------------------------

include(PATH . 'control/system/offline.php');

?>