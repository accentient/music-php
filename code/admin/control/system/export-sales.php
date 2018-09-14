<?php

if (!defined('PARENT')) {
  mg_ecode($gblang[4],'403');
}

if (isset($_POST['process'])) {
  @ini_set('memory_limit', '100M');
  @set_time_limit(0);
  include(MM_BASE_PATH . 'control/classes/class.download.php');
  $DL      = new downloads();
  $SLS->dl = $DL;
  $SLS->exportSales($adlang9[55]);
  exit;
}

$titleBar = $adlang9[11] . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/export-sales.php');
include(PATH . 'templates/footer.php');

?>