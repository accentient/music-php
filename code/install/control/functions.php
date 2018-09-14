<?php

if (!defined('PARENT')) {
  header('HTTP/1.0 403 Forbidden');
  header('Content-type: text/plain; charset=utf-8');
  echo 'Permission Denied';
  exit;
}

function ins_logDBError($table,$error,$code,$line,$file,$type='Create') {
  global $DB;
  $header  = '';
  if (!file_exists(REL_PATH.'logs/install-error-report.txt')) {
    $header   = 'Script: '.SCRIPT_NAME.mswDefineNewline();
    $header  .= 'Script Version: '.SCRIPT_VERSION. mswDefineNewline();
    $header  .= 'PHP Version: '.phpVersion().mswDefineNewline();
    $header  .= 'DB Version: '.$DB->db_version().mswDefineNewline();
    if (isset($_SERVER['SERVER_SOFTWARE'])) {
      $header  .= 'Server Software: '.$_SERVER['SERVER_SOFTWARE'].mswDefineNewline();
    }
    if (isset($_SERVER["HTTP_USER_AGENT"])) {
      if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'win')) {
        $platform = 'Windows';
      } else if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mac')) {
        $platform = 'Mac';
      } else {
        $platform = 'Other';
      }
      $header  .= 'Platform: '.$platform.mswDefineNewline();
    }
    $header  .= '================================================================================='.mswDefineNewline();
  }
  $string  = 'Table: '.$table.mswDefineNewline();
  $string .= 'Operation: '.$type.mswDefineNewline();
  $string .= 'Error Code: '.$code.mswDefineNewline();
  $string .= 'Error Msg: '.$error.mswDefineNewline();
  $string .= 'On Line: '.$line.mswDefineNewline();
  $string .= 'In File: '.$file.mswDefineNewline();
  $string .= '- - - - - - - - - - - - - - - - - - - - - '.mswDefineNewline();
  @file_put_contents(REL_PATH.'logs/install-error-report.txt',$header.$string,FILE_APPEND);
}

// Generates 60 character product key..
$_SERVER['HTTP_HOST']    = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : uniqid(rand(),1));
$_SERVER['REMOTE_ADDR']  = (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : uniqid(rand(),1));
$c1                      = sha1($_SERVER['HTTP_HOST'].date('YmdHis').$_SERVER['REMOTE_ADDR'].time());
$c2                      = sha1(uniqid(rand(),1).time());
$prodKey                 = substr($c1.$c2,0,60);
$prodKey                 = strtoupper($prodKey);

?>