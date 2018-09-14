<?php if (!defined('PARENT')) { exit; }
if (isset($_GET['sub'])) {
  $subID   = (int) $_GET['sub'];
  $SQL     = 'WHERE `type` = \''.$subID.'\'';
  $Q       = $DB->db_query("SELECT * FROM `".DB_PREFIX."music_styles` WHERE `id` = '{$subID}'");
  $SUB     = $DB->db_object($Q);
  if (!isset($SUB->id)) {
    die('<p style="padding:30px">Style not found, invalid ID</p>');
  }
} else {
  $SQL  = 'WHERE `type` = \'0\'';
}
$FLTR = 'ORDER BY `orderby`';
if (isset($_GET['q']) && $_GET['q']) {
  $sString = mswSafeString($_GET['q'],$DB);
  $SQL     = "WHERE `name` LIKE '%{$sString}%'";
}
if (isset($_GET['f'])) {
  switch ($_GET['f']) {
    case 'name_asc':
      $FLTR  = 'ORDER BY `name`';
      break;
    case 'name_dsc':
      $FLTR  = 'ORDER BY `name` DESC';
      break;
    case 'cols_asc':
      $FLTR  = 'ORDER BY `collectionCount`';
      break;
    case 'cols_dsc':
      $FLTR  = 'ORDER BY `collectionCount` DESC';
      break;
    case 'style_asc':
      $FLTR  = 'ORDER BY `subCount`';
      break;
    case 'style_dsc':
      $FLTR  = 'ORDER BY `subCount` DESC';
      break;
    case 'assoc':
      if ($SQL) {
        $FLTR  = 'AND `collection` > 0 ORDER BY `name`';
      } else {
        $FLTR  = 'WHERE `collection` > 0 ORDER BY `name`';
      }
      break;
  }
}
$filters = array(
 'name_asc' => $adlang5[9],
 'name_dsc' => $adlang5[10],
 'cols_asc' => $adlang5[11],
 'cols_dsc'  => $adlang5[12],
 'style_asc' => $adlang5[17],
 'style_dsc' => $adlang5[18],
 'assoc' => $adlang5[23]
);
if (isset($_GET['sub'])) {
  unset($filters['style_asc'],$filters['style_dsc']);
}
?>
      <div id="wrapper">
        <script>
        //<![CDATA[
		    jQuery(document).ready(function() {
		    // Apply width to prevent shrinkage on drag/drop..
		    jQuery('td').each(function(){
		      jQuery(this).css('width',jQuery(this).width()+'px');
		      });
		      mm_reOrderData('dragArea','style-order');
		    });
		    //]]>
        </script>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <?php
			      // Load top bar and navigation menu..
            include(PATH.'templates/header-top-bar.php');
			      include(PATH.'templates/header-nav-bar.php');
			      ?>
		    </nav>

		    <?php
        $Q  = $DB->db_query("SELECT SQL_CALC_FOUND_ROWS *,
		          (SELECT count(*) FROM `".DB_PREFIX."collection_styles` WHERE `style` = `".DB_PREFIX."music_styles`.`id`) AS `collectionCount`,
              (SELECT count(*) FROM `".DB_PREFIX."music_styles` s1 WHERE `s1`.`type` = `".DB_PREFIX."music_styles`.`id`) AS `subCount`
			        FROM `".DB_PREFIX."music_styles`
              $SQL
              $FLTR
              ");
        $r  = $DB->db_foundrows($Q);
        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                     <span style="float:right">
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('new-style<?php echo (isset($subID) ? '&amp;subID=' . $subID : ''); ?>')" title="<?php echo mswSafeDisplay($adlang5[0]); ?>"><i class="fa fa-plus fa-fw"></i></button>
                      <?php
                      if ($r > 0) {
                      ?>
                      <button type="button" class="btn btn-default btn-sm" title="<?php echo mswSafeDisplay($adlang4[1]); ?>" onclick="jQuery('#mm_searchBox').slideToggle();jQuery('#mm_filters').hide()"><i class="fa fa-search fa-fw"></i></button>
                      <button type="button" class="btn btn-default btn-sm" title="<?php echo mswSafeDisplay($adlang9[94]); ?>" onclick="jQuery('#mm_filters').slideToggle();jQuery('#mm_searchBox').hide()"><i class="fa fa-filter fa-fw"></i></button>
					            <?php
                      }
                      ?>
                     </span>
                     <?php echo (isset($_GET['sub']) ? $adlang5[16].' - '.mswSafeDisplay($SUB->name) : substr($titleBar,0,-2)); ?> (<?php echo @number_format($r); ?>)
                    </h1>
                </div>
            </div>
            <?php
            include(PATH.'templates/search-box.php');
            include(PATH.'templates/filters.php');
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                    <div class="panel-heading">
					   <span style="float:right" class="italics small-font"><i class="fa fa-reorder fa-fw"></i> <?php echo $adlang4[27]; ?></span>
             &nbsp;
					  </div>
                      <div class="panel-body panel-body-padding">
						    <div class="table-responsive" style="margin-bottom:0" id="dragArea">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $adlang5[1]; ?></th>
											                      <th><?php echo $adlang5[7]; ?></th>
                                            <?php
                                            if (!isset($_GET['sub'])) {
                                            ?>
                                            <th><?php echo $adlang5[16]; ?></th>
                                            <?php
                                            }
                                            ?>
                                            <th><?php echo $adlang4[19]; ?></th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									                    <?php
                                        while ($S = $DB->db_object($Q)) {
	                                    ?>
										                   <tr id="styles-<?php echo $S->id; ?>">
                                            <td><?php echo mswSafeDisplay($S->name); ?></td>
                                            <td><?php echo ($S->subCount > 0 ? 'N/A' : @number_format($S->collectionCount)); ?></td>
                                            <?php
                                            if (!isset($_GET['sub'])) {
                                            ?>
                                            <td><?php echo ($S->subCount > 0 ? '<a href="?p=styles&amp;sub='.$S->id.'">'.@number_format($S->subCount).'</a>' : '0'); ?></td>
                                            <?php
                                            }
                                            ?>
                                            <td><?php echo mswGetStatus($S->enabled,$gblang); ?></td>
                                            <td>
                                             <?php
                                             if (!isset($_GET['sub']) && $S->subCount > 0) {
                                             ?>
                                             <a href="?p=styles&amp;sub=<?php echo $S->id; ?>" title="<?php echo mswSafeDisplay($adlang5[19]); ?>"><i class="fa fa-indent fa-fw"></i></a>
                                             <?php
                                             } else {
                                             // Don`t show for subs..
                                             if (!isset($_GET['sub'])) {
                                             ?>
                                             <i class="fa fa-indent fa-fw" style="color:#c0c0c0" title="<?php echo mswSafeDisplay($adlang5[24]); ?>"></i>
                                             <?php
                                             }
                                             }
                                             ?>
                                             <a href="?p=new-style&amp;edit=<?php echo $S->id.(isset($_GET['sub']) ? '&amp;sub='.(int) $_GET['sub'] : ''); ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a>
                                             <a href="#" onclick="mm_del_confirm('styles','<?php echo $S->id; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                      </div>
                    </div>
                    <?php
                    if (!isset($_GET['sub'])) {
                      //$PTION = new pagination(array($r,$gblang[15],$page),'?p='.$_GET['p'].'&amp;next=');
                      //echo $PTION->display();
                    } else {
                    ?>
                    <button type="button" class="btn btn-link" onclick="mm_windowLoc('styles')"><?php echo mswSafeDisplay($adlang5[20]); ?></button>
                    <?php
                    }
                    ?>
				</div>
			</div>
			<?php
			include(PATH.'templates/cp.php');
			?>
        </div>

    </div>
