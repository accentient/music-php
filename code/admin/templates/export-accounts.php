<?php if (!defined('PARENT')) { exit; } 
define('CALBOX','from|to');
include(PATH.'templates/date-picker.php');

?>
      <div id="wrapper">

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <?php
			// Load top bar and navigation menu..
            include(PATH.'templates/header-top-bar.php');
			include(PATH.'templates/header-nav-bar.php');
			?>
		</nav>

        <form method="post" action="?p=export-accounts">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo substr($titleBar,0,-2); ?></h1>
                </div>
            </div>
			<?php
			if (isset($_GET['nodata'])) {
			?>
			<div class="row">
			 <div class="col-lg-12">
              <div class="panel panel-default nodata">
			   <i class="fa fa-warning fa-fw"></i> <?php echo $gblang[42]; ?>
			  </div>
			 </div> 
			</div>
			<?php
			}
			?>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="one">
								 <div class="form-group">
								  <label><?php echo $adlang6[19]; ?></label>
								  <input type="text" name="from" id="from" value="" class="form-control">
                                 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[20]; ?></label>
								  <input type="text" name="to" id="to" value="" class="form-control">
                                 </div>
								</div>
							</div>
						</div>	
						<div class="panel-footer">
						    <input type="hidden" name="process" value="yes">
                            <button type="submit" class="btn btn-primary"><?php echo mswSafeDisplay($adlang6[1]); ?></button>
                            <button type="button" class="btn btn-link" onclick="mm_windowLoc('accounts')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
					    </div>
					</div>
				</div>
			</div>	
			<?php
			include(PATH.'templates/cp.php');
			?>
        </div>
		</form>

    </div>
