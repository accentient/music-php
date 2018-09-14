<?php

if (!defined('PARENT')) {
  mswEcode($gblang[4],'403');
}

$ID    = (int)$_GET['id'];
$Q     = $DB->db_query("SELECT * FROM `".DB_PREFIX."sales` WHERE `id` = '{$ID}'");
$SALE  = $DB->db_object($Q);

if (isset($SALE->id)) {
  $titleBar = $adlang9[20] . ': (#'.mswSaleInvoiceNumber($SALE->invoice) . ')';

  include(PATH . 'templates/header.php');
  include(PATH . 'templates/invoice.php');
  include(PATH . 'templates/footer.php');
  exit;
}

mswEcode($gblang[4],'403');

?>