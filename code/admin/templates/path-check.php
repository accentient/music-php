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
                    <h1 class="page-header">
                     <span style="float:right">
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('paths&amp;check=yes')" title="<?php echo mswSafeDisplay($adlang23[0]); ?>"><i class="fa fa-refresh fa-fw"></i> <?php echo mswSafeDisplay($adlang23[0]); ?></button>
                     </span>
                     <?php echo substr($titleBar,0,-2); ?>
                    </h1>
                </div>
            </div>
            <?php
            if (!isset($_GET['check'])) {
            ?>
            <div class="row">
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-body">
                  <?php
                  echo $adlang23[1];
                  ?>
                  </div>
                </div>
              </div>
            </div>
            <?php
            } else {
            $missing = array(array(),array(),array());
            // MP3 files and previews..
            $Q  = $DB->db_query("SELECT `id`, `mp3file`, `previewfile` FROM `".DB_PREFIX."music`
                  ORDER BY `id`
                  LIMIT " . PATH_CHECK_LIMIT
                  );
            while ($MS = $DB->db_object($Q)) {
              if ($MS->mp3file == '' || !file_exists($SETTINGS->secfolder . '/' . $MS->mp3file)) {
                $missing[0][] = $MS->id;
              }
              if ($MS->previewfile && !file_exists(MM_BASE_PATH . PREVIEW_FOLDER . '/' . $MS->previewfile)) {
                $missing[1][] = $MS->id;
              }
            }
            // Cover art....
            $Q2 = $DB->db_query("SELECT `id`, `coverart` FROM `".DB_PREFIX."collections`
                  WHERE `coverart` != ''
                  ORDER BY `id`
                  LIMIT " . PATH_CHECK_LIMIT
                  );
            while ($ART = $DB->db_object($Q2)) {
              if ($ART->coverart && !file_exists(MM_BASE_PATH . COVER_ART_FOLDER . '/' . $ART->coverart)) {
                $missing[2][] = $ART->id;
              }
            }
            ?>
            <div class="row">
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <i class="fa fa-file-sound-o fa-fw"></i> <?php echo $adlang23[2]; ?> (<?php echo count($missing[0]); ?>)
                  </div>
                  <div class="panel-body">
                  <?php
                  if (!empty($missing[0])) {
                  ?>
                  <div class="table-responsive" style="margin-bottom:0">
                    <table class="table table-striped table-hover">
                      <tbody>
                      <?php
                      $Q  = $DB->db_query("SELECT *,
                            `".DB_PREFIX."music`.`id` AS `musicID`,
                            `".DB_PREFIX."music`.`title` AS `trackTitle`,
                            `".DB_PREFIX."collections`.`id` AS `colID`
                            FROM `".DB_PREFIX."music`
                            LEFT JOIN `".DB_PREFIX."collections`
                            ON `".DB_PREFIX."music`.`collection` = `".DB_PREFIX."collections`.`id`
                            WHERE `".DB_PREFIX."music`.`id` IN(" . implode(',', $missing[0]) . ")
                            ORDER BY `".DB_PREFIX."music`.`title`
                            ");
                      while ($T = $DB->db_object($Q)) {
                      ?>
										  <tr id="tracks-<?php echo $T->id; ?>">
                        <td><?php echo mswSafeDisplay($T->name); ?></td>
                        <td><?php echo mswSafeDisplay($T->trackTitle); ?></td>
                        <td>
                         <a href="?p=new-tracks&edit=<?php echo $T->colID; ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a>
                          <a href="#" onclick="mm_del_confirm('tracks','<?php echo $T->musicID; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
                        </td>
                      </tr>
										  <?php
                      }
                      ?>
                      </tbody>
                    </table>
                  </div>
                  <?php
                  } else {
                    echo $adlang23[5];
                  }
                  ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <i class="fa fa-file-sound-o fa-fw"></i> <?php echo $adlang23[3]; ?> (<?php echo count($missing[1]); ?>)
                  </div>
                  <div class="panel-body">
                  <?php
                  if (!empty($missing[1])) {
                  ?>
                  <div class="table-responsive" style="margin-bottom:0">
                    <table class="table table-striped table-hover">
                      <tbody>
                      <?php
                      $Q  = $DB->db_query("SELECT *,
                            `".DB_PREFIX."music`.`id` AS `musicID`,
                            `".DB_PREFIX."music`.`title` AS `trackTitle`,
                            `".DB_PREFIX."collections`.`id` AS `colID`
                            FROM `".DB_PREFIX."music`
                            LEFT JOIN `".DB_PREFIX."collections`
                            ON `".DB_PREFIX."music`.`collection` = `".DB_PREFIX."collections`.`id`
                            WHERE `".DB_PREFIX."music`.`id` IN(" . implode(',', $missing[1]) . ")
                            ORDER BY `".DB_PREFIX."music`.`title`
                            ");
                      while ($T = $DB->db_object($Q)) {
                      ?>
										  <tr id="tracks-<?php echo $T->id; ?>">
                        <td><?php echo mswSafeDisplay($T->name); ?></td>
                        <td><?php echo mswSafeDisplay($T->trackTitle); ?></td>
                        <td>
                         <a href="?p=new-tracks&edit=<?php echo $T->colID; ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a>
                          <a href="#" onclick="mm_del_confirm('tracks','<?php echo $T->musicID; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
                        </td>
                      </tr>
										  <?php
                      }
                      ?>
                      </tbody>
                    </table>
                  </div>
                  <?php
                  } else {
                    echo $adlang23[5];
                  }
                  ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <i class="fa fa-file-image-o fa-fw"></i> <?php echo $adlang23[4]; ?> (<?php echo count($missing[2]); ?>)
                  </div>
                  <div class="panel-body">
                  <?php
                  if (!empty($missing[2])) {
                  ?>
                  <div class="table-responsive" style="margin-bottom:0">
                    <table class="table table-striped table-hover">
                      <tbody>
                        <?php
                        $Q  = $DB->db_query("SELECT * FROM `".DB_PREFIX."collections`
                              WHERE `id` IN(" . implode(',', $missing[2]) . ")
                              ORDER BY `name`
                              ");
                        while ($COL = $DB->db_object($Q)) {
                        ?>
                        <tr id="collections-<?php echo $COL->id; ?>">
                          <td><?php echo mswSafeDisplay($COL->name); ?></td>
                          <td>
                          <a href="?p=new&amp;edit=<?php echo $COL->id; ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a>
                          <a href="#" onclick="mm_del_confirm('collections','<?php echo $COL->id; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
                          </td>
                        </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <?php
                  } else {
                    echo $adlang23[5];
                  }
                  ?>
                  </div>
                </div>
              </div>
            </div>
            <?php
            }
            include(PATH.'templates/cp.php');
            ?>
      </div>

    </div>
