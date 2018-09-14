<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$titleBar     = $adlang1[7] . ': ';
$loadIBox     = true;
$musicPlayer  = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/tracks.php');
include(PATH . 'templates/footer.php');

?>