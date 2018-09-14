<?php if (!defined('PARENT')) { exit; }
if (isset($_GET['edit'])) {
  $ID    = (int)$_GET['edit'];
  $Q     = $DB->db_query("SELECT * FROM `".DB_PREFIX."accounts` WHERE `id` = '{$ID}'");
  $EDIT  = $DB->db_object($Q);
  if (!isset($EDIT->id)) {
    die('<p style="padding:30px">Account not found, invalid ID</p>');
  }
  $Q2    = $DB->db_query("SELECT * FROM `".DB_PREFIX."accounts_addr` WHERE `account` = '{$ID}'");
  $ADDR  = $DB->db_object($Q2);
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
                    <h1 class="page-header"><?php echo (isset($EDIT->id) ? $adlang6[6] : $adlang6[0]); ?></h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#one" data-toggle="tab"><?php echo $adlang6[7]; ?></a></li>
                                <li><a href="#two" data-toggle="tab"><?php echo $adlang6[13]; ?></a></li>
                                <li><a href="#three" data-toggle="tab"><?php echo $adlang6[8]; ?></a></li>
                                <li><a href="#four" data-toggle="tab"><?php echo $adlang6[9]; ?></a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="one">
								 <div class="form-group">
								  <label><?php echo $adlang6[2]; ?></label>
								  <input type="text" name="name" value="<?php echo (isset($EDIT->name) ? mswSafeDisplay($EDIT->name) : ''); ?>" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[3]; ?></label>
								  <input type="text" name="email" value="<?php echo (isset($EDIT->email) ? mswSafeDisplay($EDIT->email) : ''); ?>" class="form-control"  maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[31]; ?></label>
								  <select name="accCountry" class="form-control">
								   <option value="">- - -</option>
								   <?php
								   include(REL_PATH.'control/countries.php');
								   foreach ($countries AS $k => $v) {
								   ?>
								   <option value="<?php echo $k; ?>"<?php echo (isset($EDIT->country) && $EDIT->country==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								   <?php
								   }
								   ?>
								   </select>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[10]; ?> <span class="passGenReveal"></span></label>
								  <div class="form-group input-group">
								   <span class="input-group-addon"><i class="fa fa-key fa-fw mm_cursor" onclick="mm_passGen()" title="<?php echo mswSafeDisplay($adlang6[21]); ?>"></i></span>
								   <input type="password" name="password" value="" class="form-control" onkeyup="mm_passClear()">
								  </div>
								 </div>
								 <?php
								 if (!isset($EDIT->id)) {
								 ?>
								 <div class="form-group">
								  <label><?php echo $adlang6[18]; ?></label>
								  <div class="checkbox">
								    <label><input type="checkbox" name="mail" value="yes" checked="checked"> <?php echo $gblang[7]; ?></label>
								  </div>
								 </div>
								 <?php
								 }
								 ?>
								</div>
								<div class="tab-pane fade" id="two">
								 <div class="form-group">
								  <label><?php echo $adlang6[15]; ?></label>
								   <select name="shipping" class="form-control">
								   <option value="">- - -</option>
								   <?php
								   $QSP  = $DB->db_query("SELECT * FROM `".DB_PREFIX."shipping` ORDER BY `name`");
								   while ($SP = $DB->db_object($QSP)) {
								   ?>
								   <option value="<?php echo $SP->id; ?>"<?php echo (isset($EDIT->id) && $EDIT->id==$SP->id ? ' selected="selected"' : ''); ?>><?php echo mswSafeDisplay($SP->name); ?> (<?php echo (substr($SP->cost,-1)=='%' ? $SP->cost : ($SP->cost>0 ? mswCurrencyFormat($SP->cost,$SETTINGS->curdisplay) : $adlang6[22])); ?>)</option>
								   <?php
								   }
								   ?>
								   </select>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[14]; ?></label>
								  <input type="text" name="address1" value="<?php echo (isset($ADDR->address1) ? mswSafeDisplay($ADDR->address1) : ''); ?>" class="form-control"  maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[26]; ?></label>
								  <input type="text" name="address2" value="<?php echo (isset($ADDR->address2) ? mswSafeDisplay($ADDR->address2) : ''); ?>" class="form-control"  maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[27]; ?></label>
								  <input type="text" name="city" value="<?php echo (isset($ADDR->city) ? mswSafeDisplay($ADDR->city) : ''); ?>" class="form-control"  maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[28]; ?></label>
								  <input type="text" name="county" value="<?php echo (isset($ADDR->county) ? mswSafeDisplay($ADDR->county) : ''); ?>" class="form-control"  maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[30]; ?></label>
								  <input type="text" name="postcode" value="<?php echo (isset($ADDR->postcode) ? mswSafeDisplay($ADDR->postcode) : ''); ?>" class="form-control"  maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[29]; ?></label>
								  <select name="addCountry" class="form-control">
								   <option value="">- - -</option>
								   <?php
								   foreach ($countries AS $k => $v) {
								   ?>
								   <option value="<?php echo $k; ?>"<?php echo (isset($ADDR->country) && $ADDR->country==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								   <?php
								   }
								   ?>
								   </select>
								 </div>
								</div>
								<div class="tab-pane fade" id="three">
								 <div class="form-group">
								  <label><?php echo $adlang6[11]; ?></label>
								   <select name="timezone" class="form-control">
								   <option value="">- - -</option>
								   <?php
								   foreach ($timezones AS $k => $v) {
								   ?>
								   <option value="<?php echo $k; ?>"<?php echo (isset($EDIT->timezone) && $EDIT->timezone==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								   <?php
								   }
								   ?>
								   </select>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[12]; ?></label>
								  <input type="text" name="ip" value="<?php echo (isset($EDIT->ip) ? mswSafeDisplay($EDIT->ip) : ''); ?>" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[16]; ?></label>
								  <input type="text" name="ts" id="ts" value="<?php echo (isset($EDIT->ts) && $EDIT->ts>0 ? $DT->tsToDate($EDIT->ts,$SETTINGS->jsformat) : $DT->tsToDate(time(),$SETTINGS->jsformat)); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang6[38]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="bypass" value="yes"<?php echo (isset($EDIT->bypass) && $EDIT->bypass=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="bypass" value="no"<?php echo (isset($EDIT->bypass) && $EDIT->bypass=='no' ? ' checked="checked"' : (!isset($EDIT->bypass) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang6[39]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="login" value="yes"<?php echo (isset($EDIT->login) && $EDIT->login=='yes' ? ' checked="checked"' : (!isset($EDIT->login) && $SETTINGS->acclogin=='yes' ? ' checked="checked"' : '')); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="login" value="no"<?php echo (isset($EDIT->login) && $EDIT->login=='no' ? ' checked="checked"' : (!isset($EDIT->login) && $SETTINGS->acclogin=='no' ? ' checked="checked"' : '')); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang6[17]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="enabled" value="yes"<?php echo (isset($EDIT->enabled) && $EDIT->enabled=='yes' ? ' checked="checked"' : (!isset($EDIT->enabled) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="enabled" value="no"<?php echo (isset($EDIT->enabled) && $EDIT->enabled=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
                </div>
								<div class="tab-pane fade" id="four">
								 <textarea name="notes" rows="10" cols="40" class="form-control"><?php echo (isset($EDIT->notes) ? mswSafeDisplay($EDIT->notes) : ''); ?></textarea>
								</div>
							</div>
						</div>
						<div class="panel-footer">
                            <?php
							if (isset($EDIT->id)) {
							?>
							<input type="hidden" name="edit" value="<?php echo $EDIT->id; ?>">
							<input type="hidden" name="pass" value="<?php echo $EDIT->pass; ?>">
							<input type="hidden" name="addbook" value="<?php echo (isset($ADDR->id) ? $ADDR->id : '0'); ?>">
							<?php
							}
							?>
							<button type="button" class="btn btn-primary" onclick="mm_processor('accounts')"><?php echo mswSafeDisplay((isset($EDIT->id) ? $adlang6[6] : $adlang6[0])); ?></button>
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

	<script>
    //<![CDATA[
	function mm_passClear() {
	  jQuery('span[class="passGenReveal"]').html('');
	}
    function mm_passGen() {
	  jQuery('input[name="password"]').css('background','url(templates/images/spinner.gif) no-repeat 98% 50%');
	  jQuery(document).ready(function() {
       jQuery.ajax({
        url: 'index.php',
        data: 'ajax=pass-gen',
        dataType: 'json',
        success: function (data) {
		  jQuery('input[name="password"]').css('background-image','none');
		  jQuery('span[class="passGenReveal"]').html(data['pass']);
		  jQuery('input[name="password"]').val(data['pass']);
        }
       });
      });
      return false;
	}
    //]]>
    </script>
