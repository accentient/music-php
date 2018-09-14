<?php

// All these things should always be present..fail if not..
if (!defined('PARENT') || !isset($_GET['ajax']) || !isset($_GET['id']) || !defined('BASE_HREF')) {
  exit;
}

include(PATH . 'control/mail.php');
include(PATH . 'control/classes/class.json.php');
$JSON = new jsonHandler();
$arr  = array(
  'resp' => 'err',
  'msg' => $jslang[12],
  'title' => $jslang[13],
  'image' => BASE_HREF . 'content/' . THEME . '/images/warning.png',
  'sys' => array()
);

switch ($_GET['ajax']) {
  case 'basket-coupon':
    $coupon = (isset($_POST['coupon']) && $_POST['coupon'] ? $_POST['coupon'] : 'no_coupon_code');
    $method = (isset($_POST['method']) && $_POST['method'] ? $_POST['method'] : '');
    $text   = array(
     'expired' => $pbbasket[26],
     'invalid' => $pbbasket[25]
    );
    if ($coupon) {
      $disCalc  = 'no';
      $discount = 'no';
      $CP       = ($coupon!='no_coupon_code' ? $CART->coupon($coupon, $systemAcc['id']) : array('none',''));
      switch($CP[0]) {
        case 'ok':
        case 'none':
          // Adjust if coupon applied..
          if ($CP[0]=='ok') {
            $discount                   = (substr($CP[1], -1) == '%' ? mswFormatPrice((substr($CP[1], 0, -1) * CART_TOTAL) / 100) : $CP[1]);
            $disCalc                    = $CP[1];
            // If discount is higher, total cost is max discount..
            if ($discount > CART_TOTAL) {
              $newPrice                 = '0.00';
              $discount                 = CART_TOTAL;
            } else {
              $newPrice                 = mswFormatPrice(CART_TOTAL - $discount);
            }
            // Coupon HTML..
            $fr = array(
              '{txt}' => $pbbasket[27],
              '{total}' => mswCurrencyFormat(mswFormatPrice($discount),$SETTINGS->curdisplay)
            );
            $html                     = $BUILDER->template($fr,'basket-coupon.tpl');
            $hidden                   = array(
              $coupon,
              $discount
            );
          } else {
            $newPrice                 = CART_TOTAL;
            $html                     = '';
            $hidden                   = array();
          }
          $ship                     = ($method ? $CART->getShipping($newPrice, $method) : '0.00');
          $tax                      = $CART->getTax($BUILDER, $systemAcc['accCountry'], 'tangible', $ship, array($disCalc, $discount));
          $tax2                     = $CART->getTax($BUILDER, $systemAcc['accCountry'], 'digital', '0.00', array($disCalc, $discount));
          // If coupon was applied, add discounts to array..
          if (!empty($hidden)) {
            $hidden[]  = $tax[3];
            $hidden[]  = $tax2[3];
          }
          $arr['resp']              = 'OK';
          $arr['sys']               = array(
            'sub' => mswCurrencyFormat(CART_TOTAL, $SETTINGS->curdisplay),
            'ship' => ($ship > 0 ? mswCurrencyFormat($ship, $SETTINGS->curdisplay) : $pbprofile[14]),
            'tax' => (mswFormatPrice($tax[0] + $tax2[0]) > 0 ? mswCurrencyFormat(mswFormatPrice($tax[0] + $tax2[0]), $SETTINGS->curdisplay) : 'no'),
            'total' => mswCurrencyFormat(mswFormatPrice($newPrice + $ship + mswFormatPrice($tax[0] + $tax2[0])), $SETTINGS->curdisplay),
            'couponhtml' => $html
          );
          // Set total session vars..
          $_SESSION['basketHidden'] = array(
            $newPrice,
            ($ship > 0 ? mswFormatPrice($ship) : '0.00'),
            ($tax[0] > 0 ? mswFormatPrice($tax[0]) : '0.00'),
            substr($tax[1], 0, -1),
            ($tax2[0] > 0 ? mswFormatPrice($tax2[0]) : '0.00'),
            substr($tax2[1], 0, -1),
            mswFormatPrice($newPrice + $ship + mswFormatPrice($tax[0] + $tax2[0])),
            $tax[2],
            $hidden,
            $tax2[2]
          );
          break;
        case 'expired':
          $arr['msg'] = str_replace('{expired}',$CP[1],$text['expired']);
          break;
        case 'invalid':
          $arr['msg'] = $text['invalid'];
          break;
      }
    } else {
      $arr['resp'] = 'CLEARED';
      print_r($_SESSION);
    }
    break;
  case 'basket-shipping':
    $vars = array(
      'method' => (isset($_POST['method']) ? $_POST['method'] : ''),
      'address1' => (isset($_POST['address1']) ? $_POST['address1'] : ''),
      'address2' => (isset($_POST['address2']) ? $_POST['address2'] : ''),
      'city' => (isset($_POST['city']) ? $_POST['city'] : ''),
      'county' => (isset($_POST['county']) ? $_POST['county'] : ''),
      'postcode' => (isset($_POST['postcode']) ? $_POST['postcode'] : ''),
      'country' => (isset($_POST['country']) ? $_POST['country'] : ''),
      'shipping' => (isset($_POST['ship']) ? $_POST['ship'] : 'no')
    );
    switch ($vars['shipping']) {
      case 'yes':
        if ($vars['method'] == '' || $vars['address1'] == '' || $vars['city'] == '' || $vars['county'] == '' || $vars['postcode'] == '' || $vars['country'] == '') {
          $eString[]  = $pbbasket[21];
          $arr['msg'] = implode('<br>', $eString);
        }
        break;
      default:
        break;
    }
    if (empty($eString)) {
      $ACC->update(array(
        'shipping' => $vars['method']
      ), $systemAcc['accID'], array(
        'address1' => $vars['address1'],
        'address2' => $vars['address2'],
        'city' => $vars['city'],
        'county' => $vars['county'],
        'postcode' => $vars['postcode'],
        'country' => $vars['country']
      ));
      $ship                     = $CART->getShipping(CART_TOTAL, $vars['method']);
      $tax                      = $CART->getTax($BUILDER, $systemAcc['accCountry'], 'tangible', $ship, array('no','no'));
      $tax2                     = $CART->getTax($BUILDER, $systemAcc['accCountry'], 'digital', '0.00', array('no','no'));
      $arr['resp']              = 'OK';
      $arr['sys']               = array(
        'sub' => mswCurrencyFormat(CART_TOTAL, $SETTINGS->curdisplay),
        'ship' => ($ship > 0 ? mswCurrencyFormat($ship, $SETTINGS->curdisplay) : $pbprofile[14]),
        'tax' => (mswFormatPrice($tax[0] + $tax2[0]) > 0 ? mswCurrencyFormat(mswFormatPrice($tax[0] + $tax2[0]), $SETTINGS->curdisplay) : 'no'),
        'total' => mswCurrencyFormat(mswFormatPrice(CART_TOTAL + $ship + mswFormatPrice($tax[0] + $tax2[0])), $SETTINGS->curdisplay),
        'couponhtml' => ''
      );
      // Set total session vars..
      $_SESSION['basketHidden'] = array(
        CART_TOTAL,
        ($ship > 0 ? mswFormatPrice($ship) : '0.00'),
        ($tax[0] > 0 ? mswFormatPrice($tax[0]) : '0.00'),
        substr($tax[1], 0, -1),
        ($tax2[0] > 0 ? mswFormatPrice($tax2[0]) : '0.00'),
        substr($tax2[1], 0, -1),
        mswFormatPrice(CART_TOTAL + $ship + mswFormatPrice($tax[0] + $tax2[0])),
        $tax[2],
        array(),
        $tax2[2]
      );
    }
    break;
  case 'basket-login':
    $vars = array(
      'email' => (isset($_POST['email']) ? $_POST['email'] : ''),
      'pass' => (isset($_POST['pass']) ? $_POST['pass'] : ''),
      'name' => (isset($_POST['name']) ? $_POST['name'] : ''),
      'method' => (isset($_POST['mthd']) ? $_POST['mthd'] : ''),
      'country' => (isset($_POST['ctry']) ? $_POST['ctry'] : ''),
      'rescnt' => (isset($_POST['cnt']) ? $_POST['cnt'] : '')
    );
    // Check min purchase amount..
    if ($SETTINGS->minpurchase > 0) {
      if (CART_TOTAL < $SETTINGS->minpurchase) {
        $arr['msg'] = str_replace('{min}',mswCurrencyFormat(mswFormatPrice($SETTINGS->minpurchase), $SETTINGS->curdisplay),$pbbasket[28]);
        echo $JSON->encode($arr);
        exit;
      }
    }
    if (mswIsValidEmail($vars['email']) == 'no' || $vars['pass'] == '') {
      $eString[]  = $pbbasket[16];
      $arr['msg'] = implode('<br>', $eString);
    } else {
      $EX = $ACC->account(array(
        'email' => $vars['email']
      ));
      // Is this a valid account?
      if (isset($EX['id']) && $EX['pass'] == sha1(SECRET_KEY . $vars['pass'])) {
        $e     = strtolower($vars['email']);
        $token = sha1(SECRET_KEY . $e . sha1(SECRET_KEY . $vars['pass']));
        $ACC->update(array(
          'token' => $token
        ), $EX['accID']);
        $_SESSION['mmEntryData']  = array(
          'id' => $token,
          'email' => $e
        );
        // Are login events enabled?
        if ($EX['login'] == 'yes') {
          include(PATH . 'control/classes/class.ip.php');
          $IPGEO           = new geoIP();
          $IPGEO->settings = $SETTINGS;
          $lookup          = $IPGEO->lookup($_SERVER['REMOTE_ADDR'],$gblang[19]);
          $ipAddress       = mswIPAddr(true);
          if (isset($ipAddress[0])) {
            $ACC->loginevent(array(
              'account' => $EX['accID'],
              'ip' => $ipAddress[0],
              'ts' => $DT->utcTime(),
              'iso' => strtolower($lookup['iso']),
              'country' => $lookup['country']
            ));
            // Are we notifying admin about multiple ips?
            if ($SETTINGS->accloginflag > 0) {
              $diffIP = $ACC->ipclicks($EX['accID']);
              if (count($diffIP) >= $SETTINGS->accloginflag) {
                $newIPLog        = $ACC->ipclicks($EX['accID']);
                $f_r['{REPORT}'] = $ACC->ipReport($newIPLog, $DT);
                $f_r['{LIMIT}']  = $SETTINGS->accloginflag;
                $f_r['{NAME}']   = $EX['name'];
                $msg             = strtr(file_get_contents(PATH . 'content/language/email-templates/wm-account-ip-alert.txt'), $f_r);
                $sbj             = str_replace('{website}', $SETTINGS->website, $emlang[18]);
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
            }
          }
        }
        $fr                       = array(
          '{name}' => mswSafeDisplay($EX['name']),
          '{email}' => mswSafeDisplay($vars['email'])
        );
        $ship                     = $CART->getShipping(CART_TOTAL, $EX['shipping']);
        $tax                      = $CART->getTax($BUILDER, $EX['accCountry'], 'tangible', $ship, array('no','no'));
        $tax2                     = $CART->getTax($BUILDER, $EX['accCountry'], 'digital', '0.00', array('no','no'));
        $arr['resp']              = 'VALID';
        $arr['sys']               = array(
          'method' => $EX['shipping'],
          'address1' => mswSafeDisplay($EX['address1']),
          'address2' => mswSafeDisplay($EX['address2']),
          'city' => mswSafeDisplay($EX['city']),
          'county' => mswSafeDisplay($EX['county']),
          'postcode' => mswSafeDisplay($EX['postcode']),
          'country' => $EX['country'],
          'sub' => mswCurrencyFormat(CART_TOTAL, $SETTINGS->curdisplay),
          'ship' => ($ship > 0 ? mswCurrencyFormat($ship, $SETTINGS->curdisplay) : $pbprofile[14]),
          'tax' => (mswFormatPrice($tax[0] + $tax2[0]) > 0 ? mswCurrencyFormat(mswFormatPrice($tax[0] + $tax2[0]), $SETTINGS->curdisplay) : 'no'),
          'total' => mswCurrencyFormat(mswFormatPrice(CART_TOTAL + $ship + mswFormatPrice($tax[0] + $tax2[0])), $SETTINGS->curdisplay),
          'couponhtml' => ''
        );
        // Set total session vars..
        $_SESSION['basketHidden'] = array(
          CART_TOTAL,
          ($ship > 0 ? mswFormatPrice($ship) : '0.00'),
          ($tax[0] > 0 ? mswFormatPrice($tax[0]) : '0.00'),
          substr($tax[1], 0, -1),
          ($tax2[0] > 0 ? mswFormatPrice($tax2[0]) : '0.00'),
          substr($tax2[1], 0, -1),
          mswFormatPrice(CART_TOTAL + $ship + mswFormatPrice($tax[0] + $tax2[0])),
          $tax[2],
          array(),
          $tax2[2]
        );
        $arr['msg']               = $BUILDER->template($fr, 'basket-account-logged-in.tpl');
      } else {
        // New account..
        if (!isset($EX['id'])) {
          if ($vars['name'] == '') {
            $eString[]  = $pbbasket[17];
            $arr['msg'] = implode('<br>', $eString);
          } elseif ($vars['rescnt'] == '0') {
            $eString[]  = $pbbasket[30];
            $arr['msg'] = implode('<br>', $eString);
          } elseif (strlen($vars['pass']) < $SETTINGS->minpass) {
            $eString[]  = str_replace('{min}', $SETTINGS->minpass, $pbprofile[20]);
            $arr['msg'] = implode('<br>', $eString);
          } else {
            $code  = $ACC->password(15, true);
            $ID    = $ACC->add(array(
              'system1' => $code,
              'ts' => $DT->utcTime(),
              'enabled' => 'no',
              'name' => $vars['name'],
              'email' => $vars['email'],
              'country' => $vars['rescnt'],
              'timezone' => $SETTINGS->timezone,
              'pass' => sha1(SECRET_KEY . $vars['pass']),
              'ip' => $_SERVER['REMOTE_ADDR'],
              'login' => $SETTINGS->acclogin
            ), array(
              'address1' => ''
            ));
            $e     = strtolower($vars['email']);
            $token = sha1(SECRET_KEY . $e . sha1(SECRET_KEY . $vars['pass']));
            $ACC->update(array(
              'token' => $token
            ), $ID);
            $_SESSION['mmEntryData'] = array(
              'id' => $token,
              'email' => $e
            );
            $f_r['{NAME}']           = $vars['name'];
            $f_r['{CODE}']           = $code;
            $f_r['{ID}']             = $ID;
            $msg                     = strtr(file_get_contents(PATH . 'content/language/email-templates/account-verification.txt'), $f_r);
            $sbj                     = str_replace('{website}', $SETTINGS->website, $emlang[9]);
            $mmMail->sendMail(array(
              'to_name' => $vars['name'],
              'to_email' => $vars['email'],
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
            $fr                       = array(
              '{name}' => mswSafeDisplay($vars['name']),
              '{email}' => mswSafeDisplay($vars['email'])
            );
            $arr['resp']              = 'NEW';
            $arr['msg']               = $BUILDER->template($fr, 'basket-account-logged-in.tpl');
            $ship                     = $CART->getShipping(CART_TOTAL, $vars['method']);
            $tax                      = $CART->getTax($BUILDER, $vars['rescnt'], 'tangible', $ship, array('no','no'));
            $tax2                     = $CART->getTax($BUILDER, $vars['rescnt'], 'digital', '0.00', array('no','no'));
            $arr['sys']               = array(
              'sub' => mswCurrencyFormat(CART_TOTAL, $SETTINGS->curdisplay),
              'ship' => ($ship > 0 ? mswCurrencyFormat($ship, $SETTINGS->curdisplay) : $pbprofile[14]),
              'tax' => (mswFormatPrice($tax[0] + $tax2[0]) > 0 ? mswCurrencyFormat(mswFormatPrice($tax[0] + $tax2[0]), $SETTINGS->curdisplay) : 'no'),
              'total' => mswCurrencyFormat(mswFormatPrice(CART_TOTAL + $ship + mswFormatPrice($tax[0] + $tax2[0])), $SETTINGS->curdisplay),
              'couponhtml' => ''
            );
            // Set total session vars..
            $_SESSION['basketHidden'] = array(
              CART_TOTAL,
              ($ship > 0 ? mswFormatPrice($ship) : '0.00'),
              ($tax[0] > 0 ? mswFormatPrice($tax[0]) : '0.00'),
              substr($tax[1], 0, -1),
              ($tax2[0] > 0 ? mswFormatPrice($tax2[0]) : '0.00'),
              substr($tax2[1], 0, -1),
              mswFormatPrice(CART_TOTAL + $ship + mswFormatPrice($tax[0] + $tax2[0])),
              $tax[2],
              array(),
              $tax2[2]
            );
          }
        } else {
          $arr['msg'] = $jslang[17];
        }
      }
    }
    break;
  case 'basket-checkout':
    if ($SETTINGS->termsenable == 'yes' && !isset($_POST['terms'])) {
      $arr['msg'] = $jslang[22];
    } else {
      $arr['resp'] = 'OK';
    }
    break;
  case 'create':
    $vars = array(
      'name' => (isset($_POST['name']) ? $_POST['name'] : ''),
      'email' => (isset($_POST['email']) ? $_POST['email'] : ''),
      'timezone' => (isset($_POST['timezone']) ? $_POST['timezone'] : ''),
      'country' => (isset($_POST['accCountry']) ? $_POST['accCountry'] : ''),
      'passwd' => (isset($_POST['passwd']) ? $_POST['passwd'] : ''),
      'passwd2' => (isset($_POST['passwd2']) ? $_POST['passwd2'] : ''),
      'method' => (isset($_POST['method']) ? (int) $_POST['method'] : ''),
      'address1' => (isset($_POST['address1']) ? $_POST['address1'] : ''),
      'address2' => (isset($_POST['address2']) ? $_POST['address2'] : ''),
      'city' => (isset($_POST['city']) ? $_POST['city'] : ''),
      'county' => (isset($_POST['county']) ? $_POST['county'] : ''),
      'postcode' => (isset($_POST['postcode']) ? $_POST['postcode'] : ''),
      'scountry' => (isset($_POST['addCountry']) ? $_POST['addCountry'] : '')
    );
    if ($vars['name'] == '') {
      $eString[] = $pbprofile[15];
    }
    if (mswIsValidEmail($vars['email']) == 'no') {
      $eString[] = $pbprofile[16];
    } else {
      $EX = $ACC->account(array(
        'email' => $vars['email']
      ));
      if (isset($EX['id'])) {
        $eString[] = $pbprofile[17];
      }
    }
    if (!in_array($vars['timezone'], array_keys($timezones))) {
      $eString[] = $pbprofile[18];
    }
    if ($vars['passwd'] == '') {
      $eString[] = str_replace('{min}', $SETTINGS->minpass, $pbprofile[23]);
    } else {
      if (strlen($vars['passwd']) < $SETTINGS->minpass) {
        $eString[] = str_replace('{min}', $SETTINGS->minpass, $pbprofile[20]);
      } else {
        if ($vars['passwd'] != $vars['passwd2']) {
          $eString[] = $pbprofile[19];
        }
      }
    }
    if (empty($eString)) {
      // Add..
      $code = $ACC->password(15, true);
      // Insert with basics to get ID..
      $ID   = $ACC->add(array(
        'system1' => $code,
        'ts' => $DT->utcTime(),
        'enabled' => 'no',
        'login' => $SETTINGS->acclogin
      ), array(
        'address1' => $vars['address1']
      ));
      // Update..
      if ($ID > 0) {
        $affRows = $ACC->update(array(
          'name' => $vars['name'],
          'email' => $vars['email'],
          'timezone' => $vars['timezone'],
          'country' => $vars['country'],
          'pass' => sha1(SECRET_KEY . $vars['passwd']),
          'shipping' => $vars['method'],
          'ip' => $_SERVER['REMOTE_ADDR']
        ), $ID, array(
          'address1' => $vars['address1'],
          'address2' => $vars['address2'],
          'city' => $vars['city'],
          'county' => $vars['county'],
          'postcode' => $vars['postcode'],
          'country' => $vars['scountry']
        ));
      }
      // Send verification email..
      if ($ID > 0) {
        $f_r['{NAME}'] = $vars['name'];
        $f_r['{CODE}'] = $code;
        $f_r['{ID}']   = $ID;
        $msg           = strtr(file_get_contents(PATH . 'content/language/email-templates/account-verification.txt'), $f_r);
        $sbj           = str_replace('{website}', $SETTINGS->website, $emlang[9]);
        $mmMail->sendMail(array(
          'to_name' => $vars['name'],
          'to_email' => $vars['email'],
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
      $arr = array(
        'resp' => 'OK',
        'modal' => array(
          'label' => '<i class="fa fa-check fa-fw"></i> ' . $pbprofile[24],
          'body' => '<div style="padding:20px">' . str_replace('{email}', mswSafeDisplay($vars['email']), $pbprofile[25]) . '</div>',
          'footer' => 'hide',
          'button_text' => '',
          'button_url' => ''
        )
      );
    } else {
      $arr['msg'] = implode('<br>', $eString);
    }
    break;
  // Account profile..
  case 'profile':
    if (LOGGED_IN == 'yes' && isset($systemAcc['id'])) {
      $vars = array(
        'name' => (isset($_POST['name']) ? $_POST['name'] : ''),
        'email' => (isset($_POST['email']) ? $_POST['email'] : ''),
        'timezone' => (isset($_POST['timezone']) ? $_POST['timezone'] : ''),
        'country' => (isset($_POST['accCountry']) ? $_POST['accCountry'] : ''),
        'passwd' => (isset($_POST['passwd']) ? $_POST['passwd'] : ''),
        'passwd2' => (isset($_POST['passwd2']) ? $_POST['passwd2'] : ''),
        'method' => (isset($_POST['method']) ? (int) $_POST['method'] : ''),
        'address1' => (isset($_POST['address1']) ? $_POST['address1'] : ''),
        'address2' => (isset($_POST['address2']) ? $_POST['address2'] : ''),
        'city' => (isset($_POST['city']) ? $_POST['city'] : ''),
        'county' => (isset($_POST['county']) ? $_POST['county'] : ''),
        'postcode' => (isset($_POST['postcode']) ? $_POST['postcode'] : ''),
        'scountry' => (isset($_POST['addCountry']) ? $_POST['addCountry'] : '')
      );
      if ($vars['name'] == '') {
        $eString[] = $pbprofile[15];
      }
      if (mswIsValidEmail($vars['email']) == 'no') {
        $eString[] = $pbprofile[16];
      } else {
        if ($vars['email'] != $systemAcc['email']) {
          $EX = $ACC->account(array(
            'email' => $vars['email'],
            'compare' => 'AND `' . DB_PREFIX . 'accounts`.`id` != \'' . $systemAcc['id'] . '\''
          ));
          if (isset($EX['id'])) {
            $eString[] = $pbprofile[17];
          }
        }
      }
      if (!in_array($vars['timezone'], array_keys($timezones))) {
        $eString[] = $pbprofile[18];
      }
      if ($vars['passwd']) {
        if (strlen($vars['passwd']) < $SETTINGS->minpass) {
          $eString[] = str_replace('{min}', $SETTINGS->minpass, $pbprofile[20]);
        } else {
          if ($vars['passwd'] != $vars['passwd2']) {
            $eString[] = $pbprofile[19];
          }
        }
      }
      if (empty($eString)) {
        // Update..
        $affRows = $ACC->update(array(
          'name' => $vars['name'],
          'email' => $vars['email'],
          'timezone' => $vars['timezone'],
          'country' => $vars['country'],
          'pass' => ($vars['passwd'] ? sha1(SECRET_KEY . $vars['passwd']) : $systemAcc['pass']),
          'shipping' => $vars['method'],
          'ip' => $_SERVER['REMOTE_ADDR']
        ), $systemAcc['id'], array(
          'address1' => $vars['address1'],
          'address2' => $vars['address2'],
          'city' => $vars['city'],
          'county' => $vars['county'],
          'postcode' => $vars['postcode'],
          'country' => $vars['scountry']
        ));
        // Send emails..
        if ($affRows > 0) {
          $f_r['{NAME}']  = $vars['name'];
          $f_r['{EMAIL}'] = $vars['email'];
          $f_r['{IP}']    = $_SERVER['REMOTE_ADDR'];
          // Customer email..
          if (isset($notify['cusprof'])) {
            $msg = strtr(file_get_contents(PATH . 'content/language/email-templates/cus-profile-update.txt'), $f_r);
            $sbj = str_replace('{website}', $SETTINGS->website, $emlang[7]);
            $mmMail->sendMail(array(
              'to_name' => $vars['name'],
              'to_email' => $vars['email'],
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
          // Webmaster email..
          if (isset($notify['webprof'])) {
            $msg = strtr(file_get_contents(PATH . 'content/language/email-templates/wm-profile-update.txt'), $f_r);
            $sbj = str_replace('{website}', $SETTINGS->website, $emlang[8]);
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
        }
        $arr = array(
          'resp' => 'OK',
          'modal' => array(
            'label' => '<i class="fa fa-check fa-fw"></i> ' . $pbprofile[21],
            'body' => '<div style="padding:20px">' . $pbprofile[22] . '</div>',
            'footer' => 'hide',
            'button_text' => '',
            'button_url' => ''
          )
        );
      } else {
        $arr['msg'] = implode('<br>', $eString);
      }
    }
    break;
  // Add to basket..
  case 'add':
  case 'add-tracks':
    $chop = explode('_', $_GET['id']);
    if (isset($chop[0], $chop[1])) {
      $ID = (int) $chop[0];
      if ($ID > 0 && in_array($chop[1], array(
        'CD',
        'MP3'
      ))) {
        if ($_GET['ajax'] == 'add-tracks' && empty($_POST['track'])) {
          $arr['msg'] = $jslang[14];
        } else {
          // For tracks, sum total is cost..
          if ($_GET['ajax'] == 'add-tracks') {
            $Q       = $DB->db_query("SELECT ROUND(SUM(`cost`),2) AS `trackTotal` FROM `" . DB_PREFIX . "music`
		                   WHERE `id` IN(" . mswSafeString(implode(',', array_keys($_POST['track'])), $DB) . ")
				               ");
            $C       = $DB->db_object($Q);
            $costing = mswFormatPrice($C->trackTotal);
            $typ     = 'track';
            $disc    = $COSTING->offer($C->trackTotal, 'track', $ID);
          } else {
            $Q       = $DB->db_query("SELECT `cost`,`costcd` FROM `" . DB_PREFIX . "collections` WHERE `id` = '{$ID}'");
            $C       = $DB->db_object($Q);
            $costing = ($chop[1] == 'CD' ? $C->costcd : $C->cost);
            $typ     = ($chop[1] == 'CD' ? 'cd' : 'col');
            $disc    = ($chop[1] == 'CD' ? $COSTING->offer($C->costcd, 'cd', $ID) : $COSTING->offer($C->cost, 'col', $ID));
          }
          $CART->add(array(
            'collection' => $ID,
            'cost' => $costing,
            'discount' => $disc,
            'type' => $chop[1],
            'tracks' => ($_GET['ajax'] == 'add-tracks' ? $_POST['track'] : array())
          ));
          $items = $CART->modalItems($BUILDER, $pbcatlang);
          $arr   = array(
            'resp' => 'OK',
            'count' => $CART->count(),
            'modal' => array(
              'label' => '<i class="fa fa-check fa-fw"></i> ' . $pbcatlang[3],
              'body' => $items,
              'footer' => str_replace(array(
                '{count}',
                '{total}'
              ), array(
                $CART->count(),
                mswCurrencyFormat($CART->total(), $SETTINGS->curdisplay)
              ), $pbcatlang[5]),
              'button_text' => '<i class="fa fa-shopping-cart fa-fw"></i> ' . $pbcatlang[4],
              'button_url' => BASE_HREF . $SEO->url('basket', array(), 'yes') // basket
            )
          );
        }
      }
    }
    break;
  case 'rem-modal':
    if (isset($_GET['id']) && isset($_SESSION['cartItems'][$_GET['id']]['void']) && $_SESSION['cartItems'][$_GET['id']]['void'] == 'no') {
      $_SESSION['cartItems'][$_GET['id']]['void'] = 'yes';
    }
    $cc  = $CART->count();
    $arr = array(
      'resp' => 'OK',
      'modal' => array(
        'footer' => str_replace(array(
          '{count}',
          '{total}'
        ), array(
          $cc,
          mswCurrencyFormat($CART->total(), $SETTINGS->curdisplay)
        ), $pbcatlang[5])
      ),
      'count' => $cc
    );
    break;
  case 'rem-basket':
    if (isset($_GET['id']) && isset($_SESSION['cartItems'][$_GET['id']]['void']) && $_SESSION['cartItems'][$_GET['id']]['void'] == 'no') {
      $_SESSION['cartItems'][$_GET['id']]['void'] = 'yes';
    }
    $arr = array(
      'resp' => 'OK',
      'total' => mswCurrencyFormat($CART->total(), $SETTINGS->curdisplay),
      'count' => $CART->count(),
      'nothing' => $pbbasket[5]
    );
    break;
  case 'login':
    if (isset($_POST['e']) && isset($_POST['p']) && in_array($_GET['id'], array(
      'forgot',
      'enter'
    ))) {
      switch ($_GET['id']) {
        case 'enter':
          if ($_POST['e'] == '' || $_POST['p'] == '') {
            $arr['msg'] = $jslang[16];
          } else {
            if (mswIsValidEmail($_POST['e']) == 'ok') {
              $ACNT = $ACC->account(array(
                'email' => $_POST['e'],
                'pass' => $_POST['p']
              ));
              if (isset($ACNT['accID'])) {
                $e     = strtolower($_POST['e']);
                $token = sha1(SECRET_KEY . $e . sha1(SECRET_KEY . $_POST['p']));
                $ACC->update(array(
                  'token' => $token
                ), $ACNT['accID']);
                $_SESSION['mmEntryData'] = array(
                  'id' => $token,
                  'email' => $e
                );
                // Are login events enabled?
                if ($ACNT['login'] == 'yes') {
                  include(PATH . 'control/classes/class.ip.php');
                  $IPGEO           = new geoIP();
                  $IPGEO->settings = $SETTINGS;
                  $lookup          = $IPGEO->lookup($_SERVER['REMOTE_ADDR'],$gblang[19]);
                  $ipAddress       = mswIPAddr(true);
                  if (isset($ipAddress[0])) {
                    $ACC->loginevent(array(
                      'account' => $ACNT['accID'],
                      'ip' => $ipAddress[0],
                      'ts' => $DT->utcTime(),
                      'iso' => strtolower($lookup['iso']),
                      'country' => $lookup['country']
                    ));
                    // Are we notifying admin about multiple ips?
                    if ($SETTINGS->accloginflag > 0) {
                      $diffIP = $ACC->ipclicks($ACNT['accID']);
                      if (count($diffIP) >= $SETTINGS->accloginflag) {
                        $newIPLog        = $ACC->ipclicks($ACNT['accID']);
                        $f_r['{REPORT}'] = $ACC->ipReport($newIPLog, $DT);
                        $f_r['{LIMIT}']  = $SETTINGS->accloginflag;
                        $f_r['{NAME}']   = $ACNT['name'];
                        $msg             = strtr(file_get_contents(PATH . 'content/language/email-templates/wm-account-ip-alert.txt'), $f_r);
                        $sbj             = str_replace('{website}', $SETTINGS->website, $emlang[18]);
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
                    }
                  }
                }
                $arr                     = array(
                  'resp' => 'rdr',
                  'wind' => BASE_HREF . $SEO->url('account', array(), 'yes')
                );
              } else {
                $arr['msg'] = $jslang[17];
              }
            } else {
              $arr['msg'] = $jslang[15];
            }
          }
          break;
        case 'forgot':
          if ($_POST['e'] == '') {
            $arr['msg'] = $jslang[15];
          } else {
            if (mswIsValidEmail($_POST['e']) == 'ok') {
              $ACNT = $ACC->account(array(
                'email' => $_POST['e']
              ));
              // Valid account, so reset password and send email..
              if (isset($ACNT['id'])) {
                $newPass = $ACC->password();
                $ACC->update(array(
                  'pass' => sha1(SECRET_KEY . $newPass)
                ), $ACNT['id']);
                $f_r['{NAME}']  = $ACNT['name'];
                $f_r['{EMAIL}'] = $ACNT['email'];
                $f_r['{PASS}']  = $newPass;
                $msg            = strtr(file_get_contents(PATH . 'content/language/email-templates/password-reset.txt'), $f_r);
                $sbj            = str_replace('{website}', $SETTINGS->website, $emlang[6]);
                $mmMail->sendMail(array(
                  'to_name' => $ACNT['name'],
                  'to_email' => $ACNT['email'],
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
              // Return message..
              $arr = array(
                'resp' => 'OK',
                'modal' => array(
                  'label' => '<i class="fa fa-check fa-fw"></i> ' . $pbaccount[14],
                  'body' => '<div style="padding:20px">' . str_replace('{email}', mswSafeDisplay($_POST['e']), $pbaccount[15]) . '</div>',
                  'footer' => 'hide',
                  'button_text' => '',
                  'button_url' => ''
                )
              );
            } else {
              $arr['msg'] = $jslang[15];
            }
          }
          break;
      }
    }
    break;
  case 'method-reload':
    $ID = (int) $_GET['id'];
    if ($ID > 0) {
      $method = $BUILDER->methods($ID);
      $arr    = array(
        'resp' => 'OK',
        'method' => $method
      );
    }
    break;
  case 'address':
    $ID = (int) $_GET['id'];
    if (isset($systemAcc['email']) && $ID > 0) {
      $EX = $ACC->account(array(
        'email' => $systemAcc['email']
      ));
      if (isset($EX['shipping'])) {
        $arr['resp'] = 'OK';
        $arr['sys']  = array(
          'method' => $EX['shipping'],
          'address1' => mswSafeDisplay($EX['address1']),
          'address2' => mswSafeDisplay($EX['address2']),
          'city' => mswSafeDisplay($EX['city']),
          'county' => mswSafeDisplay($EX['county']),
          'postcode' => mswSafeDisplay($EX['postcode']),
          'country' => $EX['country'],
          'couponhtml' => ''
        );
      }
    }
    break;
  case 'resend-verification':
    if (isset($systemAcc['email'])) {
      $code = $ACC->password(15, true);
      $ACC->update(array(
        'system1' => $code,
        'system2' => 'resend'
      ), $systemAcc['id']);
      $f_r['{NAME}'] = $systemAcc['name'];
      $f_r['{CODE}'] = $code;
      $f_r['{ID}']   = $systemAcc['id'];
      $msg           = strtr(file_get_contents(PATH . 'content/language/email-templates/account-verification.txt'), $f_r);
      $sbj           = str_replace('{website}', $SETTINGS->website, $emlang[9]);
      $mmMail->sendMail(array(
        'to_name' => $systemAcc['name'],
        'to_email' => $systemAcc['email'],
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
      // Return message..
      $arr = array(
        'resp' => 'OK',
        'modal' => array(
          'label' => '<i class="fa fa-check fa-fw"></i> ' . $pbaccount[21],
          'body' => '<div style="padding:20px">' . str_replace('{email}', mswSafeDisplay($systemAcc['email']), $pbaccount[22]) . '</div>',
          'footer' => 'hide',
          'button_text' => '',
          'button_url' => ''
        )
      );
    }
    break;
  case 'tac':
    if ($SETTINGS->termsenable == 'yes') {
      $arr  = array(
       'resp' => 'OK',
          'modal' => array(
            'label' => '<i class="fa fa-legal fa-fw"></i> ' . $checklang[12],
            'body' => '<div style="padding:10px">' . mswNL2BR($SETTINGS->termsmsg) . '</div>',
            'footer' => 'hide',
            'button_text' => '',
            'button_url' => ''
          )
      );
    }
    break;
  case 'clear-all-basket':
    $_SESSION['cartItems'] = array();
    unset($_SESSION['cartItems']);
    $arr  = array(
       'resp' => 'OK-BASKET',
       'nothing' => $pbbasket[5]
    );
    break;
  case 'tax-info':
    $tax  = $CART->taxBreakdown($BUILDER);
    $frep = array(
      '{ITEMS}' => $tax['items'],
      '{TOTAL}' => $tax['total'],
      '{COUPON}' => $tax['coupon'],
      '{COUNTRY}' => $tax['country'],
      '{RATE}' => $tax['rate'],
      '{SHIPPING}' => $tax['shipping'],
      '{AMOUNT}' => $tax['amount'],
      '{ITEMS2}' => $tax['items2'],
      '{TOTAL2}' => $tax['total2'],
      '{COUPON2}' => $tax['coupon2'],
      '{COUNTRY2}' => $tax['country2'],
      '{RATE2}' => $tax['rate2'],
      '{AMOUNT2}' => $tax['amount2'],
      '{TOTAL-TAX}' => $tax['total-tax']
    );
    $html = strtr(file_get_contents(PATH . 'content/language/tax-info.txt'), $frep);
    $arr  = array(
     'resp' => 'OK',
        'modal' => array(
          'label' => '<i class="fa fa-info fa-fw"></i> ' . $pbbasket[31],
          'body' => '<div style="padding:10px">' . mswNL2BR($html) . '</div>',
          'footer' => 'hide',
          'button_text' => '',
          'button_url' => ''
        )
    );
    break;
  // Contact page..
  case 'contact-page':
    // Assign data variables..
    $data   = array(
      'name' => (isset($_POST['nm']) ? $_POST['nm'] : ''),
      'email' => (isset($_POST['em']) ? $_POST['em'] : ''),
      'subject' => (isset($_POST['sb']) ? $_POST['sb'] : ''),
      'comments' => (isset($_POST['cm']) ? $_POST['cm'] : ''),
      'robot' => (isset($_POST['sp_rb_chk']) ? $_POST['sp_rb_chk'] : ''),
    );
    // Basic error check..
    foreach (array_keys($data) AS $k) {
      if ($k != 'robot' && $data[$k] == '') {
        $arr = array(
          'resp' => 'err',
          'title' => $pbcontact[8],
          'msg' => $gblang[11]
        );
        echo $JSON->encode($arr);
        exit;
      }
    }
    // If the robot field has data we don`t send the email..
    // Notification is still shown though..
    if ($data['robot'] == '') {
      // Send message..
      $mmMail->sendMail(array(
        'to_name' => $SETTINGS->website,
        'to_email' => $SETTINGS->email,
        'from_name' => $data['name'],
        'from_email' => $data['email'],
        'subject' => $data['subject'],
        'msg' => $mmMail->htmlWrapper(array(
          'global' => $gblang,
          'title' => $data['subject'],
          'header' => $data['subject'],
          'content' => mswNL2BR($data['comments']),
          'footer' => (!defined('LICENCE_VER') || LICENCE_VER == 'locked' ? $gblang[41] : '')
        )),
        'reply-to' => ($SETTINGS->smtp_email ? $SETTINGS->smtp_email : $SETTINGS->email),
        'other' => $SETTINGS->smtp_other,
        'plain' => $data['comments'],
        'htmlWrap' => 'yes'
      ), $gblang);
      $mmMail->smtpClose();
    }
    // Show message..
    $arr    = array(
     'resp' => 'OK',
       'modal' => array(
          'label' => '<i class="fa fa-check fa-fw"></i> ' . $pbcontact[6],
          'body' => '<div style="padding:10px">' . mswNL2BR($pbcontact[7]) . '</div>',
          'footer' => 'hide',
          'button_text' => '',
          'button_url' => ''
        )
    );
    break;
  // Search filters
  case 'search-filters':
    include(PATH . 'control/system/filters.php');
    if (isset($_POST['filter']) && in_array($_POST['filter'], array_keys($listFilters))) {
      $_SESSION['mmFilters'] = $_POST['filter'];
    } else {
      if (isset($_SESSION['mmFilters'])) {
        $_SESSION['mmFilters'] = '';
        unset($_SESSION['mmFilters']);
      }
    }
    break;
}

echo $JSON->encode($arr);
exit;

?>