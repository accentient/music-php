<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4],'403');
}

$pluginLoader[] = 'mmusic';
$pluginLoader[] = 'music-ops';
$pluginLoader[] = 'soundmanager';

$COL = $BUILDER->load('collection');

if (!isset($COL->id)) {
  include(PATH . 'control/system/404.php');
  exit;
}

if ($COL->metakeys) {
  $metaData[1] = mswSafeDisplay($COL->metakeys);
}
if ($COL->metadesc) {
  $metaData[0] = mswSafeDisplay($COL->metadesc);
}

$title = mswSafeDisplay(($COL->title ? $COL->title : $COL->name));

// Update collection hits count..
$BUILDER->hitcounter($COL->id);

$url = array(
  'seo' => array(
    ($COL->slug ? $COL->slug : $SEO->filter($COL->name)),
    ($COL->slug=='' ? $COL->id : '')
  ),
  'standard' => array(
    '#' => $COL->id
  )
);

// Open graph..
$og['title'] = $title;
$og['url']   = BASE_HREF.$SEO->url('collection',$url);
if ($COL->coverart) {
  $og['image'] = BASE_HREF.$BUILDER->cover($COL->coverart);
}

// Add this api..
$addthisapi = $BUILDER->params('addthis');

include(PATH . 'control/system/header.php');

$tpl  = new Savant3();
$tpl->assign('TXT',
 array(
  mswSafeDisplay($COL->name),
  $pbcatlang[7],
  $pbcatlang[8],
  $pbcatlang[9],
  $pbcatlang[1],
  $pbcatlang[2],
  $pbcatlang[10],
  $pbcatlang[11],
  $pbcatlang[12],
  $pbcatlang[13],
  $pbcatlang[14],
  $pbcatlang[15],
  $pbcatlang[16],
  $pbcatlang[17],
  $pbcatlang[19],
  $pbcatlang[21],
  $pbcatlang[25],
  $pbcatlang[26]
 )
);

// Offer check..
$disc  = ($COL->cost!='' ? $COSTING->offer($COL->cost,'col',$COL->id) : '');
$disc2 = ($COL->costcd!='' ? $COSTING->offer($COL->costcd,'cd',$COL->id) : '');

$tpl->assign('COST', ($COL->cost!='' ? mswCurrencyFormat((!in_array($disc,array('no','')) && $COL->cost!=$disc ? $disc : $COL->cost),$SETTINGS->curdisplay) : ''));
$tpl->assign('COSTCD', ($COL->costcd!='' ? mswCurrencyFormat((!in_array($disc2,array('no','')) && $COL->costcd!=$disc2 ? $disc2 : $COL->costcd),$SETTINGS->curdisplay) : ''));
$tpl->assign('TRACKS', $BUILDER->tracks($COL->id));
$tpl->assign('COMMENTS', $BUILDER->comments($COL));
$tpl->assign('RELATED', $BUILDER->related($COL->related,$cmd,$pbcatlang));
$tpl->assign('TAGS', $BUILDER->tags($COL->searchtags,$page));
$tpl->assign('STYLES', $BUILDER->colStyles($COL->id));
$tpl->assign('SOCIAL', ($COL->social ? unserialize($COL->social) : array()));
$tpl->assign('HIT_COUNTER', str_replace('{counter}',@number_format($COL->views),$pbcatlang[18]));
$tpl->assign('API', $addthisapi);

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/'.THEME . '/collection.tpl.php');

include(PATH . 'control/system/footer.php');

?>