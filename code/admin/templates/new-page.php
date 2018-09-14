<?php if (!defined('PARENT')) { exit; } 
if (isset($_GET['edit'])) {
  $ID    = (int)$_GET['edit'];
  $Q     = $DB->db_query("SELECT * FROM `".DB_PREFIX."pages` WHERE `id` = '{$ID}'");
  $EDIT  = $DB->db_object($Q);
  if (!isset($EDIT->id)) {
    die('<p style="padding:30px">Page not found, invalid ID</p>');
  }
}
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
                    <h1 class="page-header"><?php echo (isset($EDIT->id) ? $adlang12[1] : $adlang12[0]); ?></h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#one" data-toggle="tab"><?php echo $adlang12[5]; ?></a></li>
                                <li><a href="#two" data-toggle="tab"><?php echo $adlang12[6]; ?></a></li>
                                <li><a href="#three" data-toggle="tab"><?php echo $adlang12[7]; ?></a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="one">
								 <div class="form-group">
								  <label><?php echo $adlang12[2]; ?></label>
								  <input type="text" name="name" value="<?php echo (isset($EDIT->name) ? mswSafeDisplay($EDIT->name) : ''); ?>" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang12[8]; ?></label>
								  <textarea name="info" rows="5" cols="40" class="form-control" style="height:300px"><?php echo (isset($EDIT->info) ? mswSafeDisplay($EDIT->info) : ''); ?></textarea>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang12[9]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="landing" value="yes"<?php echo (isset($EDIT->landing) && $EDIT->landing=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="landing" value="no"<?php echo (isset($EDIT->landing) && $EDIT->landing=='no' ? ' checked="checked"' : (!isset($EDIT->id) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[8]; ?> </label>
								   </div>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang12[10]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="enabled" value="yes"<?php echo (isset($EDIT->enabled) && $EDIT->enabled=='yes' ? ' checked="checked"' : (!isset($EDIT->id) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="enabled" value="no"<?php echo (isset($EDIT->enabled) && $EDIT->enabled=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?> </label>
								   </div>
								 </div>
								</div>
								<div class="tab-pane fade" id="two">
								 <div class="form-group">
								  <select name="template" class="form-control">
								  <?php
								  $tc = 0;
								  $dir = opendir(MM_BASE_PATH.'content/'.THEME.'/custom-pages');
								  while (false!==($t=readdir($dir))) {
								  if (substr(strtolower($t),-8)=='.tpl.php') {
								  ++$tc;
								  if ($tc==1) {
								  ?>
								  <option value="">- - -</option>
								  <?php
								  }
								  ?>
								  <option value="<?php echo $t; ?>"<?php echo (isset($EDIT->template) && $EDIT->template==$t ? ' selected="selected"' : ''); ?>> <?php echo mswSafeDisplay($t); ?></option>
								  <?php
								  }
								  }
								  closedir($dir);
								  if ($tc==0) {
								  ?>
								  <option value=""><?php echo $adlang12[17]; ?></option>
								  <?php
								  }
								  ?>
								  </select>
								 </div>
								</div>
								<div class="tab-pane fade" id="three">
								 <div class="form-group">
								  <label><?php echo $adlang12[11]; ?></label>
								  <input type="text" name="title" value="<?php echo (isset($EDIT->title) ? mswSafeDisplay($EDIT->title) : ''); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang12[12]; ?></label>
								  <input type="text" name="keys" value="<?php echo (isset($EDIT->keys) ? mswSafeDisplay($EDIT->keys) : ''); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang12[13]; ?></label>
								  <input type="text" name="desc" value="<?php echo (isset($EDIT->desc) ? mswSafeDisplay($EDIT->desc) : ''); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang12[14]; ?></label>
								  <input type="text" name="slug" value="<?php echo (isset($EDIT->slug) ? mswSafeDisplay($EDIT->slug) : ''); ?>" class="form-control" maxlength="50">
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
							<button type="button" class="btn btn-primary" onclick="mm_processor('pages')"><?php echo mswSafeDisplay((isset($EDIT->id) ? $adlang12[1] : $adlang12[0])); ?></button>
                            <button type="button" class="btn btn-link" onclick="mm_windowLoc('pages')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
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
