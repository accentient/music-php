<?php if (!defined('PARENT')) { exit; }
if (function_exists('curl_init') && version_compare(phpversion(), '5.3', '>')) {
?>
<div id="iboxWindow">

<h2 class="cliph2"><i class="fa fa-edit fa-fw"></i> <?php echo $adlang1[32]; ?></h2>

<div style="padding-top:30px;border-top:1px dashed #ccc">
 <label><?php echo $adlang1[33]; ?></label>
 <textarea name="tweet" rows="4" cols="40" style="height:150px" class="form-control"></textarea><br>
 <button type="button" class="btn btn-primary" onclick="mm_apiHandler('tweet')"><i class="fa fa-twitter fa-fw"></i> <?php echo mswSafeDisplay($adlang1[32]); ?></button>
</div>

</div>
<?php
} else {
?>
<div id="iboxWindow">

<h2 class="cliph2"><i class="fa fa-warning fa-fw"></i> ERROR</h2>

<div style="padding-top:30px;border-top:1px dashed #ccc">
 One or more requirements are not met for this function to work.<br><br>
 <?php
 echo 'PHP 5.3 or higher (Required): ' . (version_compare(phpversion(), '5.3', '>') ? '<i class="fa fa-check fa-fw"></i>' : '<i class="fa fa-times fa-fw"></i>').'<br>';
 echo 'CURL functions enabled (Required): ' . (function_exists('curl_init') ? '<i class="fa fa-check fa-fw"></i>' : '<i class="fa fa-times fa-fw"></i>').'<br>';
 echo 'Open SSL enabled (Optional, but recommended): ' . (function_exists('openssl_open') ? '<i class="fa fa-check fa-fw"></i>' : '<i class="fa fa-times fa-fw"></i>');
 ?>
</div>

</div>
<?php
}
?>