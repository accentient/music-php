<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$titleBar    = (isset($_GET['edit']) ? $adlang5[2] : $adlang5[0]) . ': ';
$loadSlugify = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/new-style.php');
include(PATH . 'templates/footer.php');

?>