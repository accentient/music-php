<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$titleBar    = (isset($_GET['edit']) ? $adlang12[1] : $adlang12[0]) . ': ';
$loadIBox    = true;
$loadSlugify = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/new-page.php');
include(PATH . 'templates/footer.php');

?>