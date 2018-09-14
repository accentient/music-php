<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$titleBar = $adlang13[0] . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/mailer.php');
include(PATH . 'templates/footer.php');

?>