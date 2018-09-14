<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

@ini_set('memory_limit', '100M');
@set_time_limit(0);

$titleBar     = (isset($_GET['edit']) ? $adlang8[22] : $adlang8[0]) . ': ';
$musicPlayer  = true;
$loadIBox     = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/new-tracks.php');
include(PATH . 'templates/footer.php');

?>