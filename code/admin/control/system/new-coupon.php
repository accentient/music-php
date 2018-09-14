<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$titleBar = (isset($_GET['edit']) ? $adlang16[9] : $adlang16[0]) . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/new-coupon.php');
include(PATH . 'templates/footer.php');

?>