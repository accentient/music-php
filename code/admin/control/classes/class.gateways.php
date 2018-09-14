<?php

class gateways extends db {

  public $settings;

  public function addEdit() {
    // Filter post data for insert..
    $_POST   = mswSafeImport($_POST, $this);
    // Pre-Checks..
    $enabled = (isset($_POST['status']) && in_array($_POST['status'], array(
      'yes',
      'no'
    )) ? $_POST['status'] : 'yes');
    $default = (isset($_POST['default']) && in_array($_POST['default'], array(
      'yes',
      'no'
    )) ? $_POST['default'] : 'no');
    // Edit or add..
    if (isset($_POST['edit'])) {
      $ID = (int) $_POST['edit'];
      $Q  = db::db_query("UPDATE `" . DB_PREFIX . "gateways` SET
            `display`       = '{$_POST['display']}',
            `liveserver`    = '{$_POST['liveserver']}',
            `sandboxserver` = '{$_POST['sandboxserver']}',
            `image`         = '{$_POST['image']}',
            `webpage`       = '{$_POST['webpage']}',
            `status`        = '{$enabled}',
            `class`         = '{$_POST['class']}',
            `default`       = '{$_POST['default']}'
            WHERE `id`      = '{$ID}'
            ");
      $ID = $_POST['edit'];
    } else {
      $Q  = db::db_query("INSERT INTO `" . DB_PREFIX . "gateways` (
            `display`,
            `liveserver`,
            `sandboxserver`,
            `image`,
            `webpage`,
            `status`,
            `class`,
            `default`
            ) VALUES (
            '{$_POST['display']}',
            '{$_POST['liveserver']}',
            '{$_POST['sandboxserver']}',
            '{$_POST['image']}',
            '{$_POST['webpage']}',
            '{$enabled}',
            '{$_POST['class']}',
            '{$_POST['default']}'
            )");
      $ID = db::db_last_insert_id();
    }
    // If this was the default gateway, clear previous..
    if ($default == 'yes') {
      db::db_query("UPDATE `" . DB_PREFIX . "gateways` SET
	    `default`   = 'no'
      WHERE `id` != '{$ID}'
	    ");
    }
    // Params..
    if (!empty($_POST['param'])) {
      gateways::params($ID);
    }
    return $Q;
  }

  // Parameters..
  public function params($id) {
    db::db_query("DELETE FROM `" . DB_PREFIX . "gateways_params` WHERE `gateway` = '{$id}'");
    if (!empty($_POST['param'])) {
      for ($i = 0; $i < count($_POST['param']); $i++) {
        if (trim($_POST['param'][$i])) {
          db::db_query("INSERT INTO `" . DB_PREFIX . "gateways_params` (
		      `gateway`,
          `param`,
	        `value`
          ) VALUES (
          '{$id}',
          '{$_POST['param'][$i]}',
          '{$_POST['value'][$i]}'
          )");
        }
      }
    }
  }

}

?>