<?php

class cart extends db {

  public $settings;
  public $seo;
  public $datetime;
  public $costing;

  public function taxBreakdown($b) {
    $breakdown = cart::basketItems($b, array(), true);
    $arr = array(
      'items' => $breakdown[2],
      'total' => mswCurrencyFormat(mswFormatPrice($breakdown[0]), $this->settings->curdisplay),
      'coupon' => (isset($_SESSION['basketHidden'][8][2]) ? mswCurrencyFormat(mswFormatPrice($_SESSION['basketHidden'][8][2]), $this->settings->curdisplay) : '0.00'),
      'country' => (isset($_SESSION['basketHidden'][7]) ? cart::country($_SESSION['basketHidden'][7]) : '0'),
      'rate' => (isset($_SESSION['basketHidden'][3]) ? $_SESSION['basketHidden'][3] : '0'),
      'shipping' => (isset($_SESSION['basketHidden'][1]) ? mswCurrencyFormat(mswFormatPrice($_SESSION['basketHidden'][1]), $this->settings->curdisplay) : '0.00'),
      'amount' => (isset($_SESSION['basketHidden'][2]) ? mswCurrencyFormat(mswFormatPrice($_SESSION['basketHidden'][2]), $this->settings->curdisplay) : '0.00'),
      'ramount' => (isset($_SESSION['basketHidden'][2]) ? $_SESSION['basketHidden'][2] : '0.00'),
      'items2' => $breakdown[3],
      'total2' => mswCurrencyFormat(mswFormatPrice($breakdown[1]), $this->settings->curdisplay),
      'coupon2' => (isset($_SESSION['basketHidden'][8][3]) ? mswCurrencyFormat(mswFormatPrice($_SESSION['basketHidden'][8][3]), $this->settings->curdisplay) : '0.00'),
      'country2' => (isset($_SESSION['basketHidden'][9]) ? cart::country($_SESSION['basketHidden'][9]) : '0'),
      'rate2' => (isset($_SESSION['basketHidden'][5]) ? $_SESSION['basketHidden'][5] : '0'),
      'amount2' => (isset($_SESSION['basketHidden'][4]) ? mswCurrencyFormat(mswFormatPrice($_SESSION['basketHidden'][4]), $this->settings->curdisplay) : '0.00'),
      'ramount2' => (isset($_SESSION['basketHidden'][4]) ? $_SESSION['basketHidden'][4] : '0.00')
    );
    $arr['total-tax'] = mswCurrencyFormat(mswFormatPrice($arr['ramount'] + $arr['ramount2']), $this->settings->curdisplay);
    return $arr;
  }

  public function coupon($coupon, $account) {
    $arr = array(
      'invalid',
      ''
    );
    $now = $this->datetime->utcTime();
    $Q   = db::db_query("SELECT * FROM `" . DB_PREFIX . "coupons` WHERE `code` = '" . mswSafeString($coupon, $this) . "' LIMIT 1");
    $CP  = db::db_object($Q);
    if (isset($CP->id) && $CP->enabled == 'yes') {
      $acc = ($CP->accounts ? explode(',', $CP->accounts) : '');
      if (!empty($acc) && !in_array($account, $acc)) {
        $arr = array(
          'invalid',
          ''
        );
      } else {
        if ($CP->expiry > 0 && date('Y-m-d', $CP->expiry) < date('Y-m-d', $now)) {
          $arr = array(
            'expired',
            $this->datetime->dateTimeDisplay($CP->expiry, $this->settings->dateformat)
          );
        } else {
          $arr = array(
            'ok',
            $CP->discount
          );
        }
      }
    }
    return $arr;
  }

  public function country($id) {
    $Q  = db::db_query("SELECT `name` FROM `".DB_PREFIX."countries` WHERE `id` = '{$id}'");
    $CN = db::db_object($Q);
    return (isset($CN->name) ? $CN->name : 'N/A');
  }

  public function music($b, $sale, $type, $l) {
    $html = '';
    $ics  = array(0,0);
    $Q    = db::db_query("SELECT * FROM `" . DB_PREFIX . "sales_items`
            WHERE `sale` = '{$sale}'
            " . ($type == 'shipped' ? 'AND `physical` = \'yes\'' : 'AND `physical` = \'no\'') . "
            ORDER BY FIELD(`type`,'collection','track'),`id` DESC
            ");
    while ($M = db::db_object($Q)) {
      // Type options..
      switch ($M->type) {
        case 'collection':
          $Q_C  = db::db_query("SELECT `id`,`slug`,`name`,`coverart`,`cost`,`costcd`,`bitrate`,`length` FROM `" . DB_PREFIX . "collections` WHERE `id` = '{$M->item}'");
          $COL  = db::db_object($Q_C);
          $info = array(
            'col' => mswSafeDisplay($COL->name),
            'det' => ($M->physical == 'yes' ? $l[14] : str_replace(array(
              '{rate}',
              '{length}'
            ), array(
              $COL->bitrate,
              mswTrimTime($COL->length)
            ), $l[16]))
          );
          if ($M->physical == 'yes') {
            ++$ics[0];
          } else {
            ++$ics[1];
          }
          break;
        default:
          $Q_T = db::db_query("SELECT `collection`,`title`,`cost`,`bitrate`,`length` FROM `" . DB_PREFIX . "music` WHERE `id` = '{$M->item}'");
          $CTK = db::db_object($Q_T);
          if (isset($CTK->title)) {
            $Q_C  = db::db_query("SELECT `id`,`slug`,`name`,`coverart` FROM `" . DB_PREFIX . "collections` WHERE `id` = '{$CTK->collection}'");
            $COL  = db::db_object($Q_C);
            $info = array(
              'col' => mswSafeDisplay($COL->name),
              'det' => str_replace(array(
                '{name}',
                '{rate}',
                '{length}'
              ), array(
                mswSafeDisplay($CTK->title),
                $CTK->bitrate,
                mswTrimTime($CTK->length)
              ), $l[15])
            );
            ++$ics[1];
          }
          break;
      }
      if (isset($info['col'])) {
        // Build url..
        $url = array(
          'seo' => array(
            ($COL->slug ? $COL->slug : $this->seo->filter($COL->name)),
            ($COL->slug == '' ? $COL->id : '')
          ),
          'standard' => array(
            'id' => $COL->id
          )
        );
        // Load template..
        $fr  = array(
          '{url}' => BASE_HREF . $this->seo->url('collection', $url),
          '{coverart}' => BASE_HREF . $b->cover($COL->coverart),
          '{collection}' => $info['col'],
          '{detail}' => $info['det'],
          '{size}' => '',
          '{cost}' => mswCurrencyFormat($M->cost, $this->settings->curdisplay),
          '{id}' => $M->id,
          '{txt}' => str_replace("'", "\'", $l[25]),
          '{path}' => 'content/' . THEME . '/'
        );
        $html .= $b->template($fr, 'order-detail-item-' . $type . '.tpl');
      }
    }
    return array($html, $ics);
  }

  public function orders($b, $id, $limit = 0, $pagelimit = array(), $justrows = false) {
    $html   = '';
    $Q      = db::db_query("SELECT SQL_CALC_FOUND_ROWS *,
              (SELECT `display` FROM `" . DB_PREFIX . "gateways` WHERE `id` = `" . DB_PREFIX . "sales`.`gateway`) AS `paymentMethod`,
              (SELECT ROUND(SUM(`cost`),2) FROM `" . DB_PREFIX . "sales_items` WHERE `sale` = `" . DB_PREFIX . "sales`.`id`) AS `saleTotal`
              FROM `" . DB_PREFIX . "sales`
              WHERE `account` = '{$id}'
              AND `enabled`   = 'yes'
              ORDER BY `id` DESC
              " . ($limit > 0 ? 'LIMIT ' . $limit : '') . "
              " . (isset($pagelimit[0]) && $pagelimit[0] > 0 ? 'LIMIT ' . $pagelimit[0] . ',' . $pagelimit[1] : ''));
    $c      = db::db_object(db::db_query("SELECT FOUND_ROWS() AS `rows`"));
    $cnRows = (isset($c->rows) ? $c->rows : '0');
    if ($justrows) {
      return $cnRows;
    }
    while ($ORD = db::db_object($Q)) {
      if ($ORD->coupon) {
        $cp = mswCleanData(unserialize($ORD->coupon));
        if (isset($cp[0], $cp[1]) && $cp[1] > 0) {
          $discount = $cp[1];
        }
        $tot = ($ORD->saleTotal > 0 ? mswFormatPrice($ORD->saleTotal - $discount) : '0.00');
      } else {
        $tot = ($ORD->saleTotal > 0 ? $ORD->saleTotal : '0.00');
      }
      $tots  = ($ORD->shipping > 0 ? $ORD->shipping : '0.00');
      $totv  = ($ORD->tax > 0 ? $ORD->tax : '0.00');
      $totv2 = ($ORD->tax2 > 0 ? $ORD->tax2 : '0.00');
      $taxT  = mswFormatPrice($totv + $totv2);
      // Build url..
      $url  = array(
        'seo' => array(
          mswSaleInvoiceNumber($ORD->id)
        ),
        'standard' => array(
          '#' => mswSaleInvoiceNumber($ORD->id)
        )
      );
      $fr   = array(
        '{url}' => BASE_HREF . $this->seo->url('view-order', $url),
        '{invoice_no}' => mswSaleInvoiceNumber($ORD->invoice),
        '{date}' => $this->datetime->dateTimeDisplay($ORD->ts, $this->settings->dateformat),
        '{method}' => (isset($ORD->paymentMethod) ? $ORD->paymentMethod : 'N/A'),
        '{total}' => mswCurrencyFormat(($tot + $tots + $taxT), $this->settings->curdisplay)
      );
      $html .= $b->template($fr, 'order-item.tpl');
    }
    return $html;
  }

  public function add($data = array()) {
    if (!isset($_SESSION['cartItems'])) {
      $_SESSION['cartItems'] = array();
    }
    $_SESSION['cartItems'][] = array(
      'collection' => $data['collection'],
      'cost' => $data['cost'],
      'discount' => $data['discount'],
      'type' => $data['type'],
      'tracks' => $data['tracks'],
      'void' => 'no'
    );
  }

  public function basketItems($b, $lang = array(), $totals = false) {
    $html     = '';
    $taxgoods = array('0.00','0.00',0,0);
    if (!empty($_SESSION['cartItems'])) {
      for ($i = 0; $i < count($_SESSION['cartItems']); $i++) {
        if ($_SESSION['cartItems'][$i]['void'] == 'no') {
          $TS = array();
          $ID = (int) $_SESSION['cartItems'][$i]['collection'];
          $Q  = db::db_query("SELECT `name`,`coverart`,`slug` FROM `" . DB_PREFIX . "collections` WHERE `enabled` = 'yes' AND `id` = '{$ID}'");
          $C  = db::db_object($Q);
          // Change detail for tracks..
          if (!empty($_SESSION['cartItems'][$i]['tracks']) && !empty($lang)) {
            $QT = db::db_query("SELECT `title`,`length`,`bitrate`,`cost` FROM `" . DB_PREFIX . "music`
                  WHERE `id`       IN(" . mswSafeString(implode(',', array_keys($_SESSION['cartItems'][$i]['tracks'])), $this) . ")
                  AND `collection`  = '{$ID}'
                  ORDER BY `order`
                  ");
            while ($TR = db::db_object($QT)) {
              $disc = $this->costing->offer($TR->cost, 'track', $ID);
              $t_fr = array(
                '{track}' => str_replace(array(
                  '{track}',
                  '{length}',
                  '{bitrate}',
                  '{cost}'
                ), array(
                  mswSafeDisplay($TR->title),
                  mswTrimTime($TR->length),
                  $TR->bitrate,
                  mswCurrencyFormat(($disc != 'no' && $disc != $TR->cost ? $disc : $TR->cost), $this->settings->curdisplay)
                ), $lang[23])
              );
              $TS[] = $b->template($t_fr, 'basket-item-track.tpl');
            }
          }
          // Build url..
          $url = array(
            'seo' => array(
              ($C->slug ? $C->slug : $this->seo->filter($C->name)),
              ($C->slug == '' ? $ID : '')
            ),
            'standard' => array(
              'id' => $ID
            )
          );
          if (isset($C->name)) {
            if (!empty($lang)) {
              $countData = ($_SESSION['cartItems'][$i]['type'] == 'CD' ? $lang[24] : $lang[22]);
              $countData = str_replace('{count}',cart::colTrackCount($ID),$countData);
              $fr = array(
                '{slot}' => $i,
                '{image}' => BASE_HREF . $b->cover($C->coverart),
                '{url}' => BASE_HREF . $this->seo->url('collection', $url),
                '{item}' => mswSafeDisplay($C->name),
                '{detail}' => (!empty($TS) ? implode(mswDefineNewline(), $TS) : $countData),
                '{cost}' => mswCurrencyFormat(($_SESSION['cartItems'][$i]['discount'] != 'no' && $_SESSION['cartItems'][$i]['discount'] != $_SESSION['cartItems'][$i]['cost'] ? $_SESSION['cartItems'][$i]['discount'] : $_SESSION['cartItems'][$i]['cost']), $this->settings->curdisplay)
              );
              $html .= $b->template($fr, 'basket-item.tpl');
            }
            // Totals..
            if ($_SESSION['cartItems'][$i]['type'] == 'CD') {
              ++$taxgoods[2];
              $taxgoods[0] = mswFormatPrice($taxgoods[0] + ($_SESSION['cartItems'][$i]['discount'] != 'no' && $_SESSION['cartItems'][$i]['discount'] != $_SESSION['cartItems'][$i]['cost'] ? $_SESSION['cartItems'][$i]['discount'] : $_SESSION['cartItems'][$i]['cost']));
            } else {
              if (empty($_SESSION['cartItems'][$i]['tracks'])) {
                ++$taxgoods[3];
              } else {
                $taxgoods[3] += count($_SESSION['cartItems'][$i]['tracks']);
              }
              $taxgoods[1] = mswFormatPrice($taxgoods[1] + ($_SESSION['cartItems'][$i]['discount'] != 'no' && $_SESSION['cartItems'][$i]['discount'] != $_SESSION['cartItems'][$i]['cost'] ? $_SESSION['cartItems'][$i]['discount'] : $_SESSION['cartItems'][$i]['cost']));
            }
          }
        }
      }
    }
    // Return breakdown totals..
    if ($totals) {
      return $taxgoods;
    }
    return $html;
  }

  public function colTrackCount($col) {
    $cnt = 0;
    $Q   = db::db_query("SELECT count(*) AS `trackCount` FROM `" . DB_PREFIX . "music` WHERE `collection` = '{$col}'");
    $C   = db::db_object($Q);
    if (isset($C->trackCount)) {
      return $C->trackCount;
    }
    return $cnt;
  }

  public function modalItems($b, $lang) {
    $html    = '';
    $wrapper = $b->template(array(), 'modal-basket.tpl');
    if (!empty($_SESSION['cartItems'])) {
      for ($i = 0; $i < count($_SESSION['cartItems']); $i++) {
        if ($_SESSION['cartItems'][$i]['void'] == 'no') {
          $TS = array();
          $ID = (int) $_SESSION['cartItems'][$i]['collection'];
          $Q  = db::db_query("SELECT `name`,`coverart`,`slug` FROM `" . DB_PREFIX . "collections` WHERE `enabled` = 'yes' AND `id` = '{$ID}'");
          $C  = db::db_object($Q); // Change detail for tracks..
          if (!empty($_SESSION['cartItems'][$i]['tracks'])) {
            $QT = db::db_query("SELECT `title`,`length`,`bitrate`,`cost` FROM `" . DB_PREFIX . "music`
                  WHERE `id`       IN(" . mswSafeString(implode(',', array_keys($_SESSION['cartItems'][$i]['tracks'])), $this) . ")
                  AND `collection`  = '{$ID}'
                  ORDER BY `order`
                  ");
            while ($TR = db::db_object($QT)) {
              $disc = $this->costing->offer($TR->cost, 'track', $ID);
              $t_fr = array(
                '{track}' => str_replace(array(
                  '{track}',
                  '{length}',
                  '{bitrate}',
                  '{cost}'
                ), array(
                  mswSafeDisplay($TR->title),
                  mswTrimTime($TR->length),
                  $TR->bitrate,
                  mswCurrencyFormat(($disc != 'no' && $disc != $TR->cost ? $disc : $TR->cost), $this->settings->curdisplay)
                ), $lang[6])
              );
              $TS[] = $b->template($t_fr, 'basket-item-track.tpl');
            }
          }
          // Build url..
          $url = array(
            'seo' => array(
              ($C->slug ? $C->slug : $this->seo->filter($C->name)),
              ($C->slug == '' ? $ID : '')
            ),
            'standard' => array(
              'id' => $ID
            )
          );
          if (isset($C->name)) {
            $countData = ($_SESSION['cartItems'][$i]['type'] == 'CD' ? $lang[24] : $lang[22]);
            $countData = str_replace('{count}',cart::colTrackCount($ID),$countData);
            $fr = array(
              '{slot}' => $i,
              '{image}' => BASE_HREF . $b->cover($C->coverart),
              '{url}' => BASE_HREF . $this->seo->url('collection', $url),
              '{item}' => mswSafeDisplay($C->name),
              '{detail}' => (!empty($TS) ? implode(mswDefineNewline(), $TS) : $countData),
              '{cost}' => mswCurrencyFormat(($_SESSION['cartItems'][$i]['discount'] != 'no' && $_SESSION['cartItems'][$i]['discount'] != $_SESSION['cartItems'][$i]['cost'] ? $_SESSION['cartItems'][$i]['discount'] : $_SESSION['cartItems'][$i]['cost']), $this->settings->curdisplay)
            );
            $html .= $b->template($fr, 'modal-basket-item.tpl');
          }
        }
      }
    }
    return str_replace('{items}', $html, $wrapper);
  }

  public function total() {
    $t = '0.00';
    if (!empty($_SESSION['cartItems'])) {
      for ($i = 0; $i < count($_SESSION['cartItems']); $i++) {
        if ($_SESSION['cartItems'][$i]['void'] == 'no') {
          $n = ($_SESSION['cartItems'][$i]['discount'] != 'no' && $_SESSION['cartItems'][$i]['discount'] != $_SESSION['cartItems'][$i]['cost'] ? $_SESSION['cartItems'][$i]['discount'] : $_SESSION['cartItems'][$i]['cost']);
          $t = mswFormatPrice(($t + $n));
        }
      }
    }
    return $t;
  }

  public function getTax($b, $country = 0, $type, $shipping = '0.00', $discount = array()) {
    $t         = '0.00';
    $tr        = '0';
    $dcnt      = '0.00';
    $cn        = ($type == 'tangible' ? $this->settings->defCountry : $this->settings->defCountry2);
    $breakdown = cart::basketItems($b, array(), true);
    $grandTot  = mswFormatPrice($breakdown[0] + $breakdown[1]);
    switch($type) {
      case 'tangible':
        // Was coupon applied..
        if (isset($discount[0]) && $discount[0] != 'no') {
          // Get percentage. If fixed, get percentage of total for fixed discount..
          switch(substr($discount[0],-1)) {
            case '%':
              $dperc = substr($discount[0], 0, -1);
              break;
            default:
              $dperc = number_format(($discount[0] / $grandTot) * 100, 5, '.', '');
              break;
          }
          $dcnt  = mswFormatPrice(($dperc * $breakdown[0]) / 100);
          $total = mswFormatPrice(($breakdown[0] - $dcnt) + $shipping);
        } else {
          $total = mswFormatPrice($breakdown[0] + $shipping);
        }
        // Country overrides..
        if ($country > 0) {
          $Q = db::db_query("SELECT `tax` FROM `" . DB_PREFIX . "countries` WHERE `id` = '" . mswSafeString($country, $this) . "'");
          $R = db::db_object($Q);
          if (isset($R->tax)) {
            // Is tax explicitly off for this country?
            if (strtolower($R->tax) == 'no') {
              $sum = mswFormatPrice($total / 100);
              $t   = mswFormatPrice('0.00', 2, '.', '');
              $tr  = '0%';
              $cn  = $country;
            } else {
              // Check country rate..
              if ($R->tax > 0) {
                $sum = mswFormatPrice($R->tax * $total / 100);
                $t   = mswFormatPrice($sum, 2, '.', '');
                $tr  = $R->tax . '%';
                $cn  = $country;
              } else {
                // Set fixed rate if applicable..
                if ($this->settings->deftax > 0) {
                  $sum = mswFormatPrice($this->settings->deftax * $total / 100);
                  $t   = mswFormatPrice($sum, 2, '.', '');
                  $tr  = $this->settings->deftax . '%';
                  $cn  = $this->settings->defCountry;
                }
              }
            }
          }
        } else {
          // Set fixed rate if applicable..
          if ($this->settings->deftax > 0) {
            $sum = mswFormatPrice($this->settings->deftax * $total / 100);
            $t   = mswFormatPrice($sum, 2, '.', '');
            $tr  = $this->settings->deftax . '%';
            $cn  = $this->settings->defCountry;
          }
        }
        break;
      case 'digital':
        // Was coupon applied..
        if (isset($discount[0]) && $discount[0] != 'no') {
          // Get percentage. If fixed, get percentage of total for fixed discount..
          switch(substr($discount[0],-1)) {
            case '%':
              $dperc = substr($discount[0], 0, -1);
              break;
            default:
              $dperc = number_format(($discount[0] / $grandTot) * 100, 5, '.', '');
              break;
          }
          $dcnt  = mswFormatPrice(($dperc * $breakdown[1]) / 100);
          $total = mswFormatPrice($breakdown[1] - $dcnt);
        } else {
          $total = $breakdown[1];
        }
        // Country overrides..
        if ($country > 0) {
          $Q = db::db_query("SELECT `tax2` FROM `" . DB_PREFIX . "countries` WHERE `id` = '" . mswSafeString($country, $this) . "'");
          $R = db::db_object($Q);
          if (isset($R->tax2)) {
            // Is tax explicitly off for this country?
            if (strtolower($R->tax2) == 'no') {
              $sum = mswFormatPrice($total / 100);
              $t   = mswFormatPrice('0.00', 2, '.', '');
              $tr  = '0%';
              $cn  = $country;
            } else {
              // Check country rate..
              if ($R->tax2 > 0) {
                $sum = mswFormatPrice($R->tax2 * $total / 100);
                $t   = mswFormatPrice($sum, 2, '.', '');
                $tr  = $R->tax2 . '%';
                $cn  = $country;
              } else {
                // Set fixed rate if applicable..
                if ($this->settings->deftax2 > 0) {
                  $sum = mswFormatPrice($this->settings->deftax2 * $total / 100);
                  $t   = mswFormatPrice($sum, 2, '.', '');
                  $tr  = $this->settings->deftax2 . '%';
                  $cn  = $this->settings->defCountry2;
                }
              }
            }
          }
        } else {
          // Set fixed rate if applicable..
          if ($this->settings->deftax2 > 0) {
            $sum = mswFormatPrice($this->settings->deftax2 * $total / 100);
            $t   = mswFormatPrice($sum, 2, '.', '');
            $tr  = $this->settings->deftax2 . '%';
            $cn  = $this->settings->defCountry2;
          }
        }
        break;
    }
    return array(
      $t,
      $tr,
      $cn,
      $dcnt
    );
  }

  public function getShipping($total, $method) {
    $c = '0.00';
    $Q = db::db_query("SELECT * FROM `" . DB_PREFIX . "shipping` WHERE `id` = '{$method}'");
    $R = db::db_object($Q);
    if (isset($R->id)) {
      switch (substr($R->cost, -1)) {
        case '%':
          $per = substr($R->cost, 0, -1);
          $sum = mswFormatPrice($per * $total / 100);
          $c   = mswFormatPrice($sum, 2, '.', '');
          break;
        default:
          $c = $R->cost;
          break;
      }
    }
    return $c;
  }

  public function isShipping() {
    if (!isset($_SESSION['cartItems'])) {
      return 'no';
    }
    for ($i = 0; $i < count($_SESSION['cartItems']); $i++) {
      if ($_SESSION['cartItems'][$i]['void'] == 'no' && $_SESSION['cartItems'][$i]['type'] == 'CD') {
        return 'yes';
      }
    }
    return 'no';
  }

  public function count() {
    $cnt = 0;
    if (!isset($_SESSION['cartItems'])) {
      return '0';
    }
    for ($i = 0; $i < count($_SESSION['cartItems']); $i++) {
      if ($_SESSION['cartItems'][$i]['void'] == 'no') {
        if (!empty($_SESSION['cartItems'][$i]['tracks'])) {
          $cnt = ($cnt + count($_SESSION['cartItems'][$i]['tracks']));
        } else {
          ++$cnt;
        }
      }
    }
    return $cnt;
  }

  public function clear() {
    $_SESSION['cartItems']    = array();
    $_SESSION['basketHidden'] = array();
    unset($_SESSION['cartItems'], $_SESSION['basketHidden']);
  }

}

?>