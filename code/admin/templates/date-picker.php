<?php if (!defined('CALBOX')) { exit; } ?>
<script>
//<![CDATA[
<?php
// Single box or multiple..
if (strpos(CALBOX,'|')===false) {
?>
jQuery(function() {
  jQuery('#<?php echo CALBOX; ?>').datepicker({
    changeMonth: true,
    changeYear: true,
    monthNamesShort: <?php echo trim($jslang[0]); ?>,
    dayNamesMin: <?php echo trim($jslang[1]); ?>,
    firstDay: <?php echo ($SETTINGS->weekstart=='sun' ? '0' : '1'); ?>,
    dateFormat: '<?php echo $DT->jsFormat(); ?>',
    isRTL: <?php echo $jslang[2]; ?>
  });
});  
<?php
} else {
$calsplit = explode('|',CALBOX);
foreach ($calsplit AS $cal) {
?>
jQuery(function() {
  jQuery('#<?php echo $cal; ?>').datepicker({
    changeMonth: true,
    changeYear: true,
    monthNamesShort: <?php echo trim($jslang[0]); ?>,
    dayNamesMin: <?php echo trim($jslang[1]); ?>,
    firstDay: <?php echo ($SETTINGS->weekstart=='sun' ? '0' : '1'); ?>,
    dateFormat: '<?php echo $DT->jsFormat(); ?>',
    isRTL: <?php echo $jslang[2]; ?>
  });
}); 
<?php
}
}
?>
//]]>
</script>
