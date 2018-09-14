<?php

@session_start();

date_default_timezone_set('UTC');

@ini_set('memory_limit', '100M');
@set_time_limit(0);

define('PATH', dirname(__FILE__).'/');
define('INC', 1);
define('PARENT',1);
define('REL_PATH', substr(PATH,0,strpos(PATH,'install')-1).'/');

//---------------------------------------------------
// Error reporting
//---------------------------------------------------

include(REL_PATH.'control/classes/class.errors.php');
if (ERR_HANDLER_ENABLED) {
  register_shutdown_function('msFatalErr');
  set_error_handler('msErrorhandler');
}

include(REL_PATH.'control/defined.php');
include(REL_PATH.'control/constants.php');
include(REL_PATH.'content/language/global.php');
include(REL_PATH.'control/functions.php');
include(REL_PATH.'control/connect.php');
include(REL_PATH.'control/classes/class.datetime.php');
include(REL_PATH.'control/classes/class.db.php');

mswfileController();

$DB       = new db();
$DT       = new mmDateTime();
$DB->lang = $gblang;
$DB->db_conn();

include(PATH.'control/functions.php');

$cmd         = (isset($_GET['s']) ? $_GET['s'] : '1');
$title       = SCRIPT_NAME.': Installation';
$stages      = 4;
$perc_width  = ($cmd>1 ? ceil(($cmd-1)*(100/$stages)) : '0');
$progress    = ($cmd>1 ? ceil(($cmd-1)*(100/$stages)) : '0');

if (isset($_GET['connectionTest'])) {
  $cmd = 'test';
}

// Check if PHP version is too old..
if (phpVersion()<5 || !function_exists('file_get_contents')) {
  $cmd   = 'e';
  $code  = 'old';
  $type  = 'FATAL ERROR';
}

switch ($cmd) {
  case '1':
  include(PATH.'templates/header.php');
  include(PATH.'templates/1.php');
  include(PATH.'templates/footer.php');
  break;

  case '2':
  include(PATH.'templates/header.php');
  include(PATH.'templates/2.php');
  include(PATH.'templates/footer.php');
  break;

  case '3':
  include(PATH.'templates/header.php');
  include(PATH.'templates/3.php');
  include(PATH.'templates/footer.php');
  break;

  case '4':

  //Install tables..
  if (isset($_POST['tables'])) {
    include(PATH.'control/tables.php');
	if (empty($tableD)) {
	  include(PATH.'control/data.php');
	  if (empty($dataE)) {
	    header("Location: index.php?s=5");
		  exit;
	  }
	}
	header("Location: index.php?s=e&msg=tables");
    exit;
  }

  include(PATH.'control/controller.php');
  include(PATH.'templates/header.php');
  include(PATH.'templates/4.php');
  include(PATH.'templates/footer.php');
  break;

  case '5':

  include(PATH.'templates/header.php');
  include(PATH.'templates/5.php');
  include(PATH.'templates/footer.php');
  break;

  case 'e':

  if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
      case 'tables':
      $cmd   = 'e';
      $code  = 'tables';
      $type  = 'DB ERROR';
      break;
      case 'sdata':
      $cmd   = 'e';
      $code  = 'sdata';
      $type  = 'DB ERROR';
      break;
      case 'data':
      $cmd   = 'e';
      $code  = 'tables';
      $type  = 'DB ERROR';
      break;
    }
  } else {
    if (!isset($code)) {
      $_GET['msg'] = 'data';
      $cmd         = 'e';
      $code        = 'sdata';
      $type        = 'DB ERROR';
    }
  }

  include(PATH.'templates/header.php');
  include(PATH.'templates/error.php');
  include(PATH.'templates/footer.php');
  break;

  case 'test':
  echo $DB->db_test_conn(true);
  break;
}

?>