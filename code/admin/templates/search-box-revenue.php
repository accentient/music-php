<?php if (!defined('PARENT')) { exit; }
$_GET['p'] = mswSafeDisplay($_GET['p']);
include(REL_PATH.'control/countries.php');
?>
     <div class="row" id="mm_searchBox"<?php echo (!isset($_GET['q']) ? ' style="display:none"' : ''); ?>>
		   <form method="get" action="index.php">
			    <div class="col-lg-12">
			       <div class="panel panel-default">
               <div class="panel-body">
                 <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>">
                 <div class="form-group">
                   <label><?php echo $adlang20[5]; ?></label>
                   <input type="text" name="q" value="<?php echo (isset($_GET['q']) ? (int) $_GET['q'] : $currentYear); ?>" maxlength="4" class="form-control">
                 </div>
                 <div class="form-group">
                   <label><?php echo $adlang20[6]; ?></label>
                   <select name="country" class="form-control">
                    <option value="0">- - -</option>
								    <?php
								    foreach ($countries AS $k => $v) {
								    ?>
								    <option value="<?php echo $k; ?>"<?php echo (isset($_GET['country']) && $_GET['country']==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								    <?php
								    }
								    ?>
                   </select>
                 </div>
                 <div class="form-group">
                   <label><?php echo $adlang20[8]; ?></label>
                   <select name="pref" class="form-control">
                    <option value="0">- - -</option>
								    <option value="tangible"<?php echo (isset($_GET['pref']) && $_GET['pref']=='tangible' ? ' selected="selected"' : ''); ?>><?php echo $adlang20[9]; ?></option>
								    <option value="digital"<?php echo (isset($_GET['pref']) && $_GET['pref']=='digital' ? ' selected="selected"' : ''); ?>><?php echo $adlang20[10]; ?></option>
								   </select>
                 </div>
                 <div style="margin-top:5px">
                   <button type="submit" class="btn btn-primary"><?php echo $adlang20[7]; ?></button>
                   <button type="button" class="btn btn-link" onclick="mm_windowLoc('<?php echo $_GET['p']; ?>')"><?php echo $gblang[13]; ?></button>
                 </div>
               </div>
				     </div>
				  </div>
				</form>
			</div>