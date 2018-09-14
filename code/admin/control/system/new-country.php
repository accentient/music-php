<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$titleBar = (isset($_GET['edit']) ? $adlang18[7] : $adlang18[6]) . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/new-country.php');
include(PATH . 'templates/footer.php');

?>