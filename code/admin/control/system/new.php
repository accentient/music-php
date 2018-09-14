<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

if (isset($_GET['cover'])) {
 include(PATH . 'templates/windows/cover-art.php');
 exit;
}

$titleBar    = (isset($_GET['edit']) ? $adlang4[14] : $adlang4[0]) . ': ';
$loadIBox    = true;
$loadSlugify = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/new.php');
include(PATH . 'templates/footer.php');

?>