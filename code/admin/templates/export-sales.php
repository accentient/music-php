<?php if (!defined('PARENT')) { exit; }
define('CALBOX','from|to');
include(PATH.'templates/date-picker.php');
$fromTo = array('','');
if (isset($_GET['ph'])) {
  $pHis  = (int)$_GET['ph'];
  $Q     = $DB->db_query("SELECT `id`,`name`,`email` FROM `".DB_PREFIX."accounts` WHERE `id` = '{$pHis}'");
  $AC    = $DB->db_object($Q);
  if (isset($AC->id)) {
    define('PURCHASE_HISTORY',$pHis);
  }
}
if (!isset($_GET['st'])) {
  $_GET['st'] = 'Completed';
}
if (isset($_GET['fr'],$_GET['to'])) {
  if ($_GET['fr'] && $_GET['to']) {
    $from = $DT->dateToTS($_GET['fr']);
    $to   = $DT->dateToTS($_GET['to']);
    if ($from > 0 && $to > 0) {
      $fromTo[0] = $_GET['fr'];
      $fromTo[1] = $_GET['to'];
    }
  }
}
include(REL_PATH.'control/countries.php');
?>
      <div id="wrapper">
        <?php
		if (AUTO_COMPLETE_ENABLE && !isset($EDIT->id)) {
		?>
        <script>
		//<![CDATA[
		jQuery(document).ready(function() {
           jQuery('input[name="acc"]').autocomplete({
		     source: 'index.php?ajax=auto-name',
			 minLength: <?php echo AUTO_COMPLETE_MIN_LENGTH; ?>,
			 select: function(event,ui) {
			   jQuery('input[name="account"]').val(ui.item.value);
			   jQuery('input[name="acc"]').prop('readonly','readonly');
			   jQuery('i[class="fa fa-times fa-fw mm_red mm_cursor"]').show();
			 }
           });
		   <?php
		   if (defined('PURCHASE_HISTORY')) {
		   ?>
		   jQuery('input[name="account"]').val('<?php echo mswSafeDisplay($AC->name).' ('.$AC->email.')'; ?>');
		   jQuery('input[name="acc"]').val('<?php echo mswSafeDisplay($AC->name).' ('.$AC->email.')'; ?>');
		   jQuery('input[name="acc"]').prop('readonly','readonly');
		   jQuery('i[class="fa fa-times fa-fw mm_red mm_cursor"]').show();
		   <?php
		   }
		   ?>
		});
		function mm_clearExpAcc() {
		  jQuery('input[name="account"]').val('');
		  jQuery('input[name="acc"]').removeProp('readonly');
		  jQuery('input[name="acc"]').val('');
		  jQuery('i[class="fa fa-times fa-fw mm_red mm_cursor"]').hide();
		}
		//]]>
		</script>
		<?php
		} else {
		  if (defined('PURCHASE_HISTORY')) {
		  ?>
		  <script>
		  //<![CDATA[
		  jQuery(document).ready(function() {
		   jQuery('input[name="account"]').val('<?php echo mswSafeDisplay($AC->name).' ('.$AC->email.')'; ?>');
		   jQuery('input[name="acc"]').val('<?php echo mswSafeDisplay($AC->name).' ('.$AC->email.')'; ?>');
		   jQuery('input[name="acc"]').prop('readonly','readonly');
		   jQuery('i[class="fa fa-times fa-fw mm_red mm_cursor"]').show();
		  });
		  //]]>
		  </script>
		  <?php
		  }
		}
		?>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <?php
			// Load top bar and navigation menu..
            include(PATH.'templates/header-top-bar.php');
			include(PATH.'templates/header-nav-bar.php');
			?>
		</nav>

        <form method="post" action="?p=export-sales">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo substr($titleBar,0,-2); ?></h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#one" data-toggle="tab"><?php echo $adlang9[59]; ?></a></li>
                                <li><a href="#two" data-toggle="tab"><?php echo $adlang9[102]; ?></a></li>
                                <li><a href="#three" data-toggle="tab"><?php echo $adlang9[69]; ?></a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="one">
								 <div class="form-group">
								  <label><?php echo $adlang9[14]; ?></label>
								  <input type="text" name="from" id="from" value="<?php echo $fromTo[0]; ?>" class="form-control">
                                 </div>
								 <div class="form-group">
								  <label><?php echo $adlang9[70]; ?></label>
								  <input type="text" name="to" id="to" value="<?php echo $fromTo[1]; ?>" class="form-control">
                                 </div>
								 <div class="form-group">
								  <label><?php echo $adlang9[18]; ?></label>
								  <select name="status[]" multiple="multiple" class="form-control">
								  <?php
								  $Q_S  = $DB->db_query("SELECT `status` FROM `".DB_PREFIX."sales` WHERE `status` != '' GROUP BY `status` ORDER BY `status`");
								  while ($ST = $DB->db_object($Q_S)) {
								  ?>
								  <option value="<?php echo $ST->status; ?>"<?php echo ($_GET['st']==$ST->status ? ' selected="selected"' : ''); ?>><?php echo mswSafeDisplay($ST->status); ?></option>
								  <?php
								  }
								  ?>
								  </select>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang9[16]; ?></label>
								  <select name="gateway[]" multiple="multiple" class="form-control">
								  <?php
								  $Q_G  = $DB->db_query("SELECT `id`,`display` FROM `".DB_PREFIX."gateways` ORDER BY `display`");
								  while ($GW = $DB->db_object($Q_G)) {
								  ?>
								  <option value="<?php echo $GW->id; ?>"> <?php echo mswSafeDisplay($GW->display); ?></option>
								  <?php
								  }
								  ?>
								  </select>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang9[87]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="music" value="yes"> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="music" value="no" checked="checked"> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
								</div>
								<div class="tab-pane fade" id="two">
                 <div class="form-group">
								  <label><?php echo $adlang9[100]; ?></label>
								  <select name="taxCountry" class="form-control">
                  <option value="0">- - - - - -</option>
								  <?php
								  foreach ($countries AS $k => $v) {
								  ?>
								  <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								  <?php
								  }
								  ?>
                  </select>
                 </div>
                 <div class="form-group">
								  <label><?php echo $adlang9[101]; ?></label>
								  <select name="taxCountry2" class="form-control">
                  <option value="0">- - - - - -</option>
								  <?php
								  foreach ($countries AS $k => $v) {
								  ?>
								  <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								  <?php
								  }
								  ?>
                  </select>
                 </div>
                </div>
                <div class="tab-pane fade" id="three">
								 <div class="form-group">
								  <div class="form-group">
								  <label><?php echo $adlang9[15]; ?> <i class="fa fa-times fa-fw mm_red mm_cursor" style="display:none" onclick="mm_clearExpAcc()"></i></label>
								  <input type="hidden" name="account" value="">
								  <input type="text" name="acc" value="" class="form-control">
								 </div>
								 </div>
								</div>
							</div>
						</div>
						<div class="panel-footer">
						    <input type="hidden" name="process" value="yes">
                            <button type="submit" class="btn btn-primary"><?php echo mswSafeDisplay($adlang9[11]); ?></button>
                            <button type="button" class="btn btn-link" onclick="mm_windowLoc('<?php echo (defined('PURCHASE_HISTORY') ? 'history&amp;id='.PURCHASE_HISTORY : 'sales&amp;st='.urlencode($_GET['st'])); ?>')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
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
