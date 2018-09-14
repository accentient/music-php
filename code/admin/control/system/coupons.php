<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

if (isset($_GET['code'])) {
  include(PATH . 'templates/windows/coupon-history.php');
  exit;
}

$titleBar = $adlang1[17] . ': ';
$loadIBox = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/coupons.php');
include(PATH . 'templates/footer.php');

?>