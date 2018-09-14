<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4], '403');
}

$title = mswSafeDisplay($pblang[3]);

include(PATH . 'control/system/header.php');

$tpl = new Savant3();
$tpl->assign('TXT', array(
  $pblang[3],
  '',
  '',
  '',
  ''
));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/' . THEME . '/popular.tpl.php');

include(PATH . 'control/system/footer.php');

?>