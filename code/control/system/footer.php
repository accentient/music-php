<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4],'403');
}

// Footer..
$footer  =  $pblang[11] . ': <a href="http://www.'.SCRIPT_URL . '" title="'.SCRIPT_NAME . '" onclick="window.open(this);return false">'.SCRIPT_NAME . '</a> ';
$footer .= '&copy;2007-'.date('Y',$DT->timeStamp()) . ' <a href="http://www.maianscriptworld.co.uk" onclick="window.open(this);return false" title="Maian Script World">Maian Script World</a>. '.$pblang[12] . ' . ';

// Commercial version..
if (LICENCE_VER=='unlocked' && $SETTINGS->pfoot) {
  $footer = $SETTINGS->pfoot;
}

$config = array(
 'template_path' => array(PATH)
);

$tpl  = new Savant3($config);
$tpl->assign('FOOTER', $footer);
$tpl->assign('TXT',
 array(
  $gblang[22]
 )
);
$tpl->assign('SOCIAL_BUTTONS', $BUILDER->socialbuttons());
$tpl->assign('PLUGIN_LOADER', $BUILDER->plugins('footer',$pluginLoader));

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/'.THEME . '/footer.tpl.php');

?>