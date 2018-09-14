<?php if (!defined('PARENT')) { exit; }
define('CALBOX','from|to');
include(PATH.'templates/date-picker.php');
$fromTo = array('','');
$SQL    = "WHERE `".DB_PREFIX."sales`.`enabled` = 'yes'";
$FLTR   = 'ORDER BY `'.DB_PREFIX.'sales`.`id` DESC';
if (isset($_GET['q']) && $_GET['q']) {
  $sString  = mswSafeString($_GET['q'],$DB);
  $SQL     .= "AND (`".DB_PREFIX."accounts`.`name` LIKE '%{$sString}%' OR `".DB_PREFIX."sales`.`invoice` LIKE '%{$sString}%')";
}
if (isset($_GET['st'])) {
  $SQL .= "AND `status` = '".mswSafeString(urldecode($_GET['st']),$DB)."'";
} else {
  $SQL .= "AND `status` = 'Completed'";
}
if (isset($_GET['fr'],$_GET['to'])) {
  if ($_GET['fr'] && $_GET['to']) {
    $from = $DT->dateToTS($_GET['fr']);
    $to   = $DT->dateToTS($_GET['to']);
    if ($from > 0 && $to > 0) {
      $fromTo[0] = $_GET['fr'];
      $fromTo[1] = $_GET['to'];
      $SQL      .= 'AND (DATE(FROM_UNIXTIME(`'.DB_PREFIX.'sales`.`ts`)) BETWEEN \'' . date('Y-m-d', $from) . '\' AND \'' . date('Y-m-d', $to) . '\')';
    }
  }
}
if (isset($_GET['f'])) {
  switch ($_GET['f']) {
    case 'name_asc':
     $FLTR  = 'ORDER BY `name`';
    break;
    case 'name_dsc':
     $FLTR  = 'ORDER BY `name` DESC';
    break;
    case 'date_asc':
     $FLTR  = 'ORDER BY `'.DB_PREFIX.'sales`.`ts`';
    break;
    case 'date_dsc':
     $FLTR  = 'ORDER BY `'.DB_PREFIX.'sales`.`ts` DESC';
    break;
    case 'cost_asc':
     $FLTR  = 'ORDER BY ROUND(`saleTotal`-`couponValue`,2)*1000';
    break;
    case 'cost_dsc':
     $FLTR  = 'ORDER BY ROUND(`saleTotal`-`couponValue`,2)*1000 DESC';
    break;
    case 'track_asc':
     $FLTR  = 'ORDER BY `trackCount`';
    break;
    case 'track_dsc':
     $FLTR  = 'ORDER BY `trackCount` DESC';
    break;
    case 'col_asc':
     $FLTR  = 'ORDER BY `collectionCount`';
    break;
    case 'col_dsc':
     $FLTR  = 'ORDER BY `collectionCount` DESC';
    break;
  }
}
$filters = array(
 'name_asc'  => $adlang9[8],
 'name_dsc'  => $adlang9[9],
 'date_asc'  => $adlang9[4],
 'date_dsc'  => $adlang9[5],
 'cost_asc'  => $adlang9[6],
 'cost_dsc'  => $adlang9[7],
 'track_asc' => $adlang9[95],
 'track_dsc' => $adlang9[96],
 'col_asc'   => $adlang9[97],
 'col_dsc'   => $adlang9[98]
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
		          (SELECT count(*) FROM `".DB_PREFIX."sales_items` WHERE `sale` = `".DB_PREFIX."sales`.`id` AND `type` = 'track') AS `trackCount`,
              (SELECT count(*) FROM `".DB_PREFIX."sales_items` WHERE `sale` = `".DB_PREFIX."sales`.`id` AND `type` = 'collection') AS `collectionCount`,
              (SELECT ROUND(SUM(`cost`),2) FROM `".DB_PREFIX."sales_items` WHERE `sale` = `".DB_PREFIX."sales`.`id`) AS `saleTotal`,
              (SELECT IF (SUBSTRING_INDEX(SUBSTRING_INDEX(`coupon`,';',4),':',-1) IS NULL,
               '0.00',
               REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(`coupon`,';',4),':',-1),'\"','')
               )
              ) AS `couponValue`,
              (SELECT IF (SUBSTRING_INDEX(SUBSTRING_INDEX(`coupon`,';',2),':',-1) IS NULL,
               '#',
               REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(`coupon`,';',2),':',-1),'\"','')
               )
              ) AS `couponCode`,
              `".DB_PREFIX."sales`.`id` AS `saleID`,
              `".DB_PREFIX."sales`.`shipping` AS `saleShipping`,
              `".DB_PREFIX."sales`.`ts` AS `saleTS`
              FROM `".DB_PREFIX."sales`
              LEFT JOIN `".DB_PREFIX."accounts`
              ON  `".DB_PREFIX."accounts`.`id` = `".DB_PREFIX."sales`.`account`
              $SQL
              $FLTR
              LIMIT $limit,".PER_PAGE);
        $r  = $DB->db_foundrows($Q);
        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                     <span style="float:right">
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('new-sale&amp;st=<?php echo urlencode($_GET['st']); ?>')" title="<?php echo mswSafeDisplay($adlang9[0]); ?>"><i class="fa fa-plus fa-fw"></i></button>
                      <?php
                      if ($r > 0) {
                      ?>
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('export-sales&amp;st=<?php echo urlencode($_GET['st']).($fromTo[0] ? '&amp;fr='.$fromTo[0] : '').($fromTo[1] ? '&amp;to='.$fromTo[1] : ''); ?>')" title="<?php echo mswSafeDisplay($adlang9[11]); ?>"><i class="fa fa-save fa-fw"></i></button>
                      <button type="button" class="btn btn-default btn-sm" title="<?php echo mswSafeDisplay($adlang4[1]); ?>" onclick="jQuery('#mm_searchBox').slideToggle();jQuery('#mm_filters').hide()"><i class="fa fa-search fa-fw"></i></button>
                      <button type="button" class="btn btn-default btn-sm" title="<?php echo mswSafeDisplay($adlang9[94]); ?>" onclick="jQuery('#mm_filters').slideToggle();jQuery('#mm_searchBox').hide()"><i class="fa fa-filter fa-fw"></i></button>
                      <?php
                      }
                      ?>
                     </span>
                     <?php echo substr($titleBar,0,-2).(isset($_GET['st']) ? ': '.($_GET['st'] ? mswSafeDisplay(urldecode($_GET['st'])) : '<span class="no-status-flag">'.$gblang[47].'</span>') : ''); ?> (<?php echo @number_format($r); ?>)
                    </h1>
                </div>
            </div>
            <?php
            include(PATH.'templates/search-box-sales.php');
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
                                            <th><?php echo $adlang9[71]; ?></th>
                                            <th><?php echo $adlang9[21]; ?></th>
                                            <th><?php echo $adlang9[12]; ?></th>
                                            <th><?php echo $adlang9[13]; ?></th>
                                            <th><?php echo $adlang9[25]; ?></th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									                      <?php
                                        while ($S = $DB->db_object($Q)) {
                                        $cpn   = ($S->couponValue > 0 ? $S->couponValue : '0.00');
                                        $tot   = ($S->saleTotal>0 ? $S->saleTotal : '0.00');
                                        $tot   = mswFormatPrice($tot-$cpn);
                                        $tots  = ($S->saleShipping>0 ? $S->saleShipping : '0.00');
                                        $totv  = ($S->tax>0 ? $S->tax : '0.00');
                                        $totv2 = ($S->tax2>0 ? $S->tax2 : '0.00');
                                        $taxT  = mswFormatPrice($totv+$totv2);
                                        ?>
                                        <tr id="sales-<?php echo $S->saleID; ?>">
                                          <td><a href="?p=invoice&amp;id=<?php echo $S->saleID; ?>" title="<?php echo mswSafeDisplay($adlang9[20]); ?>" onclick="window.open(this);return false"><?php echo mswSaleInvoiceNumber($S->invoice); ?></a></td>
                                          <td><?php echo mswSafeDisplay($S->name); ?></td>
                                          <td><?php echo @number_format($S->collectionCount); ?> / <?php echo @number_format($S->trackCount); ?></td>
                                          <td><?php echo $DT->dateTimeDisplay($S->saleTS,$SETTINGS->dateformat); ?></td>
                                          <td><?php echo mswCurrencyFormat(($tot+$tots+$taxT),$SETTINGS->curdisplay); ?></td>
                                          <td>
                                          <a href="#" onclick="mm_lockSalePage('<?php echo $S->saleID; ?>');return false" title="<?php echo mswSafeDisplay($adlang9[30]); ?>"><i id="lock-<?php echo $S->saleID; ?>" <?php echo ($S->locked=='no' ? 'class="fa fa-unlock-alt fa-fw mm_green"' : 'class="fa fa-lock fa-fw mm_red"'); ?>></i></a>
                                          <a href="?p=new-sale&amp;edit=<?php echo $S->saleID.(isset($_GET['st']) ? '&amp;st='.mswSafeDisplay(urlencode($_GET['st'])) : ''); ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a>
                                          <a href="#" onclick="mm_del_confirm('sales','<?php echo $S->saleID; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
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
