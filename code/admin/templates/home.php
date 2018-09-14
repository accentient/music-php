<?php if (!defined('PARENT')) { exit; }
$SQL   = "WHERE `".DB_PREFIX."sales`.`enabled` = 'yes'";
$FLTR  = 'ORDER BY `'.DB_PREFIX.'sales`.`id` DESC';
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
              `".DB_PREFIX."sales`.`id` AS `saleID`,
              `".DB_PREFIX."sales`.`shipping` AS `saleShipping`,
              `".DB_PREFIX."sales`.`ts` AS `saleTS`
              FROM `".DB_PREFIX."sales`
              LEFT JOIN `".DB_PREFIX."accounts`
              ON  `".DB_PREFIX."accounts`.`id` = `".DB_PREFIX."sales`.`account`
              $SQL
              $FLTR
              LIMIT ".ADMIN_HOME_LATEST_SALES);
              ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo str_replace('{latest}',ADMIN_HOME_LATEST_SALES,$adlang14[0]); ?></h1>
                </div>
            </div>
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
								                      $cpn  = ($S->couponValue > 0 ? $S->couponValue : '0.00');
                                      $tot  = ($S->saleTotal>0 ? $S->saleTotal : '0.00');
                                      $tot  = mswFormatPrice($tot-$cpn);
                                      $tots = ($S->saleShipping>0 ? $S->saleShipping : '0.00');
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
				</div>
			</div>

			<?php
			$Q  = $DB->db_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".DB_PREFIX."accounts` ORDER BY `id` DESC LIMIT ".ADMIN_HOME_LATEST_ACCOUNTS);
			?>

			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top:10px"><?php echo str_replace('{latest}',ADMIN_HOME_LATEST_ACCOUNTS,$adlang14[1]); ?></h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-body panel-body-padding">
						    <div class="table-responsive" style="margin-bottom:0">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $adlang6[2]; ?></th>
                                            <th><?php echo $adlang6[3]; ?></th>
                                            <th><?php echo $adlang6[4]; ?></th>
											<th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									    <?php
                                        while ($A = $DB->db_object($Q)) {
	                                    ?>
										<tr id="accounts-<?php echo $A->id; ?>">
                                            <td><?php echo mswSafeDisplay($A->name); ?></td>
                                            <td><?php echo mswSafeDisplay($A->email); ?></td>
                                            <td><?php echo mswGetStatus($A->enabled,$gblang); ?></td>
											<td>
											 <a href="?p=history&amp;id=<?php echo $A->id; ?>" title="<?php echo mswSafeDisplay($adlang6[5]); ?>"><i class="fa fa-clock-o fa-fw"></i></a>
											 <a href="?p=new-account&amp;edit=<?php echo $A->id; ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a>
											 <a href="#" onclick="mm_del_confirm('accounts','<?php echo $A->id; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
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
				</div>
			</div>
			<?php
			include(PATH.'templates/cp.php');
			?>
        </div>

    </div>
