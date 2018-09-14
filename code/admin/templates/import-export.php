<?php if (!defined('PARENT')) { exit; } ?>
      <div id="wrapper">

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <?php
			      // Load top bar and navigation menu..
            include(PATH.'templates/header-top-bar.php');
            include(PATH.'templates/header-nav-bar.php');
            ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $adlang1[34]; ?></h1>
                </div>
            </div>

            <form method="post" action="index.php?ajax=impexp-col" enctype="multipart/form-data" id="icol">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                         <i class="fa fa-upload fa-fw"></i> <?php echo $adlang22[0]; ?>
                        </div>
                        <div class="panel-body">
                         <div class="form-group">
                           <label><?php echo $adlang22[10]; ?></label>
                           <input type="file" name="csv">
                         </div>
                         <div class="form-group" style="height:250px;overflow:auto;border:1px dashed #ddd;padding:10px">
                          <?php
                          $L = $DB->db_query("SELECT `id`,`name`,
                               (SELECT count(*) FROM `".DB_PREFIX."music_styles` s1 WHERE `s1`.`type` = `".DB_PREFIX."music_styles`.`id`) AS `subCount`
                               FROM `".DB_PREFIX."music_styles` WHERE `type` = '0' ORDER BY `orderby`");
                          while ($S = $DB->db_object($L)) {
                          if ($S->subCount > 0) {
                          ?>
                          <b><?php echo mswSafeDisplay($S->name); ?></b><br>
                          <?php
                          } else {
                          ?>
                          <div class="checkbox">
                           <label><input type="checkbox" name="styles[]" value="<?php echo $S->id; ?>"> <?php echo mswSafeDisplay($S->name); ?></label>
                          </div>
                          <?php
                          }
                          $L2 = $DB->db_query("SELECT `id`,`name` FROM `".DB_PREFIX."music_styles` WHERE `type` = '{$S->id}' ORDER BY `orderby`");
                          while ($S2 = $DB->db_object($L2)) {
                          ?>
                          <div class="checkbox">
                            <label>&nbsp;&nbsp;<input type="checkbox" name="styles[]" value="<?php echo $S2->id; ?>"><?php echo mswSafeDisplay($S2->name); ?></label>
                          </div>
                          <?php
                          }
                          }
                          ?>
                         </div>
                         <div class="form-group">
                          <label><?php echo $adlang4[9]; ?></label>
                          <div class="radio">
                            <label><input type="radio" name="social[disqus]" value="yes"> <?php echo $gblang[7]; ?></label>
                           </div>
                           <div class="radio">
                            <label><input type="radio" name="social[disqus]" value="no" checked="checked"> <?php echo $gblang[8]; ?></label>
                           </div>
                          </div>
                          <div class="form-group">
                          <label><?php echo $adlang2[129]; ?></label>
                          <div class="radio">
                            <label><input type="radio" name="social[addthis]" value="yes"> <?php echo $gblang[7]; ?></label>
                           </div>
                           <div class="radio">
                            <label><input type="radio" name="social[addthis]" value="no" checked="checked"> <?php echo $gblang[8]; ?></label>
                           </div>
                          </div>
                         <div class="form-group">
                           <div class="checkbox">
                            <label><input type="checkbox" name="clear" value="yes"> <?php echo $adlang22[7]; ?></label>
                           </div>
                         </div>
                        </div>
                        <div class="panel-footer">
                          <button type="submit" class="btn btn-primary" onclick="mm_processorFileUpload('icol','actionMsg1')"><?php echo mswSafeDisplay($adlang22[2]); ?></button>
                          <span class="actionMsg1"></span>
					              </div>
                    </div>
				        </div>
            </div>
            </form>

            <form method="post" action="index.php?ajax=impexp-music" enctype="multipart/form-data" id="imusic">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                         <i class="fa fa-upload fa-fw"></i> <?php echo $adlang22[1]; ?>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                           <label><?php echo $adlang22[10]; ?></label>
                           <input type="file" name="csv">
                          </div>
                          <div class="form-group">
                           <label><?php echo $adlang22[8]; ?></label>
                           <select name="collection" class="form-control">
                           <option value="0">- - - - - - -</option>
                           <?php
                           $Q = $DB->db_query("SELECT `id`,`name` FROM `".DB_PREFIX."collections` WHERE `enabled` = 'yes' ORDER BY `name`");
                           while ($C = $DB->db_object($Q)) {
                           ?>
                           <option value="<?php echo $C->id; ?>"><?php echo mswSafeDisplay($C->name); ?></option>
                           <?php
                           }
                           ?>
                           </select>
                          </div>
                          <div class="form-group">
                           <div class="checkbox">
                            <label><input type="checkbox" name="clear" value="yes"> <?php echo $adlang22[9]; ?></label>
                           </div>
                          </div>
                        </div>
                        <div class="panel-footer">
                          <button type="submit" class="btn btn-primary" onclick="mm_processorFileUpload('imusic','actionMsg2')"><?php echo mswSafeDisplay($adlang22[2]); ?></button>
                          <span class="actionMsg2"></span>
						            </div>
					          </div>
				        </div>
            </div>
            </form>

            <form method="post" action="index.php?p=impexp">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                         <i class="fa fa-download fa-fw"></i> <?php echo $adlang22[3]; ?>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                           <div class="radio">
                            <label><input type="radio" name="type" value="col" checked="checked"> <?php echo $adlang22[4]; ?></label>
                           </div>
                           <div class="radio">
                            <label><input type="radio" name="type" value="music"> <?php echo $adlang22[5]; ?></label>
                           </div>
                          </div>
                        </div>
                        <div class="panel-footer">
                          <input type="hidden" name="export" value="yes">
                          <button type="submit" class="btn btn-primary"><?php echo mswSafeDisplay($adlang22[6]); ?></button>
                        </div>
					          </div>
				        </div>
            </div>
            </form>

            <?php
            include(PATH.'templates/cp.php');
            ?>
        </div>

    </div>
