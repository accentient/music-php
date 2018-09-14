<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

if (isset($_GET['export'])) {
  @ini_set('memory_limit', '100M');
  @set_time_limit(0);
  include(MM_BASE_PATH . 'control/classes/class.download.php');
  $DL      = new downloads();
  $SLS->dl = $DL;
  $SLS->exportMoss($adlang24[6]);
  exit;
}

$titleBar = $adlang1[37] . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/moss-export.php');
include(PATH . 'templates/footer.php');

?>