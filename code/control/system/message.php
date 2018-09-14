<?php

if (!defined('PARENT') || !defined('BASE_HREF')) {
  mswEcode($gblang[4],'403');
}

$title = mswSafeDisplay($checkmsg[0]);

include(PATH . 'control/system/header.php');

switch($_GET['msg']) {
  // Standard error..
  case '1':
  $msg = $checkmsg[2].$checkmsg[6];
  break;
  // Declined..
  case '2':
  $msg = $checkmsg[1].$checkmsg[6];
  break;
  // Callback failure..
  case '3':
  $msg = $checkmsg[3].$checkmsg[6];
  break;
  // Gateway timeout..
  case '4':
  $msg = $checkmsg[8].$checkmsg[6];
  break;
  // Pending..
  case '5':
  $msg = $checkmsg[9].$checkmsg[6];
  break;
  // Sale locked..
  case '6':
  $ID = (isset($_GET['id']) ? (int) $_GET['id'] : '0');
  $Q  = $DB->db_query("SELECT `lockreason` FROM `".DB_PREFIX."sales` WHERE `id` = '{$ID}'");
  $D  = $DB->db_object($Q);
  if (isset($D->lockreason) && $D->lockreason) {
    $msg = mswNL2BR(mswCleanData($D->lockreason));
  } else {
    $msg = $pbdownloads[3].$checkmsg[6];
  }
  break;
  // Problem with download..
  case '7':
  $msg = $checkmsg[10].$checkmsg[6];
  break;
  // Generic thanks message..
  case '8':
  $msg = $checkmsg[11];
  break;
  // Paypoint ok..
  case '9':
  $msg = str_replace('{url}',BASE_HREF . 'index.php?gw=' . $SALE_ID . '-' . $SALE_CODE, $checkmsg[12]);
  break;
  // Gateway specific or generic if blank..
  default:
  // Beanstream gives us a trailing line break, so remove it to keep things tidy..
  if (isset($_GET['msg']) && substr($_GET['msg'],-7) == '[break]') {
    $_GET['msg'] = substr($_GET['msg'],0,-7);
  }
  $msg = ($_GET['msg'] ? $checkmsg[4] . '&quot;<b>'.str_replace('[break]','<br>',mswSafeDisplay(urldecode($_GET['msg']))) . '</b>&quot;'.$checkmsg[7] : $checkmsg[5]).$checkmsg[6];
  break;
}

$config = array(
 'template_path' => array(PATH)
);
$tpl  = new Savant3($config);
$tpl->assign('TXT',
 array(
  $checkmsg[0],
  $msg
 )
);

// Global template vars..
include(PATH . 'control/lib/global.php');

// Load template..
$tpl->display('content/'.THEME . '/message.tpl.php');

include(PATH . 'control/system/footer.php');


?>