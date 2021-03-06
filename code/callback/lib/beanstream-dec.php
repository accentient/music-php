<?php

// SET PATH TO ROOT FOLDER..
$basePath = pathinfo(dirname(__FILE__));
define('PATH',  substr($basePath['dirname'],0,-9).'/');
define('PARENT', 1);

// Load defined admin options..
include(PATH . 'control/defined.php');

// SET GATEWAY FLAG
$thisGateway = 'beanstream';

// ERROR REPORTING..
define('GW_ERR_LOG', $thisGateway);
include(PATH . 'control/classes/class.errors.php');
if (ERR_HANDLER_ENABLED) {
  register_shutdown_function('msFatalErr');
  set_error_handler('msErrorhandler');
}

// INIT/LANG..
include(PATH . 'control/init.php');

// DETECT SSL..
$url = (mswSSL() == 'yes' ? str_replace('http://', 'https://', BASE_HREF) : BASE_HREF);

header("Location: " . $url . "index.php?msg=2");

?>