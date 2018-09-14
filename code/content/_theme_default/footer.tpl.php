<?php if (!defined('PARENT')) { exit; } ?>
           <div class="clearfix visible-sm"></div>

		 </div>
	</div>

	<footer>
    	<div class="navbar-inverse text-right copyright">
		    <div class="footWrap">
			 <p>
			 <?php
			 // Social Buttons..
			 echo $this->SOCIAL_BUTTONS;
			 ?>
			 </p>
        	 <?php
			 // Footer must NOT be changed unless a commercial licence has been purchased..
			 // http://www.maianmusic.com/purchase.html
			 echo $this->FOOTER;
			 ?>
			</div>
        </div>
    </footer>

    <a href="#" class="back-top text-center" onclick="jQuery('body,html').animate({scrollTop:0},500);return false" rel="nofollow">
    	<i class="fa fa-angle-double-up"></i>
    </a>

	<?php
	// Load JS
	?>
  <script src="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/js/bootstrap.js"></script>
  <script src="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/js/plugins/bootstrap.dialog.js"></script>
  <?php

	// Load plugins if required..DO NOT remove
	echo $this->PLUGIN_LOADER;

	// The Bootstrap modal box. Used to display messages. Should NOT be removed..
	// Text data populates programmatically on data operations..
	?>
	<div class="modal fade" id="mmModalBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog">
      <div class="modal-content">
       <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->TXT[0]; ?></span></button>
        <h4 class="modal-title" id="myModalLabel">&nbsp;</h4>
       </div>
       <div class="modal-body">
	     <?php
       // Basket items..
		   // html/modal-basket.tpl
		   // html/modal-basket-item.tpl
		   ?>
       &nbsp;
       </div>
       <div class="modal-footer">
	      <span class="hidden-sm hidden-xs">&nbsp;</span>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->TXT[0]; ?></button>
        <button type="button" class="btn btn-primary">&nbsp;</button>
       </div>
      </div>
     </div>
    </div>

	<?php
	// Various global ops..
	?>
	<script>
	//<![CDATA[
	jQuery(document).ready(function() {
     jQuery(window).scroll(function(){
      if (jQuery(this).scrollTop()>70) {
	    jQuery('.back-top').fadeIn();
	  } else {
	    jQuery('.back-top').fadeOut();
	  }
     });

    });
    //]]>
	</script>

 </body>
</html>