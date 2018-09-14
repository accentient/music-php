<?php

//-------------------------------------------------------------
// SYSTEM CONSTANTS
// DO NOT Change
//-------------------------------------------------------------

if (!defined('PARENT') || !isset($SETTINGS->httppath)) {
  mswEcode($gblang[4],'403');
}

define('BASE_HREF', $SETTINGS->httppath);
define('CART_COUNT', $CART->count());
define('CART_TOTAL', $CART->total());
define('LOGGED_IN', (is_array($loggedInUser) && isset($loggedInUser['id']) ? 'yes' : 'no'));
define('PAGE_PARAM', $cmd);
define('CURR_PAGE', $page);
define('PAGE_LIMIT', $limit);

?>