<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4],'403');
}

if (defined('NO_HEADER')) {
  exit;
}

// Is open graph enabled?
$pluginData = array();
if ($SETTINGS->facebook=='yes') {
  $ogapi                    = $BUILDER->params('facebook');
  $pluginLoader[]           = 'open-graph';
  $pluginData['open-graph'] = array(
   'url'    => (isset($og['url']) ? $og['url'] : BASE_HREF),
   'site'   => mswSafeDisplay($SETTINGS->website),
   'image'  => (isset($og['image']) ? $og['image'] : (isset($ogapi['facebook']['fbimage']) && $ogapi['facebook']['fbimage'] ? $ogapi['facebook']['fbimage'] : BASE_HREF . 'content/'.THEME . '/images/fb-default.png')),
   'title'  => mswSafeDisplay((isset($og['title']) ? $og['title'] . ': '.mswSafeDisplay($SETTINGS->website) : $SETTINGS->website)),
   'id'     => (isset($ogapi['facebook']['fbinsights']) ? $ogapi['facebook']['fbinsights'] : '0')
  );
}

$config = array(
 'template_path' => array(PATH)
);

$tpl  = new Savant3($config);
$tpl->assign('CHARSET', $gblang[0]);
$tpl->assign('LANG', $gblang[2]);
$tpl->assign('DIR', $gblang[1]);
$tpl->assign('TITLE', ($title ? mswSafeDisplay($title) . ': ' : '').mswSafeDisplay($SETTINGS->website).(LICENCE_VER!='unlocked' ? ' ('.SCRIPT_NAME . ')' : '').(LICENCE_VER!='unlocked' ? ' - Free Version' : '').(DEV_BETA=='yes' ? ' - BETA VERSION' : ''));
$tpl->assign('META_DESC', mswSafeDisplay($metaData[0]));
$tpl->assign('META_KEYS', mswSafeDisplay($metaData[1]));
$tpl->assign('STORE', mswSafeDisplay($SETTINGS->website));
$tpl->assign('TAGLINE', mswSafeDisplay($pblang[0]));
$tpl->assign('TXT',
 array(
  $pblang[5],
  $pblang[6],
  $pblang[8],
  $pblang[1],
  $pblang[2],
  $pblang[3],
  $pblang[4],
  str_replace('{count}',CART_COUNT,$pblang[7]),
  $pblang[9],
  $pblang[10],
  $pbaccount[0],
  $pbaccount[1],
  $pbaccount[2],
  $pbaccount[3],
  $pbaccount[16]
 )
);
$tpl->assign('URL',
 array(
  BASE_HREF,
  BASE_HREF.$SEO->url('account',array(),'yes'), //account
  BASE_HREF.$SEO->url('latest',array(),'yes'), //latest
  BASE_HREF.$SEO->url('popular',array(),'yes'), //popular
  BASE_HREF.$SEO->url('specials',array(),'yes'), //specials
  BASE_HREF.$SEO->url('basket',array(),'yes'), // basket
  BASE_HREF.$SEO->url('profile',array(),'yes'), // profile
  BASE_HREF.$SEO->url('orders',array(),'yes'), //orders
  BASE_HREF.$SEO->url('logout',array(),'yes') //logout
 )
);
$tpl->assign('KEYS', (isset($keys) ? mswSafeDisplay($keys) : ''));
$tpl->assign('TOTAL', str_replace('{cost}',0,$pblang[16]));
$tpl->assign('STYLES', $BUILDER->styles());
$tpl->assign('OTHER', $BUILDER->pages());
$tpl->assign('PLUGIN_LOADER', $BUILDER->plugins('header',$pluginLoader,$pluginData));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/'.THEME . '/header.tpl.php');

?>
