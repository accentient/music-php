<?php if (!defined('INC')) { die('You do not have permission to view this file!!'); } ?>
<div id="wrapper">

 <div id="left">

   <span class="head"><?php echo SCRIPT_NAME; ?> Installer</span>

   <h1 style="margin-bottom:30px">COMPLETED</h1>

   <p class="progress"><span style="width:<?php echo $perc_width; ?>%">&nbsp;</span></p>

   <p class="percent"><?php echo $progress; ?>%</p>

 </div>

 <div id="right">

   <p style="line-height:20px">All done! The installer ran with no issues and <?php echo SCRIPT_NAME; ?> is ready to go.<br><br>

   Lets look at fixing a major security issue first:<br><br>

   <b style="display:block;color:red;padding:10px 0 10px 0;font-size:14px;text-transform:uppercase;border-top:1px dashed red;border-bottom:1px dashed red">DELETE or rename the 'install' folder NOW!!</b><br>

   Once you have completed this task, here are a few things worth considering:<br><br>

   <b>1</b>: Read the rest of the <a href="../docs/install.html" onclick="window.open(this);return false">installation</a> instructions carefully.<br><br>
   <b>2</b>: The 'Documentation' link in your admin area loads the corresponding help page in the docs.<br><br>
   <b>5</b>: If you have issues, see the '<a href="../docs/support.html" onclick="window.open(this);return false">Support Options</a>'. As with any new software, please be patient with it.<br><br>
   <b>6</b>: If available, check the <a href="http://www.maianmusic.com/video-tutorials.html" onclick="window.open(this);return false">video tutorials</a> on the <a href="http://www.maianmusic.com" onclick="window.open(this);return false"><?php echo SCRIPT_NAME; ?></a> website for assistance.<br><br>
   <b>7</b>: Check out the <a href="../docs/language.html" onclick="window.open(this);return false">template/language</a> section in the docs for templates/language help.<br><br>
   <b>8</b>: If you like this software, a one time payment for the <a href="http://www.maianmusic.com/purchase.html" onclick="window.open(this);return false">commercial version</a> offers many benefits.<br><br>

   I really hope you like <?php echo SCRIPT_NAME; ?> and thank you very much for trying it out.

   </p>

   <p class="nav" style="text-align:center">
    <input onclick="window.location='../index.php'" class="button_view_admin" type="button" value="View Store &raquo;" title="View Store" style="margin-right:50px">
    <input onclick="window.location='../admin/index.php'" class="button_view_admin" type="button" value="View Administration &raquo;" title="View Administration">
   </p>

 </div>

 <br class="clear">

</div>
