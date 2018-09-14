<?php

class accPublic extends db {

  public $settings;

  public function ipclicks($ID) {
    $ar = array();
    $Q  = db::db_query("SELECT `ip`,`ts`,`country` FROM `" . DB_PREFIX . "accounts_login`
          WHERE `account` = '{$ID}'
          GROUP BY `ip`
          ORDER BY `id`
          ");
    while ($H = db::db_object($Q)) {
      $ar[$H->ip] = array(
        $H->ts,
        $H->country
      );
    }
    return $ar;
  }

  public function ipReport($ar, $dt) {
    $str = array();
    if (!empty($ar)) {
      foreach ($ar AS $ipK => $ipV) {
        $str[] = '[' . $ipK . ' - ' . $ipV[1] . '] - ' . $dt->dateTimeDisplay($ipV[0], $this->settings->dateformat) . ' / ' . $dt->dateTimeDisplay($ipV[0], $this->settings->timeformat);
      }
    }
    return (!empty($str) ? implode(mswDefineNewline(), $str) : 'N/A');
  }

  public function loginevent($data=array()) {
    db::db_query("INSERT INTO `" . DB_PREFIX . "accounts_login` (
    `account`,
	  `ip`,
	  `ts`,
	  `iso`,
	  `country`
    ) VALUES (
    '{$data['account']}',
    '{$data['ip']}',
    '{$data['ts']}',
    '{$data['iso']}',
    '{$data['country']}'
    )");
  }

  public function login() {
    $l = 'no';
    if (isset($_SESSION['mmEntryData']['id'], $_SESSION['mmEntryData']['email'])) {
      $a = accPublic::account(array(
        'email' => $_SESSION['mmEntryData']['email']
      ));
      // Check token..
      if (isset($a['id'])) {
        if ($a['token'] == sha1(SECRET_KEY . $a['email'] . $a['pass'])) {
          return $a;
        }
      }
    }
    return 'no';
  }

  public function account($data = array()) {
    $email = mswSafeString($data['email'], $this);
    $pass  = (isset($data['pass']) ? sha1(SECRET_KEY . $data['pass']) : '');
    $token = (isset($data['token']) ? sha1(SECRET_KEY . $data['token']) : '');
    $en    = (isset($data['enabled']) ? 'yes' : 'no');
    $Q     = db::db_query("SELECT *,
             `" . DB_PREFIX . "accounts`.`id` AS `accID`,
             `" . DB_PREFIX . "accounts`.`country` AS `accCountry`,
             `" . DB_PREFIX . "accounts_addr`.`country` AS `addCountry`
             FROM `" . DB_PREFIX . "accounts`
             LEFT JOIN `" . DB_PREFIX . "accounts_addr`
             ON `" . DB_PREFIX . "accounts`.`id` = `" . DB_PREFIX . "accounts_addr`.`account`
             WHERE `email` = '{$email}'
             " . ($pass ? 'AND `pass` = \'' . $pass . '\'' : '') . "
             " . ($token ? 'AND `token` = \'' . $token . '\'' : '') . "
             " . ($en == 'yes' ? 'AND `enabled` = \'' . $en . '\'' : '') . "
             " . (isset($data['compare']) ? $data['compare'] : '') . "
             LIMIT 1
             ");
    $A     = db::db_object($Q);
    return (isset($A->id) ? (array) $A : array());
  }

  public function add($data, $ship) {
    $sql  = array(
      array(),
      array()
    );
    $sql2 = array(
      array(),
      array()
    );
    // Account data..
    if (!empty($data)) {
      foreach ($data AS $k => $v) {
        $sql[0][] = '`' . $k . '`' . mswDefineNewline();
        $sql[1][] = "'" . mswSafeString($v, $this) . "'" . mswDefineNewline();
      }
      if (!empty($sql[0])) {
        $Q = db::db_query("INSERT INTO `" . DB_PREFIX . "accounts` (
	        " . implode(',', $sql[0]) . "
            ) VALUES (
	        " . implode(',', $sql[1]) . "
			)");
      }
    }
    // Insert ID..
    $ID = db::db_last_insert_id();
    // Add to ship array..
    $ship['account'] = $ID;
    // Shipping data..
    if (!empty($ship)) {
      foreach ($ship AS $k => $v) {
        $sql2[0][] = '`' . $k . '`' . mswDefineNewline();
        $sql2[1][] = "'" . mswSafeString($v, $this) . "'" . mswDefineNewline();
      }
      if (!empty($sql2[0])) {
        $Q = db::db_query("INSERT INTO `" . DB_PREFIX . "accounts_addr` (
	           " . implode(',', $sql2[0]) . "
             ) VALUES (
	           " . implode(',', $sql2[1]) . "
			)");
      }
    }
    return $ID;
  }

  public function update($data = array(), $id, $ship = array()) {
    $sql  = array();
    $sql2 = array();
    $rows = 0;
    // Account data..
    if (!empty($data)) {
      foreach ($data AS $k => $v) {
        $sql[] = '`' . $k . '` = \'' . mswSafeString($v, $this) . '\'' . mswDefineNewline();
      }
      if (!empty($sql)) {
        $Q = db::db_query("UPDATE `" . DB_PREFIX . "accounts` SET
	           " . implode(',', $sql) . "
             WHERE `id` = '{$id}'
	           ");
      }
      $rows = db::db_aff_rows();
    }
    // Shipping data..
    if (!empty($ship)) {
      foreach ($ship AS $k => $v) {
        $sql2[] = '`' . $k . '` = \'' . mswSafeString($v, $this) . '\'' . mswDefineNewline();
      }
      if (!empty($sql2)) {
        $Q = db::db_query("UPDATE `" . DB_PREFIX . "accounts_addr` SET
	           " . implode(',', $sql2) . "
             WHERE `account` = '{$id}'
	           ");
      }
    }
    return $rows;
  }

  public function password($min = 0, $alpha = false) {
    $pass = '';
    if ($min > 0) {
      $this->settings->minpass = $min;
    }
    if ($alpha) {
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
        '9'
      );
    } else {
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
    }
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

}

?>