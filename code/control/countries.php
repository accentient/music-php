<?php

$countries = array();

$Q = $DB->db_query("SELECT `id`,`name` FROM `".DB_PREFIX."countries` WHERE `display` = 'yes' ORDER BY `name`");
while ($CN = $DB->db_object($Q)) {
  $countries[$CN->id] = $CN->name;
}

?>