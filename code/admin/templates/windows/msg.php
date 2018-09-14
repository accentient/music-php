<?php if (!defined('PARENT') || !isset($_GET['msg'])) { exit; } ?>
<div id="iboxWindow">

 <div style="text-align:center;padding-top:10px">

 <?php
 switch(substr($_GET['msg'],0,5)) {
   case 'reset':
   if (isset($_GET['option']) && $_GET['option']=='yes') {
     echo '<i class="fa fa-check fa-fw bigfont"></i><br><br>'.str_replace('{count}',(int)substr($_GET['msg'],5),$adlang9[56]);
   } else {
     echo '<i class="fa fa-check fa-fw bigfont"></i><br><br>'.str_replace('{count}',(int)substr($_GET['msg'],5),$adlang9[84]);
   }
   break;
   case 'pusho':
   echo '<i class="fa fa-check fa-fw bigfont"></i><br><br>'.$adlang2[126];
   break;
 }
 ?>

 </div>

</div>