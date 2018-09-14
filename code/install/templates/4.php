<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<div id="wrapper">
 <script>
 //<![CDATA[
 function warningMSG() {
   var confirmSub = confirm('CONFIRM INSTALLATION\n\nPlease confirm you want to clean install this software?\n\nClick "OK" to proceed..');
   if (confirmSub) {
     return true;
   } else {
     return false;
   }
 }
 //]]>
 </script>
 <div id="left">

   <span class="head"><?php echo SCRIPT_NAME; ?> Installer</span>

   <h1>STEP<span>&lt; <?php echo $cmd; ?> &gt;</span></h1>

   <p class="progress"><span style="width:<?php echo $perc_width; ?>%">&nbsp;</span></p>

   <p class="percent"><?php echo $progress; ?>%</p>

 </div>

 <div id="right">

   <form method="post" action="?s=4" id="form" onsubmit="return warningMSG()">
   <?php
   if (DB_TYPE == 'mysqli') {
   ?>
   <p style="line-height:20px">Before the installer adds the database tables and information, please specify your MySQL database version, engine and preferred character set for MySQL operations. If you aren`t sure of this, leave the
   settings as they are.

   <span class="head">MySQL</span>

   <span class="info"><span class="right"><input type="radio" name="mysqli_version" value="MySQL4" <?php echo ((int)$mysqlVer<5 ? ' checked="checked"' : ''); ?>>MySQL4&nbsp;&nbsp;&nbsp;<input type="radio" name="mysqli_version" value="MySQL5" <?php echo ((int)$mysqlVer>=5 ? ' checked="checked"' : ''); ?>> MySQL5</span>Version:</span>
   <span class="info"><span class="right"><input type="radio" name="mysqli_engine" value="MyISAM" checked="checked">MyISAM&nbsp;&nbsp;&nbsp;<input type="radio" name="mysqli_engine" value="InnoDB"> InnoDB</span>Engine:</span>
   <span class="info" style="text-align:right">
    <span style="float:left">Character Set:</span>
    <select name="charset">
     <?php
     foreach ($cSets AS $set) {
     ?>
     <option value="<?php echo $set; ?>"<?php echo ($set==$defChar ? ' selected="selected"' : ''); ?>><?php echo $set; ?></option>
     <?php
     }
     ?>
    </select>
   </span>
   <span class="info"><span class="right"><input type="checkbox" name="styles" value="yes" checked="checked"></span>Install Example Music Styles:</span>

   </p>
   <?php
   } else {
   ?>
   <p>Before the installer adds the database tables and information, please specify your PostreSQL database version and preferred character set for PostgreSQL operations. If you aren`t sure of this, leave the
   settings as they are.

   <span class="head">PostreSQL</span>

   <span class="info"><span class="right"><input type="radio" name="pg_version" value="MySQL4" <?php echo ((int)$mysqlVer<5 ? ' checked="checked"' : ''); ?>>MySQL4&nbsp;&nbsp;&nbsp;<input type="radio" name="pg_version" value="MySQL5" <?php echo ((int)$mysqlVer>=5 ? ' checked="checked"' : ''); ?>> MySQL5</span>Version:</span>
   <span class="info" style="text-align:right">
    <span style="float:left">Character Set:</span>
    <select name="charset">
     <?php
     foreach ($cSets AS $set) {
     ?>
     <option value="<?php echo $set; ?>"<?php echo ($set==$defaultSet ? ' selected="selected"' : ''); ?>><?php echo $set; ?></option>
     <?php
     }
     ?>
    </select>
   </span>

   </p>
   <?php
   }
   ?>
   <p class="nav">
    <span><input onclick="window.location='?s=3'" class="button_prev" type="button" value="&laquo; Prev" title="Previous"></span>
    <input type="hidden" name="tables" value="yes">
    <input class="button_next_tables" type="submit" value="Install Tables &raquo;" title="Install Tables">
   </p>
   </form>

 </div>

 <br class="clear">

</div>
