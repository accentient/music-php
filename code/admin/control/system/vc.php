<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4], '403');
}

if (isset($_GET['vck'])) {
  $d = $SYS->version();
  echo $JSON->encode(array(
    'html' => mswNL2BR($d)
  ));
  exit;
}

$titleBar = $adlang1[15] . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/version-check.php');
include(PATH . 'templates/footer.php');

?>