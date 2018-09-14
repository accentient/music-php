<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

if (isset($_GET['pushover'])) {
  $_GET['msg']    = 'pusho';
  include(REL_PATH . 'control/classes/class.apis.php');
  $APIS           = new apis();
  $APIS->settings = $SETTINGS;
  $APIS->builder  = $SBDR;
  $APIS->pushover($adlang2[128], $adlang2[127]);
  include(PATH . 'templates/windows/msg.php');
  exit;
}

if (isset($_GET['test'])) {
  include(PATH . 'templates/windows/test-mail.php');
  exit;
}

$titleBar       = $adlang1[5] . ': ';
$loadFormPlugin = true;
$loadIBox       = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/settings.php');
include(PATH . 'templates/footer.php');

?>