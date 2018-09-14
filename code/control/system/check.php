<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4], '403');
}

$title = mswSafeDisplay($pborders[2]);

$tpl = new Savant3();
$tpl->assign('CHARSET', $gblang[0]);
$tpl->assign('LANG', $gblang[2]);
$tpl->assign('TITLE', mswSafeDisplay($title));
$tpl->assign('META_DESC', mswSafeDisplay($metaData[0]));
$tpl->assign('META_KEYS', mswSafeDisplay($metaData[1]));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/' . THEME . '/order-check.tpl.php');

?>