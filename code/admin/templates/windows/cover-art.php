<?php if (!defined('PARENT')) { exit; }
@ini_set('memory_limit', '100M');
@set_time_limit(0);
$folders = mswFolderScanner(REL_PATH.COVER_ART_FOLDER);
$files   = mswFolderFileScanner(REL_PATH.COVER_ART_FOLDER,SUPPORTED_IMAGES);
?>
<div class="iboxWindow">

<h2 class="coverH2">
 <select onchange="mm_reloadCoverArt(this.value)">
 <option value="<?php echo REL_PATH.COVER_ART_FOLDER; ?>"><?php echo COVER_ART_FOLDER; ?></option>
 <?php
 if (!empty($folders)) {
 foreach ($folders AS $f) {
 ?>
 <option value="<?php echo $f; ?>"><?php echo substr($f,strlen(REL_PATH)); ?></option>
 <?php
 }
 }
 ?>
 </select>
 <span>
 <?php echo $adlang4[39]; ?>
 </span>
</h2>

<div id="winCoverArt">
<?php
if (!empty($files)) {
foreach ($files AS $img) {
?>
<img onclick="mm_selectCoverArt('<?php echo mswJSFilters(substr($img,strlen(REL_PATH.COVER_ART_FOLDER)+1)); ?>','<?php echo REL_PATH.COVER_ART_FOLDER.'/'; ?>');iBox.hide()" src="<?php echo mswSafeDisplay($img); ?>" alt="<?php echo mswSafeDisplay(basename($img)); ?>" title="<?php echo mswSafeDisplay(basename($img)); ?>">
<?php
}
?>
<br style="clear:both">
<?php
} else {
?>
<p><i class="fa fa-warning fa-fw"></i> <?php echo $adlang4[23]; ?></p>
<?php
}
?>
</div>

</div>