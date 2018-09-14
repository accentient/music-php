<?php

class geoIP extends db {

  public $settings;

  public function lookup($ip,$lang) {
    $ar = array(
      'country' => $lang,
      'iso' => ''
    );
    // Check for localhost..
    if (in_array($ip,array('127.0.0.1','::1'))) {
      $ar = array(
        'country' => 'Localhost',
        'iso' => '1l'
      );
    } else {
      // Are GEO ip settings enabled?
      if ($this->settings->geoip=='yes') {
        // Ipv4/Ipv6 detection..
        if (strpos($ip, ':') !== false) {
          $Q = db::db_query("SELECT `country`,`iso2` FROM `" . DB_PREFIX . "geo_ipv6`
               LEFT JOIN `" . DB_PREFIX . "countries`
               ON `" . DB_PREFIX . "geo_ipv6`.`country_iso` = `" . DB_PREFIX . "countries`.`iso2`
               WHERE HEX(INET6_ATON('" . mswSafeString($ip, $this) . "')) BETWEEN HEX(INET6_ATON(`from_ip`)) AND HEX(INET6_ATON(`to_ip`))
               LIMIT 1
               ", true);
        } else {
          $Q = db::db_query("SELECT `country`,`iso2` FROM `" . DB_PREFIX . "geo_ipv4`
               LEFT JOIN `" . DB_PREFIX . "countries`
               ON `" . DB_PREFIX . "geo_ipv4`.`country_iso` = `" . DB_PREFIX . "countries`.`iso2`
               WHERE INET_ATON('" . mswSafeString($ip, $this) . "') BETWEEN INET_ATON(`from_ip`) AND INET_ATON(`to_ip`)
               LIMIT 1
               ");
        }
        if ($Q) {
          $IP = db::db_object($Q);
          if (isset($IP->country)) {
            $ar = array(
              'country' => $IP->country,
              'iso' => $IP->iso2
            );
          }
        }
      }
    }
    return $ar;
  }

  public function flag($data) {
    if ($data['iso']=='1l') {
      $string = '<i class="fa fa-th-large fa-fw" title="' . mswSafeDisplay($data['country']) . '"></i> ' . ($data['showip'] == 'yes' ? $data['ip'] : '');
    } else {
      if ($data['iso']) {
        if ($data['conly'] == 'no' && file_exists(PATH . $data['path'] . $data['iso'] . '.png')) {
          $string = '<span style="background:url(' . $data['path'] . $data['iso'] . '.png) no-repeat left center;padding-left:15px" title="' . mswSafeDisplay($data['country']) . '"></span> ' . ($data['showip'] == 'yes' ? $data['ip'] : '');
        } else {
          $string = '[' . mswSafeDisplay($data['country']) . '] ' . ($data['showip'] == 'yes' ? $data['ip'] : '');
        }
      } else {
        $string = '<i class="fa fa-globe fa-fw" title="' . mswSafeDisplay($data['unknown']) . '"></i> ' . ($data['showip'] == 'yes' ? $data['ip'] : '');
      }
    }
    return $string;
  }

}

?>