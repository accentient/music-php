<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4],'403');
}

// Load filters..
include(PATH . 'control/system/filters.php');

$title          = mswSafeDisplay($pblang[2]);

$pluginLoader[] = 'mmusic';
$pluginLoader[] = 'music-ops';

$LATEST = $BUILDER->load(
 'latest',
 array(
  $pbcatlang[0],
  $pbcatlang[1],
  $pbcatlang[2],
  $pbcatlang[20]
 )
);

// Open graph..
$og['title'] = $title;
$og['url']   = BASE_HREF.$SEO->url('latest',array(),'yes');

include(PATH . 'control/system/header.php');

$tpl  = new Savant3();
$tpl->assign('TXT',
 array(
  $pblang[2]
 )
);
$tpl->assign('LATEST', $LATEST['data']);
$tpl->assign('FEED_URL', BASE_HREF.$SEO->url('rss-latest',array(),'yes'));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/'.THEME . '/latest.tpl.php');

include(PATH . 'control/system/footer.php');


?>