<?php

// Increase memory and time limit..
@ini_set('memory_limit', '100M');
@set_time_limit(300);

// CHECK PERMISSIONS..
if (!defined('PARENT') || !isset($_GET['dmf']) || !isset($systemAcc['enabled']) || $systemAcc['enabled']=='no') {
  exit;
}

// ACTIVATE JSON CLASS..
include(PATH . 'control/classes/class.json.php');
$JSON = new jsonHandler();
$arr  = array(
 'resp'  => 'err',
 'msg'   => $jslang[12],
 'title' => $jslang[13],
 'image' => BASE_HREF . 'content/'.THEME . '/images/warning.png'
);

// ACTIVATE GEO IP CLASS..
include(PATH . 'control/classes/class.ip.php');
$IPGEO           = new geoIP();
$IPGEO->settings = $SETTINGS;
$lookup          = $IPGEO->lookup($_SERVER['REMOTE_ADDR'],$gblang[19]);

// DOWNLOAD ID..
$ID  = (int) $_GET['dmf'];

// ACTIVATE SALE CLASS..
include(PATH . 'control/classes/class.sales.php');
$access          = ($SETTINGS->access ? unserialize($SETTINGS->access) : array());
$clickLimit      = (isset($access[5]) ? (int) $access[5] : '0');
$multIPLimit     = (isset($access[2]) ? (int) $access[2] : '0');
$restrictIP      = (isset($access[4]) && in_array($access[4],array('yes','no')) ? $access[4] : 'no');
$debugLog        = (isset($access[6]) && in_array($access[6],array('yes','no')) ? $access[6] : 'no');
$lockNotify      = (isset($access[3]) && in_array($access[3],array('yes','no')) ? $access[3] : 'no');
$zipTmpFldr      = (isset($access[7]) && in_array($access[7],array('tmp','log')) ? $access[7] : 'tmp');
$dlZone          = ($systemAcc['timezone'] ? $systemAcc['timezone'] : $SETTINGS->timezone);
$SALE            = new salesPublic();
$SALE->settings  = $SETTINGS;
$SALE->datetime  = $DT;
$SALE->cart      = $CART;
$dlErrs          = 0;

// ACTIVATE DOWNLOAD CLASS..
include(PATH . 'control/classes/class.download.php');
$DL              = new downloads();
$DL->debuglog    = $debugLog;

// PROCESS TOKEN..
if ($ID>0 && !isset($_GET['t'])) {

  $DL->log('Start Download - Sale Item: '.$ID);

  $clickItem = $SALE->getSaleItem($ID);

  // Is ID valid?
  if ($clickItem!='fail') {

    $DL->log('Item: '.$ID . ' - Item Data Found. Sale ID is: '.$clickItem->saleID . ', buyer is '.$systemAcc['name']);

    // Get track/collection info..
    $itemLoader = $SALE->downloadItem($clickItem);

    // Does download belong to this account and does valid download data exist?
    if (isset($itemLoader['name']) && $clickItem->account==$systemAcc['id']) {

      $DL->log('Item: '.$ID . ' - Track/Collection Data Found');

      // Restrictions if not bypassed..
      if ($systemAcc['bypass']=='no') {

        $DL->log('Item: '.$ID . ' - Download ByPass Not Set for '.$systemAcc['name'] . ' - Checks Required');

        // Are we restricting downloads to original sale IP?
        if ($restrictIP=='yes') {
          $curIps = mswIPAddr(true);
          if (!in_array($clickItem->saleIP,$curIps)) {
            $DL->log('Item: '.$ID . ' - Restriction by Original IP set. IP does not equal original sale IP: '.$clickItem->saleIP . ' - Download Aborted');
            $DL->log(print_r($curIps,true));
            ++$dlErrs;
            $arr['msg'] = $pbdownloads[0];
          }
        }

        // Has link expired?
        if ($dlErrs==0 && $clickItem->expiry>0) {
          $exTime  = strtotime($DT->dateTimeDisplay($clickItem->expiry,'Y-m-d H:i:s',$dlZone));
          $nowTime = strtotime($DT->dateTimeDisplay($DT->utcTime(),'Y-m-d H:i:s',$dlZone));
          if ($exTime<=$nowTime) {
            $DL->log('Item: '.$ID . ' - Download link expired on '.$DT->dateTimeDisplay($exTime,$SETTINGS->dateformat) . '/'.$DT->dateTimeDisplay($exTime,'H:i:A') . ' - Download Aborted');
            ++$dlErrs;
            $arr['msg'] = $pbdownloads[1];
          }
        }

        // Has click limit been reached?
        if ($dlErrs==0 && $clickLimit>0) {
          if ($clickItem->clicks>=$clickLimit) {
            $DL->log('Item: '.$ID . ' - Click limit set and limit of '.$clickLimit . ' has been reached - Download Aborted');
            ++$dlErrs;
            $arr['msg'] = str_replace('{max}',$clickLimit,$pbdownloads[2]);
          }
        }

        // Is lock enabled for multiple IPs?
        if ($dlErrs==0 && $multIPLimit>0) {
          $clickTracking = $SALE->getSaleItemClicks($ID,$clickItem->saleID);
          if ((count($clickTracking)+1)>=$multIPLimit) {
            $DL->log('Item: '.$ID . ' - Restriction by multiple IP set and limit of '.$multIPLimit . ' has been reached - Download Aborted');
            ++$dlErrs;
            $arr['resp'] = 'LOCK';
            // Write log entry..
            mswHistoryLog(array(
              'sale' => $clickItem->saleID,
              'trackcol' => $ID,
              'action' => $pbdownloads[4],
              'type' => 'visitor',
              'iso' => strtolower($lookup['iso']),
              'country' => $lookup['country']
            ),$DB);
            $DL->log('Item: '.$ID . ' - Log written');
            // Is lock email notification enabled?
            if ($lockNotify=='yes') {
              // MAIL CONFIG..
              include(PATH . 'control/mail.php');
              $DL->log('Item: '.$ID . ' - Email enabled, sending report to webmaster');
              $newClickLog     = $SALE->getSaleItemClicks($ID,$clickItem->saleID);
              $f_r['{REPORT}'] = $SALE->ipReport($newClickLog);
              $f_r['{LIMIT}']  = $multIPLimit;
              $f_r['{NAME}']   = $systemAcc['name'];
              $f_r['{ITEM}']   = $itemLoader['name'].($itemLoader['tname'] ? ' / '.$itemLoader['tname'] : '');
              $msg             = strtr(file_get_contents(PATH . 'content/language/email-templates/wm-ip-alert.txt'), $f_r);
              $sbj             = str_replace('{website}', $SETTINGS->website, $emlang[16]);
              $mmMail->sendMail(array(
                'to_name' => $SETTINGS->website,
                'to_email' => $SETTINGS->email,
                'from_name' => ($SETTINGS->smtp_from ? $SETTINGS->smtp_from : $SETTINGS->website),
                'from_email' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
                'subject' => $sbj,
                'msg' => $mmMail->htmlWrapper(array(
                  'global' => $gblang,
                  'title' => $sbj,
                  'header' => $sbj,
                  'content' => mswNL2BR($msg),
                  'footer' => (!defined('LICENCE_VER') || LICENCE_VER == 'locked' ? $gblang[41] : '')
                )),
                'reply-to' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
                'other' => $SETTINGS->smtp_other,
                'plain' => $msg,
                'htmlWrap' => 'yes'
              ), $gblang);
              $mmMail->smtpClose();
            }
            // Lock sale..
            $SALE->lockSale($clickItem->saleID);
            $DL->log('Item: '.$ID . ' - Sale locked');
          }
        }

      } else {
        $DL->log('Item: '.$ID . ' - Download ByPass Set for '.$systemAcc['name'] . ' - NO Checks Required');
      }

      // If we are ok, download is ok so far..
      if ($dlErrs==0) {
        // Create unique download token..
        $token         = $SALE->code($ID.$systemAcc['email'],substr(md5(uniqid(rand(),1)),3,25));
        $SALE->addToken($ID,$token);
        $DL->log('Item: '.$ID . ' - Item click OK, token created: '.$token);
        $arr['msg']    = 'OK';
        $arr['image']  = '';
        $arr['title']  = 'OK';
        $arr['resp']   = 'TOKEN';
        $arr['itemid'] = $ID;
        $arr['token']  = $token;
      }

    } else {
      $DL->log('Item: '.$ID . ' - Track/Collection Data NOT Found - Aborted');
    }

  } else {
    $DL->log('Item: '.$ID . ' - Item Data NOT Found - Aborted');
  }

}

// PROCESS DOWNLOAD..
if ($ID>0 && isset($_GET['t']) && ctype_alnum($_GET['t'])) {
  $DL->log('Item: '.$ID . ' - Token '.$_GET['t'] . ' received for download');
  $ITEM = $SALE->getSaleFromToken($ID,$_GET['t']);
  $path = '';
  if (isset($ITEM->type)) {
    $DL->log('Item: '.$ID . ' - Data found for sale item');
    $LOADER = $SALE->downloadItem($ITEM);
    if (isset($LOADER['name'])) {
      $DL->log('Item: '.$ID . ' - Data information found for sale item. Type is: '.$ITEM->type);
      switch($ITEM->type) {
        case 'track':
          if (file_exists($SETTINGS->secfolder . '/'.$LOADER['mp3'])) {
            $DL->log('Item: '.$ID . ' - Track to download is: '.$SETTINGS->secfolder . '/'.$LOADER['mp3']);
            // Clear token and add to click count..
            $SALE->addToken($ID,'');
            $SALE->addClick($ID);
            $DL->log('Item: '.$ID . ' - Token cleared and click count updated');
            // Write tracking log..
            mswHistoryLog(array(
              'sale' => $ITEM->saleID,
              'trackcol' => $ID,
              'action' => $pbdownloads[5],
              'type' => 'visitor',
              'iso' => strtolower($lookup['iso']),
              'country' => $lookup['country']
            ),$DB);
            $DL->log('Item: '.$ID . ' - Log written');
            // Set path, mime and force download..
            $path = $SETTINGS->secfolder . '/'.$LOADER['mp3'];
            $mime = $DL->mime($path);
            $DL->log('Item: '.$ID . ' - Attempt to download track');
            $DL->dl($path,$mime,'no',$ID);
          } else {
            $DL->log('Item: '.$ID . ' - File '.$SETTINGS->secfolder . '/'.$LOADER['mp3'] . ' DOES NOT exist - Download aborted','yes');
            header("Location: index.php?msg=7");
            exit;
          }
          break;
        case 'collection':
          if (class_exists('ZipArchive')) {
            $DL->log('Item: '.$ID . ' - ZipArchive class enabled');
            $sysTempDir = sys_get_temp_dir();
            $TMP        = ($zipTmpFldr=='tmp' && is_writeable($sysTempDir) ? tempnam($sysTempDir, 'Zip') : PATH . 'logs/Zip-'.$systemAcc['accID'] . '-'.md5($ITEM->saleID.$ID.time()) . '.zip');
            if (file_exists($TMP) || $zipTmpFldr=='log') {
              // Make sure tmp file doesn`t already exist in logs folder..
              // Also, clear any previous zips if they exist for this account that were created, but not downloaded..
              if ($zipTmpFldr=='log') {
                if (file_exists($TMP)) {
                  @unlink($TMP);
                }
                $SALE->clearOldZips('Zip-'.$systemAcc['accID']);
              }
              $DL->log('Item: '.$ID . ' - Temp file '.$TMP . ' exists. Create zip file . ');
              $ZIP      = new ZipArchive();
              $ZIPFILE  = $ZIP->open($TMP, ZipArchive::CREATE);
              // Folder name is collection name minus any dodgy cars..
              $folder   = preg_replace('/[^\w-]/','',$LOADER['name']);
              $DL->log('Item: '.$ID . ' - Zip folder is '.$folder);
              $ZIP->addEmptyDir($folder);
              // Add tracks..
              if (!empty($LOADER['tracks'])) {
                foreach ($LOADER['tracks'] AS $trk) {
                  if (file_exists($SETTINGS->secfolder . '/'.$trk)) {
                    $DL->log('Item: '.$ID . ' - Adding track to zip: '.$SETTINGS->secfolder . '/'.$trk);
                    $ZIP->addFile($SETTINGS->secfolder . '/'.$trk,$folder . '/'.basename($trk));
                  } else {
                    $DL->log('Item: '.$ID . ' - Track '.$SETTINGS->secfolder . '/'.$trk . ' DOES NOT exist and will NOT be added to zip');
                  }
                }
              }
              // Cover Art..
              if ($LOADER['cover']) {
                if (file_exists(PATH.COVER_ART_FOLDER . '/'.$LOADER['cover'])) {
                  $DL->log('Item: '.$ID . ' - Adding cover art to zip: '.PATH.COVER_ART_FOLDER . '/'.$LOADER['cover']);
                  $ZIP->addFile(PATH.COVER_ART_FOLDER . '/'.$LOADER['cover'],$folder . '/'.basename($LOADER['cover']));
                } else {
                  $DL->log('Item: '.$ID . ' - Cover art '.PATH.COVER_ART_FOLDER . '/'.$LOADER['cover'] . ' DOES NOT exist and will NOT be added to zip');
                }
              }
              // Additional cover art..
              if (!empty($LOADER['covero'])) {
                foreach ($LOADER['covero'] AS $oca) {
                  if (file_exists(PATH.COVER_ART_FOLDER . '/'.$oca)) {
                    $DL->log('Item: '.$ID . ' - Adding additional cover art to zip: '.PATH.COVER_ART_FOLDER . '/'.$oca);
                    $ZIP->addFile(PATH.COVER_ART_FOLDER . '/'.$oca,$folder . '/'.basename($oca));
                  } else {
                    $DL->log('Item: '.$ID . ' - Additional cover art '.PATH.COVER_ART_FOLDER . '/'.$oca . ' DOES NOT exist and will NOT be added to zip');
                  }
                }
              }
              // Support for additional files..
              if (is_dir(PATH . 'zip-files')) {
                $DL->log('Item: '.$ID . ' - Additional files folder detected: '.PATH . 'zip-files');
                $dir = opendir(PATH . 'zip-files');
                while (false!==($read=readdir($dir))) {
                  if (!in_array($read,array(' . ','. . ','index.php','index.htm','index.html')) && !is_dir(PATH . 'zip-files/'.$read)) {
                    $DL->log('Item: '.$ID . ' - Adding additional file to zip: '.PATH . 'zip-files/'.$read);
                    $ZIP->addFile(PATH . 'zip-files/'.$read,basename($read));
                  }
                }
                closedir($dir);
              }
              $ZIP->close();
              // Clear token and add to click count..
              $SALE->addToken($ID,'');
              $SALE->addClick($ID);
              $DL->log('Item: '.$ID . ' - Token cleared and click count updated');
              // Write tracking log..
              mswHistoryLog(array(
                'sale' => $ITEM->saleID,
                'trackcol' => $ID,
                'action' => $pbdownloads[6],
                'type' => 'visitor',
                'iso' => strtolower($lookup['iso']),
                'country' => $lookup['country']
              ),$DB);
              $DL->log('Item: '.$ID . ' - Log written');
              // Set path, mime and force download..
              $DL->log('Item: '.$ID . ' - Attempt to download collection zip');
              $DL->dl($TMP, 'application/zip', 'yes', $ID, array($ITEM->saleID));
            } else {
              $DL->log('Item: '.$ID . ' - Temp file '.$TMP . ' DOES NOT exist. Check destination is writeable . ','yes');
              header("Location: index.php?msg=7");
              exit;
            }
          } else {
            $DL->log('Item: '.$ID . ' - ZipArchive class NOT enabled - Download aborted','yes');
            header("Location: index.php?msg=7");
            exit;
          }
          break;
      }
    } else {
      $DL->log('Item: '.$ID . ' - Data information NOT found for sale item - Download aborted','yes');
      header("Location: index.php?msg=7");
      exit;
    }
  } else {
    $DL->log('Item: '.$ID . ' - Data NOT found for sale item - Download aborted','yes');
    header("Location: index.php?msg=7");
    exit;
  }
}

$DL->log('Item: '.$ID . ' - JSON response (if applicable) was: '.print_r($arr,true),($arr['msg']=='OK' ? 'no' : 'yes'));

echo $JSON->encode($arr);
exit;

?>