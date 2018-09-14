<?php

//------------------------------------------------
// E.U (EUROPEAN UNION) UPDATE
// Update Tax and EU flag for EU countries.
//------------------------------------------------

$taxEU = array(
 'BE' => '21',
 'BG' => '20',
 'CZ' => '21',
 'DK' => '25',
 'DE' => '19',
 'EE' => '20',
 'GR' => '23',
 'ES' => '21',
 'FR' => '20',
 'HR' => '25',
 'IE' => '23',
 'IT' => '22',
 'CY' => '19',
 'LV' => '21',
 'LT' => '21',
 'LU' => '17',
 'HU' => '27',
 'MT' => '18',
 'NL' => '21',
 'AT' => '20',
 'PL' => '23',
 'PT' => '23',
 'RO' => '20',
 'SI' => '22',
 'SK' => '20',
 'FI' => '24',
 'SE' => '25',
 'GB' => '20'
);
foreach ($taxEU AS $iso2 => $tax) {
  $query = $DB->db_query("UPDATE `" . DB_PREFIX . "countries` SET `tax2` = '{$tax}', `eu` = 'yes' WHERE `iso2` = '{$iso2}'", true);
  if ($query==='err') {
    $dataE[]  = 'N/A';
    $ERR      = $DB->db_error(true);
    ins_logDBError('European Union',$ERR[1],$ERR[0],__LINE__,__FILE__,'Update');
  }
}

?>