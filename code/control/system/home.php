<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4],'403');
}

// Is a new page a landing page..
$landing = $BUILDER->landing();
if ($landing>0) {
  $_GET['pg'] = $landing;
  include(PATH . 'control/system/pg.php');
  exit;
}

// Is a search being performed?
if (isset($_GET['q']) && strlen($_GET['q'])>MIN_SEARCH_WORD_LENGTH) {
  $url = array(
   'seo' => array(
	urlencode($_GET['q']),
	''
   ),
   'standard' => array(
	'keys' => urlencode($_GET['q']),
	'next' => ''
   )
  );
  header("Location: ".BASE_HREF.$SEO->url('search',$url));
  exit;
}

$pluginLoader[] = 'mmusic';
$pluginLoader[] = 'music-ops';

include(PATH . 'control/system/header.php');

$HOME = $BUILDER->load(
 'home',
 array(
  $pbcatlang[0],
  $pbcatlang[1],
  $pbcatlang[2],
  $pbcatlang[20]
 )
);

$tpl  = new Savant3();
$tpl->assign('TXT',
 array(
  $pblang[17]
 )
);
$tpl->assign('FEATURED', $HOME['data']);
$tpl->assign('FEED_URL', BASE_HREF.$SEO->url('rss-home',array(),'yes'));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/'.THEME . '/home.tpl.php');

include(PATH . 'control/system/footer.php');


?>