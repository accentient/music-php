<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4], '403');
}

// Load filters..
include(PATH . 'control/system/filters.php');

define('SEARCH_PER_PAGE_LOADER', 1);
$sData  = '';
$sPages = '';

$pluginLoader[] = 'mmusic';
$pluginLoader[] = 'music-ops';

if ($SETTINGS->rewrite == 'yes') {
  $rre  = $SEO->rewriteElements();
  $keys = (isset($rre[1]) ? $rre[1] : '');
  $sp   = (isset($rre[2]) ? (int) $rre[2] : '1');
  if ($sp > 1) {
    $page = $sp;
    define('PAGE_LIMIT_OR', ($page * SEARCH_PER_PAGE - (SEARCH_PER_PAGE)));
  }
} else {
  $keys = (isset($_GET['search']) ? urldecode(mswSafeDisplay($_GET['search'])) : '');
  if ($page > 1) {
    $sp = $page;
    define('PAGE_LIMIT_OR', ($sp * SEARCH_PER_PAGE - (SEARCH_PER_PAGE)));
  }
}

include(PATH . 'control/system/header.php');

if ($keys) {
  $SEARCH = $BUILDER->load('search', array(
    $pbcatlang[0],
    $pbcatlang[1],
    $pbcatlang[2],
    $pbcatlang[20]
  ), array(
    'keys' => explode(' ', urldecode($keys))
  ));

  $sData = $SEARCH['data'];
  $limit = $page * SEARCH_PER_PAGE - (SEARCH_PER_PAGE);

  $url    = array(
    'seo' => array(
      $keys . '/'
    ),
    'standard' => array(
      'keys' => $keys,
      'next' => '#'
    )
  );
  $PTION  = new pagination(array(
    $SEARCH['rows'],
    $gblang[15],
    $page
  ), $SEO->url('search', $url));
  $sPages = $PTION->display();
}

$tpl = new Savant3();
$tpl->assign('TXT', array(
  $pblang[14] . ' (' . (isset($SEARCH['rows']) ? (int) $SEARCH['rows'] : '0') . ')',
  '',
  '',
  '',
  ''
));
$tpl->assign('SEARCH', $sData);
$tpl->assign('PAGINATION', $sPages);

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/' . THEME . '/search.tpl.php');

include(PATH . 'control/system/footer.php');

?>