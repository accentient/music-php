<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

if (isset($_GET['export'])) {
  @ini_set('memory_limit', '100M');
  @set_time_limit(0);
  include(MM_BASE_PATH . 'control/classes/class.download.php');
  $DL      = new downloads();
  $ACC->dl = $DL;
  $ACC->exportLoginHistory($adlang21[5]);
  exit;
}

if (isset($_GET['clearall'])) {
  $ID = (int) $_GET['clearall'];
  $ACC->clearLoginHistory($ID);
  header("Location: index.php?p=login-history&id=" . $ID);
  exit;
}

$titleBar  = $adlang21[0] . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/accounts-login.php');
include(PATH . 'templates/footer.php');

?>