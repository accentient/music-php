<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4], '403');
}

$title = mswSafeDisplay($pborders[0]);

include(PATH . 'control/system/header.php');

$tpl = new Savant3();
$tpl->assign('TXT', array(
  $pborders[0],
  $pborders[1]
));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/' . THEME . '/order-cancel.tpl.php');

include(PATH . 'control/system/footer.php');

?>