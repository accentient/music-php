<?php

//------------------------------------------------------------------------------
// ADMIN SCRIPT LOADER
// DO NOT change this file
//------------------------------------------------------------------------------

@session_start();

// PREVENT DATE ERRORS
date_default_timezone_set('UTC');

define('PATH', dirname(__FILE__) . '/');
define('REL_PATH', '../');
define('PARENT', 1);

// DEFINED..
include(REL_PATH . 'control/defined.php');

// INITIALISE..
include(PATH . 'control/init.php');

// ERROR REPORTING..
include(REL_PATH . 'control/classes/class.errors.php');
if (ERR_HANDLER_ENABLED) {
  register_shutdown_function('msFatalErr');
  set_error_handler('msErrorhandler');
}

// LOAD..
if (file_exists(PATH . 'control/system/' . $cmd . '.php')) {
  include(PATH . 'control/system/' . $cmd . '.php');
} else {
  mswEcode($gblang[4], '403');
}

?>