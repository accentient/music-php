<?php

class seo extends db {

  public $settings;
  public $expression = '`[^\w_-]`';
  public $curPage = 1;
  public $cmd;

  public function url($page = '', $params = array(), $single = 'no') {
    if ($page == '') {
      return '';
    }
    switch ($single) {
      case 'yes':
        $url = ($this->settings->rewrite == 'yes' ? seo::converter($page) : '?p=' . $page);
        break;
      default:
        $url = ($this->settings->rewrite == 'yes' ? seo::converter($page) : '?');
        if (!empty($params)) {
          $ar = $params[($this->settings->rewrite == 'yes' ? 'seo' : 'standard')];
          $ac = 0;
          foreach ($ar AS $par => $v) {
            ++$ac;
            if ($v) {
              if ($this->settings->rewrite == 'yes') {
                $url .= '/' . $v;
              } else {
                // Next gets populated by the pagination class, so clear it here..
                if ($par == 'next') {
                  $v = '';
                }
                if ($ac == 1) {
                  $url .= seo::converter($page) . '=' . $v;
                } else {
                  $url .= '&amp;' . $par . '=' . $v;
                }
              }
            }
          }
        }
    }
    return str_replace('//', '/', $url);
  }

  public function filter($page) {
    // Foreign character ascii conversions..
    // http://www.asciitable.com/
    $chars = array(
      chr(195) . chr(128) => 'A',
      chr(195) . chr(129) => 'A',
      chr(195) . chr(130) => 'A',
      chr(195) . chr(131) => 'A',
      chr(195) . chr(132) => 'A',
      chr(195) . chr(133) => 'A',
      chr(195) . chr(135) => 'C',
      chr(195) . chr(136) => 'E',
      chr(195) . chr(137) => 'E',
      chr(195) . chr(138) => 'E',
      chr(195) . chr(139) => 'E',
      chr(195) . chr(140) => 'I',
      chr(195) . chr(141) => 'I',
      chr(195) . chr(142) => 'I',
      chr(195) . chr(143) => 'I',
      chr(195) . chr(145) => 'N',
      chr(195) . chr(146) => 'O',
      chr(195) . chr(147) => 'O',
      chr(195) . chr(148) => 'O',
      chr(195) . chr(149) => 'O',
      chr(195) . chr(150) => 'O',
      chr(195) . chr(153) => 'U',
      chr(195) . chr(154) => 'U',
      chr(195) . chr(155) => 'U',
      chr(195) . chr(156) => 'U',
      chr(195) . chr(157) => 'Y',
      chr(195) . chr(159) => 's',
      chr(195) . chr(160) => 'a',
      chr(195) . chr(161) => 'a',
      chr(195) . chr(162) => 'a',
      chr(195) . chr(163) => 'a',
      chr(195) . chr(164) => 'a',
      chr(195) . chr(165) => 'a',
      chr(195) . chr(167) => 'c',
      chr(195) . chr(168) => 'e',
      chr(195) . chr(169) => 'e',
      chr(195) . chr(170) => 'e',
      chr(195) . chr(171) => 'e',
      chr(195) . chr(172) => 'i',
      chr(195) . chr(173) => 'i',
      chr(195) . chr(174) => 'i',
      chr(195) . chr(175) => 'i',
      chr(195) . chr(177) => 'n',
      chr(195) . chr(178) => 'o',
      chr(195) . chr(179) => 'o',
      chr(195) . chr(180) => 'o',
      chr(195) . chr(181) => 'o',
      chr(195) . chr(182) => 'o',
      chr(195) . chr(182) => 'o',
      chr(195) . chr(185) => 'u',
      chr(195) . chr(186) => 'u',
      chr(195) . chr(187) => 'u',
      chr(195) . chr(188) => 'u',
      chr(195) . chr(189) => 'y',
      chr(195) . chr(191) => 'y',
      chr(195) . chr(158) => 'S',
      chr(195) . chr(159) => 's',
      chr(195) . chr(166) => 'G',
      chr(195) . chr(167) => 'g',
      chr(195) . chr(152) => 'I',
      chr(195) . chr(141) => 'i',
      chr(195) . chr(154) => 'U',
      chr(195) . chr(129) => 'u',
      chr(195) . chr(153) => 'O',
      chr(195) . chr(148) => 'o',
      chr(195) . chr(128) => 'C',
      chr(195) . chr(135) => 'c'
    );

    // Convert foreign characters..
    $title = strtr($page, $chars);

    // Strip none alphabetic and none numeric chars..
    $page = strtolower(preg_replace($this->expression, '-', $page));
    $page = str_replace(array(
      '-----',
      '----',
      '---',
      '--'
    ), array(
      '-',
      '-',
      '-',
      '-'
    ), $page);
    return $page;
  }

  public function converter($p = '', $arr = false) {
    $c = array(
      'style' => 'style',
      'collection' => 'collection',
      'latest' => 'latest',
      'popular' => 'popular',
      'account' => 'account',
      'pg' => 'pg',
      'search' => 'search',
      'basket' => 'basket',
      'checkout' => 'checkout',
      'create' => 'create',
      'profile' => 'profile',
      'orders' => 'orders',
      'view-order' => 'view-order',
      'rss-home' => 'rss-home',
      'rss-latest' => 'rss-latest',
      'rss-popular' => 'rss-popular'
    );
    if ($arr) {
      return $c;
    }
    return (isset($c[$p]) ? $c[$p] : $p);
  }

  public function rewriteParam() {
    if (!isset($_GET['_mm_'])) {
      return 'home';
    }
    $chop = explode('/', $_GET['_mm_']);
    if (isset($chop[0])) {
      $arrF = array_flip(seo::converter('', true));
      if (isset($arrF[$chop[0]])) {
        return $arrF[$chop[0]];
      } else {
        return $chop[0];
      }
    } else {
      return '#';
    }
  }

  public function rewriteElements() {
    return (isset($_GET['_mm_']) ? explode('/', $_GET['_mm_']) : array());
  }

}

?>