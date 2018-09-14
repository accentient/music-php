<?php

if (!defined('PARENT')) {
  header('HTTP/1.0 403 Forbidden');
  header('Content-type: text/plain; charset=utf-8');
  echo $gblang[4];
  exit;
}

if (isset($_POST['q'])) {
  @ini_set('memory_limit', '100M');
  @set_time_limit(0);
  include(MM_BASE_PATH . 'control/classes/class.download.php');
  $DL      = new downloads();
  $SLS->dl = $DL;
  $SLS->exportRevenue($adlang20[13],$gbdates);
  exit;
}

$titleBar = $adlang1[20] . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/revenue.php');
include(PATH . 'templates/footer.php');

?>