<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$titleBar = (isset($_GET['edit']) ? $adlang3[0] : $adlang3[8]) . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/new-gateway.php');
include(PATH . 'templates/footer.php');

?>