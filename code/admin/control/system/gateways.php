<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$titleBar = $adlang1[6] . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/gateways.php');
include(PATH . 'templates/footer.php');

?>