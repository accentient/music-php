<?php if (!defined('PARENT')) { exit; }
$FLTR    = 'ORDER BY `'.DB_PREFIX.'coupons`.`id`';
if (isset($_GET['f'])) {
  switch ($_GET['f']) {
    case 'disc_asc':
     $FLTR  = "ORDER BY (IF(SUBSTR(`discount`,-1)='%',SUBSTR(`discount` FROM 1 FOR LENGTH(`discount`)-1),`discount`)*1000)";
    break;
    case 'disc_dsc':
     $FLTR  = "ORDER BY (IF(SUBSTR(`discount`,-1)='%',SUBSTR(`discount` FROM 1 FOR LENGTH(`discount`)-1),`discount`)*1000) DESC";
    break;
    case 'code_asc':
     $FLTR  = 'ORDER BY `code`';
    break;
    case 'code_dsc':
     $FLTR  = 'ORDER BY `code` DESC';
    break;
    case 'exp_asc':
     $FLTR  = 'ORDER BY `expiry`';
    break;
    case 'exp_dsc':
     $FLTR  = 'ORDER BY `expiry` DESC';
    break;
    case 'usg_asc':
     $FLTR  = 'ORDER BY `couponUsage`';
    break;
    case 'usg_dsc':
     $FLTR  = 'ORDER BY `couponUsage` DESC';
    break;
  }
}
$filters = array(
 'disc_asc'  => $adlang16[10],
 'disc_dsc'  => $adlang16[11],
 'code_asc'  => $adlang16[12],
 'code_dsc'  => $adlang16[13],
 'exp_asc'   => $adlang16[14],
 'exp_dsc'   => $adlang16[15],
 'usg_asc'   => $adlang16[17],
 'usg_dsc'   => $adlang16[16]
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
              (SELECT count(*) FROM `".DB_PREFIX."sales`
               WHERE REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(`".DB_PREFIX."sales`.`coupon`,';',2),':',-1),'\"','') = `".DB_PREFIX."coupons`.`code`
               AND `enabled` = 'yes'
              ) AS `couponUsage`
              FROM `".DB_PREFIX."coupons`
              $FLTR
              LIMIT $limit,".PER_PAGE);
        $r  = $DB->db_foundrows($Q);
        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                     <span style="float:right">
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('new-coupon')" title="<?php echo mswSafeDisplay($adlang16[0]); ?>"><i class="fa fa-plus fa-fw"></i></button>
                      <?php
                      if ($r > 0) {
                      ?>
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
                                            <th><?php echo $adlang16[1]; ?></th>
                                            <th><?php echo $adlang16[3]; ?></th>
                                            <th><?php echo $adlang16[2]; ?></th>
                                            <th><?php echo $adlang16[9]; ?></th>
                                            <th><?php echo $adlang16[4]; ?></th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									                  <?php
                                    while ($C = $DB->db_object($Q)) {
	                                  ?>
                                    <tr id="coupons-<?php echo $C->id; ?>">
                                            <td><?php echo mswSafeDisplay($C->discount); ?></td>
                                            <td><?php echo mswSafeDisplay($C->code); ?></td>
                                            <td><?php echo ($C->expiry>0 ? $DT->dateTimeDisplay($C->expiry,$SETTINGS->dateformat) : $gblang[39]); ?></td>
                                            <td><?php echo ($C->couponUsage>0 ? '<a href="#" onclick="iBox.showURL(\'?p=coupons&amp;code='.$C->code.'\',\'\',{width:750,height:400});return false">'.$C->couponUsage.'</a>' : '0'); ?></td>
                                            <td><?php echo mswGetStatus($C->enabled,$gblang); ?></td>
                                            <td>
                                             <a href="?p=new-coupon&amp;edit=<?php echo $C->id; ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a>
                                             <a href="#" onclick="mm_del_confirm('coupons','<?php echo $C->id; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
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
