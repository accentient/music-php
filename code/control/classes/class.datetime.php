<?php

class mmDateTime {

  public $settings;

  // Convert ts to specified date..
  public function tsToDate($ts, $format) {
    if ($ts == 0) {
      return date('Y-m-d', $ts);
    }
    switch ($format) {
      case 'DD-MM-YYYY':
        return date('d-m-Y', $ts);
        break;
      case 'DD/MM/YYYY':
        return date('d/m/Y', $ts);
        break;
      case 'YYYY-MM-DD':
        return date('Y-m-d', $ts);
        break;
      case 'YYYY/MM/DD':
        return date('Y/m/d', $ts);
        break;
      case 'MM-DD-YYYY':
        return date('m-d-Y', $ts);
        break;
      case 'MM/DD/YYYY':
        return date('m/d/Y', $ts);
        break;
      default:
        return date('Y-m-d', $ts);
        break;
    }
  }

  // Calendar picker format to sql..
  public function dateToTS($val) {
    $ts = 0;
    // Convert into js format dates..
    switch ($this->settings->jsformat) {
      case 'DD-MM-YYYY':
      case 'DD/MM/YYYY':
        $d = substr($val, 6, 4) . '-' . substr($val, 3, 2) . '-' . substr($val, 0, 2);
        break;
      case 'YYYY-MM-DD':
        $d = $val;
        break;
      case 'YYYY/MM/DD':
        $d = str_replace('/', '-', $val);
        break;
      case 'MM-DD-YYYY':
      case 'MM/DD/YYYY':
        $d = substr($val, 6, 4) . '-' . substr($val, 0, 2) . '-' . substr($val, 3, 2);
        break;
    }
    if ($d) {
      return strtotime($d);
    }
    return $ts;
  }

  // Calendar picker format..
  public function jsFormat() {
    // Convert into js format dates..
    switch ($this->settings->jsformat) {
      case 'DD-MM-YYYY':
        return 'dd-mm-yy';
        break;
      case 'DD/MM/YYYY':
        return 'dd/mm/yy';
        break;
      case 'YYYY-MM-DD':
        return 'yy-mm-dd';
        break;
      case 'YYYY/MM/DD':
        return 'yy/mm/dd';
        break;
      case 'MM-DD-YYYY':
        return 'mm-dd-yy';
        break;
      case 'MM/DD/YYYY':
        return 'mm/dd/yy';
        break;
      default:
        return 'dd/mm/yy';
        break;
    }
  }

  public function setTimeZone($timezone, $zones) {
    if (in_array($timezone, array_keys($zones))) {
      date_default_timezone_set($timezone);
    } else {
      date_default_timezone_set('Europe/London');
    }
  }

  public function utcTime() {
    return (date('I') ? strtotime(date('Y-m-d H:i:s', strtotime('-1 hour'))) : strtotime(date('Y-m-d H:i:s')));
  }

  public function timeStamp() {
    return time();
  }

  public function dateTimeDisplay($ts = 0, $format, $zone = '') {
    if ($ts == 0) {
      $ts = mmDateTime::timeStamp();
    }
    if (!defined('MMTZ_SET')) {
      define('MMTZ_SET', $this->settings->timezone);
    }
    $dt = new DateTime(date('Y-m-d H:i:s', $ts) . ' UTC');
    $dt->setTimezone(new DateTimeZone(($zone ? $zone : MMTZ_SET)));
    return $dt->format($format);
  }

  public function gmtTime() {
    $ts = time() + date('Z');
    return strtotime(gmdate('Y-m-d H:i:s', $ts));
  }

  public function sqlDate() {
    return date('Y-m-d');
  }

  public function microtimeFloat() {
    list($usec, $sec) = explode(' ', microtime());
    return ((float) $usec + (float) $sec);
  }

}

?>