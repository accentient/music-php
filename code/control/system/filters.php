<?php

if (!defined('PARENT') || !class_exists('storeBuilder')) {
  mswEcode($gblang[4], '403');
}

// Filters..
$listFilters = array(
  'name_asc' => $pbglobalfront[1],
  'name_desc' => $pbglobalfront[2],
  'pmp3_high' => $pbglobalfront[3],
  'pmp3_low' => $pbglobalfront[4],
  'cd_high' => $pbglobalfront[5],
  'cd_low' => $pbglobalfront[6],
  'rel_asc' => $pbglobalfront[7],
  'rel_desc' => $pbglobalfront[8],
  'date_asc' => $pbglobalfront[9],
  'date_desc' => $pbglobalfront[10]
);

// Send to store builder class..
$BUILDER->filters = $listFilters;

?>