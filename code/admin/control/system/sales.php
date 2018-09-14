<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

if (isset($_GET['lock'])) {
  include(PATH . 'templates/windows/sale-lock.php');
  exit;
}

$titleBar = $adlang1[8] . ': ';
$loadIBox = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/sales.php');
include(PATH . 'templates/footer.php');

?>