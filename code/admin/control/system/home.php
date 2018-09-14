<?php

if (!defined('PARENT')) {
  header('HTTP/1.0 403 Forbidden');
  header('Content-type: text/plain; charset=utf-8');
  echo $gblang[4];
  exit;
}

if (isset($_GET['logout'])) {
  $_SESSION['mm_access_'.mswEncrypt(SECRET_KEY)] = '';
  unset($_SESSION['mm_access_'.mswEncrypt(SECRET_KEY)]);
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit;
}

$titleBar   = '';
$dataTables = true;
$loadIBox   = true;

include(PATH . 'templates/header.php');
include(PATH . 'templates/home.php');
include(PATH . 'templates/footer.php');

?>