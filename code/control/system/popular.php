<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4], '403');
}

// Load filters..
include(PATH . 'control/system/filters.php');

$title = mswSafeDisplay($pblang[3]);

$pluginLoader[] = 'mmusic';
$pluginLoader[] = 'music-ops';

$POPULAR = $BUILDER->load('popular', array(
  $pbcatlang[0],
  $pbcatlang[1],
  $pbcatlang[2],
  $pbcatlang[20]
));

// Open graph..
$og['title'] = $title;
$og['url']   = BASE_HREF . $SEO->url('popular', array(), 'yes');

include(PATH . 'control/system/header.php');

$tpl = new Savant3();
$tpl->assign('TXT', array(
  $pblang[3]
));
$tpl->assign('POPULAR', $POPULAR['data']);
$tpl->assign('FEED_URL', BASE_HREF . $SEO->url('rss-popular', array(), 'yes'));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/' . THEME . '/popular.tpl.php');

include(PATH . 'control/system/footer.php');

?>