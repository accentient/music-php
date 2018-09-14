<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<div id="wrapper">

 <div id="left">

   <span class="head"><?php echo SCRIPT_NAME; ?> Installer</span>

   <h1>STEP<span>&lt; <?php echo $cmd; ?> &gt;</span></h1>

   <p class="progress"><span style="width:<?php echo $perc_width; ?>%">&nbsp;</span></p>

   <p class="percent"><?php echo $progress; ?>%</p>

 </div>

 <div id="right">

   <p style="line-height:20px">Thank you for trying out <?php echo SCRIPT_NAME; ?>, I hope you like it.<br><br>
   This installation system will guide you through the install procedure.<br><br>
   To begin, please confirm your database connection information as set in the connection file:<br><br><b>control/connect.php</b>.

   <span class="head">Connection Details</span>

   <span class="info"><span class="right"><?php echo DB_HOST; ?></span>Database Host:</span>
   <span class="info"><span class="right"><?php echo DB_NAME; ?></span>Database Name:</span>
   <span class="info"><span class="right"><?php echo DB_USER; ?></span>Database User:</span>
   <span class="info"><span class="right"><?php echo DB_PASS; ?></span>Database Pass:</span>
   <span class="info"><span class="right"><?php echo DB_PREFIX; ?></span>Database Table Prefix:</span>
   <span class="info"><span class="right"><?php echo DB_CHAR_SET; ?></span>Encoding:</span>

   </p>

   <p class="nav">
    <span><input id="test" onclick="connectionTest()" class="button_con_test" type="button" value="Test Connection" title="Test Connection"></span>
    <input onclick="window.location='?s=2'" class="button_next" type="button" value="Next &raquo;" title="Next">
   </p>

 </div>

 <br class="clear">

</div>
