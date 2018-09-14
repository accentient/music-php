<?php if (!defined('PARENT')) { exit; } 
if (isset($_GET['edit'])) {
  $ID    = (int)$_GET['edit'];
  $Q     = $DB->db_query("SELECT * FROM `".DB_PREFIX."offers` WHERE `id` = '{$ID}'");
  $EDIT  = $DB->db_object($Q);
  if (!isset($EDIT->id)) {
    die('<p style="padding:30px">Offer not found, invalid ID</p>');
  }
  $colArray = ($EDIT->collections ? explode(',',$EDIT->collections) : array());
}
define('CALBOX','expiry');
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
                    <h1 class="page-header"><?php echo (isset($EDIT->id) ? $adlang11[9] : $adlang11[0]); ?></h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#one" data-toggle="tab"><?php echo $adlang11[10]; ?></a></li>
                                <li><a href="#two" data-toggle="tab"><?php echo $adlang11[12]; ?></a></li>
                                <li><a href="#three" data-toggle="tab"><?php echo $adlang11[11]; ?></a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="one">
								 <div class="form-group">
								  <label><?php echo $adlang11[1]; ?></label>
								  <input type="text" name="discount" value="<?php echo (isset($EDIT->discount) ? mswSafeDisplay($EDIT->discount) : ''); ?>" class="form-control">
                                 </div>
								 <div class="form-group">
								  <label><?php echo $adlang11[3]; ?></label>
								  <select name="type" class="form-control">
								   <option value="all"<?php echo (isset($EDIT->type) && $EDIT->type=='all' ? ' selected="selected"' : ''); ?>><?php echo $adlang11[7]; ?></option>
								   <option value="collections"<?php echo (isset($EDIT->type) && $EDIT->type=='collections' ? ' selected="selected"' : ''); ?>><?php echo $adlang11[5]; ?></option>
								   <option value="tracks"<?php echo (isset($EDIT->type) && $EDIT->type=='tracks' ? ' selected="selected"' : ''); ?>><?php echo $adlang11[6]; ?></option>
								   <option value="cd"<?php echo (isset($EDIT->type) && $EDIT->type=='cd' ? ' selected="selected"' : ''); ?>><?php echo $adlang11[4]; ?></option>
								  </select>
                                 </div>
								</div>
								<div class="tab-pane fade" id="two">
								 <div class="form-group" style="max-height:250px;overflow:auto">
								 <?php
								 $Q_C    = $DB->db_query("SELECT `id`,`name` FROM `".DB_PREFIX."collections` WHERE `enabled` = 'yes' ORDER BY `name`");
								 while ($COL  = $DB->db_object($Q_C)) {
								 ?>
								 <div class="checkbox">
								  <label><input type="checkbox" name="cols[]" value="<?php echo $COL->id; ?>"<?php echo (!empty($colArray) && in_array($COL->id,$colArray) ? ' checked="checked"' : ''); ?>> <?php echo mswSafeDisplay($COL->name); ?></label>
                                 </div>
								 <?php
								 }
								 ?>
								 </div>
								</div>
								<div class="tab-pane fade" id="three">
								 <div class="form-group">
								  <label><?php echo $adlang11[2]; ?></label>
								  <input type="text" name="expiry" id="expiry" value="<?php echo (isset($EDIT->expiry) && $EDIT->expiry>0 ? $DT->tsToDate($EDIT->expiry,$SETTINGS->jsformat) : ''); ?>" class="form-control">
                                 </div>
								 <div class="form-group">
								  <label><?php echo $adlang11[13]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="enabled" value="yes"<?php echo (isset($EDIT->enabled) && $EDIT->enabled=='yes' ? ' checked="checked"' : (!isset($EDIT->enabled) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="enabled" value="no"<?php echo (isset($EDIT->enabled) && $EDIT->enabled=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
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
							<button type="button" class="btn btn-primary" onclick="mm_processor('offers')"><?php echo mswSafeDisplay((isset($EDIT->id) ? $adlang11[9] : $adlang11[0])); ?></button>
                            <button type="button" class="btn btn-link" onclick="mm_windowLoc('offers')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
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
