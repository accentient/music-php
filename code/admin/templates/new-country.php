<?php if (!defined('PARENT')) { exit; }
if (isset($_GET['edit'])) {
  $ID    = (int)$_GET['edit'];
  $Q     = $DB->db_query("SELECT * FROM `".DB_PREFIX."countries` WHERE `id` = '{$ID}'");
  $EDIT  = $DB->db_object($Q);
  if (!isset($EDIT->id)) {
    die('<p style="padding:30px">Country not found, invalid ID</p>');
  }
}
define('CALBOX','ts');
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

        <form method="post" action="#">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo (isset($EDIT->id) ? $adlang18[7] : $adlang18[6]); ?></h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#one" data-toggle="tab"><?php echo $adlang6[7]; ?></a></li>
                                <li><a href="#two" data-toggle="tab"><?php echo $adlang18[12]; ?></a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="one">
								 <div class="form-group">
								  <label><?php echo $adlang18[0]; ?></label>
								  <input type="text" name="name" value="<?php echo (isset($EDIT->name) ? mswSafeDisplay($EDIT->name) : ''); ?>" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang18[9]; ?> <a href="http://en.wikipedia.org/wiki/ISO_3166-1" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <input type="text" name="iso2" value="<?php echo (isset($EDIT->iso2) ? mswSafeDisplay($EDIT->iso2) : ''); ?>" class="form-control"  maxlength="2">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang18[10]; ?> <a href="http://en.wikipedia.org/wiki/ISO_3166-1" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <input type="text" name="iso" value="<?php echo (isset($EDIT->iso) ? mswSafeDisplay($EDIT->iso) : ''); ?>" class="form-control"  maxlength="3">
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang18[11]; ?> <a href="http://en.wikipedia.org/wiki/ISO_3166-1" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <input type="text" name="iso4217" value="<?php echo (isset($EDIT->iso4217) ? mswSafeDisplay($EDIT->iso4217) : ''); ?>" class="form-control"  maxlength="50">
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang18[8]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="display" value="yes"<?php echo (isset($EDIT->display) && $EDIT->display=='yes' ? ' checked="checked"' : (!isset($EDIT->display) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="display" value="no"<?php echo (isset($EDIT->display) && $EDIT->display=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
								</div>
								<div class="tab-pane fade" id="two">
								 <div class="form-group">
								  <label><?php echo $adlang18[13]; ?></label>
								  <input type="text" name="tax" value="<?php echo (isset($EDIT->tax) ? mswSafeDisplay($EDIT->tax) : ''); ?>" class="form-control"  maxlength="2">
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang18[20]; ?></label>
								  <input type="text" name="tax2" value="<?php echo (isset($EDIT->tax2) ? mswSafeDisplay($EDIT->tax2) : ''); ?>" class="form-control"  maxlength="2">
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang18[16]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="eu" value="yes"<?php echo (isset($EDIT->eu) && $EDIT->eu=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="eu" value="no"<?php echo (isset($EDIT->eu) && $EDIT->eu=='no' ? ' checked="checked"' : (!isset($EDIT->eu) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
								</div>
							</div>
						</div>
						<div class="panel-footer">
              <?php
							if (isset($EDIT->id)) {
							?>
							<input type="hidden" name="edit" value="<?php echo $EDIT->id; ?>">
							<?php
							}
							?>
							<button type="button" class="btn btn-primary" onclick="mm_processor('countries')"><?php echo (isset($EDIT->id) ? $adlang18[7] : $adlang18[6]); ?></button>
              <button type="button" class="btn btn-link" onclick="mm_windowLoc('countries')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
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
