<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

if (isset($_GET['load'])) {
  switch($_GET['load']) {
    case 'tweet':
      include(PATH . 'templates/windows/api-tweet.php');
      break;
  }
}

?>