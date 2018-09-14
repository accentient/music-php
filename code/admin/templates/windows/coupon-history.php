<?php if (!defined('PARENT') || !isset($_GET['code'])) { exit; }
$code  = $_GET['code'];
$Q  = $DB->db_query("SELECT SQL_CALC_FOUND_ROWS *,
      (SELECT ROUND(SUM(`cost`),2) FROM `".DB_PREFIX."sales_items` WHERE `sale` = `".DB_PREFIX."sales`.`id`) AS `saleTotal`,
      (SELECT IF (SUBSTRING_INDEX(SUBSTRING_INDEX(`coupon`,';',4),':',-1) IS NULL,
       '0.00',
       REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(`coupon`,';',4),':',-1),'\"','')
       )
      ) AS `couponValue`,
      `".DB_PREFIX."sales`.`id` AS `saleID`,
      `".DB_PREFIX."sales`.`shipping` AS `saleShipping`,
      `".DB_PREFIX."sales`.`ts` AS `saleTS`
      FROM `".DB_PREFIX."sales`
      LEFT JOIN `".DB_PREFIX."accounts`
      ON  `".DB_PREFIX."accounts`.`id` = `".DB_PREFIX."sales`.`account`
      WHERE `".DB_PREFIX."sales`.`enabled` = 'yes'
      AND REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(`coupon`,';',2),':',-1),'\"','') = '".mswSafeString($code,$DB)."'
      ORDER BY `".DB_PREFIX."sales`.`ts` DESC
      ");
$r  = $DB->db_foundrows($Q);
?>
<div id="iboxWindow" class="history">

 <div class="row" style="width:102%;padding:0">
  <div class="col-lg-12">
   <h1 class="page-header" style="padding-top:10px;margin-top:10px">
   <span style="float:right"><?php echo mswSafeDisplay($code); ?></span>
   <?php
	 echo $adlang16[18];
   ?> (<span class="his_counter"><?php echo $r; ?></span>)
   </h1>
  </div>
 </div>

 <div class="row" style="width:102%;padding:0">
   <div class="col-lg-12">
     <div class="panel panel-default">
       <div class="panel-body">
	     <div class="table-responsive">
		   <table class="table table-striped table-hover">
		     <thead>
         <tr>
         <th><?php echo $adlang9[71]; ?></th>
         <th><?php echo $adlang9[21]; ?></th>
         <th><?php echo $adlang9[13]; ?></th>
         <th><?php echo $adlang9[25]; ?></th>
         <th>&nbsp;</th>
         </tr>
         </thead>
         <tbody>
			   <?php
         if ($r>0) {
			   while ($S = $DB->db_object($Q)) {
			   $cpn   = ($S->couponValue > 0 ? $S->couponValue : '0.00');
         $tot   = ($S->saleTotal>0 ? $S->saleTotal : '0.00');
         $tot   = mswFormatPrice($tot-$cpn);
         $tots  = ($S->saleShipping>0 ? $S->saleShipping : '0.00');
         $totv  = ($S->tax>0 ? $S->tax : '0.00');
         $totv2 = ($S->tax2>0 ? $S->tax2 : '0.00');
         $taxT  = mswFormatPrice($totv+$totv2);
         ?>
			   <tr>
			   <td><a href="?p=invoice&amp;id=<?php echo $S->saleID; ?>" title="<?php echo mswSafeDisplay($adlang9[20]); ?>" onclick="window.open(this.href,'_self')"><?php echo mswSaleInvoiceNumber($S->invoice); ?></a></td>
         <td><?php echo mswSafeDisplay($S->name); ?></td>
         <td><?php echo $DT->dateTimeDisplay($S->saleTS,$SETTINGS->dateformat); ?></td>
         <td><?php echo mswCurrencyFormat(($tot+$tots+$taxT),$SETTINGS->curdisplay); ?></td>
         <td><a href="?p=new-sale&amp;edit=<?php echo $S->saleID; ?>&amp;st=<?php echo mswSafeDisplay(urlencode($S->status)); ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a></td>
         </tr>
			   <?php
			   }
			   } else {
			   ?>
			   <td><?php echo $adlang16[19]; ?></td>
			   <?php
			   }
			   ?>
			 </tbody>
		   </table>
		 </div>
	   </div>
	 </div>
   </div>
  </div>

</div>