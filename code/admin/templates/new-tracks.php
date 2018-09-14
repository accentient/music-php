<?php if (!defined('PARENT')) { exit; }
$hideTracks = array();
$ID = (isset($_GET['id']) ? (int)$_GET['id'] : '0');
if ($ID==0 && !isset($_GET['edit'])) {
  die('<p style="padding:30px">Invalid ID</p>');
}
// Edit mode or add mode..
if (isset($_GET['edit'])) {
  $ID          = (int)$_GET['edit'];
  $mp3Edit     = array();
  $Q           = $DB->db_query("SELECT `name` FROM `".DB_PREFIX."collections` WHERE `id` = '{$ID}'");
  $COL         = $DB->db_object($Q);
  if (!isset($COL->name)) {
    die('<p style="padding:30px">Collection not found, invalid ID</p>');
  }
  // Get all tracks..
  $QT          = $DB->db_query("SELECT * FROM `".DB_PREFIX."music` WHERE `collection` = '{$ID}' ORDER BY `order`");
  while ($T = $DB->db_object($QT)) {
    $mp3Edit[] = (array)$T;
  }
  $musicCount  = count($mp3Edit);

} else {
  $folder      = (!isset($_GET['f']) ? basename($SETTINGS->secfolder) : $_GET['f']);
  if (isset($_GET['r'])) {
    $musicFiles  = mswFolderFileScanner($SETTINGS->secfolder,SUPPORTED_MUSIC);
  } else {
    $musicFiles  = mswFolderFileScanner($SETTINGS->secfolder.'/'.$folder,SUPPORTED_MUSIC);
  }
  $musicCount  = count($musicFiles);
  $Q           = $DB->db_query("SELECT `name` FROM `".DB_PREFIX."collections` WHERE `id` = '{$ID}'");
  $COL         = $DB->db_object($Q);
  if (!isset($COL->name)) {
    die('<p style="padding:30px">Collection not found, invalid ID</p>');
  }
  if (READ_MP3_TAGS) {
    include(PATH.'control/classes/GetID3/getid3.php');
    $tags = new getID3();
  }
  // Get all tracks in collection..
  $allTracks = array();
  $Q  = $DB->db_query("SELECT `mp3file` FROM `".DB_PREFIX."music` WHERE `collection` = '{$ID}' ORDER BY `order`");
  while ($T = $DB->db_object($Q)) {
    $allTracks[] = mswSafeDisplay($T->mp3file);
  }
}
?>
      <div id="wrapper">
      <script>
      //<![CDATA[
      function toggleAddCounter(act,id) {
        var c = 0;
        if (id == 'all') {
          jQuery('input[name="addtrack[]"]').each(function(){
            var incr = jQuery(this).attr('value');
            if (jQuery(this).prop('checked') == true) {
              jQuery('#music-tab-' + incr + ' span').attr('class', 'label label-danger');
              jQuery(this).prop('checked', false);
            } else {
              jQuery('#music-tab-' + incr + ' span').attr('class', 'label label-info');
              jQuery(this).prop('checked', true);
            }
          });
        } else {
          switch(act) {
            case true:
              jQuery('#music-tab-'+id+' span').attr('class', 'label label-info');
              break;
            default:
              jQuery('#music-tab-'+id+' span').attr('class', 'label label-danger');
              break;
          }
        }
        jQuery('input[name="addtrack[]"]:checked').each(function(){
          ++c;
        });
        jQuery('.addcounter').html(c);
        jQuery('.h1trackcounter').html(c);
        if (c == 0) {
          jQuery('button[class="btn btn-primary"]').prop('disabled', true);
        } else {
          jQuery('button[class="btn btn-primary"]').prop('disabled', false);
        }
      }
      //]]>
      </script>
	    <?php
      if (AUTO_COMPLETE_ENABLE) {
      ?>
      <script>
		  //<![CDATA[
      jQuery(document).ready(function() {
        jQuery('input[name="previewfile[]"]').autocomplete({
          source: 'index.php?ajax=auto-previews',
          minLength: <?php echo AUTO_COMPLETE_MIN_LENGTH; ?>,
          select: function(event,ui) {
            var inp  = this.id;
            var tkid = inp.substring(11);
            jQuery('#play-'+tkid).attr('href','../<?php echo PREVIEW_FOLDER; ?>/'+ui.item.value);
          }
        });
      });
      //]]>
      </script>
      <?php
      }
      ?>
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
                    <div class="page-header page-header-div">
					<?php
					if (!isset($_GET['edit'])) {
					?>
					<div class="btn-group" style="float:right">
					  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-folder-open-o fa-fw"></i> <?php echo $adlang8[4]; ?> <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu dropdown-menu-right" role="menu" style="max-height:250px;overflow:auto">
			            <?php
                  if (isset($_GET['f']) && $_GET['f']==basename($SETTINGS->secfolder)) {
                  ?>
                  <li><a href="?p=new-tracks&amp;id=<?php echo $ID; ?>&amp;f=<?php echo basename($SETTINGS->secfolder); ?>&amp;r=yes"><b><?php echo basename($SETTINGS->secfolder).' '.$adlang8[30]; ?></b> <i class="fa fa-check fa-fw"></i></a></li>
                  <?php
                  } else {
                  ?>
                  <li><a href="?p=new-tracks&amp;id=<?php echo $ID; ?>&amp;f=<?php echo basename($SETTINGS->secfolder); ?>&amp;r=yes"><?php echo basename($SETTINGS->secfolder).' '.$adlang8[30]; ?></a></li>
                  <?php
                    }
                    $dir = mswFolderScanner($SETTINGS->secfolder);
                    if (is_array($dir)) {
                      sort($dir);
                    }
                    if (!empty($dir)) {
                    foreach ($dir AS $d) {
                    $d   = substr($d,strlen($SETTINGS->secfolder)+1);
                    if (isset($_GET['f']) && $_GET['f']==$d) {
                    ?>
                    <li><a href="?p=new-tracks&amp;id=<?php echo $ID; ?>&amp;f=<?php echo $d; ?>"><b><?php echo $d; ?></b> <i class="fa fa-check fa-fw"></i></a></li>
                    <?php
                    } else {
                    ?>
                    <li><a href="?p=new-tracks&amp;id=<?php echo $ID; ?>&amp;f=<?php echo $d; ?>"><?php echo $d; ?></a></li>
                    <?php
                    }
                    }
                    }
                    ?>
                  </ul>
                </div>
                <?php
                }
                echo (isset($_GET['edit']) ? $adlang8[22] : $adlang8[0]); ?> (<span class="h1trackcounter"><?php echo $musicCount; ?></span>)</div>
                </div>
            </div>
			<?php
			if ($musicCount>0) {
			?>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
							    <?php
								for ($i=1; $i<($musicCount+1); $i++) {
								?>
                                <li id="music-tab-<?php echo $i; ?>"<?php echo ($i=='1' ? ' class="active"' : ''); ?>><a href="#track<?php echo $i; ?>" data-toggle="tab"><span class="label label-info"><?php echo $i; ?></span></a></li>
								<?php
								}
								?>
                            </ul>
                        </div>
                        <div class="panel-body" style="padding-top:0">
                            <div class="tab-content" id="trackArea">
							    <?php
								for ($i=1; $i<($musicCount+1); $i++) {
								$mp3 = '';
								if (isset($_GET['edit'])) {
								  $slot      =  ($i-1);
								  $data      =  array(
								   'EditID'  => $mp3Edit[$slot]['id'],
								   'Title'   => $mp3Edit[$slot]['title'],
								   'Sample'  => $mp3Edit[$slot]['samplerate'],
								   'Bit'     => $mp3Edit[$slot]['bitrate'],
								   'Cost'    => $mp3Edit[$slot]['cost'],
								   'Hrs'     => substr($mp3Edit[$slot]['length'],0,2),
								   'Mins'    => substr($mp3Edit[$slot]['length'],3,2),
								   'Secs'    => substr($mp3Edit[$slot]['length'],6,2),
								   'Preview' => $mp3Edit[$slot]['previewfile'],
								   'File'    => $mp3Edit[$slot]['mp3file']
								  );
								} else {
								  $slot        =  ($i-1);
								  if (READ_MP3_TAGS) {
								    $read      =  $tags->analyze($musicFiles[$slot]);
									  $time      = (isset($read['playtime_string']) ? explode(':',$read['playtime_string']) : array());
									  $data      =  array(
								     'EditID'  => 0,
								     'Title'   => (isset($read['tags']['id3v1']['title'][0]) ? $read['tags']['id3v2']['title'][0] : ''),
								     'Sample'  => (isset($read['audio']['sample_rate']) ? $read['audio']['sample_rate'].$adlang8[18] : ''),
								     'Bit'     => (isset($read['audio']['bitrate']) ? mswBitRate($read['audio']['bitrate']).$adlang8[17] : (isset($read['bitrate']) ? mswBitRate($read['bitrate']).$adlang8[17] : '')),
								     'Cost'    => DEFAULT_TRACK_COST,
								     'Hrs'     => (isset($time[2]) ? $time[0] : '00'),
								     'Mins'    => (isset($time[2]) ? $time[1] : (isset($time[0]) ? $time[0] : '00')),
								     'Secs'    => (isset($time[2]) ? $time[2] : (isset($time[1]) ? $time[1] : '00')),
								     'Preview' => '',
								     'File'    => substr($musicFiles[$slot],strlen($SETTINGS->secfolder)+1)
								    );
								  } else {
								    $data      =  array(
									    'File'    => $musicFiles[$slot]
									  );
                  }
                  if (!empty($allTracks) && in_array((isset($data['File']) ? mswSafeDisplay($data['File']) : ''), $allTracks)) {
                    $hideTracks[] = $i;
                  }
								}
								?>
                                <div class="tab-pane fade<?php echo ($i=='1' ? ' in active' : ''); ?>" id="track<?php echo $i; ?>">
                                <div class="alert alert-info" style="margin-bottom:10px;padding:7px">
                                  <span class="pull-right"><i class="fa fa-music fa-fw"></i> <?php echo (isset($data['File']) ? basename(mswSafeDisplay($data['File'])) : ''); ?></span>
                                  <?php
                                  if (!isset($_GET['edit'])) {
                                  ?>
                                  <input type="checkbox" name="addtrack[]" value="<?php echo $i; ?>" checked="checked" onclick="toggleAddCounter(this.checked,<?php echo $i; ?>)"> <?php echo $adlang8[31]; ?>
                                  <?php
                                  }
                                  ?>
                                  <div class="clearfix"></div>
							                  </div>

                <div class="row">
                                 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

								  <div class="form-group">
								   <label><?php echo $adlang8[7]; ?></label>
								   <input type="hidden" name="mp3file[]" value="<?php echo (isset($data['File']) ? mswSafeDisplay($data['File']) : ''); ?>">
								   <input type="hidden" name="mp3ID[]" value="<?php echo (isset($data['EditID']) ? (int)$data['EditID'] : '0'); ?>">
								   <input type="text" name="title[]" value="<?php echo (isset($data['Title']) ? mswSafeDisplay($data['Title']) : ''); ?>" class="form-control" maxlength="250">
                                  </div>
								  <div class="form-group">
								   <label><?php echo $adlang8[10]; ?></label>
								   <div class="row">
								    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									 <input type="text" name="samplerate[]" value="<?php echo (isset($data['Sample']) ? mswSafeDisplay($data['Sample']) : ''); ?>" class="form-control" maxlength="100">
                                    </div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								     <input type="text" name="bitrate[]" value="<?php echo (isset($data['Bit']) ? mswSafeDisplay($data['Bit']) : ''); ?>" class="form-control" maxlength="100">
                                    </div>
								   </div>
								  </div>
								 </div>
								 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								  <div class="form-group">
								   <label><?php echo $adlang8[11]; ?></label>
								   <input type="text" name="cost[]" value="<?php echo (isset($data['Cost']) ? mswSafeDisplay($data['Cost']) : DEFAULT_TRACK_COST); ?>" class="form-control" maxlength="10">
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang8[16]; ?></label>
								   <div class="row">
								    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
								     <input type="text" name="hrs[]" value="<?php echo (isset($data['Hrs']) ? (strlen($data['Hrs'])==1 && $data['Hrs']<10 ? '0'.mswSafeDisplay($data['Hrs']) : mswSafeDisplay($data['Hrs'])) : '00'); ?>" class="form-control" maxlength="2">
								    </div>
								    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
								     <input type="text" name="mins[]" value="<?php echo (isset($data['Mins']) ? (strlen($data['Mins'])==1 && $data['Mins']<10 ? '0'.mswSafeDisplay($data['Mins']) : mswSafeDisplay($data['Mins'])) : '00'); ?>" class="form-control" maxlength="2">
								    </div>
								    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
								     <input type="text" name="secs[]" value="<?php echo (isset($data['Secs']) ? (strlen($data['Secs'])==1 && $data['Secs']<10 ? '0'.mswSafeDisplay($data['Secs']) : mswSafeDisplay($data['Secs'])) : '00'); ?>" class="form-control" maxlength="2">
                                    </div>
								   </div>
								  </div>
								 </div>
								</div>
								<div class="form-group">
								 <label><?php echo $adlang8[8]; ?></label>
								 <div class="form-group input-group">
								  <span class="input-group-addon"><a href="#" onclick="mm_changePlayState('<?php echo $i; ?>','tracks','<?php echo (isset($data['Preview']) && $data['Preview'] ? '../'.PREVIEW_FOLDER.'/'.str_replace("'","\'",mswSafeDisplay($data['Preview'])) : ''); ?>');return false" id="play-<?php echo $i; ?>" class="sm2_button"><i class="fa fa-play fa-fw" title="<?php echo mswSafeDisplay($adlang8[19]); ?>"></i></a></span>
								  <input type="text" name="previewfile[]" id="prev_music_<?php echo $i; ?>" value="<?php echo (isset($data['Preview']) ? mswSafeDisplay($data['Preview']) : ''); ?>" class="form-control">
								 </div>
								</div>
								</div>
								<?php
								}
								?>
							</div>
							<?php
							if (!isset($_GET['edit'])) {
							?>
							<div class="tab-content clearcolfirst">
							 <input type="checkbox" name="clear" value="yes"> <?php echo str_replace('{collection}',mswSafeDisplay($COL->name),$adlang8[15]); ?>
							</div>
							<?php
							}
							?>
						</div>
						<div class="panel-footer">
						    <?php
                if (isset($_GET['edit'])) {
                ?>
                <input type="hidden" name="update-tracks" value="yes">
                <?php
                }
                ?>
                <input type="hidden" name="collection" value="<?php echo $ID; ?>">
                <button type="button" class="btn btn-primary" onclick="mm_processor('<?php echo (isset($_GET['edit']) ? 'edit-tracks' : 'tracks'); ?>')"><?php echo str_replace('{count}',$musicCount,(isset($_GET['edit']) ? $adlang8[27] : $adlang8[6])); ?></button>
                <button type="button" class="btn btn-link" onclick="mm_windowLoc('tracks&amp;id=<?php echo $ID; ?>')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
                <span class="actionMsg"></span>
						</div>
					</div>
				</div>
			</div>
			<?php
			} else {
			?>
			<div class="row">
			 <p class="nothing"><?php echo $adlang8[5]; ?></p>
			</div>
			<?php
			}
			include(PATH.'templates/cp.php');
			?>
        </div>
		</form>

    </div>

	  <?php
    if (!empty($hideTracks)) {
      ?>
      <script>
      //<![CDATA[
      jQuery(document).ready(function() {
        <?php
        foreach ($hideTracks AS $hT) {
        ?>
        jQuery('#music-tab-<?php echo $hT; ?> span').attr('class', 'label label-primary').html('<i class="fa fa-check fa-fw"></i>');
        var file = jQuery('#track<?php echo $hT; ?> input[name="mp3file[]"]').val();
        jQuery('#track<?php echo $hT; ?>').html('<div class="trackexists"><i class="fa fa-check fa-fw"></i><br><br><b><?php echo str_replace("'","\'",$adlang8[32]); ?></b><br><br>' + file + '<input type="hidden" name="previewfile[]" value=""><input type="hidden" name="secs[]" value=""><input type="hidden" name="mins[]" value=""><input type="hidden" name="hrs[]" value=""><input type="hidden" name="cost[]" value=""><input type="hidden" name="bitrate[]" value=""><input type="hidden" name="samplerate[]" value=""><input type="hidden" name="title[]" value=""><input type="hidden" name="addtrack[]" value="0"><input type="hidden" name="mp3ID[]" value="0"><input type="hidden" name="mp3file[]" value=""></div>');
        <?php
        }
        ?>
        var cboxesload = 0;
        jQuery('ul[class="nav nav-tabs"] li span[class="label label-info"]').each(function() {
          ++cboxesload;
        });
        if (cboxesload == 0) {
          jQuery('.clearcolfirst').hide();
          jQuery('button[class="btn btn-primary"]').prop('disabled', true);
          jQuery('.addcounter').html('0');
        } else {
          var orboxes = parseInt(jQuery('.addcounter').html());
          jQuery('.addcounter').html(parseInt(orboxes - <?php echo count($hideTracks); ?>));
        }
      });
      //]]>
      </script>
      <?php
    }

    if (isset($_GET['ok'])) {
    ?>
    <script>
    //<![CDATA[
    jQuery(document).ready(function() {
	    jQuery('span[class="actionMsg"]').html(mm_actioned('<?php echo str_replace('{count}',(int)$_GET['ok'],$adlang8[14]); ?>'));
    });
    //]]>
    </script>
    <?php
    }
    ?>
