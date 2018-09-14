<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4], '403');
}

// Load filters..
include(PATH . 'control/system/filters.php');

define('COLLECTIONS_PER_PAGE_LOADER', 1);

$STYLE = $BUILDER->load('style');

if (!isset($STYLE->id)) {
  include(PATH . 'control/system/404.php');
  exit;
}

$title = mswSafeDisplay($STYLE->name);

$pluginLoader[] = 'mmusic';
$pluginLoader[] = 'music-ops';

// Pagination..
if ($SETTINGS->rewrite == 'yes') {
  $sef = $SEO->rewriteElements();
  if (isset($sef[3]) && $sef[3] > 1) {
    $page = (int) $sef[3];
    define('PAGE_LIMIT_OR', ($page * COLLECTIONS_PER_PAGE - (COLLECTIONS_PER_PAGE)));
  }
} else {
  if ($page > 1) {
    define('PAGE_LIMIT_OR', ($page * COLLECTIONS_PER_PAGE - (COLLECTIONS_PER_PAGE)));
  }
}
$limit = $page * COLLECTIONS_PER_PAGE - (COLLECTIONS_PER_PAGE);
$url   = array(
  'seo' => array(
    ($STYLE->slug ? $STYLE->slug : $SEO->filter($STYLE->name)),
    $STYLE->id,
    '/'
  ),
  'standard' => array(
    'style' => $STYLE->id,
    'next' => '#'
  )
);

$STYLE_DATA = $BUILDER->load('view-style', array(
  $pbcatlang[0],
  $pbcatlang[1],
  $pbcatlang[2],
  $pbcatlang[20]
), array(
  'id' => $STYLE->id
));

$PTION  = new pagination(array(
  $STYLE_DATA['rows'],
  $gblang[15],
  $page
), $SEO->url('style', $url));
$sPages = $PTION->display();

// Open graph..
$og['title'] = $title;
$og['url']   = BASE_HREF . $SEO->url('style', $url);

include(PATH . 'control/system/header.php');

$tpl = new Savant3();
$tpl->assign('TXT', array(
  mswSafeDisplay($STYLE->name)
));
$tpl->assign('COLLECTIONS', $STYLE_DATA['data']);
$tpl->assign('PAGINATION', $sPages);
$tpl->assign('FEED_URL', BASE_HREF . $SEO->url('rss-style' . $STYLE->id, array(), 'yes'));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/' . THEME . '/style.tpl.php');

include(PATH . 'control/system/footer.php');

?>