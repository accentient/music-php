<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

if (isset($_GET['settings'])) {
  include(PATH . 'templates/windows/graph-settings.php');
  exit;
}

$titleBar       = $adlang1[9] . ': ';
$loadFlotPlugin = true;
$loadIBox       = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/statistics.php');
include(PATH . 'templates/footer.php');

?>