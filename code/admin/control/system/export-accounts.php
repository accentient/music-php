<?php

if (!defined('PARENT')) {
  mg_ecode($gblang[4],'403');
}

if (isset($_POST['process'])) {
  @ini_set('memory_limit', '100M');
  @set_time_limit(0);
  include(MM_BASE_PATH . 'control/classes/class.download.php');
  $DL      = new downloads();
  $ACC->dl = $DL;
  $ACC->export($adlang6[25]);
  exit;
}

$titleBar = $adlang6[1] . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/export-accounts.php');
include(PATH . 'templates/footer.php');

?>