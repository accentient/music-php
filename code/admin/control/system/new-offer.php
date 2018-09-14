<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$titleBar = (isset($_GET['edit']) ? $adlang11[9] : $adlang11[0]) . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/new-offer.php');
include(PATH . 'templates/footer.php');

?>