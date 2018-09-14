<?php if (!defined('PARENT')) { exit; }
if (isset($_GET['edit'])) {
  $ID    = (int)$_GET['edit'];
  $Q     = $DB->db_query("SELECT * FROM `".DB_PREFIX."gateways` WHERE `id` = '{$ID}'");
  $EDIT  = $DB->db_object($Q);
  if (!isset($EDIT->id)) {
    die('<p style="padding:30px">Gateway not found, invalid ID</p>');
  }
  // For help file url..
  define('GW_HELP', substr($EDIT->class,6,-4));
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
                    <h1 class="page-header"><?php echo (isset($EDIT->id) ? $adlang3[0] : $adlang3[8]).(isset($EDIT->id) ? ' - '.mswSafeDisplay($EDIT->display) : ''); ?></h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#one" data-toggle="tab"><?php echo $adlang3[1]; ?></a></li>
								<li><a href="#two" data-toggle="tab"><?php echo $adlang3[13]; ?></a></li>
                                <li><a href="#three" data-toggle="tab"><?php echo $adlang3[2]; ?></a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="one">
								 <div class="form-group">
								  <label><?php echo $adlang3[6]; ?></label>
								  <input type="text" name="display" value="<?php echo (isset($EDIT->display) ? mswSafeDisplay($EDIT->display) : ''); ?>" class="form-control" maxlength="100">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang3[3]; ?></label>
								  <input type="text" name="liveserver" value="<?php echo (isset($EDIT->liveserver) ? mswSafeDisplay($EDIT->liveserver) : ''); ?>" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang3[4]; ?></label>
								  <input type="text" name="sandboxserver" value="<?php echo (isset($EDIT->sandboxserver) ? mswSafeDisplay($EDIT->sandboxserver) : ''); ?>" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang3[5]; ?></label>
								  <input type="text" name="webpage" value="<?php echo (isset($EDIT->webpage) ? mswSafeDisplay($EDIT->webpage) : ''); ?>" class="form-control" maxlength="100">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang3[7]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="status" value="yes"<?php echo (isset($EDIT->status) && $EDIT->status=='yes' ? ' checked="checked"' : (!isset($_GET['edit']) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="status" value="no"<?php echo (isset($EDIT->status) && $EDIT->status=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
								</div>
								<div class="tab-pane fade" id="two">
								  <div class="form-group">
								   <label><?php echo $adlang3[14]; ?></label>
								   <select name="class" class="form-control">
								   <?php
								   $dir = opendir(MM_BASE_PATH.'control/classes/gateways');
								   while (false!==($d=readdir($dir))) {
								   if (substr(strtolower($d),-4)=='.php') {
								   ?>
								   <option value="<?php echo $d; ?>"<?php echo (isset($EDIT->class) && $EDIT->class==$d ? ' selected="selected"' : ''); ?>><?php echo $d; ?></option>
								   <?php
								   }
								   }
								   closedir($dir);
								   ?>
								   </select>
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang3[17]; ?></label>
								   <select name="image" class="form-control">
								   <?php
								   $imf = array('.gif','.tiff','.jpg','.jpeg','.png');
								   $dir = opendir(PATH.'templates/images/gateways');
								   while (false!==($im=readdir($dir))) {
								   $ext = strrchr(strtolower($im),'.');
								   if (in_array($ext,$imf)) {
								   ?>
								   <option value="<?php echo $im; ?>"<?php echo (isset($EDIT->image) && $EDIT->image==$im ? ' selected="selected"' : ''); ?>><?php echo $im; ?></option>
								   <?php
								   }
								   }
								   closedir($dir);
								   ?>
								   </select>
								  </div>
								  <div class="form-group">
								  <label><?php echo $adlang3[15]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="default" value="yes"<?php echo (isset($EDIT->default) && $EDIT->default=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="default" value="no"<?php echo (isset($EDIT->default) && $EDIT->default=='no' ? ' checked="checked"' : (!isset($_GET['edit']) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
								</div>
								<div class="tab-pane fade" id="three">
								 <?php
								 if (isset($EDIT->id)) {
								 $QGP  = $DB->db_query("SELECT * FROM `".DB_PREFIX."gateways_params` WHERE `gateway` = '{$EDIT->id}' ORDER BY `id`");
								 if ($DB->db_rows($QGP)>0) {
								 while ($GP = $DB->db_object($QGP)) {
								 ?>
								 <div class="row" style="margin-top:5px">
								  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								   <input type="text" name="param[]" value="<?php echo mswSafeDisplay($GP->param); ?>" class="form-control">
								  </div>
								  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								   <input type="<?php echo ($SETTINGS->hideparams=='yes' ? 'password' : 'text'); ?>" name="value[]" value="<?php echo mswSafeDisplay($GP->value); ?>" class="form-control">
								  </div>
								 </div>
								 <?php
								 }
								 } else {
								 ?>
								 <div class="row">
								  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								   <input type="text" name="param[]" value="" class="form-control" placeholder="<?php echo mswSafeDisplay($adlang3[9]); ?>">
								  </div>
								  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								   <input type="<?php echo ($SETTINGS->hideparams=='yes' ? 'password' : 'text'); ?>" name="value[]" value="" class="form-control" placeholder="<?php echo mswSafeDisplay($adlang3[10]); ?>">
								  </div>
								 </div>
								 <?php
								 }
								 } else {
                 ?>
								 <div class="row">
								  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								   <input type="text" name="param[]" value="" class="form-control" placeholder="<?php echo mswSafeDisplay($adlang3[9]); ?>">
								  </div>
								  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								   <input type="<?php echo ($SETTINGS->hideparams=='yes' ? 'password' : 'text'); ?>" name="value[]" value="" class="form-control" placeholder="<?php echo mswSafeDisplay($adlang3[10]); ?>">
								  </div>
								 </div>
								 <?php
                 }
								 ?>
								 <div style="text-align:right;margin-top:20px">
								  <button onclick="mm_gatewayParam('add')" type="button" class="btn btn-success" title="<?php echo mswSafeDisplay($gblang[40]); ?>"><i class="fa fa-plus fa-fw"></i></button>
								  <button onclick="mm_gatewayParam('rem')" type="button" class="btn btn-success" title="<?php echo mswSafeDisplay($gblang[26]); ?>"><i class="fa fa-minus fa-fw"></i></button>
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
							<button type="button" class="btn btn-primary" onclick="mm_processor('gateways')"><?php echo mswSafeDisplay((isset($EDIT->id) ? $adlang3[0] : $adlang3[8])); ?></button>
                            <button type="button" class="btn btn-link" onclick="mm_windowLoc('gateways')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
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

	<script>
	//<![CDATA[
  function mm_gatewayParam(type) {
	  var dcnt = jQuery('#three .row').length;
	  switch(type) {
	    case 'add':
      var clone = jQuery('#three .row').last().html();
      jQuery('#three .row').last().after('<div class="row" style="margin-top:5px">'+clone+'</div>');
      jQuery('#three input[name="param[]"]').last().val('');
      jQuery('#three input[name="value[]"]').last().val('');
      break;
      case 'rem':
      if (dcnt>1) {
        jQuery('#three .row').last().remove();
      } else {
        jQuery('#three input[name="param[]"]').last().val('');
        jQuery('#three input[name="value[]"]').last().val('');
      }
      break;
	  }
	}
  //]]>
	</script>
