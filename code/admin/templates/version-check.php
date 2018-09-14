<?php if (!defined('PARENT')) { exit; } ?>
      <div id="wrapper">

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <?php
			// Load top bar and navigation menu..
            include(PATH.'templates/header-top-bar.php');
			include(PATH.'templates/header-nav-bar.php');
			?>
		</nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo substr($titleBar,0,-2); ?></h1>
                </div>
            </div>
			
			<div class="container-fluid">
                <div class="row versioncheck">
				   <div class="progress progress-striped active">
                     <div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width:50%"></div>
				   </div>	 
	            </div>
				<span class="help-block"><?php echo $gblang[38]; ?></span>
			</div>
			<p>&nbsp;</p>
            <?php
			include(PATH.'templates/cp.php');
			?>
        </div>

    </div>

	<script>
    //<![CDATA[
    jQuery(document).ready(function() {
      setTimeout(function() {
       mm_versionCheck();
      },3000);
    });
    //]]>
    </script>