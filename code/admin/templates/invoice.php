<?php if (!defined('PARENT')) { exit; }
if (!isset($SALE->id)) {
  die('<p style="padding:30px">Sale not found, invalid ID</p>');
}
$QA  = $DB->db_query("SELECT * FROM `".DB_PREFIX."accounts` WHERE `id` = '{$SALE->account}'");
$ACC = $DB->db_object($QA);
if (!isset($ACC->id)) {
  die('<p style="padding:30px">Account not found, invalid account ID for sale</p>');
}
$QST  = $DB->db_query("SELECT ROUND(SUM(`cost`),2) AS `saleTotal` FROM `".DB_PREFIX."sales_items` WHERE `sale` = '{$SALE->id}'");
$TCS  = $DB->db_object($QST);
if ($SALE->coupon) {
  $cp = mswCleanData(unserialize($SALE->coupon));
  if (isset($cp[0],$cp[1]) && $cp[1]>0) {
    $discount   = $cp[1];
    $couponCode = $cp[0];
  }
  $tot  = ($TCS->saleTotal>0 ? mswFormatPrice($TCS->saleTotal - $discount) : '0.00');
} else {
  $tot  = ($TCS->saleTotal>0 ? $TCS->saleTotal : '0.00');
}
$tots  = ($SALE->shipping>0 ? $SALE->shipping : '0.00');
$totv  = ($SALE->tax>0 ? $SALE->tax : '0.00');
$totv2 = ($SALE->tax2>0 ? $SALE->tax2 : '0.00');
$QGW   = $DB->db_query("SELECT `display` FROM `".DB_PREFIX."gateways` WHERE `id` = '{$SALE->gateway}'");
$PGW   = $DB->db_object($QGW);
$taxT  = mswFormatPrice($totv+$totv2);
?>

<div class="container-fluid invoice" style="margin-top:20px">

  <div class="panel panel-default">
   <div class="panel-heading" style="height:200px">
    <p style="float:right">
	 <b><?php
	 echo $adlang9[73].'</b>:<br><br>'.mswSafeDisplay($ACC->name);
	 ?>
	 <br>
	 <?php
	 echo mswNL2BR(mswSafeDisplay($SALE->shippingAddr));
	 ?>
	</p>
	<span class="inv-head"><?php echo $adlang9[53]; ?></span>
	<i class="fa fa-file-text-o fa-fw"></i> <?php echo $adlang9[71].': '.mswSaleInvoiceNumber($SALE->invoice); ?><br>
	<i class="fa fa-calendar fa-fw"></i> <?php echo $adlang9[27].': '.$DT->dateTimeDisplay($SALE->ts,$SETTINGS->dateformat); ?><br>
	<i class="fa fa-credit-card fa-fw"></i> <?php echo $adlang9[16].': '.($SALE->gateway == 0 ? $adlang9[86] : (isset($PGW->display) ? $PGW->display : 'N/A')); ?>

   </div>
   <div class="panel-body">
    <div class="table-responsive">
	 <table class="table table-striped">
	 <thead>
	  <tr>
	   <th>&nbsp;</th>
	   <th><?php echo $adlang9[74]; ?></th>
	   <th><?php echo $adlang9[75]; ?></th>
	   <th></th>
	  </tr>
	 </thead>
	 <tbody>
	 <?php
	 $typec = array(0,0);
	 $QP    = $DB->db_query("SELECT * FROM `".DB_PREFIX."sales_items`
	          WHERE `sale` = '{$SALE->id}'
	          ORDER BY `id`
	          ");
	 while ($I = $DB->db_object($QP)) {
	 $name = '';
	 switch ($I->type) {
	  case 'collection':
	  $Q_C    = $DB->db_query("SELECT `id`,`name`,`coverart`,`cost`,`costcd` FROM `".DB_PREFIX."collections` WHERE `id` = '{$I->item}'");
	  $CTION  = $DB->db_object($Q_C);
	  if (isset($CTION->name)) {
	   $col    = mswSafeDisplay($CTION->name);
	   $cost   = $I->cost;
	   $track  = '*';
	   $kind   = ($I->physical=='no' ? $adlang9[64] : $adlang9[65]);
	   if ($I->physical=='yes') {
	     ++$typec[0];
	   } else {
       ++$typec[1];
     }
	  }
	  break;
	  case 'track':
	  $Q_T    = $DB->db_query("SELECT `collection`,`title`,`cost` FROM `".DB_PREFIX."music` WHERE `id` = '{$I->item}'");
	  $CTK    = $DB->db_object($Q_T);
	  if (isset($CTK->title)) {
	   $Q_C    = $DB->db_query("SELECT `name`,`coverart` FROM `".DB_PREFIX."collections` WHERE `id` = '{$CTK->collection}'");
	   $CTION  = $DB->db_object($Q_C);
	   if (isset($CTION->name)) {
	    $col    = mswSafeDisplay($CTION->name);
		  $cost   = $I->cost;
		  $track  = mswSafeDisplay($CTK->title);
		  $kind   = $adlang9[66];
      ++$typec[1];
	   }
	  }
	  break;
	 }
	 if ($col) {
	 ?>
	 <tr>
	  <td style="width:70px"><img class="clipart" src="<?php echo mswCoverArtLoader($CTION->coverart,$SETTINGS->httppath); ?>" title="<?php echo $col; ?>" alt="<?php echo $col; ?>"></td>
	  <td><?php echo $col; ?><br><br><span class="small"><?php echo $kind; ?></span></td>
	  <td><?php echo $track; ?></td>
	  <td style="text-align:right"><?php echo mswCurrencyFormat($cost,$SETTINGS->curdisplay); ?></td>
	 </tr>
	 <?php
	 }
	 }
   ?>
   <tr>
	  <td style="width:70px">&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><i class="fa fa-money fa-fw"></i> <?php echo $adlang10[12]; ?></td>
	  <td style="text-align:right"><?php echo mswCurrencyFormat(($TCS->saleTotal>0 ? $TCS->saleTotal : '0.00'),$SETTINGS->curdisplay); ?></td>
	 </tr>
   <?php
   if (isset($discount)) {
   ?>
	 <tr>
	  <td style="width:70px">&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><i class="fa fa-gift fa-fw"></i> <?php echo $adlang10[10]; ?><span class="coupon_code"><?php echo $adlang10[11] . ' ' . $couponCode; ?></span></td>
	  <td style="text-align:right">-<?php echo mswCurrencyFormat($discount,$SETTINGS->curdisplay); ?></td>
	 </tr>
	 <?php
	 }
	 if ($typec[0]>0) {
	 ?>
	 <tr>
	  <td style="width:70px">&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><i class="fa fa-truck fa-fw"></i> <?php echo $adlang9[78]; ?></td>
	  <td style="text-align:right"><?php echo mswCurrencyFormat($tots,$SETTINGS->curdisplay); ?></td>
	 </tr>
	 <?php
	 }
	 if ($totv>0) {
	 ?>
	 <tr>
	  <td style="width:70px">&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><i class="fa fa-plus-square fa-fw"></i> <?php echo str_replace(array('{count}','{tax}'),array($typec[0],$SALE->taxRate),$adlang10[($typec[0]>0 ? 14 : 15)]); ?></td>
	  <td style="text-align:right"><?php echo mswCurrencyFormat($totv,$SETTINGS->curdisplay); ?></td>
	 </tr>
	 <?php
	 }
   if ($totv2>0) {
	 ?>
	 <tr>
	  <td style="width:70px">&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><i class="fa fa-plus-square fa-fw"></i> <?php echo str_replace(array('{count}','{tax}'),array($typec[1],$SALE->taxRate2),$adlang10[13]); ?></td>
	  <td style="text-align:right"><?php echo mswCurrencyFormat($totv2,$SETTINGS->curdisplay); ?></td>
	 </tr>
	 <?php
	 }
	 ?>
	 </tbody>
	 </table>
	</div>
   </div>
   <div class="panel-footer">
    <p style="float:right"><?php echo $adlang9[72].': <b>'.mswCurrencyFormat(($tot+$tots+$taxT),$SETTINGS->curdisplay); ?></b></p>
	<?php echo $adlang9[26].': '.$SALE->ip; ?>
   </div>
  </div>

  <?php
  include(PATH.'templates/cp.php');
  ?>

</div>
