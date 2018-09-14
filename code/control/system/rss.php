<?php

if (!defined('PARENT') || !defined('BASE_HREF') || $SETTINGS->rss == 'no') {
  mswEcode($gblang[4], '403');
}

$type = '';

include(PATH . 'control/classes/class.rss.php');
$RSS           = new rss();
$RSS->settings = $SETTINGS;

// Determine what feed we are loading..
// Check standard cmd..
if (isset($_GET['p'])) {
  $type = substr($_GET['p'], 4);
  if (substr($_GET['p'], 4, 5) == 'style') {
    $type = 'style';
  }
} else {
  // Rewrite rules..
  if ($SETTINGS->rewrite == 'yes') {
    $sef = $SEO->rewriteElements();
    if (isset($sef[0])) {
      $type = substr($sef[0], 4);
      if (substr($sef[0], 4, 5) == 'style') {
        $type = 'style';
      }
    }
  }
}
if (!in_array($type, array(
  'home',
  'latest',
  'popular',
  'style'
))) {
  mswEcode($gblang[4], '403');
}

$build = $RSS->open();

switch ($type) {
  case 'home':
    $lk  = BASE_HREF;
    $dc  = str_replace('{website}', $SETTINGS->website, $pbrss[0]);
    $it  = '';
    $sql = 'ORDER BY `id` DESC LIMIT ' . FEATURED_HOME_LIMIT;
    if ($SETTINGS->featured) {
      $IDS = unserialize($SETTINGS->featured);
      if (!empty($IDS)) {
        $ft  = mswSafeString(implode(',', $IDS), $DB);
        $sql = 'AND `id` IN(' . $ft . ') ORDER BY FIELD(`id`,' . $ft . ')';
      }
    }
    $Q = $DB->db_query("SELECT * FROM `" . DB_PREFIX . "collections`
         WHERE `enabled` = 'yes'
         " . $sql);
    while ($COL = $DB->db_object($Q)) {
      $cost2 = '-';
      $disc  = $COSTING->offer($COL->cost, 'col', $COL->id);
      $cost  = ($disc != 'no' ? $disc : $COL->cost);
      if ($SETTINGS->cdpur == 'yes' && $COL->costcd != '') {
        $disc2 = $COSTING->offer($COL->costcd, 'col', $COL->id);
        $cost2 = ($disc2 != 'no' ? $disc2 : $COL->costcd);
      }
      $url = array(
        'seo' => array(
          ($COL->slug ? $COL->slug : $SEO->filter($COL->name)),
          ($COL->slug == '' ? $COL->id : '')
        ),
        'standard' => array(
          '#' => $COL->id
        )
      );
      $it .= $RSS->item(array(
        'title' => mswCleanData($COL->name),
        'date' => date('r', $COL->added),
        'link' => BASE_HREF . $SEO->url('collection', $url),
        'desc' => str_replace('{cost}', mswCurrencyFormat(mswFormatPrice($cost), $SETTINGS->curdisplay), $pbrss[4]) . ($cost2 != '-' ? str_replace('{cost}', mswCurrencyFormat(mswFormatPrice($cost2), $SETTINGS->curdisplay), $pbrss[5]) : '')
      ));
    }
    break;
  case 'latest':
    $lk  = BASE_HREF . $SEO->url('latest', array(), 'yes');
    $dc  = str_replace('{website}', $SETTINGS->website, $pbrss[1]);
    $it  = '';
    $sql = 'ORDER BY `id` DESC LIMIT ' . LATEST_LIMIT;
    $Q   = $DB->db_query("SELECT * FROM `" . DB_PREFIX . "collections`
         WHERE `enabled` = 'yes'
         " . $sql);
    while ($COL = $DB->db_object($Q)) {
      $cost2 = '-';
      $disc  = $COSTING->offer($COL->cost, 'col', $COL->id);
      $cost  = ($disc != 'no' ? $disc : $COL->cost);
      if ($SETTINGS->cdpur == 'yes' && $COL->costcd != '') {
        $disc2 = $COSTING->offer($COL->costcd, 'col', $COL->id);
        $cost2 = ($disc2 != 'no' ? $disc2 : $COL->costcd);
      }
      $url = array(
        'seo' => array(
          ($COL->slug ? $COL->slug : $SEO->filter($COL->name)),
          ($COL->slug == '' ? $COL->id : '')
        ),
        'standard' => array(
          '#' => $COL->id
        )
      );
      $it .= $RSS->item(array(
        'title' => mswCleanData($COL->name),
        'date' => date('r', $COL->added),
        'link' => BASE_HREF . $SEO->url('collection', $url),
        'desc' => str_replace('{cost}', mswCurrencyFormat(mswFormatPrice($cost), $SETTINGS->curdisplay), $pbrss[4]) . ($cost2 != '-' ? str_replace('{cost}', mswCurrencyFormat(mswFormatPrice($cost2), $SETTINGS->curdisplay), $pbrss[5]) : '')
      ));
    }
    break;
  case 'popular':
    $lk  = BASE_HREF . $SEO->url('popular', array(), 'yes');
    $dc  = str_replace('{website}', $SETTINGS->website, $pbrss[2]);
    $it  = '';
    $sql = 'ORDER BY count(*) DESC LIMIT ' . POPULAR_LIMIT;
    $Q   = $DB->db_query("SELECT *,
           `" . DB_PREFIX . "collections`.`id` AS `collID`,
           `" . DB_PREFIX . "collections`.`cost` AS `colCost`
           FROM `" . DB_PREFIX . "collections`
           LEFT JOIN `" . DB_PREFIX . "sales_items`
           ON `" . DB_PREFIX . "collections`.`id`         = `" . DB_PREFIX . "sales_items`.`collection`
           WHERE `" . DB_PREFIX . "collections`.`enabled` = 'yes'
           GROUP BY `" . DB_PREFIX . "sales_items`.`collection`
           " . $sql);
    while ($COL = $DB->db_object($Q)) {
      $cost2 = '-';
      $disc  = $COSTING->offer($COL->colCost, 'col', $COL->collID);
      $cost  = ($disc != 'no' ? $disc : $COL->colCost);
      if ($SETTINGS->cdpur == 'yes' && $COL->costcd != '') {
        $disc2 = $COSTING->offer($COL->costcd, 'col', $COL->collID);
        $cost2 = ($disc2 != 'no' ? $disc2 : $COL->costcd);
      }
      $url = array(
        'seo' => array(
          ($COL->slug ? $COL->slug : $SEO->filter($COL->name)),
          ($COL->slug == '' ? $COL->collID : '')
        ),
        'standard' => array(
          '#' => $COL->collID
        )
      );
      $it .= $RSS->item(array(
        'title' => mswCleanData($COL->name),
        'date' => date('r', $COL->added),
        'link' => BASE_HREF . $SEO->url('collection', $url),
        'desc' => str_replace('{cost}', mswCurrencyFormat(mswFormatPrice($cost), $SETTINGS->curdisplay), $pbrss[4]) . ($cost2 != '-' ? str_replace('{cost}', mswCurrencyFormat(mswFormatPrice($cost2), $SETTINGS->curdisplay), $pbrss[5]) : '')
      ));
    }
    break;
  case 'style':
    $ID = (isset($sef[0]) ? (int) substr($sef[0], 9) : (int) substr($_GET['p'], 9));
    if ($ID > 0) {
      $Q  = $DB->db_query("SELECT * FROM `" . DB_PREFIX . "music_styles` WHERE `id` = '{$ID}' AND `enabled` = 'yes'");
      $SY = $DB->db_object($Q);
      if (isset($SY->name)) {
        $url = array(
          'seo' => array(
            ($SY->slug ? $SY->slug : $SEO->filter($SY->name)),
            ($SY->slug == '' ? $SY->id : '')
          ),
          'standard' => array(
            '#' => $SY->id
          )
        );
        $lk  = BASE_HREF . $SEO->url('style', $url);
        $it  = '';
        $dc  = str_replace(array(
          '{website}',
          '{style}'
        ), array(
          $SETTINGS->website,
          $SY->name
        ), $pbrss[3]);
        $sql = 'ORDER BY `' . DB_PREFIX . 'collections`.`name`';
        $Q   = $DB->db_query("SELECT SQL_CALC_FOUND_ROWS *,`" . DB_PREFIX . "collections`.`id` AS `collID` FROM `" . DB_PREFIX . "collection_styles`
               LEFT JOIN `" . DB_PREFIX . "collections`
               ON `" . DB_PREFIX . "collection_styles`.`collection` = `" . DB_PREFIX . "collections`.`id`
               WHERE `" . DB_PREFIX . "collections`.`enabled`   = 'yes'
               AND `" . DB_PREFIX . "collection_styles`.`style` = '{$SY->id}'
               " . $sql);
        while ($COL = $DB->db_object($Q)) {
          $cost2 = '-';
          $disc  = $COSTING->offer($COL->cost, 'col', $COL->collID);
          $cost  = ($disc != 'no' ? $disc : $COL->cost);
          if ($SETTINGS->cdpur == 'yes' && $COL->costcd != '') {
            $disc2 = $COSTING->offer($COL->costcd, 'col', $COL->collID);
            $cost2 = ($disc2 != 'no' ? $disc2 : $COL->costcd);
          }
          $url = array(
            'seo' => array(
              ($COL->slug ? $COL->slug : $SEO->filter($COL->name)),
              ($COL->slug == '' ? $COL->collID : '')
            ),
            'standard' => array(
              '#' => $COL->collID
            )
          );
          $it .= $RSS->item(array(
            'title' => mswCleanData($COL->name),
            'date' => date('r', $COL->added),
            'link' => BASE_HREF . $SEO->url('collection', $url),
            'desc' => str_replace('{cost}', mswCurrencyFormat(mswFormatPrice($cost), $SETTINGS->curdisplay), $pbrss[4]) . ($cost2 != '-' ? str_replace('{cost}', mswCurrencyFormat(mswFormatPrice($cost2), $SETTINGS->curdisplay), $pbrss[5]) : '')
          ));
        }
      } else {
        mswEcode($gblang[4], '403');
      }
    } else {
      mswEcode($gblang[4], '403');
    }
    break;
}

$build .= $RSS->feed(array(
  'title' => $SETTINGS->website,
  'date' => date('r'),
  'link' => $lk,
  'desc' => $dc,
  'site' => $SETTINGS->website,
  'self' => BASE_HREF . (isset($sef[0]) ? $sef[0] : '?p=' . $_GET['p'])
));
$build .= $it;
$build .= $RSS->close();

// Show feed..
echo $build;

?>