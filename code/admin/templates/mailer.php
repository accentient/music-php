<?php if (!defined('PARENT')) { exit; } ?>
      <div id="wrapper">

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <?php
			// Load top bar and navigation menu..
            include(PATH.'templates/header-top-bar.php');
			include(PATH.'templates/header-nav-bar.php');
			?>
		</nav>

        <form method="post" action="#">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $adlang13[0]; ?></h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#one" data-toggle="tab"><?php echo $adlang12[5]; ?></a></li>
                                <li><a href="#two" data-toggle="tab"><?php echo $adlang13[1]; ?></a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="one">
								 <div class="form-group">
								  <label><?php echo $adlang13[4]; ?></label>
								  <input type="text" name="subject" value="" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang13[2]; ?></label>
								  <input type="text" name="from" value="<?php echo mswSafeDisplay($SETTINGS->smtp_from); ?>" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang13[3]; ?></label>
								  <input type="text" name="email" value="<?php echo mswSafeDisplay($SETTINGS->smtp_email); ?>" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang13[5]; ?></label>
								  <textarea name="msg" rows="5" cols="40" class="form-control"></textarea>
								  <span class="help-block" style="font-size:12px"><?php echo $adlang13[6]; ?></span>
								 </div>
								</div>
								<div class="tab-pane fade" id="two">
								 <div class="form-group" style="max-height:400px;overflow:auto">
								  <?php
								  $Q_A  = $DB->db_query("SELECT `name`,`email` FROM `".DB_PREFIX."accounts` WHERE `enabled` = 'yes' ORDER BY `name`");
								  while ($A = $DB->db_object($Q_A)) {
								  ?>
								  <div class="checkbox">
								   <label><input type="checkbox" name="acc[]" value="<?php echo $A->name; ?>###<?php echo $A->email; ?>" checked="checked"><?php echo mswSafeDisplay($A->name); ?> (<?php echo mswSafeDisplay($A->email); ?>)</label>
								  </div>
								  <?php
								  }
								  ?>
								 </div>
								</div>
							</div>
						</div>	
						<div class="panel-footer">
                            <button type="button" class="btn btn-primary" onclick="mm_processor('mailer')"><?php echo mswSafeDisplay($adlang13[8]); ?></button>
                            <button type="button" class="btn btn-link" onclick="mm_windowLoc('accounts')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
							<span class="actionMsg"></span>
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
