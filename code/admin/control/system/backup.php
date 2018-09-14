<?php

if (!defined('PARENT')) {
  header('HTTP/1.0 403 Forbidden');
  header('Content-type: text/plain; charset=utf-8');
  echo $gblang[4];
  exit;
}

include(PATH . 'control/classes/class.backup.php');

// Backup..
if (isset($_POST['process'])) {
  @ini_set('memory_limit', '100M');
  @set_time_limit(0);
  include(MM_BASE_PATH . 'control/classes/class.download.php');
  $DL      = new downloads();
  if (!is_writeable(PATH . 'backup') || !is_dir(PATH . 'backup')) {
    die('"<b>' . PATH . 'backup' . '</b>" folder must exist and be writeable. Please check directory and permissions. . ');
  }
  $download = (isset($_POST['download']) ? $_POST['download'] : 'yes');
  $compress = (isset($_POST['compress']) ? $_POST['compress'] : 'yes');
  // File path..
  if ($compress == 'yes') {
    $filepath = PATH . 'backup/db-' . time() . '.gz';
  } else {
    $filepath = PATH . 'backup/db-' . time() . '.sql';
  }
  // Save backup..
  $BACKUP           = new dbBackup($filepath, ($compress == 'yes' ? true : false));
  $BACKUP->settings = $SETTINGS;
  $BACKUP->datetime = $DT;
  $BACKUP->doDump();
  // Download..
  if ($download == 'yes') {
    if (file_exists($filepath)) {
      $DL->dl($filepath, ($compress == 'yes' ? 'application/x-compressed' : 'text/plain'));
    }
  } else {
    header("Location: ?p=backup&done=yes");
  }
  exit;
}

$titleBar = $adlang1[11] . ': ';

include(PATH . 'templates/header.php');
include(PATH . 'templates/backup.php');
include(PATH . 'templates/footer.php');

?>