<?php if (!defined('PARENT')) { exit; } 
$chop = explode('_',$_GET['changePrice']);
if (in_array($chop[2],array('cadl','cacd')) && (int)$chop[0]>0) {
?>
<div id="iboxWindow">

 <input type="text" name="new-price" value="<?php echo mswSafeDisplay($chop[1]); ?>" class="form-control">
 
 <div style="text-align:center;margin-top:10px">
  <button type="button" class="btn btn-primary" onclick="mm_changePriceSave('<?php echo (int)$chop[0]; ?>','<?php echo $chop[2]; ?>')"><?php echo $adlang2[19]; ?></button>
  <button type="button" class="btn btn-link" onclick="iBox.hide()"><?php echo mswSafeDisplay($gblang[13]); ?></button>
 </div>
 
</div>
<?php
}
?>