<?php

if (!isset($_GET['ajax'])) {
  mswEcode($gblang[4], '403');
}

switch ($_GET['ajax']) {

  //-------------------------------------
  // Update settings..
  //-------------------------------------

  case 'settings':
  case 'order-featured';
  case 'featured':
  case 'rem-featured':
    switch ($_GET['ajax']) {
      case 'settings':
        // Check that the POST vars are present.
        // If the POST Content-Length is exceeded, it will wipe the settings table..
        $R = (isset($_POST['weekstart']) ? $SYS->update() : false);
        if ($R) {
          echo $JSON->encode(array(
            'OK',
            $adlang2[24],
            'actionMsg'
          ));
        } else {
          echo $JSON->encode(array(
            'ERR',
            $gblang[11].$jslang[18],
            $jslang[4]
          ));
        }
        break;
      case 'order-featured':
        $SYS->featuredOrder();
        echo $JSON->encode(array(
          'OK'
        ));
        break;
      case 'featured':
        $resp = $SYS->featured();
        echo $JSON->encode(array(
          $resp
        ));
        break;
      case 'rem-featured':
        $SYS->featuredRemove();
        echo $JSON->encode(array(
          'OK'
        ));
        break;
    }
    break;

  //-------------------------------------
  // Collections..
  //-------------------------------------

  case 'collections':
  case 'order-related';
    switch ($_GET['ajax']) {
      case 'collections':
        // Check licence..
        if (!isset($_POST['edit']) && LICENCE_VER == 'locked') {
          if ($DB->db_rowcount('collections') >= LIC_RESTR_COL) {
            echo $JSON->encode(array(
              'ERR',
              str_replace('{limit}',LIC_RESTR_COL,$jslang[19]),
              $jslang[4]
            ));
            exit;
          }
        }
        $R = $MSC->collections();
        if ($R) {
          echo $JSON->encode(array(
            'OK',
            (isset($_POST['edit']) ? $adlang4[16] : $adlang4[15])
          ));
        } else {
          echo $JSON->encode(array(
            'ERR',
            $gblang[11],
            $jslang[4]
          ));
        }
        break;
      case 'order-related':
        echo $JSON->encode(array(
          'OK'
        ));
        break;
    }
    break;

  //-------------------------------------
  // Music Styles..
  //-------------------------------------

  case 'styles':
  case 'style-order':
    switch ($_GET['ajax']) {
      case 'styles':
        $R = $MSC->styles();
        if ($R[0] == 'ok') {
          echo $JSON->encode(array(
            'OK',
            (isset($_POST['edit']) ? $adlang5[5] : str_replace('{count}', $R[1], $adlang5[4]))
          ));
        } else {
          echo $JSON->encode(array(
            'ERR',
            $gblang[11],
            $jslang[4]
          ));
        }
        break;
      case 'style-order':
        $MSC->styleOrdering();
        echo $JSON->encode(array(
          'resp' => 'OK'
        ));
        break;
    }
    break;

  //-------------------------------------
  // Tracks..
  //-------------------------------------

  case 'tracks':
  case 'edit-tracks':
  case 'track-order':
    switch ($_GET['ajax']) {
      // Track order..
      case 'track-order':
        $MSC->ordering();
        echo $JSON->encode(array(
          'resp' => 'OK'
        ));
        break;
      // Add/edit..
      case 'tracks':
      case 'edit-tracks':
        $c = $MSC->tracks();
        if ($c == 'Trk-Err') {
          echo $JSON->encode(array(
            'ERR',
            str_replace('{limit}',LIC_RESTR_TRKS,$jslang[20]),
            $jslang[4]
          ));
          exit;
        }
        echo $JSON->encode(array(
          'OK',
          str_replace('{count}', $c, (isset($_POST['update-tracks']) ? $adlang8[24] : $adlang8[14])),
          $c
        ));
        break;
    }
    break;

  //-------------------------------------
  // Reload cover art
  //-------------------------------------

  case 'cover-art':
    @ini_set('memory_limit', '100M');
    @set_time_limit(0);
    $files = mswFolderFileScanner($_GET['folder'], SUPPORTED_IMAGES);
    $html  = '';
    if (!empty($files)) {
      foreach ($files AS $img) {
        $html .= '<img onclick="mm_selectCoverArt(\'' . mswJSFilters(substr($img, strlen(REL_PATH . COVER_ART_FOLDER) + 1)) . '\',\'' . REL_PATH . COVER_ART_FOLDER . '/\');iBox.hide()" src="' . mswSafeDisplay($img) . '" alt="' . mswSafeDisplay(basename($img)) . '" title="' . mswSafeDisplay(basename($img)) . '">';
      }
    } else {
      $html .= '<p><i class="fa fa-warning fa-fw"></i> ' . $adlang4[23] . '</p>';
    }
    echo $JSON->encode(array(
      'resp' => $html
    ));
    break;

  //-------------------------------------
  // Accounts
  //-------------------------------------

  case 'accounts':
    if ($_POST['name'] && $_POST['email']) {
      // Check if email exists..
      if ($ACC->check($_POST['email'], 'email', (isset($_POST['edit']) ? (int) $_POST['edit'] : '0')) == 'exists') {
        echo $JSON->encode(array(
          'ERR',
          str_replace('{email}', $_POST['email'], $jslang[9]),
          $jslang[4]
        ));
        exit;
      }
      $R = $ACC->account();
    } else {
      $R = false;
    }
    if ($R) {
      // Send mail..
      if (isset($_POST['mail']) && !isset($_POST['edit'])) {
        include(MM_BASE_PATH . 'control/mail.php');
        $f_r['{NAME}'] = $_POST['name'];
        $f_r['{USER}'] = $_POST['email'];
        $f_r['{PASS}'] = $_POST['password'];
        $msg           = strtr(file_get_contents(MM_BASE_PATH . 'content/language/email-templates/new-account.txt'), $f_r);
        $sbj           = str_replace('{website}', $SETTINGS->website, $emlang[2]);
        $mmMail->sendMail(array(
          'to_name' => $_POST['name'],
          'to_email' => $_POST['email'],
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
          'plain' => $msg,
          'htmlWrap' => 'yes'
        ), $gblang);
        $mmMail->smtpClose();
      }
      echo $JSON->encode(array(
        'OK',
        (isset($_POST['edit']) ? $adlang6[24] : $adlang6[23])
      ));
    } else {
      echo $JSON->encode(array(
        'ERR',
        $gblang[11],
        $jslang[4]
      ));
    }
    break;

  //-------------------------------------
  // Sales
  //-------------------------------------

  case 'sales':
    // Get next invoice number..
    include(REL_PATH . 'control/classes/class.sales.php');
    $S           = new salesPublic();
    $S->settings = $SETTINGS;
    $S->datetime = $DT;
    $R           = $SLS->addEditSale($ACC, $S->invoice(false));
    if ($R[0] && $R[1] > 0) {
      // Send mail for new sale if selected..
      if (isset($_POST['mailer']) && $_POST['mailer'] == 'yes') {
        include(MM_BASE_PATH . 'control/mail.php');
        include(REL_PATH . 'control/currencies.php');
        $order              = mswGetSaleOrder($R[1], $DB, $emvars);
        $totals             = mswGetSaleOrderTotals($R[1], $DB);
        // Mail tags..
        $f_r['{C1}']        = count($order['dl']);
        $f_r['{C2}']        = count($order['cd']);
        $f_r['{DOWNLOADS}'] = (!empty($order['dl']) ? implode(mswDefineNewline(), $order['dl']) : 'N/A');
        $f_r['{CDS}']       = (!empty($order['cd']) ? implode(mswDefineNewline(), $order['cd']) : 'N/A');
        $f_r['{SUB}']       = $totals['sub'];
        $f_r['{SHIP}']      = $totals['ship'];
        $f_r['{COUPON}']    = $totals['coupon'];
        $f_r['{CPN_CODE}']  = $totals['couponcode'];
        $f_r['{TAX}']       = $totals['tax'];
        $f_r['{RATE}']      = $totals['rate'];
        $f_r['{TAX2}']      = $totals['tax2'];
        $f_r['{RATE2}']     = $totals['rate2'];
        $f_r['{TCOUNT}']    = $totals['counts'][0];
        $f_r['{DCOUNT}']    = $totals['counts'][1];
        $f_r['{SHIP_ADDR}'] = mswShippingAddress($R[1], $DB);
        $f_r['{TOTAL}']     = $totals['total'];
        $f_r['{PASS}']      = $R[2];
        $f_r['{CURRENCY}']  = $currencies[$SETTINGS->currency];
        $f_r['{IP}']        = $_POST['ip'];
        foreach ($_POST AS $pK => $pV) {
          if (!is_array($pK) && !is_array($pV)) {
            $f_r['{' . strtoupper($pK) . '}'] = $pV;
          }
        }
        $msg = strtr(file_get_contents(MM_BASE_PATH . 'content/language/email-templates/new-sale' . ($R[2] ? '-new-account' : '') . '.txt'), $f_r);
        $sbj = str_replace('{website}', $SETTINGS->website, $emlang[$R[2] ? 4 : 3]);
        $mmMail->sendMail(array(
          'to_name' => $_POST['name'],
          'to_email' => $_POST['email'],
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
          'plain' => $msg,
          'htmlWrap' => 'yes'
        ), $gblang);
        $mmMail->smtpClose();
      }
      echo $JSON->encode(array(
        'OK',
        (isset($_POST['edit']) ? $adlang9[52] : $adlang9[51])
      ));
    } else {
      echo $JSON->encode(array(
        'ERR',
        $gblang[11],
        $jslang[4]
      ));
    }
    break;

  //-------------------------------------
  // Reset downloads..
  //-------------------------------------

  case 'reset':
    if (!empty($_POST['rem'])) {
      $ID = (int) $_POST['edit'];
      $SLS->resetDownloads($ID, $adlang9, $gblang[19]);
      // Send mail?
      if (isset($_POST['send-reset'])) {
        include(MM_BASE_PATH . 'control/mail.php');
        $order              = mswGetSaleOrder($ID, $DB, $emvars, $_POST['rem']);
        $Q                  = $DB->db_query("SELECT `invoice` FROM `".DB_PREFIX."sales` WHERE `id` = '{$ID}'");
        $INV                = $DB->db_object($Q);
        // Mail tags..
        $f_r['{DOWNLOADS}'] = (!empty($order['dl']) ? implode(mswDefineNewline(), $order['dl']) : 'N/A');
        $f_r['{NAME}']      = $_POST['e_name'];
        $f_r['{INV}']       = mswSaleInvoiceNumber($INV->invoice);
        $msg                = strtr(file_get_contents(MM_BASE_PATH . 'content/language/email-templates/download-reset.txt'), $f_r);
        $sbj                = str_replace('{website}', $SETTINGS->website, $emlang[5]);
        $mmMail->sendMail(array(
          'to_name' => $_POST['e_name'],
          'to_email' => $_POST['e_mail'],
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
          'plain' => $msg,
          'htmlWrap' => 'yes'
        ), $gblang);
        $mmMail->smtpClose();
      }
      echo $JSON->encode(array(
        'OK',
        count($_POST['rem']),
        (isset($_POST['send-reset']) ? 'yes' : 'no')
      ));
    } else {
      echo $JSON->encode(array(
        'ERR',
        $adlang9[57],
        $jslang[4]
      ));
    }
    break;

  //-------------------------------------
  // Clear history..
  //-------------------------------------

  case 'clear-history':
    $C = $SLS->clearHistory();
    echo $JSON->encode(array(
      $C,
      '<td>' . $adlang9[42] . '</td>'
    ));
    break;

  //-------------------------------------
  // Offers
  //-------------------------------------

  case 'offers':
    $R = $SYS->offers();
    if ($R) {
      echo $JSON->encode(array(
        'OK',
        (isset($_POST['edit']) ? $adlang11[15] : $adlang11[14])
      ));
    } else {
      echo $JSON->encode(array(
        'ERR',
        $gblang[11],
        $jslang[4]
      ));
    }
    break;

  //-------------------------------------
  // Coupons
  //-------------------------------------

  case 'coupons':
  $R = $SYS->coupons();
  if ($R) {
    echo $JSON->encode(array(
      'OK',
      (isset($_POST['edit']) ? $adlang16[8] : $adlang16[7])
    ));
  } else {
    echo $JSON->encode(array(
      'ERR',
      $gblang[11],
      $jslang[4]
    ));
  }
  break;

  //-------------------------------------
  // Gateways..
  //-------------------------------------

  case 'gateways':
    $R = $PAY->addEdit();
    if ($R) {
      echo $JSON->encode(array(
        'OK',
        (isset($_POST['edit']) ? $adlang3[12] : $adlang3[11])
      ));
    } else {
      echo $JSON->encode(array(
        'ERR',
        $gblang[11],
        $jslang[4]
      ));
    }
    break;

  //-------------------------------------
  // Page Management..
  //-------------------------------------

  case 'pages':
  case 'page-order':
    switch ($_GET['ajax']) {
      case 'pages':
        $R = $SYS->pageManagement();
        if ($R) {
          echo $JSON->encode(array(
            'OK',
            (isset($_POST['edit']) ? $adlang12[16] : $adlang12[15])
          ));
        } else {
          echo $JSON->encode(array(
            'ERR',
            $gblang[11],
            $jslang[4]
          ));
        }
        break;
      case 'page-order':
        $SYS->pageOrdering();
        echo $JSON->encode(array(
          'resp' => 'OK'
        ));
        break;
    }
    break;

  //-------------------------------------
  // Countries..
  //-------------------------------------

  case 'countries':
    $R = $SYS->addEditCountry();
    if ($R) {
      echo $JSON->encode(array(
        'OK',
        (isset($_POST['edit']) ? $adlang18[15] : $adlang18[14])
      ));
    } else {
      echo $JSON->encode(array(
        'ERR',
        $gblang[11],
        $jslang[4]
      ));
    }
    break;

  //-------------------------------------
  // Refresh Totals
  //-------------------------------------

  case 'total-refresh':
    if (!empty($_POST['newID'])) {
      foreach ($_POST['newID'] AS $n) {
        $nt                   = explode('#####', $n);
        $_POST['itemTotal'][] = $nt[1];
      }
    }
    if (!empty($_POST['itemTotal'])) {
      $totals = $_POST['itemTotal'];
      echo mswFormatPrice(array_sum($totals));
      exit;
    }
    echo '0.00';
    break;

  //-------------------------------------
  // Clear Clip Board
  //-------------------------------------

  case 'clear-clipboard':
  case 'clipboard-choice':
    switch ($_GET['ajax']) {
      case 'clear-clipboard':
        $C = $SLS->clearClipBoard();
        echo $JSON->encode(array(
          'count' => $C
        ));
        break;
      case 'clipboard-choice':
        $SLS->updateClipBoard();
        echo $JSON->encode(array(
          'OK'
        ));
        break;
    }
    break;

  //-------------------------------------
  // Delete..
  //-------------------------------------

  case 'delete':
  case 'delete-confirm':
    switch ($_GET['ajax']) {
      case 'delete-confirm':
        switch ($_GET['table']) {
          case 'collections':
            $txt = $adlang17[0];
            break;
          case 'styles':
            $txt = $adlang17[1];
            break;
          case 'sales':
            $txt = $adlang17[2];
            break;
          case 'accounts':
            $txt = $adlang17[3];
            break;
          case 'pages':
            $txt = $adlang17[4];
            break;
          case 'offers':
            $txt = $adlang17[5];
            break;
          case 'coupons':
            $txt = $adlang17[6];
            break;
          case 'countries':
            $txt = $adlang17[7];
            break;
          case 'tracks':
            $txt = $adlang17[8];
            break;
           case 'loghistory':
            $txt = $adlang17[9];
            break;
           default:
            exit;
            break;
        }
        echo $JSON->encode(array(
          mswSafeDisplay($gblang[24]),
          mswSafeDisplay($gblang[13]),
          $txt
        ));
        break;
      case 'delete':
        $SYS->delete();
        echo $JSON->encode(array(
          'OK'
        ));
        break;
    }
    break;

  //-------------------------------------
  // Auto complete
  //-------------------------------------

  case 'auto-previews':
  case 'auto-name':
  case 'auto-email':
  case 'auto-featured':
  case 'auto-cover-art':
  case 'auto-account-coupons':
    $arr = array();
    if (isset($_GET['term'])) {
      switch ($_GET['ajax']) {
        // Search mp3 previews..
        case 'auto-previews':
          $prev = mswFolderFiles(MM_BASE_PATH . PREVIEW_FOLDER, '*');
          $q    = $_GET['term'];
          $exts = array_map('strtolower', explode('|', SUPPORTED_MUSIC));
          foreach ($prev AS $pFile) {
            $fileBase = basename($pFile);
            if (!is_dir($pFile) && $fileBase && strpos($fileBase, '.') !== false) {
              $info = pathinfo($pFile);
              if (isset($info['extension'])) {
                $ext  = strtolower($info['extension']);
                if (in_array($ext, $exts) && strpos(strtolower($fileBase), strtolower($q)) !== false) {
                  $arr[] = substr($pFile, strlen(MM_BASE_PATH . PREVIEW_FOLDER) + 1);
                }
              }
            }
          }
          break;
        // Search account by name..
        case 'auto-name':
          $arr = $ACC->search('name', $_GET['term']);
          break;
        // Search account by email..
        case 'auto-email':
          $arr = $ACC->search('email', $_GET['term']);
          break;
        // Find featured music..
        case 'auto-featured':
          $arr = $MSC->featured($_GET['term']);
          break;
        // Find cover art..
        case 'auto-cover-art':
          $arr = $MSC->coverArtOther($_GET['term']);
          break;
        // Accounts for coupons
        case 'auto-account-coupons':
          $arr = $ACC->search(array('name','email'), $_GET['term'], true);
          break;
      }
      if (!empty($arr)) {
        sort($arr);
        echo $JSON->encode($arr);
        exit;
      }
    }
    echo $JSON->encode(array(
      $jslang[10]
    ));
    break;

  //--------------------------------
  // Update lock reason
  //--------------------------------

  case 'lock-reason':
    if (isset($_POST['reason']) && isset($_GET['id'])) {
      $SLS->updateLockReason((int) $_GET['id']);
    }
    echo $JSON->encode(array(
      'OK'
    ));
    break;

  //--------------------------------
  // Password generator
  //--------------------------------

  case 'pass-gen':
    echo $JSON->encode(array(
      'pass' => $ACC->password()
    ));
    break;

  //--------------------------------
  // Address loader
  //--------------------------------

  case 'address-loader':
    if (isset($_GET['em'])) {
      echo $JSON->encode(array(
        'addr' => $ACC->getAddress()
      ));
    }
    break;

  //--------------------------------
  // Check secure folder path
  //--------------------------------

  case 'check-sec-path':
    $res = (isset($_GET['path']) && is_dir($_GET['path']) ? 'OK' : 'ERR');
    echo $JSON->encode(array($res));
    break;

  //--------------------------------
  // Payment statuses
  //--------------------------------

  case 'pay-statuses':
    echo $JSON->encode($SLS->getStatuses());
    break;

  //--------------------------------
  // Get Tax Rate
  //--------------------------------

  case 'tax-rate':
    $rate  = 0;
    $rates = array($SETTINGS->deftax, $SETTINGS->deftax2);
    $ID    = (isset($_GET['id']) ? (int) $_GET['id'] : '0');
    $type  = (isset($_GET['t']) && in_array($_GET['t'],array('t','d')) ? $_GET['t'] : 't');
    $Q     = $DB->db_query("SELECT `tax`,`tax2` FROM `" . DB_PREFIX . "countries` WHERE `id` = '{$ID}'");
    $TAX   = $DB->db_object($Q);
    switch ($type) {
      case 't':
        $rate = ($TAX->tax == 'no' ? '0' : ($TAX->tax > 0 ? $TAX->tax : $rates[0]));
        break;
      case 'd':
        $rate = ($TAX->tax2 == 'no' ? '0' : ($TAX->tax2 > 0 ? $TAX->tax2 : $rates[1]));
        break;
    }
    echo $JSON->encode(array((int) $rate));
    break;

  //--------------------------------
  // Next invoice number
  //--------------------------------

  case 'next-invoice':
    include(REL_PATH . 'control/classes/class.sales.php');
    $S           = new salesPublic();
    $S->settings = $SETTINGS;
    $S->datetime = $DT;
    echo $JSON->encode(array(
      'inv' => mswSaleInvoiceNumber($S->invoice(false))
    ));
    break;

  //--------------------------------
  // Mailer
  //--------------------------------

  case 'mailer':
    if ($_POST['subject'] == '' || $_POST['msg'] == '' || empty($_POST['acc'])) {
      echo $JSON->encode(array(
        'ERR',
        $jslang[11],
        $jslang[4]
      ));
    } else {
      include(MM_BASE_PATH . 'control/mail.php');
      $sbj = $_POST['subject'];
      $cnt = count($_POST['acc']);
      for ($i = 0; $i < count($_POST['acc']); $i++) {
        $msg           = strtr($_POST['msg'], $f_r);
        $account       = explode('###', $_POST['acc'][$i]);
        $f_r['{NAME}'] = $account[0];
        $mmMail->sendMail(array(
          'to_name' => $account[0],
          'to_email' => $account[1],
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
          'reply-to' => ($_POST['email'] ? $_POST['email'] : $SETTINGS->email),
          'plain' => $msg,
          'htmlWrap' => 'yes',
          'alive' => ($cnt > 1 ? 'yes' : 'no')
        ), $gblang);
      }
      $mmMail->smtpClose();
      echo $JSON->encode(array(
        'OK',
        str_replace('{count}', $cnt, $adlang13[7])
      ));
    }
    break;

  //--------------------------
  // Login
  //--------------------------

  case 'login':
    include(PATH . 'control/access.php');
    $_POST['u'] = (isset($_POST['u']) ? $_POST['u'] : '');
    $_POST['p'] = (isset($_POST['p']) ? $_POST['p'] : '');
    if ($_POST['u'] != MM_ADMIN_USER || mswEncrypt($_POST['p'] . SECRET_KEY) != mswEncrypt(MM_ADMIN_PASS . SECRET_KEY)) {
      $eString[] = $adlang15[4];
    }
    if (empty($eString)) {
      if (IP) {
        $allowed = explode(',', IP);
        $current = explode(',', mswGetRealIPAddr());
        if (isset($current[0]) && !in_array($current[0], $allowed)) {
          $eString[] = $adlang15[5];
        }
      }
      if (empty($eString)) {
        $_SESSION['mm_access_' . mswEncrypt(SECRET_KEY)] = (SECRET_KEY . time());
        echo $JSON->encode(array(
          'OK'
        ));
      } else {
        echo $JSON->encode(array(
          implode('<br>', $eString)
        ));
      }
    } else {
      echo $JSON->encode(array(
        implode('<br>', $eString)
      ));
    }
    break;

  //--------------------------------
  // Graphs..
  //--------------------------------

  case 'graph-year':
  case 'graph-revenue':
  case 'graph-month':
  case 'stats-tracks':
  case 'stats-collections':
  case 'graph-settings':
  case 'graph-countries':
  case 'graph-gateway':
    include(PATH . 'control/classes/class.graphs.php');
    $G           = new graphs();
    $G->settings = $SETTINGS;
    $G->lang     = $adlang19;
    $G->dates    = $gbdates;
    switch($_GET['ajax']) {
      case 'graph-year':
        echo $JSON->encode($G->yearly());
        break;
      case 'graph-revenue':
        echo $JSON->encode($G->revenue());
        break;
      case 'graph-month':
        echo $JSON->encode($G->month());
        break;
      case 'stats-tracks':
        echo $JSON->encode($G->tracks());
        break;
      case 'stats-collections':
        echo $JSON->encode($G->collections());
        break;
      case 'graph-settings':
        $G->settings();
        echo $JSON->encode(array('OK'));
        break;
      case 'graph-countries':
        echo $JSON->encode($G->countries());
        break;
      case 'graph-gateway':
        echo $JSON->encode($G->gateways());
        break;
    }
    break;

  //-------------------------------
  // Import / Export
  //-------------------------------

  case 'impexp-col':
  case 'impexp-music':
    switch($_GET['ajax']) {
      // Import collections..
      case 'impexp-col':
        $name   = (isset($_FILES['csv']['name']) ? $_FILES['csv']['name'] : '');
        $tmp    = (isset($_FILES['csv']['tmp_name']) ? $_FILES['csv']['tmp_name'] : '');
        $styles = (!empty($_POST['styles']) ? $_POST['styles'] : array());
        if ($name && $tmp && !empty($styles)) {
          $cnt  = $MSC->importCollections($name, $tmp, $styles);
          echo $JSON->encode(array(
            'OK',
            str_replace('{count}',@number_format($cnt),$adlang22[12]),
            'actionMsg1'
          ));
        } else {
          echo $JSON->encode(array(
            'ERR',
            $jslang[23],
            $jslang[4]
          ));
        }
        break;
      // Import music tracks..
      case 'impexp-music':
        $name = (isset($_FILES['csv']['name']) ? $_FILES['csv']['name'] : '');
        $tmp  = (isset($_FILES['csv']['tmp_name']) ? $_FILES['csv']['tmp_name'] : '');
        $col  = (isset($_POST['collection']) && $_POST['collection'] > 0 ? $_POST['collection'] : '0');
        if ($name && $tmp && $col > 0) {
          $cnt  = $MSC->importTracks($name, $tmp, $col);
          echo $JSON->encode(array(
            'OK',
            str_replace('{count}',@number_format($cnt),$adlang22[13]),
            'actionMsg2'
          ));
        } else {
          echo $JSON->encode(array(
            'ERR',
            $jslang[24],
            $jslang[4]
          ));
        }
        break;
    }
    break;

  //-------------------------------
  // Resend licence agreement
  //-------------------------------

  case 'agreement':
    $ID   = (isset($_POST['edit']) ? (int) $_POST['edit'] : '0');
    $em   = (isset($_POST['e_mail']) ? $_POST['e_mail'] : '');
    $nm   = (isset($_POST['e_name']) ? $_POST['e_name'] : '');
    $ts   = (isset($_POST['e_ts']) ? $_POST['e_ts'] : $DT->utcTime());
    $copy = (isset($_POST['copyTo']) ? $_POST['copyTo'] : '');
    if ($ID > 0 && $em && $nm) {
      include(MM_BASE_PATH . 'control/mail.php');
      $saleD  = mswGetSaleOrderAgreement($ID, $DB, $emvars);
      $licmsg = str_replace(array(
        '{NAME}',
        '{EMAIL}',
        '{DATE}',
        '{INVOICE}',
        '{CD}',
        '{CDD}',
        '{TRACKS}',
        '{ID}'
      ), array(
        $nm,
        $em,
        $DT->dateTimeDisplay($ts, $SETTINGS->dateformat, $SETTINGS->timezone),
        mswSaleInvoiceNumber($_POST['invoice']),
        (!empty($saleD['cd']) ? implode(mswDefineNewline(), $saleD['cd']) : 'N/A'),
        (!empty($saleD['cdd']) ? implode(mswDefineNewline(), $saleD['cdd']) : 'N/A'),
        (!empty($saleD['dl']) ? implode(mswDefineNewline(), $saleD['dl']) : 'N/A'),
        $_POST['transaction']
      ), $SETTINGS->licmsg);
      $mmMail->sendMail(array(
        'to_name' => $nm,
        'to_email' => $em,
        'from_name' => ($SETTINGS->smtp_from ? $SETTINGS->smtp_from : $SETTINGS->website),
        'from_email' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
        'subject' => $SETTINGS->licsubj,
        'msg' => $mmMail->htmlWrapper(array(
          'global' => $gblang,
          'title' => $SETTINGS->licsubj,
          'header' => $SETTINGS->licsubj,
          'content' => mswNL2BR($licmsg),
          'footer' => (!defined('LICENCE_VER') || LICENCE_VER == 'locked' ? $gblang[41] : '')
        )),
        'reply-to' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
        'other' => $copy,
        'plain' => $licmsg,
        'htmlWrap' => 'yes'
      ), $gblang);
      $mmMail->smtpClose();
      echo $JSON->encode(array('OK'));
    } else {
      echo $JSON->encode(array(
        'ERR',
        $gblang[11].$jslang[18],
        $jslang[4]
      ));
    }
    break;

  //-------------------------------
  // Mail Test..
  //-------------------------------

  case 'send-mail-test':
    include(MM_BASE_PATH . 'control/mail.php');
    $msg = $adlang2[124];
    $sbj = str_replace('{website}', $SETTINGS->website, $emlang[17]);
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
    echo $JSON->encode(array('msg' => $adlang2[123]));
    break;

  //---------------------------------
  // REMOTE API OPTIONS
  //---------------------------------

  case 'api-tweet':
    $arr = array(
      'ERR',
      $gblang[11].$jslang[18],
      $jslang[4]
    );
    switch($_GET['ajax']) {
      case 'api-tweet':
        if (isset($_POST['tweet']) && $_POST['tweet']) {
          include(PATH . 'control/api/twitter/codebird.php');
          $tweetapi = $SBDR->params('twitter');
          if (isset($tweetapi['twitter']['conkey'])) {
            $CB  = new Codebird();
            $CB->setConsumerKey($tweetapi['twitter']['conkey'], $tweetapi['twitter']['consecret']);
            $cbi = $CB->getInstance();
            $cbi->setToken($tweetapi['twitter']['token'], $tweetapi['twitter']['key']);
            $params = array(
              'status' => $_POST['tweet']
            );
            $pingreply  = (array) $cbi->statuses_update($params);
            if (isset($pingreply['httpstatus']) && $pingreply['httpstatus'] == '200') {
              $arr[0] = 'OK';
            }
          }
        }
        break;
    }
    echo $JSON->encode($arr);
    break;

  case 'sum-tracks':
    $ID  = (isset($_GET['id']) ? (int) $_GET['id'] : '0');
    $Q   = $DB->db_query("SELECT ROUND(SUM(`cost`),2) AS `trackSum` FROM `".DB_PREFIX."music` WHERE `collection` = '{$ID}'");
    $TTL = $DB->db_object($Q);
    $sum = (isset($TTL->trackSum) ? @number_format($TTL->trackSum, 2, '.', '') : '0.00');
    echo $JSON->encode(array($sum));
    break;

}

?>