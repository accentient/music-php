<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$titleBar = $adlang6[5] . ': ';
$loadIBox = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/history.php');
include(PATH . 'templates/footer.php');

?>