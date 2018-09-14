<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4],'403');
}

if (isset($_SESSION['mmEntryData'])) {
  $_SESSION['mmEntryData'] = array();
  unset($_SESSION['mmEntryData']);
}

header("Location: ".BASE_HREF.$SEO->url('login',array(),'yes'));

?>