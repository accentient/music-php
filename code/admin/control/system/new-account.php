<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$titleBar = (isset($_GET['edit']) ? $adlang6[6] : $adlang6[0]) . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/new-account.php');
include(PATH . 'templates/footer.php');

?>