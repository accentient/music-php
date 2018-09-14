<?php

//------------------------------------------------------------------------------
// SCRIPT LOADER
// DO NOT change this file
//------------------------------------------------------------------------------

@session_start();

// PREVENT DATE ERRORS
date_default_timezone_set('UTC');

define('PATH', dirname(__file__).'/');
define('PARENT',1);

// DEFINED..
include(PATH.'control/defined.php');

// ERROR REPORTING..
include(dirname(__file__).'/control/classes/class.errors.php');
if (ERR_HANDLER_ENABLED) {
  register_shutdown_function('msFatalErr');
  set_error_handler('msErrorhandler');
}

// LOAD..
include(PATH.'control/init.php');

// RUN..
if ((in_array($cmd, mswAcceptedParams()) || substr($cmd,0,3) == 'rss') && file_exists(PATH.'control/system/'.$cmd.'.php')) {
  // Clear the search filters..
  if (!in_array($cmd, array('search','latest','popular','style'))) {
    if (isset($_SESSION['mmFilters'])) {
      $_SESSION['mmFilters'] = '';
      unset($_SESSION['mmFilters']);
    }
  }
  include(PATH.'control/system/'.$cmd.'.php');
} else {
  mswEcode($gblang[4],'403');
}


?>