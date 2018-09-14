<?php if (!defined('PARENT')) { exit; }
$history   = (int)$_GET['history'];
$saleItem  = (int)$_GET['saleItem'];
if ($history==0 || $saleItem==0) {
  die('<p style="padding:30px">Item not found, invalid ID(s)</p>');
}
$Q  = $DB->db_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".DB_PREFIX."sales_click`
      WHERE `sale`   = '{$history}'
      AND `trackcol` = '{$saleItem}'
      ORDER BY `ts` DESC
      ");
$r  = $DB->db_foundrows($Q);
// Call the IP class..
include(REL_PATH . 'control/classes/class.ip.php');
$IPGEO           = new geoIP();
$IPGEO->settings = $SETTINGS;
?>
<div id="iboxWindow" class="history">

 <div class="row" style="width:102%;padding:0">
  <div class="col-lg-12">
   <h1 class="page-header" style="padding-top:10px;margin-top:10px">
    <?php
    if ($r>0) {
    ?>
    <span style="float:right" id="his_buttons">
      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('new-sale&amp;dhistory=<?php echo $history; ?>&amp;saleItem=<?php echo $saleItem; ?>')" title="<?php echo mswSafeDisplay($gblang[25]); ?>"><i class="fa fa-save fa-fw"></i></button>
      <button type="button" class="btn btn-warning btn-sm" onclick="mm_confHisDelete('<?php echo str_replace("'","\'",$jslang[21]); ?>','<?php echo $saleItem; ?>','<?php echo $history; ?>')" title="<?php echo mswSafeDisplay($adlang9[44]); ?>"><i class="fa fa-times fa-fw"></i></button>
    </span>
    <?php }
	  echo $adlang9[38]; ?> (<span class="his_counter"><?php echo $r; ?></span>)
   </h1>
  </div>
 </div>

 <div class="row" style="width:102%;padding:0">
   <div class="col-lg-12">
     <div class="panel panel-default">
       <div class="panel-body">
	     <div class="table-responsive">
		   <table class="table table-striped table-hover">
		     <tbody>
			   <?php
         if ($r>0) {
			   while ($H = $DB->db_object($Q)) {
         $ipData = array(
           'iso' => $H->iso,
           'conly' => 'no',
           'path' => 'templates/images/flags/',
           'ip' => $H->ip,
           'country' => $H->country,
           'unknown' => $gblang[19],
           'showip' => 'yes'
         );
			   ?>
			   <tr id="his_<?php echo $H->id; ?>">
			   <td><?php echo mswNL2BR(mswSafeDisplay($H->action)); ?>
         <span class="history_info"><i class="fa fa-calendar fa-fw"></i> <?php echo $DT->dateTimeDisplay($H->ts,$SETTINGS->dateformat); ?> @ <?php echo $DT->dateTimeDisplay($H->ts,$SETTINGS->timeformat); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $IPGEO->flag($ipData); ?></span>
         </td>
				 <td style="width:10%;text-align:right"><a href="#" onclick="mm_removeHistory('<?php echo $H->id; ?>','<?php echo $history; ?>','yes');return false" title="<?php echo mswSafeDisplay($gblang[26]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a></td>
			   </tr>
			   <?php
			   }
			   } else {
			   ?>
			   <td><?php echo $adlang9[42]; ?></td>
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