<?php if (!defined('PARENT')) { exit; }
$SQL  = '';
$FLTR = 'ORDER BY `name`';
if (isset($_GET['q']) && $_GET['q']) {
  $sString = mswSafeString($_GET['q'],$DB);
  $SQL     = "WHERE `name` LIKE '%{$sString}%' OR `title` LIKE '%{$sString}%' OR `metakeys` LIKE '%{$sString}%' OR `metadesc` LIKE '%{$sString}%' OR `information` LIKE '%{$sString}%' OR `searchtags` LIKE '%{$sString}%'";
}
if (isset($_GET['f'])) {
  switch ($_GET['f']) {
    case 'name_asc':
     $FLTR  = 'ORDER BY `name`';
    break;
    case 'name_dsc':
     $FLTR  = 'ORDER BY `name` DESC';
    break;
    case 'tracks_asc':
     $FLTR  = 'ORDER BY `trackCount`';
    break;
    case 'tracks_dsc':
     $FLTR  = 'ORDER BY `trackCount` DESC';
    break;
    case 'cost_asc':
     $FLTR  = 'ORDER BY `cost`*1000';
    break;
    case 'cost_dsc':
     $FLTR  = 'ORDER BY `cost`*1000 DESC';
    break;
    case 'cd_asc':
     $FLTR  = 'ORDER BY `costcd`*1000';
    break;
    case 'cd_dsc':
     $FLTR  = 'ORDER BY `costcd`*1000 DESC';
    break;
  }
}
$filters = array(
 'name_asc'    => $adlang4[59],
 'name_dsc'    => $adlang4[60],
 'tracks_asc'  => $adlang4[61],
 'tracks_dsc'  => $adlang4[62],
 'cost_asc'    => $adlang4[63],
 'cost_dsc'    => $adlang4[64],
 'cd_asc'      => $adlang4[65],
 'cd_dsc'      => $adlang4[66]
);
?>
      <div id="wrapper">

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <?php
            // Load top bar and navigation menu..
            include(PATH.'templates/header-top-bar.php');
            include(PATH.'templates/header-nav-bar.php');
			      ?>
        </nav>

        <?php
        $Q  = $DB->db_query("SELECT SQL_CALC_FOUND_ROWS *,
		          (SELECT count(*) FROM `".DB_PREFIX."music` WHERE `collection` = `".DB_PREFIX."collections`.`id`) AS `trackCount`
			        FROM `".DB_PREFIX."collections` $SQL
              $FLTR
              LIMIT $limit,".PER_PAGE);
        $r  = $DB->db_foundrows($Q);
        ?>

		    <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                     <span style="float:right">
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('new')" title="<?php echo mswSafeDisplay($adlang4[0]); ?>"><i class="fa fa-plus fa-fw"></i></button>
                      <?php
                      if ($r > 0) {
                      ?>
                      <button type="button" class="btn btn-success btn-sm" onclick="iBox.showURL('?p=collections&amp;clipBoard=view','',{width:700,height:500});return false" title="<?php echo mswSafeDisplay($adlang4[45]); ?>"><i class="fa fa-shopping-cart fa-fw"></i></button>
                      <button type="button" class="btn btn-default btn-sm" title="<?php echo mswSafeDisplay($adlang4[1]); ?>" onclick="jQuery('#mm_searchBox').slideToggle();jQuery('#mm_filters').hide()"><i class="fa fa-search fa-fw"></i></button>
                      <button type="button" class="btn btn-default btn-sm" title="<?php echo mswSafeDisplay($adlang9[94]); ?>" onclick="jQuery('#mm_filters').slideToggle();jQuery('#mm_searchBox').hide()"><i class="fa fa-filter fa-fw"></i></button>
					            <?php
                      }
                      ?>
                     </span>
                     <?php echo substr($titleBar,0,-2); ?> (<?php echo @number_format($r); ?>)
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
                      <div class="panel-body panel-body-padding">
						              <div class="table-responsive" style="margin-bottom:0">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $adlang4[2]; ?></th>
                                            <th><?php echo $adlang4[18]; ?></th>
                                            <th><?php echo $adlang4[44]; ?></th>
                                            <?php
                                            if ($SETTINGS->cdpur=='yes') {
                                            ?>
                                            <th><?php echo $adlang4[10]; ?></th>
                                            <?php
                                            }
                                            ?>
                                            <th><?php echo $adlang4[19]; ?></th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    while ($C = $DB->db_object($Q)) {
	                                  ?>
								                    <tr id="collections-<?php echo $C->id; ?>">
                                        <td><?php echo mswSafeDisplay($C->name); ?></td>
                                        <td><?php echo @number_format($C->trackCount); ?></td>
                                        <td><?php echo ($C->cost!='' ? ($C->cost>0 ? mswCurrencyFormat($C->cost,$SETTINGS->curdisplay) : $adlang4[58]) : '--'); ?></td>
                                        <?php
                                        if ($SETTINGS->cdpur=='yes') {
                                        ?>
                                        <td><?php echo ($C->costcd!='' ? ($C->costcd>0 ? mswCurrencyFormat($C->costcd,$SETTINGS->curdisplay) : $adlang4[58]) : '--'); ?></td>
                                        <?php
                                        }
                                        ?>
                                        <td><?php echo mswGetStatus($C->enabled,$gblang); ?></td>
                                        <td>
                                        <a href="#" onclick="iBox.showURL('?p=collections&amp;clipBoard=c<?php echo $C->id; ?>','',{width:700,height:500});return false" title="<?php echo mswSafeDisplay($adlang4[30]); ?>" style="margin-right:10px"><i class="fa fa-cart-plus fa-fw mm_black mm_cursor"></i></a>
                                        <a href="?p=tracks&amp;id=<?php echo $C->id; ?>" title="<?php echo mswSafeDisplay($adlang4[22]); ?>"><i class="fa fa-music fa-fw"></i></a>
                                        <a href="?p=new&amp;edit=<?php echo $C->id; ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a>
                                        <a href="#" onclick="mm_del_confirm('collections','<?php echo $C->id; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
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
                    $PTION = new pagination(array($r,$gblang[15],$page),'?p='.$_GET['p'].'&amp;next=');
                    echo $PTION->display();
                    ?>
              </div>
            </div>
            <?php
            include(PATH.'templates/cp.php');
            ?>
        </div>

    </div>
