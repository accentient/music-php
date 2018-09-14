<?php if (!defined('PARENT')) { exit; }
if (isset($_GET['edit'])) {
  $ID    = (int)$_GET['edit'];
  $Q     = $DB->db_query("SELECT * FROM `".DB_PREFIX."collections` WHERE `id` = '{$ID}'");
  $EDIT  = $DB->db_object($Q);
  if (!isset($EDIT->id)) {
    die('<p style="padding:30px">Collection not found, invalid ID</p>');
  }
  $styles        = array();
  $Q2             = $DB->db_query("SELECT * FROM `".DB_PREFIX."collection_styles` WHERE `collection` = '{$ID}'");
  while ($CSS = $DB->db_object($Q2)) {
    $styles[] = $CSS->style;
  }
  $related  = ($EDIT->related ? unserialize($EDIT->related) : array());
  $coveroth = ($EDIT->coverartother ? unserialize($EDIT->coverartother) : array());
  $social   = ($EDIT->social ? unserialize($EDIT->social) : array());
}
define('CALBOX','released');
include(PATH.'templates/date-picker.php');
?>
      <div id="wrapper">
        <script>
		//<![CDATA[
		jQuery(document).ready(function() {
          jQuery('div[class="related"]').sortable({
		   opacity: 0.6,
		   cursor: 'move',
		   update: function() {
		    var order = jQuery(this).sortable("serialize");
			jQuery.post('index.php?ajax=order-related',
			 order,
			 function(data){
			   // Nothing doing..add custom ops if necessary..
			 },
			'json');
		   }
		  });
		});
		function mm_remRelated(id) {
		  jQuery('div[class="related ui-sortable"] #col-'+id).slideUp(1000,
		   function(){
		    jQuery('div[class="related ui-sortable"] #col-'+id).remove()
		   }
		  );
		}
		function mm_related(id,name) {
		  var h = '<p id="col-' + id + '"><input type="hidden" name="related[]" value="' + id + '"><a href="#" onclick="mm_remRelated(\'' + id + '\');return false"><i class="fa fa-times fa-fw mm_red"></i></a> ' + name + '</p>';
		  var n = jQuery('div[class="related ui-sortable"] p').length;
		  if (n>0) {
		    jQuery('div[class="related ui-sortable"] p').last().after(h);
		  } else {
		    jQuery('div[class="related ui-sortable"]').html(h);
		  }
		}
    function mm_remCoverOther(id) {
		  jQuery('div[class="coverartother"] #carto-'+id).slideUp(1000,
		   function(){
		    jQuery('div[class="coverartother"] #carto-'+id).remove()
		   }
		  );
		}
		function mm_coverOther(id,name) {
		  var h = '<p id="carto-' + id + '"><input type="hidden" name="coverother[]" value="' + name + '"><a href="#" onclick="mm_remCoverOther(\'' + id + '\');return false"><i class="fa fa-times fa-fw mm_red"></i></a> ' + name + '</p>';
		  var n = jQuery('div[class="coverartother"] p').length;
		  if (n>0) {
		    jQuery('div[class="coverartother"] p').last().after(h);
		  } else {
		    jQuery('div[class="coverartother"]').html(h);
		  }
		}
    <?php
		if (isset($EDIT->id)) {
		?>
    function mm_sumTracks(box) {
      jQuery(document).ready(function() {
        jQuery('input[name="' + box + '"]').css('background','url(templates/images/spinner.gif) no-repeat 98% 50%');
        jQuery.ajax({
          url: 'index.php',
          data: 'ajax=sum-tracks&id=<?php echo $EDIT->id; ?>',
          dataType: 'json',
          success: function (data) {
            jQuery('input[name="' + box + '"]').css('background-image', 'none');
            jQuery('input[name="' + box + '"]').val(data[0]);
          }
        });
      });
      return false;
    }
    <?php
    }
		if (AUTO_COMPLETE_ENABLE) {
		?>
		jQuery(document).ready(function() {
      jQuery('input[name="search-related"]').autocomplete({
		    source: 'index.php?ajax=auto-featured',
			  minLength: <?php echo AUTO_COMPLETE_MIN_LENGTH; ?>,
			  select: function(event,ui) {
			    mm_related(ui.item.value,ui.item.label);
			  },
			  close: function(event,ui) {
			    jQuery('input[name="search-related"]').val('');
			  }
      });
      jQuery('input[name="cover-art-other"]').autocomplete({
		    source: 'index.php?ajax=auto-cover-art',
			  minLength: <?php echo AUTO_COMPLETE_MIN_LENGTH; ?>,
			  select: function(event,ui) {
			    mm_coverOther(ui.item.value,ui.item.label);
			  },
			  close: function(event,ui) {
			    jQuery('input[name="cover-art-other"]').val('');
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
                    <h1 class="page-header"><?php echo (isset($EDIT->id) ? $adlang4[14] : $adlang4[0]); ?></h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#one" data-toggle="tab"><?php echo $adlang4[31]; ?></a></li>
                                <li><a href="#two" data-toggle="tab"><?php echo $adlang4[33]; ?></a></li>
                                <li><a href="#three" data-toggle="tab"><?php echo $adlang4[34]; ?></a></li>
								<li><a href="#four" data-toggle="tab"><?php echo $adlang4[32]; ?></a></li>
                                <li><a href="#five" data-toggle="tab"><?php echo $adlang4[35]; ?></a></li>
								<li class="dropdown">
								 <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $adlang4[53]; ?> <span class="caret"></span></a>
                                 <ul class="dropdown-menu" role="menu">
								  <li><a href="#six" data-toggle="tab"><?php echo $adlang4[54]; ?></a></li>
								 </ul>
								</li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="one">
								 <div class="form-group">
								  <label><?php echo $adlang4[4]; ?></label>
								  <input type="text" name="name" value="<?php echo (isset($EDIT->name) ? mswSafeDisplay($EDIT->name) : ''); ?>" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[8]; ?></label>
								  <textarea name="information" rows="5" cols="40" class="form-control"><?php echo (isset($EDIT->information) ? mswSafeDisplay($EDIT->information) : ''); ?></textarea>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[29]; ?><?php echo (isset($EDIT->id) ? '&nbsp;&nbsp;&nbsp;<a href="#" onclick="mm_sumTracks(\'cost\');return false"><i class="fa fa-refresh fa-fw"></i> '.$adlang4[68].'</a>' : ''); ?></label>
								  <input type="text" name="cost" value="<?php echo (isset($EDIT->cost) ? mswSafeDisplay($EDIT->cost) : '0.00'); ?>" maxlength="10" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[37]; ?><?php echo (isset($EDIT->id) ? '&nbsp;&nbsp;&nbsp;<a href="#" onclick="mm_sumTracks(\'costcd\');return false"><i class="fa fa-refresh fa-fw"></i> '.$adlang4[68].'</a>' : ''); ?></label>
								  <input type="text" name="costcd" value="<?php echo (isset($EDIT->costcd) ? mswSafeDisplay($EDIT->costcd) : '0.00'); ?>" maxlength="10" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[28]; ?></label>
								  <input type="text" name="released" id="released" value="<?php echo (isset($EDIT->released) && $EDIT->released>0 ? $DT->tsToDate($EDIT->released,$SETTINGS->jsformat) : ''); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[36]; ?></label>
								  <input type="text" name="catnumber" value="<?php echo (isset($EDIT->catnumber) ? mswSafeDisplay($EDIT->catnumber) : ''); ?>" class="form-control" maxlength="200">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[56]; ?></label>
								  <input type="text" name="bitrate" value="<?php echo (isset($EDIT->bitrate) ? mswSafeDisplay($EDIT->bitrate) : ''); ?>" class="form-control" maxlength="200">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[57]; ?></label>
								  <input type="text" name="length" value="<?php echo (isset($EDIT->length) ? mswSafeDisplay($EDIT->length) : ''); ?>" class="form-control" maxlength="200">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[21]; ?></label>
								  <input type="text" name="views" value="<?php echo (isset($EDIT->views) ? mswSafeDisplay($EDIT->views) : '0'); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[13]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="enabled" value="yes"<?php echo (isset($EDIT->enabled) && $EDIT->enabled=='yes' ? ' checked="checked"' : (!isset($EDIT->id) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="enabled" value="no"<?php echo (isset($EDIT->enabled) && $EDIT->enabled=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?> </label>
								   </div>
								 </div>
								</div>
								<div class="tab-pane fade" id="two">
								 <div class="form-group" style="height:250px;overflow:auto">
								  <?php
								  $L = $DB->db_query("SELECT `id`,`name`,
                       (SELECT count(*) FROM `".DB_PREFIX."music_styles` s1 WHERE `s1`.`type` = `".DB_PREFIX."music_styles`.`id`) AS `subCount`
                       FROM `".DB_PREFIX."music_styles` WHERE `type` = '0' AND `collection` = '0' ORDER BY `orderby`");
								  while ($S = $DB->db_object($L)) {
								  if ($S->subCount > 0) {
                  ?>
								  <b><?php echo mswSafeDisplay($S->name); ?></b><br>
								  <?php
                  } else {
                  ?>
                  <div class="checkbox">
                   <label><input type="checkbox" name="styles[]" value="<?php echo $S->id; ?>"<?php echo (isset($EDIT->id) && in_array($S->id,$styles) ? ' checked="checked"' : ''); ?>> <?php echo mswSafeDisplay($S->name); ?></label>
								  </div>
                  <?php
                  }
								  $L2 = $DB->db_query("SELECT `id`,`name` FROM `".DB_PREFIX."music_styles` WHERE `type` = '{$S->id}' AND `collection` = '0' ORDER BY `orderby`");
								  while ($S2 = $DB->db_object($L2)) {
                  ?>
                  <div class="checkbox">
                   <label>&nbsp;&nbsp;<input type="checkbox" name="styles[]" value="<?php echo $S2->id; ?>"<?php echo (isset($EDIT->id) && in_array($S2->id,$styles) ? ' checked="checked"' : ''); ?>> <?php echo mswSafeDisplay($S2->name); ?></label>
								  </div>
                  <?php
                  }
                  }
								  ?>
								 </div>
								</div>
								<div class="tab-pane fade" id="three">
								 <div class="form-group">
								  <label><?php echo $adlang4[11]; ?></label>
								  <a href="#" onclick="iBox.showURL('?p=new&amp;cover=yes','',{width:850,height:550});return false"><br><br>
								   <img class="cover_art" src="<?php echo (isset($EDIT->coverart) && $EDIT->coverart && file_exists(MM_BASE_PATH.COVER_ART_FOLDER.'/'.$EDIT->coverart) ? REL_PATH.COVER_ART_FOLDER.'/'.$EDIT->coverart : 'templates/images/tempart.png'); ?>" alt="<?php echo (isset($EDIT->name) ? mswSafeDisplay($EDIT->name) : mswSafeDisplay($adlang4[11])); ?>" title="<?php echo (isset($EDIT->name) ? mswSafeDisplay($EDIT->name) : mswSafeDisplay($adlang4[11])); ?>">
								  </a>
								 </div>
								 <span style="display:block;margin-top:10px<?php echo (isset($EDIT->coverart) && !$EDIT->coverart ? ';display:none' : (!isset($EDIT->coverart) ? ';display:none' : '')); ?>" id="clearArt">
								   <i class="fa fa-times fa-fw mm_cursor" onclick="mm_clearCoverArt();jQuery('#clearArt').hide()" title="<?php echo mswSafeDisplay($adlang4[40]); ?>"></i>
								 </span>
                 <div class="form-group" style="margin-top:20px">
                  <label><?php echo $adlang4[67]; ?></label>
								  <input type="text" name="cover-art-other" value="" class="form-control">
                 </div>
                 <div class="coverartother">
								  <?php
								  if (!empty($coveroth)) {
								  foreach ($coveroth AS $cV) {
                  $sha1_c = mswEncrypt($cV);
								  ?>
								  <p id="carto-<?php echo $sha1_c; ?>">
								   <input type="hidden" name="coverother[]" value="<?php echo $cV; ?>">
								   <a href="#" onclick="mm_remCoverOther('<?php echo $sha1_c; ?>');return false"><i class="fa fa-times fa-fw mm_red"></i></a> <?php echo mswSafeDisplay($cV); ?>
								  </p>
								  <?php
								  }
								  }
								  ?>
								 </div>
								</div>
								<div class="tab-pane fade" id="four">
								 <div class="form-group">
								  <label><?php echo $adlang4[9]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="social[disqus]" value="yes"<?php echo (isset($social['disqus']) && $social['disqus']=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="social[disqus]" value="no"<?php echo (isset($social['disqus']) && $social['disqus']=='no' ? ' checked="checked"' : (!isset($EDIT->id) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								  </div>
                  <div class="form-group">
								  <label><?php echo $adlang2[129]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="social[addthis]" value="yes"<?php echo (isset($social['addthis']) && $social['addthis']=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="social[addthis]" value="no"<?php echo (isset($social['addthis']) && $social['addthis']=='no' ? ' checked="checked"' : (!isset($EDIT->id) ? ' checked="checked"' : '')); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								  </div>
								</div>
								<div class="tab-pane fade" id="five">
								 <div class="form-group">
								  <label><?php echo $adlang4[5]; ?></label>
								  <input type="text" name="title" value="<?php echo (isset($EDIT->title) ? mswSafeDisplay($EDIT->title) : ''); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[6]; ?></label>
								  <input type="text" name="metakeys" value="<?php echo (isset($EDIT->metakeys) ? mswSafeDisplay($EDIT->metakeys) : ''); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[7]; ?></label>
								  <input type="text" name="metadesc" value="<?php echo (isset($EDIT->metadesc) ? mswSafeDisplay($EDIT->metadesc) : ''); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[17]; ?></label>
								  <input type="text" name="searchtags" value="<?php echo (isset($EDIT->searchtags) ? mswSafeDisplay($EDIT->searchtags) : ''); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang4[38]; ?></label>
								  <input type="text" name="slug" value="<?php echo (isset($EDIT->slug) ? mswSafeDisplay($EDIT->slug) : ''); ?>" class="form-control" maxlength="50">
								 </div>
								</div>
								<div class="tab-pane fade" id="six">
								<div class="form-group">
								  <label><?php echo $adlang4[55]; ?></label>
								  <input type="text" name="search-related" value="" class="form-control">
								 </div>
								 <div class="related">
								  <?php
								  if (!empty($related)) {
								  $Q  = $DB->db_query("SELECT `id`,`name` FROM `".DB_PREFIX."collections` WHERE `id` IN(".mswSafeString(implode(',',$related),$DB).") ORDER BY FIELD(`id`,".mswSafeString(implode(',',$related),$DB).")");
								  while ($C = $DB->db_object($Q)) {
								  ?>
								  <p id="col-<?php echo $C->id; ?>">
								   <input type="hidden" name="related[]" value="<?php echo $C->id; ?>">
								   <a href="#" onclick="mm_remRelated('<?php echo $C->id; ?>');return false"><i class="fa fa-times fa-fw mm_red"></i></a> <?php echo mswSafeDisplay($C->name); ?>
								  </p>
								  <?php
								  }
								  }
								  ?>
								 </div>
								</div>
							</div>
						</div>
						<div class="panel-footer">
                            <input type="hidden" name="coverart" value="<?php echo (isset($EDIT->coverart) ? $EDIT->coverart : ''); ?>">
							<?php
							if (isset($EDIT->id)) {
							?>
							<input type="hidden" name="edit" value="<?php echo $EDIT->id; ?>">
							<?php
							}
							?>
							<button type="button" class="btn btn-primary" onclick="mm_processor('collections')"><?php echo mswSafeDisplay((isset($EDIT->id) ? $adlang4[14] : $adlang4[0])); ?></button>
                            <button type="button" class="btn btn-link" onclick="mm_windowLoc('collections')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
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
