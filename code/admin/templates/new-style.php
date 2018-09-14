<?php if (!defined('PARENT')) { exit; }
if (isset($_GET['edit'])) {
  $ID     = (int)$_GET['edit'];
  $Q      = $DB->db_query("SELECT * FROM `".DB_PREFIX."music_styles` WHERE `id` = '{$ID}'");
  $EDIT   = $DB->db_object($Q);
  if (!isset($EDIT->id)) {
    die('<p style="padding:30px">Style not found, invalid ID</p>');
  }
}
?>
      <div id="wrapper">
        <script>
        //<![CDATA[
        function mm_remStyle() {
          jQuery('#stylecol').remove();
          jQuery('input[name="search-collection"]').prop('disabled', false);
        }
        function mm_styleCol(id,name) {
          jQuery('div[class="styleAssoc"]').html('<p id="stylecol"><input type="hidden" name="collection" value="' + id + '"><a href="#" onclick="mm_remStyle();return false"><i class="fa fa-times fa-fw mm_red"></i></a> '+name+'</p>');
          jQuery('input[name="search-collection"]').prop('disabled', true);
        }
        function mswLinkCol(value) {
          if (value > 0) {
            jQuery('ul[class="nav nav-tabs"] li:nth-child(2)').show();
          } else {
            jQuery('ul[class="nav nav-tabs"] li:nth-child(2)').hide();
          }
        }
        <?php
        if (AUTO_COMPLETE_ENABLE) {
        ?>
        jQuery(document).ready(function() {
          jQuery('input[name="search-collection"]').autocomplete({
             source: 'index.php?ajax=auto-featured',
             minLength: <?php echo AUTO_COMPLETE_MIN_LENGTH; ?>,
             select: function(event,ui) {
               mm_styleCol(ui.item.value,ui.item.label);
             },
             close: function(event,ui) {
              jQuery('input[name="search-collection"]').val('');
             }
           });
        });
        <?php
        }
        ?>
        //]]>
        </script>
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
                    <h1 class="page-header"><?php echo (isset($EDIT->id) ? $adlang5[2] : $adlang5[0]); ?></h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#one" data-toggle="tab"><?php echo $adlang2[0]; ?></a></li>
                                <li<?php echo (isset($EDIT->type) && $EDIT->type == 0 ? ' style="display:none"' : ''); ?>><a href="#two" data-toggle="tab"><?php echo $adlang5[21]; ?></a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="one">
                                 <div class="form-group">
                                  <label><?php echo (isset($EDIT->id) ? $adlang5[1] : $adlang5[6]); ?></label>
                                  <?php
                                    if (isset($EDIT->id)) {
                                    ?>
                                  <input type="text" name="name" value="<?php echo mswSafeDisplay($EDIT->name); ?>" class="form-control" maxlength="250">
                                    <?php
                                    } else {
                                  ?>
                                    <textarea name="styles" rows="5" cols="40" class="form-control"></textarea>
                                  <?php
                                  }
                                    ?>
                                 </div>
                                 <?php
                                 if (isset($EDIT->id)) {
                                 ?>
                                 <div class="form-group">
                                  <label><?php echo $adlang5[8]; ?></label>
                                  <input type="text" name="slug" value="<?php echo mswSafeDisplay($EDIT->slug); ?>" class="form-control" maxlength="250">
                                   </div>
                                 <?php
                                 }
                                 ?>
                                 <div class="form-group">
                                  <label><?php echo $adlang5[13]; ?></label>
                                  <select name="type" class="form-control" onchange="mswLinkCol(this.value)">
                                   <option value="0"><?php echo $adlang5[14]; ?></option>
                                   <optgroup label="<?php echo mswSafeDisplay($adlang5[15]); ?>">
                                     <?php
                                     $fsy = '';
                                     if (isset($_GET['edit'])) {
                                       $fsy = 'AND `id` != \'' . $EDIT->id . '\'';
                                     }
                                     $Q  = $DB->db_query("SELECT `id`,`name` FROM `".DB_PREFIX."music_styles` WHERE `type` = '0' $fsy ORDER BY `name`");
                                     while ($S = $DB->db_object($Q)) {
                                     ?>
                                     <option value="<?php echo $S->id; ?>"<?php echo (isset($EDIT->type) && $EDIT->type==$S->id ? ' selected="selected"' : (isset($_GET['subID']) && $_GET['subID']==$S->id ? ' selected="selected"' : '')); ?>><?php echo mswSafeDisplay($S->name); ?></option>
                                     <?php
                                     }
                                     ?>
                                   </optgroup>
                                  </select>
                                 </div>
                                 <div class="form-group">
                                  <label><?php echo $adlang5[3]; ?></label>
                                  <div class="radio">
                                    <label><input type="radio" name="enabled" value="yes"<?php echo (isset($EDIT->enabled) && $EDIT->enabled=='yes' ? ' checked="checked"' : (!isset($EDIT->id) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[7]; ?></label>
                                   </div>
                                   <div class="radio">
                                    <label><input type="radio" name="enabled" value="no"<?php echo (isset($EDIT->enabled) && $EDIT->enabled=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
                                   </div>
                                 </div>
                                </div>
                                <div class="tab-pane fade" id="two">
                                <div class="form-group">
								  <label><?php echo $adlang5[22]; ?></label>
								  <input type="text" name="search-collection" value="" class="form-control"<?php echo (isset($EDIT->collection) && $EDIT->collection > 0 ? ' disabled="disabled"' : ''); ?>>
								 </div>
								 <div class="styleAssoc">
								  <?php
								  if (isset($EDIT->collection) && $EDIT->collection > 0) {
								  $Q = $DB->db_query("SELECT `id`,`name` FROM `".DB_PREFIX."collections` WHERE `id` = '{$EDIT->collection}'");
								  $C = $DB->db_object($Q);
								  ?>
								  <p id="stylecol"><input type="hidden" name="collection" value="<?php echo $EDIT->collection; ?>"><a href="#" onclick="mm_remStyle();return false"><i class="fa fa-times fa-fw mm_red"></i></a> <?php echo mswSafeDisplay($C->name); ?></p>
								  <?php
								  }
                  ?>
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
                            <button type="button" class="btn btn-primary" onclick="mm_processor('styles')"><?php echo mswSafeDisplay((isset($EDIT->id) ? $adlang5[2] : $adlang5[0])); ?></button>
                            <button type="button" class="btn btn-link" onclick="mm_windowLoc('styles<?php echo (isset($_GET['sub']) ? '&amp;sub='.(int) $_GET['sub'] : ''); ?>')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
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
