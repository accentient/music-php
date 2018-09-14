<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

if (isset($_GET['play'])) {
  include(PATH . 'templates/windows/play.php');
  exit;
}

if (isset($_GET['clipBoard'])) {
  if ($_GET['clipBoard']!='view' && substr($_GET['clipBoard'],1)>0) {
    $SLS->addToClipBoard();
  }
  include(PATH . 'templates/windows/sales-clipboard.php');
  exit;
}

$titleBar     = $adlang1[12] . ': ';
$loadIBox     = true;
$audioPlayer  = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/collections.php');
include(PATH . 'templates/footer.php');

?>