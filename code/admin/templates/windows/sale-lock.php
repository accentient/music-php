<?php if (!defined('PARENT')) { exit; } 
if (isset($_GET['st']) && in_array($_GET['st'],array('lock','unlock'))) {
$ID  = (int)$_GET['lock'];
$Q   = $DB->db_query("SELECT `id`,`invoice`,`lockreason` FROM `".DB_PREFIX."sales` WHERE `id` = '{$ID}'");
$SL  = $DB->db_object($Q);
if (isset($SL->id)) {
$SLS->updateLock($ID);
?>
<div id="iboxWindow">

<h2 class="cliph2<?php echo ($_GET['st']=='lock' ? ' mm_red' : ' mm_green'); ?>"><i class="fa <?php echo ($_GET['st']=='lock' ? 'fa-lock' : 'fa-unlock-alt'); ?> fa-fw"></i> <?php echo ($_GET['st']=='lock' ? $adlang9[88] : $adlang9[89]).mswSaleInvoiceNumber($SL->invoice); ?></h2>

<div style="padding-top:30px;border-top:1px dashed #ccc">
 <label><?php echo $adlang9[91]; ?></label>
 <textarea name="lockreason" rows="4" cols="40" style="height:200px" class="form-control"><?php echo mswSafeDisplay($SL->lockreason); ?></textarea><br>
 <button type="button" class="btn btn-primary" onclick="mm_updateLockReason('<?php echo $ID; ?>')"><i class="fa fa-check fa-fw"></i> <?php echo mswSafeDisplay($adlang9[90]); ?></button>
</div>

</div>
<?php
}
}
?>