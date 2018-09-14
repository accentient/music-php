<?php

//-------------------------------------------------------------
// SYSTEM CONSTANTS
// DO NOT Change
//-------------------------------------------------------------

if (!defined('PARENT') || !isset($SETTINGS->id)) {
  mswEcode($gblang[4],'403');
}

$tpl->assign('SETTINGS', $SETTINGS);
$tpl->assign('DT', $DT);
$tpl->assign('BUILD', $BUILDER);
$tpl->assign('SEO', $SEO);
$tpl->assign('ACCOUNT', $systemAcc);
$tpl->assign('COLLECTION', (isset($COL->id) ? $COL : ''));
$tpl->assign('FILTERS', $listFilters);
$tpl->assign('TXT_GLOBAL', $pbglobalfront);

?>
