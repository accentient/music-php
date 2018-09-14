<?php if (!defined('PARENT')) { exit; }
$gset  = ($SETTINGS->statistics ? unserialize($SETTINGS->statistics) : array());
?>
<div id="iboxWindow">

<h2 class="cliph2"><?php echo $adlang19[10]; ?></h2>

<form method="post" action="#">
<div style="padding-top:30px;border-top:1px solid #ccc" id="graphSetArea">
 <label><?php echo $adlang19[12]; ?></label>
 <input name="graph[years]" class="form-control yearbox" value="<?php echo (isset($gset['years']) ? mswSafeDisplay($gset['years']) : date('Y').','.date('Y',strtotime('-1 year'))); ?>"><br>
 <label><?php echo $adlang19[13]; ?></label>
 <select name="graph[best]" class="form-control">
 <?php
 foreach ($adlang19[7] AS $v) {
 ?>
 <option value="<?php echo $v; ?>"<?php echo (isset($gset['best']) && $gset['best']==$v ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
 <?php
 }
 ?>
 </select><br>
 <label><?php echo $adlang19[16]; ?></label>
 <select name="graph[month]" class="form-control">
 <?php
 for ($i=0; $i<2; $i++) {
   $m = date('m',strtotime('-'.$i.' month'.($i>1 || $i==0 ? 's' : '')));
   $y = date('Y',strtotime('-'.$i.' month'.($i>1 || $i==0 ? 's' : '')));
   $d = (date('n',strtotime('-'.$i.' month'.($i>1 || $i==0 ? 's' : '')))-1);
   switch($i) {
     case '0':
       $t = $adlang19[14];
       $v = 'this';
       break;
     case '1':
       $t = $adlang19[15];
       $v = 'last';
       break;
     default:
       $t = $gbdates[0][$d].' - '.$y;
       break;
   }
   ?>
   <option value="<?php echo $v; ?>"<?php echo (isset($gset['month']) && $gset['month']==$v ? ' selected="selected"' : ''); ?>><?php echo $t; ?></option>
   <?php
 }
 ?>
 </select><br>
 <label><?php echo $adlang19[17]; ?></label>
 <input name="graph[legacy]" class="form-control yearbox" value="<?php echo (isset($gset['legacy']) ? (int) $gset['legacy'] : 12); ?>" maxlength="2"><br>
 <button type="button" class="btn btn-primary" onclick="mm_graphStats()"><i class="fa fa-check fa-fw"></i> <?php echo mswSafeDisplay($adlang19[11]); ?></button>
</div>
</form>

</div>