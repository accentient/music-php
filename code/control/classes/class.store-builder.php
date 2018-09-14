<?php

class storeBuilder extends db {

  public $settings;
  public $featured;
  public $seo;
  public $costing;
  public $filters;

  public function params($flag = 'all') {
    $arr = array();
    switch($flag) {
      case 'all':
        $Q   = db::db_query("SELECT `desc`, `param`, `value` FROM `" . DB_PREFIX . "api`");
        break;
      default:
        $Q   = db::db_query("SELECT `desc`, `param`, `value` FROM `" . DB_PREFIX . "api` WHERE `desc` = '{$flag}'");
        break;
    }
    while ($PAR = db::db_object($Q)) {
      $arr[$PAR->desc][$PAR->param] = $PAR->value;
    }
    return $arr;
  }

  public function socialbuttons() {
    $html   = '';
    $social = ($this->settings->social ? unserialize($this->settings->social) : array());
    if (!empty($social)) {
      foreach ($social AS $k => $v) {
        switch ($k) {
          case 'fb':
            $i = 'facebook';
            break;
          case 'gg':
            $i = 'google-plus';
            break;
          case 'tw':
            $i = 'twitter';
            break;
          case 'li':
            $i = 'linkedin';
            break;
          case 'yt':
            $i = 'youtube';
            break;
          case 'sc':
            $i = 'soundcloud';
            break;
          case 'sp':
            $i = 'spotify';
            break;
          case 'fm':
            $i = 'lastfm';
            break;
          default:
            $v = '';
            break;
        }
        if ($v) {
          $ar = array(
            '{url}' => $v,
            '{icon}' => $i
          );
          $html .= storeBuilder::template($ar, 'social.tpl');
        }
      }
    }
    return $html;
  }

  public function opengraph($data) {
    $html = '';
    $ar   = array(
      '{og:url}' => $data['url'],
      '{og:site_name}' => $data['site'],
      '{og:image}' => $data['image'],
      '{og:title}' => $data['title'],
      '{fb:app_id}' => $data['id']
    );
    return storeBuilder::template($ar, 'open-graph.tpl');
  }

  public function hitcounter($id) {
    db::db_query("UPDATE `" . DB_PREFIX . "collections` SET
    `views`    = (`views`+1)
    WHERE `id` = '{$id}'
    ");
  }

  public function basketAcc($acc, $l, $countries = array()) {
    if (isset($acc['id'])) {
      $fr  = array(
        '{name}' => mswSafeDisplay($acc['name']),
        '{email}' => mswSafeDisplay($acc['email']),
        '{text}' => $l[15]
      );
      $tmp = 'basket-account-logged-in';
    } else {
      $ct  = array();
      $of  = storeBuilder::template(array(), 'option.tpl', true);
      if (!empty($countries)) {
        foreach ($countries AS $cid => $cname) {
          $ct[] = str_replace(array('{id}','{name}'),array($cid,$cname),$of);
        }
      }
      $fr  = array(
        '{name}' => '',
        '{email}' => '',
        '{text}' => $l[15],
        '{text2}' => $l[29],
        '{em_place}' => mswSafeDisplay($l[18]),
        '{ps_place}' => mswSafeDisplay($l[19]),
        '{nm_place}' => mswSafeDisplay($l[20]),
        '{countries}' => (!empty($ct) ? implode(mswDefineNewline(),$ct) : '')
      );
      $tmp = 'basket-account-login';
    }
    return storeBuilder::template($fr, $tmp . '.tpl');
  }

  public function methods($id = 0) {
    $html = array();
    $sql  = '';
    if ($id > 0) {
      $sql = 'AND `id` = \'' . $id . '\'';
    }
    $Q = db::db_query("SELECT `id`,`display`,`image`,`default` FROM `" . DB_PREFIX . "gateways` WHERE `status` = 'yes' $sql ORDER BY `display`");
    while ($M = db::db_object($Q)) {
      $html[] = array(
        'id' => $M->id,
        'name' => mswSafeDisplay($M->display),
        'img' => BASE_HREF . 'content/' . THEME . '/images/gateways/' . $M->image,
        'def' => $M->default
      );
    }
    return $html;
  }

  public function rates($l) {
    $html = array();
    $Q    = db::db_query("SELECT * FROM `" . DB_PREFIX . "shipping` ORDER BY `name`");
    while ($R = db::db_object($Q)) {
      $html[$R->id] = mswSafeDisplay($R->name) . ' (' . (substr($R->cost, -1) == '%' ? $R->cost : ($R->cost > 0 ? mswCurrencyFormat($R->cost, $this->settings->curdisplay) : $l[14])) . ')';
    }
    return $html;
  }

  public function landing() {
    $Q = db::db_query("SELECT `id` FROM `" . DB_PREFIX . "pages`
         WHERE `landing` = 'yes'
         AND `enabled` = 'yes'
         LIMIT 1
         ");
    $S = db::db_object($Q);
    return (isset($S->id) ? $S->id : '0');
  }

  public function span($data, $class) {
    $fr = array(
      '{class}' => $class,
      '{data}' => $data
    );
    return storeBuilder::template($fr, 'span-tag.tpl');
  }

  public function colStyles($id) {
    $html = array();
    $Q    = db::db_query("SELECT `slug`,`name`,`" . DB_PREFIX . "music_styles`.`id` AS `styleID` FROM `" . DB_PREFIX . "collection_styles`
            LEFT JOIN `" . DB_PREFIX . "music_styles`
            ON `" . DB_PREFIX . "music_styles`.`id`               = `" . DB_PREFIX . "collection_styles`.`style`
            WHERE `" . DB_PREFIX . "music_styles`.`enabled`       = 'yes'
            AND `" . DB_PREFIX . "collection_styles`.`collection` = '{$id}'
            AND `" . DB_PREFIX . "music_styles`.`collection`      = '0'
            ORDER BY `" . DB_PREFIX . "music_styles`.`name`
            ");
    while ($S = db::db_object($Q)) {
      $url    = array(
        'seo' => array(
          ($S->slug ? $S->slug : $this->seo->filter($S->name)),
          ($S->slug == '' ? $S->styleID : '')
        ),
        'standard' => array(
          '#' => $S->styleID
        )
      );
      $fr     = array(
        '{url}' => BASE_HREF . $this->seo->url('style', $url),
        '{text}' => mswSafeDisplay($S->name)
      );
      $html[] = storeBuilder::template($fr, 'standard-url.tpl');
    }
    return (!empty($html) ? implode(', ', $html) : '');
  }

  public function comments($col) {
    $html   = '';
    $social = ($col->social ? unserialize($col->social) : array());
    $disapi = storeBuilder::params('disqus');
    if ($disapi['disqus']['disname'] && isset($social['disqus']) && $social['disqus'] == 'yes') {
      // Build url..
      $url  = array(
        'seo' => array(
          ($col->slug ? $col->slug : $this->seo->filter($col->name)),
          ($col->slug == '' ? $col->id : '')
        ),
        'standard' => array(
          '#' => $col->id
        )
      );
      if (isset($disapi['disqus']['disname'])) {
        $fr   = array(
          '{short_name}' => $disapi['disqus']['disname'],
          '{id}' => substr(md5($disapi['disqus']['disname']), 0, 10) . '-' . $col->id,
          '{url}' => BASE_HREF . $this->seo->url('collection', $url),
          '{category}' => $disapi['disqus']['discat']
        );
        $html = storeBuilder::template($fr, 'disqus.tpl');
      }
    }
    return $html;
  }

  public function tags($tags, $page) {
    $html = array();
    if ($tags) {
      $chop = explode(',', $tags);
      foreach ($chop AS $t) {
        $url    = array(
          'seo' => array(
            urlencode($t),
            ($this->seo->curPage > 1 ? $this->seo->curPage : '')
          ),
          'standard' => array(
            'keys' => urlencode($t),
            'next' => ($this->seo->curPage > 1 ? $this->seo->curPage : '')
          )
        );
        $fr     = array(
          '{url}' => BASE_HREF . $this->seo->url('search', $url),
          '{tag}' => mswSafeDisplay($t)
        );
        $html[] = storeBuilder::template($fr, 'search-tag.tpl');
      }
    }
    return (!empty($html) ? implode(', ', $html) : '');
  }

  public function related($rel, $cmd, $l) {
    $html = '';
    if ($rel) {
      $d    = storeBuilder::load('related', array(
        $l[0],
        $l[1],
        $l[2]
      ), array(
        'related' => $rel
      ));
      $html = $d['data'];
    }
    return $html;
  }

  public function tracks($id) {
    $html = '';
    $Q    = db::db_query("SELECT * FROM `" . DB_PREFIX . "music`
            WHERE `collection` = '{$id}'
            AND `cost`        != ''
            ORDER BY `order`
		  ");
    while ($T = db::db_object($Q)) {
      $button = array(
        '{file}' => BASE_HREF . PREVIEW_FOLDER . '/' . str_replace("'", "\'", $T->previewfile),
        '{id}' => $T->id,
        '{ipath}' => BASE_HREF.'content/' . THEME . '/'
      );
      $disc   = $this->costing->offer($T->cost, 'track', $id);
      $fr     = array(
        '{title}' => mswSafeDisplay($T->title),
        '{play}' => ($T->previewfile ? storeBuilder::template($button, 'track-play-button.tpl') : 'N/A'),
        '{time}' => mswTrimTime($T->length),
        '{bitrate}' => ($T->bitrate ? $T->bitrate : 'N/A'),
        '{id}' => $T->id,
        '{cost}' => storeBuilder::span(mswCurrencyFormat($T->cost, $this->settings->curdisplay), ($disc != 'no' && $disc != $T->cost ? 'cost-offer hidden-sm hidden-xs' : 'cost-ok')),
        '{discount}' => ($disc != 'no' ? storeBuilder::span(mswCurrencyFormat($disc, $this->settings->curdisplay), 'cost-discount') : ''),
        '{cost_raw}' => $T->cost
      );
      $html .= storeBuilder::template($fr, 'collection-tracks.tpl');
    }
    return $html;
  }

  public function styles() {
    $styles = array();
    $Q      = db::db_query("SELECT *,
              (
               SELECT count(*) FROM `" . DB_PREFIX . "collection_styles`
               LEFT JOIN `" . DB_PREFIX . "collections`
               ON `" . DB_PREFIX . "collection_styles`.`collection` = `" . DB_PREFIX . "collections`.`id`
               WHERE `" . DB_PREFIX . "collection_styles`.`style` = `" . DB_PREFIX . "music_styles`.`id`
               AND `" . DB_PREFIX . "collections`.`enabled`       = 'yes'
              ) AS `styleCount`
              FROM `" . DB_PREFIX . "music_styles`
              WHERE `enabled` = 'yes'
              AND `type`      = '0'
              ORDER BY `orderby`
              ");
    while ($S = db::db_object($Q)) {
      $styles[$S->id] = array(
        'name' => $S->name,
        'slug' => $S->slug,
        'count' => @number_format($S->styleCount)
      );
      // Sub styles..
      $styles[$S->id]['sub'] = array();
      $Q2  = db::db_query("SELECT *,
            (
            SELECT count(*) FROM `" . DB_PREFIX . "collection_styles`
            LEFT JOIN `" . DB_PREFIX . "collections`
            ON `" . DB_PREFIX . "collection_styles`.`collection` = `" . DB_PREFIX . "collections`.`id`
            WHERE `" . DB_PREFIX . "collection_styles`.`style` = `" . DB_PREFIX . "music_styles`.`id`
            AND `" . DB_PREFIX . "collections`.`enabled`       = 'yes'
            ) AS `styleCount`,
            (
            SELECT count(*) FROM `" . DB_PREFIX . "music`
            WHERE `" . DB_PREFIX . "music`.`collection` = `" . DB_PREFIX . "music_styles`.`collection`
            ) AS `styleCount2`,
            `" . DB_PREFIX . "music_styles`.`collection` AS `styleCollection`
            FROM `" . DB_PREFIX . "music_styles`
            WHERE `enabled` = 'yes'
            AND `type`      = '{$S->id}'
            ORDER BY `orderby`
            ");
      while ($SB = db::db_object($Q2)) {
        $colurl = '';
        // Is style linked to collection?
        if ($SB->collection > 0) {
          $COL = db::db_table('collections','id', $SB->styleCollection, ' AND `enabled` = \'yes\'');
          if (isset($COL->id)) {
            $url = array(
              'seo' => array(
                ($COL->slug ? $COL->slug : $this->seo->filter($COL->name)),
                ($COL->slug == '' ? $COL->id : '')
              ),
              'standard' => array(
                '#' => $COL->id
              )
            );
            $colurl = BASE_HREF . $this->seo->url('collection', $url);
          }
        }
        $styles[$S->id]['sub'][] = array(
          'name' => $SB->name,
          'slug' => $SB->slug,
          'count' => ($SB->styleCollection > 0 ? @number_format($SB->styleCount2) : @number_format($SB->styleCount)),
          'id' => $SB->id,
          'linked' => ($SB->styleCollection > 0 ? 'yes' : 'no'),
          'colurl' => $colurl
        );
      }
    }
    return $styles;
  }

  public function load($page, $lang = array(), $other = array()) {
    $sef = $this->seo->rewriteElements();
    switch ($page) {
      case 'page':
        $sql  = '';
        $ID   = (isset($_GET['pg']) && (int) $_GET['pg'] > 0 ? (int) $_GET['pg'] : '0');
        $NM   = ($this->settings->rewrite == 'yes' && isset($sef[1]) ? $sef[1] : '');
        $NMID = (isset($sef[2]) ? (int) $sef[2] : '0');
        if ($NM != '' || $NMID > 0) {
          if ($NMID > 0) {
            $sql = '`id` = \'' . $NMID . '\'';
          } else {
            $sql = '`slug` = \'' . $NM . '\'';
          }
        } else {
          $sql = '`id` = \'' . $ID . '\'';
        }
        if ($sql) {
          $Q = db::db_query("SELECT * FROM `" . DB_PREFIX . "pages`
               WHERE $sql
               AND `enabled` = 'yes'
               LIMIT 1
               ");
          return (db::db_rows($Q) > 0 ? db::db_object($Q) : array());
        }
        return array();
        break;
      case 'order':
        $sql  = '';
        $ID   = (isset($_GET['view-order']) && (int) $_GET['view-order'] > 0 ? (int) $_GET['view-order'] : '0');
        $NMID = (isset($sef[1]) ? (int) $sef[1] : '0');
        if ($NMID > 0) {
          $sql = '`id` = \'' . ltrim($NMID, '0') . '\'';
        } else {
          $sql = '`id` = \'' . $ID . '\'';
        }
        if ($sql) {
          $Q = db::db_query("SELECT *,
               (SELECT `display` FROM `" . DB_PREFIX . "gateways` WHERE `id` = `" . DB_PREFIX . "sales`.`gateway`) AS `paymentMethod`,
               (SELECT ROUND(SUM(`cost`),2) FROM `" . DB_PREFIX . "sales_items` WHERE `sale` = `" . DB_PREFIX . "sales`.`id`) AS `saleTotal`,
               (SELECT `name` FROM `" . DB_PREFIX . "accounts` WHERE `id` = `" . DB_PREFIX . "sales`.`account`) AS `accountName`
               FROM `" . DB_PREFIX . "sales`
               WHERE $sql
               AND `enabled` = 'yes'
               LIMIT 1
               ");
          return (db::db_rows($Q) > 0 ? db::db_object($Q) : array());
        }
        return array();
        break;
      case 'collection':
        $sql  = '';
        $ID   = (isset($_GET['collection']) && (int) $_GET['collection'] > 0 ? (int) $_GET['collection'] : '0');
        $NM   = ($this->settings->rewrite == 'yes' && isset($sef[1]) ? $sef[1] : '');
        $NMID = (isset($sef[2]) ? (int) $sef[2] : '0');
        if ($NM != '' || $NMID > 0) {
          if ($NMID > 0) {
            $sql = '`id` = \'' . $NMID . '\'';
          } else {
            $sql = '`slug` = \'' . $NM . '\'';
          }
        } else {
          $sql = '`id` = \'' . $ID . '\'';
        }
        if ($sql) {
          $Q = db::db_query("SELECT * FROM `" . DB_PREFIX . "collections`
               WHERE $sql
               AND `enabled` = 'yes'
               LIMIT 1
               ");
          return (db::db_rows($Q) > 0 ? db::db_object($Q) : array());
        }
        return array();
        break;
      case 'style':
        $sql  = '';
        $ID   = (isset($_GET['style']) && (int) $_GET['style'] > 0 ? (int) $_GET['style'] : '0');
        $NM   = ($this->settings->rewrite == 'yes' && isset($sef[1]) ? $sef[1] : '');
        $NMID = (isset($sef[2]) ? (int) $sef[2] : '0');
        if ($NM != '' || $NMID > 0) {
          if ($NMID > 0) {
            $sql = '`id` = \'' . $NMID . '\'';
          } else {
            $sql = '`slug` = \'' . $NM . '\'';
          }
        } else {
          $sql = '`id` = \'' . $ID . '\'';
        }
        if ($sql) {
          $Q = db::db_query("SELECT * FROM `" . DB_PREFIX . "music_styles`
               WHERE $sql
               AND `enabled` = 'yes'
               LIMIT 1
               ");
          return (db::db_rows($Q) > 0 ? db::db_object($Q) : array());
        }
        return array();
        break;
      case 'home':
      case 'view-style':
      case 'latest':
      case 'popular':
      case 'related':
      case 'search':
        $str = '';
        switch ($page) {
          // Featured homepage..
          case 'home':
            $sql = 'ORDER BY `id` DESC LIMIT ' . FEATURED_HOME_LIMIT;
            if ($this->settings->featured) {
              $IDS = unserialize($this->settings->featured);
              if (!empty($IDS)) {
                $ft  = mswSafeString(implode(',', $IDS), $this);
                $sql = 'AND `id` IN(' . $ft . ') ORDER BY FIELD(`id`,' . $ft . ')';
              }
            }
            $Q = db::db_query("SELECT *,`id` AS `collID` FROM `" . DB_PREFIX . "collections` WHERE `enabled` = 'yes' " . $sql);
            break;
          // Related..
          case 'related':
            $rp  = unserialize($other['related']);
            $ft  = mswSafeString(implode(',', $rp), $this);
            $sql = 'AND `id` IN(' . $ft . ') ORDER BY FIELD(`id`,' . $ft . ')';
            $Q   = db::db_query("SELECT *,`id` AS `collID` FROM `" . DB_PREFIX . "collections` WHERE `enabled` = 'yes' " . $sql);
            break;
          // Latest..
          case 'latest':
            $sql = 'ORDER BY `id` DESC LIMIT ' . LATEST_LIMIT;
            $ids = array();
            // Get latest collections IDs into array..
            $Q   = db::db_query("SELECT `id` FROM `" . DB_PREFIX . "collections` WHERE `enabled` = 'yes' " . $sql);
            while ($CLAT = db::db_object($Q)) {
              $ids[] = $CLAT->id;
            }
            if (isset($_SESSION['mmFilters']) && in_array($_SESSION['mmFilters'], array_keys($this->filters))) {
              switch($_SESSION['mmFilters']) {
                case 'name_asc':
                  $sql = 'ORDER BY `name` LIMIT ' . LATEST_LIMIT;
                  break;
                case 'name_desc':
                  $sql = 'ORDER BY `name` DESC LIMIT ' . LATEST_LIMIT;
                  break;
                case 'pmp3_high':
                  $sql = 'ORDER BY `cost`*1000 DESC LIMIT ' . LATEST_LIMIT;
                  break;
                case 'pmp3_low':
                  $sql = 'ORDER BY `cost`*1000 LIMIT ' . LATEST_LIMIT;
                  break;
                case 'cd_high':
                  $sql = 'ORDER BY `costcd`*1000 DESC LIMIT ' . LATEST_LIMIT;
                  break;
                case 'cd_low':
                  $sql = 'ORDER BY `costcd`*1000 LIMIT ' . LATEST_LIMIT;
                  break;
                case 'rel_asc':
                  $sql = 'ORDER BY `released` LIMIT ' . LATEST_LIMIT;
                  break;
                case 'rel_desc':
                  $sql = 'ORDER BY `released` DESC LIMIT ' . LATEST_LIMIT;
                  break;
                case 'date_asc':
                  $sql = 'ORDER BY `added` LIMIT ' . LATEST_LIMIT;
                  break;
                case 'date_desc':
                  $sql = 'ORDER BY `added` DESC LIMIT ' . LATEST_LIMIT;
                  break;
              }
            }
            $Q   = db::db_query("SELECT *,`id` AS `collID` FROM `" . DB_PREFIX . "collections` WHERE `id` IN(" . (!empty($ids) ? implode(',',$ids) : '0') . ") AND `enabled` = 'yes' " . $sql);
            break;
          // Popular..
          case 'popular':
            $Q   = db::db_query("SELECT count(*) AS `saleCount` FROM `" . DB_PREFIX . "sales` WHERE `enabled` = 'yes'");
            $S   = db::db_object($Q);
            // If sale, popular is based on sales. If no sales, just view hits..
            // Prevents weird SQL errors..
            if (isset($S->saleCount) && $S->saleCount > 0) {
              $sql = 'ORDER BY count(*) DESC LIMIT ' . POPULAR_LIMIT;
            } else {
              $sql = 'ORDER BY `views` DESC LIMIT ' . POPULAR_LIMIT;
            }
            $ids = array();
            // Get popular collections IDs into array..
            if (isset($S->saleCount) && $S->saleCount > 0) {
              $Q   = db::db_query("SELECT *,
                     `" . DB_PREFIX . "collections`.`id` AS `collID`,
                     `" . DB_PREFIX . "collections`.`cost` AS `colCost`
                     FROM `" . DB_PREFIX . "collections`
                     LEFT JOIN `" . DB_PREFIX . "sales_items`
                     ON `" . DB_PREFIX . "collections`.`id`         = `" . DB_PREFIX . "sales_items`.`collection`
                     WHERE `" . DB_PREFIX . "collections`.`enabled` = 'yes'
                     GROUP BY `" . DB_PREFIX . "sales_items`.`collection`
                     " . $sql);
            } else {
              $Q   = db::db_query("SELECT *,`id` AS `collID` FROM `" . DB_PREFIX . "collections` WHERE `enabled` = 'yes' " . $sql);
            }
            while ($CLAT = db::db_object($Q)) {
              $ids[] = $CLAT->collID;
            }
            if (isset($_SESSION['mmFilters']) && in_array($_SESSION['mmFilters'], array_keys($this->filters))) {
              switch($_SESSION['mmFilters']) {
                case 'name_asc':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`name` LIMIT ' . POPULAR_LIMIT;
                  break;
                case 'name_desc':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`name` DESC LIMIT ' . POPULAR_LIMIT;
                  break;
                case 'pmp3_high':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`cost`*1000 DESC LIMIT ' . POPULAR_LIMIT;
                  break;
                case 'pmp3_low':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`cost`*1000 LIMIT ' . POPULAR_LIMIT;
                  break;
                case 'cd_high':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`costcd`*1000 DESC LIMIT ' . POPULAR_LIMIT;
                  break;
                case 'cd_low':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`costcd`*1000 LIMIT ' . POPULAR_LIMIT;
                  break;
                case 'rel_asc':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`released` LIMIT ' . POPULAR_LIMIT;
                  break;
                case 'rel_desc':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`released` DESC LIMIT ' . POPULAR_LIMIT;
                  break;
                case 'date_asc':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`added` LIMIT ' . POPULAR_LIMIT;
                  break;
                case 'date_desc':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`added` DESC LIMIT ' . POPULAR_LIMIT;
                  break;
              }
            }
            if (isset($S->saleCount) && $S->saleCount > 0) {
              $Q   = db::db_query("SELECT *,
                     `" . DB_PREFIX . "collections`.`id` AS `collID`,
                     `" . DB_PREFIX . "collections`.`cost` AS `colCost`
                     FROM `" . DB_PREFIX . "collections`
                     LEFT JOIN `" . DB_PREFIX . "sales_items`
                     ON `" . DB_PREFIX . "collections`.`id`         = `" . DB_PREFIX . "sales_items`.`collection`
                     WHERE `" . DB_PREFIX . "collections`.`enabled` = 'yes'
                     AND `" . DB_PREFIX . "collections`.`id` IN(" . (!empty($ids) ? implode(',',$ids) : '0') . ")
                     GROUP BY `" . DB_PREFIX . "sales_items`.`collection`
                     " . $sql);
            } else {
              $Q   = db::db_query("SELECT *,`id` AS `collID` FROM `" . DB_PREFIX . "collections` WHERE `id` IN(" . (!empty($ids) ? implode(',',$ids) : '0') . ") AND `enabled` = 'yes' " . $sql);
            }
            break;
          // Style collections..
          case 'view-style':
            $sql    = 'ORDER BY `' . DB_PREFIX . 'collections`.`name` LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . COLLECTIONS_PER_PAGE;
            if (isset($_SESSION['mmFilters']) && in_array($_SESSION['mmFilters'], array_keys($this->filters))) {
              switch($_SESSION['mmFilters']) {
                case 'name_asc':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`name` LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . COLLECTIONS_PER_PAGE;
                  break;
                case 'name_desc':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`name` DESC LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . COLLECTIONS_PER_PAGE;
                  break;
                case 'pmp3_high':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`cost`*1000 DESC LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . COLLECTIONS_PER_PAGE;
                  break;
                case 'pmp3_low':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`cost`*1000 LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . COLLECTIONS_PER_PAGE;
                  break;
                case 'cd_high':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`costcd`*1000 DESC LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . COLLECTIONS_PER_PAGE;
                  break;
                case 'cd_low':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`costcd`*1000 LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . COLLECTIONS_PER_PAGE;
                  break;
                case 'rel_asc':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`released` LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . COLLECTIONS_PER_PAGE;
                  break;
                case 'rel_desc':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`released` DESC LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . COLLECTIONS_PER_PAGE;
                  break;
                case 'date_asc':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`added` LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . COLLECTIONS_PER_PAGE;
                  break;
                case 'date_desc':
                  $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`added` DESC LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . COLLECTIONS_PER_PAGE;
                  break;
              }
            }
            $Q      = db::db_query("SELECT SQL_CALC_FOUND_ROWS *,`" . DB_PREFIX . "collections`.`id` AS `collID` FROM `" . DB_PREFIX . "collection_styles`
                      LEFT JOIN `" . DB_PREFIX . "collections`
                      ON `" . DB_PREFIX . "collection_styles`.`collection` = `" . DB_PREFIX . "collections`.`id`
                      WHERE `" . DB_PREFIX . "collections`.`enabled`   = 'yes'
                      AND `" . DB_PREFIX . "collection_styles`.`style` = '{$other['id']}'
                      " . $sql);
            $rQuery = db::db_object(db::db_query("SELECT FOUND_ROWS() AS `rows`"));
            $cnRows = (isset($rQuery->rows) ? $rQuery->rows : '0');
            break;
          // Search store..
          case 'search':
            $string = '';
            for ($i = 0; $i < count($other['keys']); $i++) {
              $word = mswSafeString($other['keys'][$i], $this);
              if (strlen($word) > MIN_SEARCH_WORD_LENGTH) {
                $string .= ($i > 0 ? ' OR (' : 'AND (') . '`name` LIKE \'%' . $word . '%\' OR `information` LIKE \'%' . $word . '%\' OR `searchtags` LIKE \'%' . $word . '%\' OR `catnumber` LIKE \'%' . $word . '%\')';
              }
            }
            // Nothing found..
            if ($string == '') {
              $string = 'AND (`id` = \'0\')';
            }
            $sql    = 'ORDER BY `id` DESC LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . SEARCH_PER_PAGE;
            if (isset($_SESSION['mmFilters']) && in_array($_SESSION['mmFilters'], array_keys($this->filters))) {
              switch($_SESSION['mmFilters']) {
                case 'name_asc':
                  $sql = 'ORDER BY `name` LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . SEARCH_PER_PAGE;
                  break;
                case 'name_desc':
                  $sql = 'ORDER BY `name` DESC LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . SEARCH_PER_PAGE;
                  break;
                case 'pmp3_high':
                  $sql = 'ORDER BY `cost`*1000 DESC LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . SEARCH_PER_PAGE;
                  break;
                case 'pmp3_low':
                  $sql = 'ORDER BY `cost`*1000 LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . SEARCH_PER_PAGE;
                  break;
                case 'cd_high':
                  $sql = 'ORDER BY `costcd`*1000 DESC LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . SEARCH_PER_PAGE;
                  break;
                case 'cd_low':
                  $sql = 'ORDER BY `costcd`*1000 LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . SEARCH_PER_PAGE;
                  break;
                case 'rel_asc':
                  $sql = 'ORDER BY `released` LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . SEARCH_PER_PAGE;
                  break;
                case 'rel_desc':
                  $sql = 'ORDER BY `released` DESC LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . SEARCH_PER_PAGE;
                  break;
                case 'date_asc':
                  $sql = 'ORDER BY `added` LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . SEARCH_PER_PAGE;
                  break;
                case 'date_desc':
                  $sql = 'ORDER BY `added` DESC LIMIT ' . (defined('PAGE_LIMIT_OR') ? PAGE_LIMIT_OR : PAGE_LIMIT) . ',' . SEARCH_PER_PAGE;
                  break;
              }
            }
            $Q      = db::db_query("SELECT SQL_CALC_FOUND_ROWS *,`id` AS `collID` FROM `" . DB_PREFIX . "collections` WHERE (`enabled` = 'yes') " . $string . " " . $sql);
            $rQuery = db::db_object(db::db_query("SELECT FOUND_ROWS() AS `rows`"));
            $cnRows = (isset($rQuery->rows) ? $rQuery->rows : '0');
            break;
        }
        while ($C = db::db_object($Q)) {
          // For popular option, value is in different var because of table join..
          if ($page == 'popular' && isset($C->colCost)) {
            $C->cost = $C->colCost;
          }
          $items       = '';
          $unavailable = 0;
          // Is a mp3 download cost set?
          if ($C->cost != '') {
            $disc  = $this->costing->offer($C->cost, 'col', $C->collID);
            $fi1   = array(
              '{id}' => $C->collID,
              '{cost}' => storeBuilder::span(mswCurrencyFormat($C->cost, $this->settings->curdisplay), ($disc != 'no' && $disc != $C->cost ? 'cost-offer hidden-sm hidden-xs' : 'cost-ok')),
              '{discount}' => ($disc != 'no' ? storeBuilder::span(mswCurrencyFormat($disc, $this->settings->curdisplay), 'cost-discount') : ''),
              '{type}' => $lang[1],
              '{ident}' => 'MP3'
            );
            $items = storeBuilder::template($fi1, 'collection-item.tpl');
          } else {
            $items = storeBuilder::template(array(
              '{type}' => $lang[1]
            ), 'collection-item-unavailable.tpl');
            ++$unavailable;
          }
          // Is a cd purchase set?
          if ($this->settings->cdpur == 'yes') {
            if ($C->costcd != '') {
              $disc2 = $this->costing->offer($C->costcd, 'cd', $C->collID);
              $fi2   = array(
                '{id}' => $C->collID,
                '{cost}' => storeBuilder::span(mswCurrencyFormat($C->costcd, $this->settings->curdisplay), ($disc2 != 'no' && $disc2 != $C->costcd ? 'cost-offer hidden-sm hidden-xs' : 'cost-ok')),
                '{discount}' => ($disc2 != 'no' ? storeBuilder::span(mswCurrencyFormat($disc2, $this->settings->curdisplay), 'cost-discount') : ''),
                '{type}' => $lang[2],
                '{ident}' => 'CD'
              );
              $items .= storeBuilder::template($fi2, 'collection-item.tpl');
            } else {
              $items .= storeBuilder::template(array(
                '{type}' => $lang[2]
              ), 'collection-item-unavailable.tpl');
              ++$unavailable;
            }
          }
          // Build url..
          $url      = array(
            'seo' => array(
              ($C->slug ? $C->slug : $this->seo->filter($C->name)),
              ($C->slug == '' ? $C->collID : '')
            ),
            'standard' => array(
              '#' => $C->collID
            )
          );
          // Does this collection have at least one track enabled?
          $tracksEn = db::db_rowcount('music WHERE `collection` = \'' . $C->collID . '\' AND `cost` != \'\'');
          $fr       = array(
            '{id}' => $C->collID,
            '{name}' => (NAME_CHAR_DISPLAY > 0 && strlen(mswSafeDisplay($C->name)) > NAME_CHAR_DISPLAY ? substr(mswSafeDisplay($C->name), 0, NAME_CHAR_DISPLAY) . '..' : mswSafeDisplay($C->name)),
            '{url}' => BASE_HREF . $this->seo->url('collection', $url),
            '{image}' => BASE_HREF . storeBuilder::cover($C->coverart),
            '{text}' => ($unavailable == 0 || $tracksEn > 0 ? $lang[0] : $lang[3]),
            '{items}' => $items,
            '{buttontype}' => ($unavailable == 0 || $tracksEn > 0 ? 'primary' : 'danger')
          );
          $str .= storeBuilder::template($fr, 'collection.tpl');
        }
        return array(
          'data' => $str,
          'rows' => (isset($cnRows) ? $cnRows : '0')
        );
        break;
    }
  }

  public function cover($cvr) {
    return ($cvr ? COVER_ART_FOLDER . '/' . $cvr : 'content/' . THEME . '/images/no-preview.png');
  }

  public function pages() {
    $pgs = array();
    $Q   = db::db_query("SELECT * FROM `" . DB_PREFIX . "pages` WHERE `enabled` = 'yes' ORDER BY `orderby`");
    while ($P = db::db_object($Q)) {
      $pgs[$P->id] = array(
        'name' => $P->name,
        'slug' => $P->slug
      );
    }
    return $pgs;
  }

  public function plugins($tmp, $arr, $data = array()) {
    $plugins = array();
    switch ($tmp) {
      case 'footer':
        if (in_array('ibox', $arr)) {
          $plugins[] = '<script src="' . BASE_HREF . 'content/' . THEME . '/js/plugins/jquery.ibox.js"></script>';
        }
        if (in_array('mmusic', $arr)) {
          $plugins[] = '<script src="' . BASE_HREF . 'content/' . THEME . '/js/mmusic.js"></script>';
        }
        if (in_array('soundmanager', $arr)) {
          $plugins[] = '<script src="' . BASE_HREF . 'content/' . THEME . '/js/soundmanager/soundmanager.js"></script>';
        }
        if (in_array('music-ops', $arr)) {
          $plugins[] = '<script src="' . BASE_HREF . 'content/' . THEME . '/js/plugins/jquery.music-ops.js"></script>';
          $plugins[] = '<script>' . mswDefineNewline() . '//<![CDATA[' . mswDefineNewline() . 'jQuery(document).ready(function() {' . mswDefineNewline() . 'jQuery(\'.main-container\').mMusicOps({' . mswDefineNewline() . '\'tmppath\' : \'' . BASE_HREF . 'content/' . THEME . '\'' . mswDefineNewline() . '});' . mswDefineNewline() . '});' . mswDefineNewline() . '//]]>' . mswDefineNewline() . '</script>';
        }
        break;
      case 'header':
        if (in_array('open-graph', $arr)) {
          $plugins[] = storeBuilder::opengraph($data['open-graph']);
        }
        if (defined('CHECK_RDR')) {
          $plugins[] = '<meta http-equiv="refresh" content="' . REDIRECT_TIME . ';url=' . BASE_HREF . CHECK_RDR . '">';
        }
        if (in_array('rdr-login', $arr)) {
          $plugins[] = '<meta http-equiv="refresh" content="' . REDIRECT_TIME . ';url=' . BASE_HREF . $this->seo->url('login',array(),'yes') . '">';
        }
        if (in_array('rdr-account', $arr)) {
          $plugins[] = '<meta http-equiv="refresh" content="' . REDIRECT_TIME . ';url=' . BASE_HREF . $this->seo->url('account',array(),'yes') . '">';
        }
        break;
    }
    return (!empty($plugins) ? implode(mswDefineNewline(), $plugins) . mswDefineNewline() : '');
  }

  public function template($arr, $file, $raw = false) {
    if (file_exists(PATH . 'content/' . THEME . '/html/' . $file)) {
      if ($raw) {
        return file_get_contents(PATH . 'content/' . THEME . '/html/' . $file);
      }
      return strtr(file_get_contents(PATH . 'content/' . THEME . '/html/' . $file), $arr);
    }
    die('<b>Template Error</b>: content/' . THEME . '/html/' . $file . ' is missing');
  }

}

?>