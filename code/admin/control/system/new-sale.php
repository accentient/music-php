<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

if (isset($_GET['changePrice'])) {
  include(PATH . 'templates/windows/change-sale-price.php');
  exit;
}

if (isset($_GET['msg'])) {
  include(PATH . 'templates/windows/msg.php');
  exit;
}

if (isset($_GET['dhistory'])) {
  include(MM_BASE_PATH . 'control/classes/class.download.php');
  $DL      = new downloads();
  $SLS->dl = $DL;
  $SLS->exportHistory($adlang9[85]);
  exit;
}

if (isset($_GET['history'])) {
  include(PATH . 'templates/windows/history.php');
  exit;
}

$titleBar = (isset($_GET['edit']) ? $adlang9[2] : $adlang9[0]) . ': ';
$loadIBox = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/new-sale.php');
include(PATH . 'templates/footer.php');

?>