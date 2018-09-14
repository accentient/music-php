<?php

class music extends db {

  public $settings;
  public $datetime;
  public $dl;

  public function tracks() {
    // Filter post data for insert..
    $_POST      = mswSafeImport($_POST, $this);
    $collection = (int) $_POST['collection'];
    $ts         = $this->datetime->utcTime();
    $count      = 0;
    if (empty($_POST['mp3file'])) {
      return $count;
    }
    if (isset($_POST['update-tracks'])) {
      for ($i = 0; $i < count($_POST['mp3file']); $i++) {
        $ID = (isset($_POST['mp3ID'][$i]) ? (int) $_POST['mp3ID'][$i] : '0');
        if ($ID > 0) {
          $length = music::digitCheck($_POST['hrs'][$i]) . ':' . music::digitCheck($_POST['mins'][$i]) . ':' . music::digitCheck($_POST['secs'][$i]);
          $Q      = db::db_query("UPDATE `" . DB_PREFIX . "music` SET
                    `title`       = '{$_POST['title'][$i]}',
                    `mp3file`     = '{$_POST['mp3file'][$i]}',
                    `previewfile` = '{$_POST['previewfile'][$i]}',
                    `length`      = '{$length}',
                    `bitrate`     = '{$_POST['bitrate'][$i]}',
                    `samplerate`  = '{$_POST['samplerate'][$i]}',
                    `cost`        = '{$_POST['cost'][$i]}',
                    `updated`     = '{$ts}'
                    WHERE `id`    = '{$ID}'
                    ");
          ++$count;
        }
      }
    } else {
      // Get count of tracks in collection and clear first if required..
      if (isset($_POST['clear'])) {
        db::db_query("DELETE FROM `" . DB_PREFIX . "music` WHERE `collection` = '{$collection}'");
        mswTableTruncationRoutine(array(
          'music'
        ), $this);
        $total = 0;
      } else {
        $total = db::db_rowcount('music WHERE `collection` = \'' . $collection . '\'');
      }
      file_put_contents(REL_PATH . 'logs/XXX.txt', print_r($_POST, true), FILE_APPEND);
      if (!empty($_POST['addtrack'])) {
        for ($i = 0; $i < count($_POST['addtrack']); $i++) {
          $tk = ($_POST['addtrack'][$i] - 1);
          if (isset($_POST['mp3file'][$tk]) && $_POST['mp3file'][$tk]) {
            $length = music::digitCheck($_POST['hrs'][$tk]) . ':' . music::digitCheck($_POST['mins'][$tk]) . ':' . music::digitCheck($_POST['secs'][$tk]);
            if (LICENCE_VER == 'locked' && db::db_rowcount('music') >= LIC_RESTR_TRKS) {
              return 'Trk-Err';
            }
            db::db_query("INSERT INTO `" . DB_PREFIX . "music` (
            `title`,
            `collection`,
            `mp3file`,
            `previewfile`,
            `length`,
            `bitrate`,
            `samplerate`,
            `cost`,
            `order`,
            `ts`,
            `updated`
            ) VALUES (
            '{$_POST['title'][$tk]}',
            '{$collection}',
            '{$_POST['mp3file'][$tk]}',
            '{$_POST['previewfile'][$tk]}',
            '{$length}',
            '{$_POST['bitrate'][$tk]}',
            '{$_POST['samplerate'][$tk]}',
            '{$_POST['cost'][$tk]}',
            '" . (++$total) . "',
            '{$ts}',
            '{$ts}'
            )");
            ++$count;
          }
        }
      }
    }
    return $count;
  }

  public function ordering() {
    if (!empty($_POST['tracks'])) {
      foreach ($_POST['tracks'] AS $k => $v) {
        $order = ($k + 1);
        $next  = ($order < 10 ? '00' . $order : ($order < 100 ? '0' . $order : $order));
        db::db_query("UPDATE `" . DB_PREFIX . "music` SET
        `order`    = '{$next}'
        WHERE `id` = '{$v}'
        ");
      }
    }
  }

  public function styleOrdering() {
    if (!empty($_POST['styles'])) {
      foreach ($_POST['styles'] AS $k => $v) {
        $order = ($k + 1);
        $next  = ($order < 10 ? '00' . $order : ($order < 100 ? '0' . $order : $order));
        db::db_query("UPDATE `" . DB_PREFIX . "music_styles` SET
        `orderby`  = '{$next}'
        WHERE `id` = '{$v}'
        ");
      }
    }
  }

  public function styles() {
    // Pre-Checks..
    $_POST['enabled'] = (isset($_POST['enabled']) && in_array($_POST['enabled'], array(
      'yes',
      'no'
    )) ? $_POST['enabled'] : 'no');
    $type = (isset($_POST['type']) ? (int) $_POST['type'] : '0');
    $coll = (isset($_POST['collection']) && $type > 0 ? (int) $_POST['collection'] : '0');
    // Edit or add..
    if (isset($_POST['edit'])) {
      $ID = (int) $_POST['edit'];
      $Q  = db::db_query("UPDATE `" . DB_PREFIX . "music_styles` SET
            `name`       = '" . mswSafeString($_POST['name'], $this) . "',
            `slug`       = '" . mswSafeString($_POST['slug'], $this) . "',
            `enabled`    = '{$_POST['enabled']}',
            `type`       = '{$type}',
            `collection` = '{$coll}'
            WHERE `id`  = '{$_POST['edit']}'
            ");
      return array(
        'ok',
        $Q
      );
    } else {
      $added = 0;
      if (trim($_POST['styles'])) {
        foreach (explode(mswDefineNewline(), $_POST['styles']) AS $style) {
          $st = mswSafeString($style, $this);
          $sg = '';
          if (strpos($style, '||') !== false) {
            $chop = array_map('trim',explode('||', $style));
            $sg   = $chop[1];
            $st   = mswSafeString($chop[0], $this);
          }
          $Q = db::db_query("INSERT INTO `" . DB_PREFIX . "music_styles` (
               `name`,
               `slug`,
               `enabled`,
               `type`,
               `collection`
               ) VALUES (
               '{$st}',
               '{$sg}',
               '{$_POST['enabled']}',
               '{$type}',
               '{$coll}'
               )");
            ++$added;
        }
      }
      return array(
        'ok',
        $added
      );
    }
  }

  public function collections() {
    // Filter post data for insert..
    $_POST    = mswSafeImport($_POST, $this);
    // Pre-Checks..
    $enabled  = (isset($_POST['enabled']) && in_array($_POST['enabled'], array(
      'yes',
      'no'
    )) ? $_POST['enabled'] : 'no');
    $social   = (!empty($_POST['social']) ? mswSafeString(serialize($_POST['social']), $this) : '');
    $views    = (int) $_POST['views'];
    $released = ($_POST['released'] ? $this->datetime->dateToTS($_POST['released']) : '0');
    $ts       = $this->datetime->utcTime();
    $related  = (!empty($_POST['related']) ? mswSafeString(serialize($_POST['related']), $this) : '');
    $covero   = (!empty($_POST['coverother']) ? mswSafeString(serialize($_POST['coverother']), $this) : '');
    // Edit or add..
    if (isset($_POST['edit'])) {
      $ID = (int) $_POST['edit'];
      $Q  = db::db_query("UPDATE `" . DB_PREFIX . "collections` SET
            `name`           = '{$_POST['name']}',
            `title`          = '{$_POST['title']}',
            `metakeys`       = '{$_POST['metakeys']}',
            `metadesc`       = '{$_POST['metadesc']}',
            `slug`           = '{$_POST['slug']}',
            `information`    = '{$_POST['information']}',
            `searchtags`     = '{$_POST['searchtags']}',
            `social`         = '{$social}',
            `coverart`       = '{$_POST['coverart']}',
            `coverartother`  = '{$covero}',
            `enabled`        = '{$enabled}',
            `views`          = '{$views}',
            `cost`           = '{$_POST['cost']}',
            `costcd`         = '{$_POST['costcd']}',
            `released`       = '{$released}',
            `catnumber`      = '{$_POST['catnumber']}',
            `updated`        = '{$ts}',
            `related`        = '{$related}',
            `length`         = '{$_POST['length']}',
            `bitrate`        = '{$_POST['bitrate']}'
            WHERE `id`       = '{$ID}'
            ");
    } else {
      $Q  = db::db_query("INSERT INTO `" . DB_PREFIX . "collections` (
            `name`,
            `title`,
            `metakeys`,
            `metadesc`,
            `slug`,
            `information`,
            `searchtags`,
            `social`,
            `coverart`,
            `coverartother`,
            `enabled`,
            `views`,
            `released`,
            `cost`,
            `costcd`,
            `catnumber`,
            `added`,
            `updated`,
            `related`,
            `length`,
            `bitrate`
            ) VALUES (
            '{$_POST['name']}',
            '{$_POST['title']}',
            '{$_POST['metakeys']}',
            '{$_POST['metadesc']}',
            '{$_POST['slug']}',
            '{$_POST['information']}',
            '{$_POST['searchtags']}',
            '{$social}',
            '{$_POST['coverart']}',
            '{$covero}',
            '{$enabled}',
            '{$views}',
            '{$released}',
            '{$_POST['cost']}',
            '{$_POST['costcd']}',
            '{$_POST['catnumber']}',
            '{$ts}',
            '{$ts}',
            '{$related}',
            '{$_POST['length']}',
            '{$_POST['bitrate']}'
            )");
      $ID = db::db_last_insert_id();
    }
    // Update styles..
    db::db_query("DELETE FROM `" . DB_PREFIX . "collection_styles` WHERE `collection` = '{$ID}'");
    mswTableTruncationRoutine(array(
      'collection_styles'
    ), $this);
    if (!empty($_POST['styles'])) {
      foreach ($_POST['styles'] AS $styleID) {
        db::db_query("INSERT INTO `" . DB_PREFIX . "collection_styles` (
        `style`,`collection`
        ) VALUES (
        '{$styleID}','{$ID}'
        )");
      }
    }
    return $Q;
  }

  public function digitCheck($digit) {
    $d = (int) $digit;
    if (strlen($d) < 2) {
      return '0' . $d;
    } else {
      return substr($d, 0, 2);
    }
  }

  public function featured($data) {
    $ar = array();
    $q  = db::db_query("SELECT `id`,`name` FROM `" . DB_PREFIX . "collections`
          WHERE (`name` LIKE '%" . mswSafeString($data, $this) . "%' OR `title` LIKE '%" . mswSafeString($data, $this) . "%')
          AND `enabled` = 'yes'
          ");
    while ($C = db::db_object($q)) {
      $ar[] = array(
        'label' => mswSafeDisplay($C->name),
        'value' => $C->id
      );
    }
    return $ar;
  }

  public function coverArtOther($search) {
    @ini_set('memory_limit', '100M');
    @set_time_limit(0);
    $dir   = mswFolderScanner(REL_PATH . COVER_ART_FOLDER, SUPPORTED_IMAGES);
    $dir[] = REL_PATH . COVER_ART_FOLDER;
    $ar    = array();
    if (!empty($dir)) {
      foreach ($dir AS $folder) {
        $files = mswFolderFileScanner($folder, SUPPORTED_IMAGES);
        if (!empty($files)) {
          foreach ($files AS $img) {
            if (strpos(strtolower($img), strtolower($search)) !== false) {
              $path = substr(mswSafeDisplay($img), strlen(REL_PATH . COVER_ART_FOLDER) + 1);
              $key  = mswEncrypt($path);
              $ar[] = array(
                'label' => $path,
                'value' => $key
              );
            }
          }
        }
      }
    }
    return $ar;
  }

  public function importCollections($name, $tmp, $styles) {
    $cnt = 0;
    // Clear?
    if (isset($_POST['clear'])) {
      db::db_query("DELETE FROM `" . DB_PREFIX . "collections`");
      db::db_query("DELETE FROM `" . DB_PREFIX . "collection_styles`");
      db::db_query("DELETE FROM `" . DB_PREFIX . "sales_clipboard`");
      db::db_query("DELETE FROM `" . DB_PREFIX . "offers`");
      db::db_query("DELETE FROM `" . DB_PREFIX . "music`");
      mswTableTruncationRoutine(array(
        'collections',
        'collection_styles',
        'sales_clipboard',
        'offers',
        'music'
      ), $this);
    }
    // Read tmp file...
    if (is_uploaded_file($tmp)) {
      $data = array_map('str_getcsv', file($tmp));
      if (!empty($data)) {
        for ($i = 0; $i < count($data); $i++) {
          if (db::db_rowcount('collections') >= LIC_RESTR_COL) {
            return $cnt;
          }
          // Prepare data..
          $name        = mswSafeString(trim($data[$i][0]), $this);
          $title       = mswSafeString(trim($data[$i][1]), $this);
          $metakeys    = mswSafeString(trim($data[$i][2]), $this);
          $metadesc    = mswSafeString(trim($data[$i][3]), $this);
          $slug        = mswSafeString(trim($data[$i][4]), $this);
          $information = mswSafeString(trim($data[$i][5]), $this);
          $searchtags  = mswSafeString(trim($data[$i][6]), $this);
          $coverart    = mswSafeString(trim($data[$i][7]), $this);
          $views       = (int) trim($data[$i][8]);
          $released    = (int) trim($data[$i][9]);
          $catnumber   = mswSafeString(trim($data[$i][10]), $this);
          $cost        = mswSafeString(trim($data[$i][11]), $this);
          $costcd      = mswSafeString(trim($data[$i][12]), $this);
          $length      = mswSafeString(trim($data[$i][13]), $this);
          $bitrate     = mswSafeString(trim($data[$i][14]), $this);
          $ts          = $this->datetime->utcTime();
          $coverother  = '';
          $social      = (!empty($_POST['social']) ? mswSafeString(serialize($_POST['social']), $this) : '');
          $related     = '';
          // Import..
          db::db_query("INSERT INTO `" . DB_PREFIX . "collections` (
          `name`,
          `title`,
          `metakeys`,
          `metadesc`,
          `slug`,
          `information`,
          `searchtags`,
          `social`,
          `coverart`,
          `coverartother`,
          `enabled`,
          `views`,
          `released`,
          `cost`,
          `costcd`,
          `catnumber`,
          `added`,
          `updated`,
          `related`,
          `length`,
          `bitrate`
          ) VALUES (
          '{$name}',
          '{$title}',
          '{$metakeys}',
          '{$metadesc}',
          '{$slug}',
          '{$information}',
          '{$searchtags}',
          '{$social}',
          '{$coverart}',
          '{$coverother}',
          'yes',
          '{$views}',
          '{$released}',
          '{$cost}',
          '{$costcd}',
          '{$catnumber}',
          '{$ts}',
          '{$ts}',
          '{$related}',
          '{$length}',
          '{$bitrate}'
          )");
          $ID = db::db_last_insert_id();
          if ($ID > 0) {
            foreach ($styles AS $styleID) {
              db::db_query("INSERT INTO `" . DB_PREFIX . "collection_styles` (
              `style`,`collection`
              ) VALUES (
              '{$styleID}','{$ID}'
              )");
            }
            ++$cnt;
          }
        }
      }
    }
    if (file_exists($tmp)) {
      @unlink($tmp);
    }
    return $cnt;
  }

  public function importTracks($name, $tmp, $col) {
    $cnt = 0;
    // Clear?
    if (isset($_POST['clear'])) {
      $clip = array();
      $Q    = db::db_query("SELECT `id` FROM `" . DB_PREFIX . "music` WHERE `collection` = '{$col}'");
      while ($TK = db::db_object($Q)) {
        $clip[] = $TK->id;
      }
      if (!empty($clip)) {
        db::db_query("DELETE FROM `" . DB_PREFIX . "sales_clipboard` WHERE `type` = 'track' AND `trackcol` IN(" . implode(',',$clip) . ")");
      }
      db::db_query("DELETE FROM `" . DB_PREFIX . "music` WHERE `collection` = '{$col}'");
      mswTableTruncationRoutine(array(
        'music',
        'sales_clipboard'
      ), $this);
    }
    // Read tmp file...
    if (is_uploaded_file($tmp)) {
      $data = array_map('str_getcsv', file($tmp));
      if (!empty($data)) {
        for ($i = 0; $i < count($data); $i++) {
          if (LICENCE_VER == 'locked' && db::db_rowcount('music') >= LIC_RESTR_TRKS) {
            return $cnt;
          }
          // Prepare data..
          $title       = mswSafeString(trim($data[$i][0]), $this);
          $mp3file     = mswSafeString(trim($data[$i][1]), $this);
          $previewfile = mswSafeString(trim($data[$i][2]), $this);
          $length      = mswSafeString(trim($data[$i][3]), $this);
          $bitrate     = mswSafeString(trim($data[$i][4]), $this);
          $samplerate  = mswSafeString(trim($data[$i][5]), $this);
          $cost        = mswSafeString(trim($data[$i][6]), $this);
          $order       = (int) trim($data[$i][7]);
          $ts          = $this->datetime->utcTime();
          // Import..
          db::db_query("INSERT INTO `" . DB_PREFIX . "music` (
          `title`,
          `collection`,
          `mp3file`,
          `previewfile`,
          `length`,
          `bitrate`,
          `samplerate`,
          `cost`,
          `order`,
          `ts`,
          `updated`
          ) VALUES (
          '{$title}',
          '{$col}',
          '{$mp3file}',
          '{$previewfile}',
          '{$length}',
          '{$bitrate}',
          '{$samplerate}',
          '{$cost}',
          '{$order}',
          '{$ts}',
          '{$ts}'
          )");
          $ID = db::db_last_insert_id();
          if ($ID > 0) {
            ++$cnt;
          }
        }
      }
    }
    if (file_exists($tmp)) {
      @unlink($tmp);
    }
    return $cnt;
  }

  public function exportMusic($l) {
    $bld    = array();
    $flds   = array();
    $del    = ',';
    $type   = (isset($_POST['type']) && in_array($_POST['type'],array('col','music')) ? $_POST['type'] : 'col');
    switch($type) {
      case 'col':
        $file  = PATH . 'backup/export-collections.csv';
        $table = 'collections';
        break;
      case 'music':
        $file  = PATH . 'backup/export-music.csv';
        $table = 'music';
        break;
    }
    $Q     = db::db_query("SHOW FIELDS FROM `" . DB_PREFIX . $table . "`");
    while ($FLD  = db::db_object($Q)) {
      if ($FLD->Field != 'id') {
        $flds[] = $FLD->Field;
      }
    }
    // Data..
    $Q2 = db::db_query("SELECT * FROM `" . DB_PREFIX . $table . "` ORDER BY `id`");
    while ($DT  = db::db_fetch_rows($Q2)) {
      $linebyline = array();
      for ($i = 0; $i < count($DT); $i++) {
        if ($i > 0) {
          $linebyline[] = mswCleanCSV($DT[$i], $del);
        }
      }
      if (!empty($linebyline)) {
        $bld[] = implode($del,$linebyline);
      }
    }
    if (!empty($bld)) {
      // Save file to server and download..
      $this->dl->write($file, implode($del,$flds) . mswDefineNewline() . implode(mswDefineNewline(), $bld));
      if (file_exists($file)) {
        $this->dl->dl($file, 'text/csv');
      }
    }
    header("Location: index.php?p=impexp");
  }

}

?>