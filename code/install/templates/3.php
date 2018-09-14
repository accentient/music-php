<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); }
$e = 0;
?>
<div id="wrapper">

 <div id="left">

   <span class="head"><?php echo SCRIPT_NAME; ?> Installer</span>

   <h1>STEP<span>&lt; <?php echo $cmd; ?> &gt;</span></h1>

   <p class="progress"><span style="width:<?php echo $perc_width; ?>%">&nbsp;</span></p>

   <p class="percent"><?php echo $progress; ?>%</p>

 </div>

 <div id="right">

   <p style="line-height:20px">Looking good so far. <br><br>

   Now lets check for the required server modules to run <?php echo SCRIPT_NAME; ?>.

   <span class="head">Required Modules/Functions/Extensions</span>
   <?php
   if (!function_exists('mysqli_connect')) {
     ++$e;
   }
   if (!function_exists('json_encode')) {
     ++$e;
   }
   if (!function_exists('mcrypt_decrypt')) {
     ++$e;
   }
   if (!function_exists('curl_init')) {
     ++$e;
   }
   if (!class_exists('ZipArchive')) {
     ++$e;
   }
   if (!function_exists('simplexml_load_string')) {
     ++$e;
   }
   ?>
   <span class="info"><span class="right"><img src="templates/images/<?php echo (function_exists('mysqli_connect') ? 'ok' : 'error'); ?>.png" alt=""></span><b>MySQL Improved Extension</b> <span class="italic">(For Database actions)</span></span>
   <span class="info"><span class="right"><img src="templates/images/<?php echo (function_exists('json_encode') ? 'ok' : 'error'); ?>.png" alt=""></span><b>JSON</b> <span class="italic">(For Ajax responses)</span></span>
   <span class="info"><span class="right"><img src="templates/images/<?php echo (function_exists('mcrypt_decrypt') ? 'ok' : 'error'); ?>.png" alt=""></span><b>MCRYPT</b> <span class="italic">(For encryption routines / some payment gateways)</span></span>
   <span class="info"><span class="right"><img src="templates/images/<?php echo (function_exists('curl_init') ? 'ok' : 'error'); ?>.png" alt=""></span><b>CURL</b> <span class="italic">(For remote operations)</span></span>
   <span class="info"><span class="right"><img src="templates/images/<?php echo (class_exists('ZipArchive') ? 'ok' : 'error'); ?>.png" alt=""></span><b>ZIPARCHIVE</b> <span class="italic">(For collection zip downloads)</span></span>
   <span class="info"><span class="right"><img src="templates/images/<?php echo (function_exists('simplexml_load_string') ? 'ok' : 'error'); ?>.png" alt=""></span><b>SIMPLE XML</b> <span class="italic">(For some payment gateways)</span></span>
   </p>

   <?php
   if ($e==0) {
   ?>
   <p class="nav">
    <span><input onclick="window.location='?s=2'" class="button_prev" type="button" value="&laquo; Prev" title="Previous"></span>
    <input onclick="window.location='?s=4'" class="button_next" type="button" value="Next &raquo;" title="Next">
   </p>
   <?php
   } else {
   ?>
   <p class="warning_msg">Please fix the <b><?php echo ($e==1 ? '1 error' : $e.' errors'); ?></b> above and then refresh page.</p>
   <?php
   }
   ?>

 </div>

 <br class="clear">

</div>
