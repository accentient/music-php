<?php

if (!defined('PARENT')) {
  header('HTTP/1.0 403 Forbidden');
  header('Content-type: text/plain; charset=utf-8');
  echo $gblang[4];
  exit;
}

$titleBar = '';

include(PATH . 'templates/header.php');
include(PATH . 'templates/login.php');
include(PATH . 'templates/footer.php');
exit;

?>