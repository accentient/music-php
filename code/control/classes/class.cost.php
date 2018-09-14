<?php

class costing extends db {

  public $settings;
  public $offers;

  public function offer($price, $type, $colId) {
    $discount = 'no';
    if (!empty($this->offers)) {
      if ($this->offers['all'] != 'no' || $this->offers['col'] != 'no' || $this->offers['cd'] != 'no' || $this->offers['track'] != 'no') {
        if ($this->offers['all'] != 'no') {
          if (empty($this->offers['rest']['all'])) {
            $discount = costing::discount($price, $this->offers['all']);
          } else {
            if (in_array($colId, $this->offers['rest']['all'])) {
              $discount = costing::discount($price, $this->offers['all']);
            }
          }
        } else {
          switch ($type) {
            // Collection ONLY..
            case 'col':
              if ($this->offers['col'] != 'no' && $type == 'col') {
                if (empty($this->offers['rest']['col'])) {
                  $discount = costing::discount($price, $this->offers['col']);
                } else {
                  if (in_array($colId, $this->offers['rest']['col'])) {
                    $discount = costing::discount($price, $this->offers['col']);
                  }
                }
              }
              break;
            // CD ONLY..
            case 'cd':
              if ($this->offers['cd'] != 'no' && $type == 'cd') {
                if (empty($this->offers['rest']['cd'])) {
                  $discount = costing::discount($price, $this->offers['cd']);
                } else {
                  if (in_array($colId, $this->offers['rest']['cd'])) {
                    $discount = costing::discount($price, $this->offers['cd']);
                  }
                }
              }
              break;
            // Tracks ONLY..
            case 'track':
              if ($this->offers['track'] != 'no' && $type == 'track') {
                if (empty($this->offers['rest']['track'])) {
                  $discount = costing::discount($price, $this->offers['track']);
                } else {
                  if (in_array($colId, $this->offers['rest']['track'])) {
                    $discount = costing::discount($price, $this->offers['track']);
                  }
                }
              }
              break;
          }
        }
        return $discount;
      }
    }
    return 'no';
  }

  public function discount($price, $offer) {
    if (substr($offer, -1) == '%') {
      $per = (int) substr($offer, 0, -1);
      $sum = mswFormatPrice(($per * $price) / 100);
      $new = mswFormatPrice(($price - $sum));
    } else {
      if ($price < $offer) {
        $new = '0.00';
      } else {
        $new = mswFormatPrice(($price - $offer));
      }
    }
    return $new;
  }

  public function offers() {
    $arr = array(
      'col' => 'no',
      'track' => 'no',
      'cd' => 'no',
      'all' => 'no',
      'rest' => array(
        'all' => array(),
        'col' => array(),
        'track' => array(),
        'cd' => array()
      )
    );
    // Should anything expire?
    db::db_query("UPDATE `" . DB_PREFIX . "offers` SET `enabled` = 'no' WHERE `expiry` > 0 AND DATE(FROM_UNIXTIME(`expiry`)) <= CURDATE()");
    // Quick check for anything enabled, prevents further queries..
    if (db::db_rowcount('offers WHERE `enabled` = \'yes\'', '', false) == 0) {
      return $arr;
    }
    // Check for site wide offers..
    $Q = db::db_query("SELECT `discount`,`collections` FROM `" . DB_PREFIX . "offers`
          WHERE `enabled` = 'yes'
          AND `type`      = 'all'
          LIMIT 1
          ");
    $O = db::db_object($Q);
    if (isset($O->discount)) {
      $arr['all']         = $O->discount;
      $arr['rest']['all'] = ($O->collections ? explode(',', $O->collections) : array());
    } else {
      // Is there a discount for all collections ONLY?
      $Q2 = db::db_query("SELECT `discount` FROM `" . DB_PREFIX . "offers`
            WHERE `enabled` = 'yes'
            AND `type`      = 'collections'
            AND (`collections` IS NULL OR `collections` = '')
            LIMIT 1
            ");
      $O  = db::db_object($Q2);
      if (isset($O->discount)) {
        $arr['col']         = $O->discount;
        $arr['rest']['col'] = array();
      } else {
        // Is there a discount for certain collections ONLY?
        $Q3 = db::db_query("SELECT `discount`,`collections` FROM `" . DB_PREFIX . "offers`
             WHERE `enabled` = 'yes'
             AND `type`      = 'collections'
             AND (`collections` IS NOT NULL OR `collections` != '')
             LIMIT 1
             ");
        $O  = db::db_object($Q3);
        if (isset($O->discount)) {
          $arr['col']         = $O->discount;
          $arr['rest']['col'] = explode(',', $O->collections);
        }
      }
      // Is there a discount for all tracks ONLY?
      $Q4 = db::db_query("SELECT `discount` FROM `" . DB_PREFIX . "offers`
            WHERE `enabled` = 'yes'
            AND `type`      = 'tracks'
            AND (`collections` IS NULL OR `collections` = '')
            LIMIT 1
            ");
      $O  = db::db_object($Q4);
      if (isset($O->discount)) {
        $arr['track']         = $O->discount;
        $arr['rest']['track'] = array();
      } else {
        // Is there a discount for tracks for certain collections ONLY?
        $Q5 = db::db_query("SELECT `discount`,`collections` FROM `" . DB_PREFIX . "offers`
              WHERE `enabled` = 'yes'
              AND `type`      = 'tracks'
              AND (`collections` IS NOT NULL OR `collections` != '')
              LIMIT 1
              ");
        $O  = db::db_object($Q5);
        if (isset($O->discount)) {
          $arr['track']         = $O->discount;
          $arr['rest']['track'] = explode(',', $O->collections);
        }
      }
      // Is there a discount for CDs ONLY?
      $Q6 = db::db_query("SELECT `discount` FROM `" . DB_PREFIX . "offers`
            WHERE `enabled` = 'yes'
            AND `type`      = 'cd'
            AND (`collections` IS NULL OR `collections` = '')
            LIMIT 1
            ");
      $O  = db::db_object($Q6);
      if (isset($O->discount)) {
        $arr['cd']         = $O->discount;
        $arr['rest']['cd'] = array();
      } else {
        // Is there a discount for cds for certain collections ONLY?
        $Q7 = db::db_query("SELECT `discount`,`collections` FROM `" . DB_PREFIX . "offers`
              WHERE `enabled` = 'yes'
              AND `type`      = 'cd'
              AND (`collections` IS NOT NULL OR `collections` != '')
              LIMIT 1
              ");
        $O  = db::db_object($Q7);
        if (isset($O->discount)) {
          $arr['cd']         = $O->discount;
          $arr['rest']['cd'] = explode(',', $O->collections);
        }
      }
    }
    return $arr;
  }

}

?>