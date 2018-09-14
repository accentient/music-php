<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

if (isset($_POST['export'])) {
  @ini_set('memory_limit', '100M');
  @set_time_limit(0);
  include(MM_BASE_PATH . 'control/classes/class.download.php');
  $DL      = new downloads();
  $MSC->dl = $DL;
  $MSC->exportMusic($adlang22);
  exit;
}

$titleBar       = $adlang1[34] . ': ';
$loadFormPlugin = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/import-export.php');
include(PATH . 'templates/footer.php');

?>