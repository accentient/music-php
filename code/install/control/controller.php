<?php

if (!defined('PARENT')) {
  header('HTTP/1.0 403 Forbidden');
  header('Content-type: text/plain; charset=utf-8');
  echo 'Permission Denied';
  exit;
}

// Get DB version..
$defChar  = 'utf8_general_ci';
$VERSION  = $DB->db_version();
$cSets    = $DB->db_charsets();

//MySQL..
if (isset($VERSION->v)) {
  $mysqlVer  = $VERSION->v;
} else {
  $mysqlVer  = 5;
}

?>
