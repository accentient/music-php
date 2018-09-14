<?php if (!defined('PARENT')) { exit; }
$SQL        = '';
$FLTR       = 'ORDER BY `name`';
if (isset($_GET['q']) && $_GET['q']) {
  $sString = mswSafeString($_GET['q'],$DB);
  $SQL     = "WHERE `name` LIKE '%{$sString}%' OR `iso` LIKE '%{$sString}%' OR `iso2` LIKE '%{$sString}%' OR `iso4217` LIKE '%{$sString}%'";
}
if (isset($_GET['f'])) {
  switch ($_GET['f']) {
    case 'eu_n':
     $FLTR  = "WHERE `eu` = 'no'";
    break;
    case 'eu_y':
     $FLTR  = "WHERE `eu` = 'yes'";
    break;
  }
}
$filters = array(
 'eu_n'  => $adlang18[18],
 'eu_y'  => $adlang18[19]
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
        $Q  = $DB->db_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".DB_PREFIX."countries` $SQL $FLTR LIMIT $limit,".PER_PAGE);
        $r  = $DB->db_foundrows($Q);
        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                     <span style="float:right">
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('new-country')" title="<?php echo mswSafeDisplay($adlang18[6]); ?>"><i class="fa fa-plus fa-fw"></i></button>
                      <?php
                      if ($r > 0) {
                      ?>
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
                                            <th><?php echo $adlang18[0]; ?></th>
                                            <th><?php echo $adlang18[1]; ?></th>
                                            <th><?php echo $adlang18[2]; ?></th>
                                            <th><?php echo $adlang18[3]; ?></th>
                                            <th><?php echo $adlang18[4]; ?></th>
                                            <th><?php echo $adlang18[17]; ?></th>
                                            <th><?php echo $adlang18[5]; ?></th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									                  <?php
                                    while ($C = $DB->db_object($Q)) {
                                    $img = '';
                                    if (file_exists(PATH.'templates/images/flags/'.strtolower($C->iso2).'.png')) {
                                      $img = 'templates/images/flags/'.strtolower($C->iso2).'.png';
                                    }
                                    $taxes = array($gblang[49],$gblang[49]);
                                    // Tangible..
                                    if ($C->tax!='no') {
                                      if ($C->tax=='' || $C->tax==0) {
                                        $taxes[0] = $SETTINGS->deftax.'%';
                                      } else {
                                        $taxes[0] = $C->tax.'%';
                                      }
                                    }
                                    // Digital..
                                    if ($C->tax2!='no') {
                                      if ($C->tax2=='' || $C->tax2==0) {
                                        $taxes[1] = $SETTINGS->deftax2.'%';
                                      } else {
                                        $taxes[1] = $C->tax2.'%';
                                      }
                                    }
                                    ?>
                                        <tr id="countries-<?php echo $C->id; ?>">
                                            <?php
                                            if ($img) {
                                            ?>
                                            <td><span style="background:url(<?php echo $img; ?>) no-repeat 2% 50%;padding-left:25px"><?php echo mswSafeDisplay($C->name); ?></td>
                                            <?php
                                            } else {
                                            ?>
                                            <td><span style="background:url(templates/images/flags/none.png) no-repeat 2% 50%;padding-left:25px"><?php echo mswSafeDisplay($C->name); ?></span></td>
                                            <?php
                                            }
                                            ?>
                                            <td><?php echo mswSafeDisplay($C->iso2); ?></td>
                                            <td><?php echo mswSafeDisplay($C->iso); ?></td>
                                            <td><?php echo mswSafeDisplay($C->iso4217); ?></td>
                                            <td><?php echo str_replace(array('{tax}','{tax2}'),array($taxes[0],$taxes[1]),$adlang18[21]); ?></td>
                                            <td><?php echo ($C->eu=='yes' ? $gblang[7] : $gblang[8]); ?></td>
                                            <td><?php echo mswGetStatus($C->display,$gblang); ?></td>
                                            <td>
                                             <a href="?p=new-country&amp;edit=<?php echo $C->id; ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a>
                                             <a href="#" onclick="mm_del_confirm('countries','<?php echo $C->id; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
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
