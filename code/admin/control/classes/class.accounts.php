<?php

class accounts extends db {

  public $settings;
  public $dl;
  public $datetime;

  public function clearLoginHistory($ID) {
    db::db_query("DELETE FROM `" . DB_PREFIX . "accounts_login` WHERE `account` = '{$ID}'");
    mswTableTruncationRoutine(array(
      'accounts_login'
    ), $this);
  }

  public function exportLoginHistory($l) {
    $csv    = '';
    $del    = ',';
    $file   = PATH . 'backup/account-login-history.csv';
    $SQL    = '';
    $ID     = (isset($_GET['id']) ? (int) $_GET['id'] : '0');
    $fromTo = array('','');
    if (isset($_GET['f'],$_GET['t'])) {
      if ($_GET['f'] && $_GET['t']) {
        $from = $this->datetime->dateToTS($_GET['f']);
        $to   = $this->datetime->dateToTS($_GET['t']);
        if ($from > 0 && $to > 0) {
          $fromTo[0] = $_GET['f'];
          $fromTo[1] = $_GET['t'];
          $SQL       = 'AND (DATE(FROM_UNIXTIME(`ts`)) BETWEEN \'' . date('Y-m-d', $from) . '\' AND \'' . date('Y-m-d', $to) . '\')';
        }
      }
    }
    $Q  = db::db_query("SELECT * FROM `".DB_PREFIX."accounts_login` WHERE `account` = '{$ID}' $SQL ORDER BY `id` DESC");
    while ($H = db::db_object($Q)) {
      $csv[] = mswCleanCSV(mswCleanData($H->ip), $del) . $del .
               mswCleanCSV(mswCleanData($H->country), $del) . $del .
               mswCleanCSV(mswCleanData($this->datetime->dateTimeDisplay($H->ts,$this->settings->dateformat)), $del) . $del .
               mswCleanCSV(mswCleanData($this->datetime->dateTimeDisplay($H->ts,$this->settings->timeformat)), $del);
    }
    if ($csv) {
      $this->dl->write($file, $l . mswDefineNewline() . implode(mswDefineNewline(), $csv));
      if (file_exists($file)) {
        $this->dl->dl($file, 'text/csv');
      }
    }
    header("Location: index.php?p=login-history&id=" . $ID);
    exit;
  }

  public function account() {
    // Filter post data for insert..
    $_POST   = mswSafeImport($_POST, $this);
    // Pre-Checks..
    $enabled = (isset($_POST['enabled']) && in_array($_POST['enabled'], array(
      'yes',
      'no'
    )) ? $_POST['enabled'] : 'yes');
    $bypass  = (isset($_POST['bypass']) && in_array($_POST['bypass'], array(
      'yes',
      'no'
    )) ? $_POST['bypass'] : 'yes');
    $ts      = ($_POST['ts'] ? $this->datetime->dateToTS($_POST['ts']) : '0');
    $pass    = (!isset($_POST['edit']) ? sha1(SECRET_KEY . $_POST['password']) : ($_POST['password'] ? sha1(SECRET_KEY . $_POST['password']) : $_POST['pass']));
    $ship    = (int) $_POST['shipping'];
    $cntry   = (int) $_POST['accCountry'];
    $login   = (isset($_POST['login']) && in_array($_POST['login'], array(
      'yes',
      'no'
    )) ? $_POST['login'] : 'yes');
    // Edit or add..
    if (isset($_POST['edit'])) {
      $ID = (int) $_POST['edit'];
      $Q  = db::db_query("UPDATE `" . DB_PREFIX . "accounts` SET
            `name`         = '{$_POST['name']}',
            `email`        = '{$_POST['email']}',
            `pass`         = '{$pass}',
            `ip`           = '{$_POST['ip']}',
            `ts`           = '{$ts}',
            `enabled`      = '{$enabled}',
            `notes`        = '{$_POST['notes']}',
            `timezone`     = '{$_POST['timezone']}',
            `country`      = '{$cntry}',
            `shipping`     = '{$ship}',
            `bypass`       = '{$bypass}',
            `login`        = '{$login}'
            WHERE `id`     = '{$ID}'
            ");
    } else {
      $Q  = db::db_query("INSERT INTO `" . DB_PREFIX . "accounts` (
            `name`,
            `email`,
            `pass`,
            `ip`,
            `ts`,
            `enabled`,
            `notes`,
            `timezone`,
            `shipping`,
            `bypass`,
            `login`
            ) VALUES (
            '{$_POST['name']}',
            '{$_POST['email']}',
            '{$pass}',
            '{$_POST['ip']}',
            '{$ts}',
            '{$enabled}',
            '{$_POST['notes']}',
            '{$_POST['timezone']}',
            '{$ship}',
            '{$bypass}',
            '{$login}'
            )");
      $ID = db::db_last_insert_id();
    }
    // Shipping address..
    if (isset($_POST['addbook']) && $_POST['addbook'] > 0) {
      $BID = (int) $_POST['addbook'];
      $cnt = (int) $_POST['addCountry'];
      $A   = db::db_query("UPDATE `" . DB_PREFIX . "accounts_addr` SET
             `address1`  = '{$_POST['address1']}',
             `address2`  = '{$_POST['address2']}',
             `city`      = '{$_POST['city']}',
             `country`   = '{$cnt}',
             `county`    = '{$_POST['county']}',
             `postcode`  = '{$_POST['postcode']}'
             WHERE `id`  = '{$BID}'
             ");
    } else {
      $cnt = (int) $_POST['addCountry'];
      $A   = db::db_query("INSERT INTO `" . DB_PREFIX . "accounts_addr` (
             `account`,
             `address1`,
             `address2`,
             `city`,
             `country`,
             `county`,
             `postcode`,
             `default`
             ) VALUES (
             '{$ID}',
             '{$_POST['address1']}',
             '{$_POST['address2']}',
             '{$_POST['city']}',
             '{$cnt}',
             '{$_POST['county']}',
             '{$_POST['postcode']}',
             'yes'
             )");
    }
    return $Q;
  }

  public function export($l) {
    $csv  = '';
    $del  = ',';
    $file = PATH . 'backup/accounts.csv';
    $SQL  = 'WHERE `enabled` = \'yes\' ';
    if ($_POST['from'] && $_POST['to']) {
      $from = $this->datetime->dateToTS($_POST['from']);
      $to   = $this->datetime->dateToTS($_POST['to']);
      if ($from > 0 && $to > 0) {
        $SQL .= 'AND DATE(FROM_UNIXTIME(`ts`)) BETWEEN \'' . date('Y-m-d', $from) . '\' AND \'' . date('Y-m-d', $to) . '\'';
      }
    }
    $Q = db::db_query("SELECT `name`,`email` FROM `" . DB_PREFIX . "accounts` $SQL ORDER BY `name`");
    while ($A = db::db_object($Q)) {
      $csv[] = mswCleanCSV(mswCleanData($A->name), $del) . $del . mswCleanCSV(mswCleanData($A->email), $del);
    }
    if ($csv) {
      $this->dl->write($file, $l . mswDefineNewline() . implode(mswDefineNewline(), $csv));
      if (file_exists($file)) {
        $this->dl->dl($file, 'text/csv');
      }
    }
    header("Location: index.php?p=export-accounts&nodata=yes");
    exit;
  }

  public function password($min = 0) {
    $pass = '';
    if ($min > 0) {
      $this->settings->minpass = $min;
    }
    $sec = array(
      'A',
      'B',
      'C',
      'D',
      'E',
      'F',
      'G',
      'H',
      'I',
      'J',
      'K',
      'L',
      'M',
      'N',
      'O',
      'P',
      'Q',
      'R',
      'S',
      'T',
      'U',
      'V',
      'W',
      'X',
      'Y',
      'Z',
      'a',
      'b',
      'c',
      'd',
      'e',
      'f',
      'g',
      'h',
      'i',
      'j',
      'k',
      'l',
      'm',
      'n',
      'o',
      'p',
      'q',
      'r',
      's',
      't',
      'u',
      'v',
      'w',
      'x',
      'y',
      'z',
      '0',
      '1',
      '2',
      '3',
      '4',
      '5',
      '6',
      '7',
      '8',
      '9',
      '[',
      ']',
      '&',
      '*',
      '(',
      ')',
      '#',
      '!',
      '%'
    );
    for ($i = 0; $i < count($sec); $i++) {
      $rand = rand(0, (count($sec) - 1));
      $char = $sec[$rand];
      $pass .= $char;
      if ($this->settings->minpass == ($i + 1)) {
        return $pass;
      }
    }
    return $pass;
  }

  public function getAddress() {
    $add = array();
    $q   = db::db_query("SELECT * FROM `" . DB_PREFIX . "accounts_addr`
           LEFT JOIN `" . DB_PREFIX . "accounts`
           ON `" . DB_PREFIX . "accounts`.`id`       = `" . DB_PREFIX . "accounts_addr`.`account`
           WHERE `" . DB_PREFIX . "accounts`.`email` = '" . mswSafeString($_GET['em'], $this) . "'
           ");
    $A   = db::db_object($q);
    if (isset($A->address1)) {
      if ($A->address1) {
        $add[] = mswSafeDisplay($A->address1);
      }
      if ($A->address2) {
        $add[] = mswSafeDisplay($A->address2);
      }
      if ($A->city) {
        $add[] = mswSafeDisplay($A->city);
      }
      if ($A->country) {
        $c = mswGetCountry($A->country, $this);
        if (isset($c->name)) {
          $add[] = $c->name;
        }
      }
      if ($A->county) {
        $add[] = mswSafeDisplay($A->county);
      }
      if ($A->postcode) {
        $add[] = mswSafeDisplay($A->postcode);
      }
      return array(
        mswSafeDisplay($A->name),
        (!empty($add) ? implode(mswDefineNewline(), $add) : ''),
        $A->ip,
        $A->shipping
      );
    }
    return array(
      '',
      '',
      '',
      ''
    );
  }

  public function check($data = '', $field = 'email', $id = 0) {
    $SQL = '';
    if ($id > 0) {
      $SQL = "AND `id` != '{$id}'";
    }
    $q = db::db_query("SELECT `id` FROM `" . DB_PREFIX . "accounts`
         WHERE `" . $field . "` = '" . mswSafeString($data, $this) . "'
         $SQL
         ");
    return (db::db_rows($q) > 0 ? 'exists' : 'accept');
  }

  public function search($field, $data, $a = false) {
    $ar = array();
    if (is_array($field)) {
      $d  = array();
      foreach ($field AS $f) {
        $d[] = '`' . $f . '` LIKE \'%' . mswSafeString($data, $this) . '%\'';
      }
      $q  = db::db_query("SELECT `id`,`name`,`email` FROM `" . DB_PREFIX . "accounts`
            WHERE " . implode('OR ', $d) . "
            AND `enabled`         = 'yes'
            ");
    } else {
      $q  = db::db_query("SELECT `id`,`name`,`email` FROM `" . DB_PREFIX . "accounts`
            WHERE `" . $field . "` LIKE '%" . mswSafeString($data, $this) . "%'
            AND `enabled`         = 'yes'
            ");
    }
    while ($A = db::db_object($q)) {
      if ($a) {
        $ar[] = array(
          'value' => $A->id,
          'label' => mswSafeDisplay($A->name) . ' (' . $A->email . ')'
        );
      } else {
        $ar[] = mswSafeDisplay($A->name) . ' (' . $A->email . ')';
      }
    }
    return $ar;
  }

}

?>