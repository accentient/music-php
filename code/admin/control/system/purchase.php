<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$titleBar = $adlang1[1] . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/purchase.php');
include(PATH . 'templates/footer.php');

?>