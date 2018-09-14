<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4], '403');
}

$pluginLoader[] = 'mmusic';

$PG = $BUILDER->load('page');

if (!isset($PG->id)) {
  include(PATH . 'control/system/404.php');
  exit;
}

if ($PG->keys) {
  $metaData[1] = mswSafeDisplay($PG->keys);
}
if ($PG->desc) {
  $metaData[0] = mswSafeDisplay($PG->desc);
}

$title = mswSafeDisplay(($PG->title ? $PG->title : $PG->name));

$url = array(
  'seo' => array(
    ($PG->slug ? $PG->slug : $SEO->filter($PG->name)),
    ($PG->slug == '' ? $PG->id : '')
  ),
  'standard' => array(
    '#' => $PG->id
  )
);

// Open graph..
$og['title'] = $title;
$og['url']   = BASE_HREF . $SEO->url('pg', $url);

include(PATH . 'control/system/header.php');

$tpl = new Savant3();
$tpl->assign('TXT', array(
  mswSafeDisplay($PG->name)
));
$tpl->assign('PAGE', $PG);
$tpl->assign('CONTACT', $pbcontact);

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
if ($PG->template && file_exists(PATH . 'content/' . THEME . '/custom-pages/' . $PG->template) && substr($PG->template, -8) == '.tpl.php') {
  $tpl->display('content/' . THEME . '/custom-pages/' . $PG->template);
} else {
  $tpl->display('content/' . THEME . '/page.tpl.php');
}

include(PATH . 'control/system/footer.php');

?>