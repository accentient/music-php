<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Code here is executed ONLY after a successful gateway callback.

  You can use any sale parameters via the $SALE_ORDER object. (See completed.php)

  You can also query the DB again based on the following:

  $SALE_ID   = ID
  $SALE_CODE = buyCode

  Emails can be sent with the $mmMail->sendMail object. View completed.php for example.

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('GW_ERR_LOG')) {
  mswEcode($gblang[4],'403');
}


?>