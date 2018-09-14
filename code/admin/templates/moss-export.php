<?php if (!defined('PARENT')) { exit; }
define('CALBOX','from|to');
include(PATH.'templates/date-picker.php');
$fromTo = array('','');
$dispFT = array('','');
$SQL    = '';
if (isset($_GET['fr'],$_GET['to'])) {
  if ($_GET['fr'] && $_GET['to']) {
    $from = $DT->dateToTS($_GET['fr']);
    $to   = $DT->dateToTS($_GET['to']);
    if ($from > 0 && $to > 0) {
      $fromTo[0] = $_GET['fr'];
      $fromTo[1] = $_GET['to'];
      $dispFT[0] = $DT->dateTimeDisplay($from,$SETTINGS->dateformat);
      $dispFT[1] = $DT->dateTimeDisplay($to,$SETTINGS->dateformat);
      $SQL      .= ' AND (DATE(FROM_UNIXTIME(`'.DB_PREFIX.'sales`.`ts`)) BETWEEN \'' . date('Y-m-d', $from) . '\' AND \'' . date('Y-m-d', $to) . '\')';
    }
  }
}
if ($fromTo[0] == '') {
  $fr        = strtotime(date('Y-m') . '-01');
  $to        = strtotime(date('Y-m') . '-' . date('t'));
  $fromTo[0] = date(str_replace(array('dd','mm','yy'),array('d','m','y'),$DT->jsFormat()), $fr);
  $fromTo[1] = date(str_replace(array('dd','mm','yy'),array('d','m','y'),$DT->jsFormat()), $to);
  $SQL      .= ' AND (DATE(FROM_UNIXTIME(`'.DB_PREFIX.'sales`.`ts`)) BETWEEN \'' . date('Y-m-d', $fr) . '\' AND \'' . date('Y-m-d', $to) . '\')';
  $dispFT[0] = $DT->dateTimeDisplay($fr,$SETTINGS->dateformat);
  $dispFT[1] = $DT->dateTimeDisplay($to,$SETTINGS->dateformat);
}
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
        $moss = array();
        $Q  = $DB->db_query("SELECT *,
              `" . DB_PREFIX . "countries`.`name` AS `countryName`,
              `" . DB_PREFIX . "sales`.`id` AS `saleID`
              FROM `" . DB_PREFIX . "sales`
              LEFT JOIN `" . DB_PREFIX . "countries`
              ON  `" . DB_PREFIX . "countries`.`id` = `" . DB_PREFIX . "sales`.`taxCountry2`
              WHERE `" . DB_PREFIX . "sales`.`enabled` = 'yes'
              AND `" . DB_PREFIX . "sales`.`status` = 'Completed'
              AND `" . DB_PREFIX . "sales`.`taxCountry2` > 0
              AND `" . DB_PREFIX . "sales`.`tax2` > 0
              AND `" . DB_PREFIX . "countries`.`eu` = 'yes'
              $SQL
              ORDER BY `" . DB_PREFIX . "countries`.`name`
              ");
        while ($CALC = $DB->db_object($Q)) {
          if (!isset($moss[$CALC->taxCountry2 . '-' . $CALC->taxRate2])) {
            $moss[$CALC->taxCountry2 . '-' . $CALC->taxRate2]    = array();
            $moss[$CALC->taxCountry2 . '-' . $CALC->taxRate2][0] = $CALC->countryName;
          }
          $moss[$CALC->taxCountry2 . '-' . $CALC->taxRate2][1][] = $CALC->saleID;
        }
        ?>

		    <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                     <span style="float:right">
                      <button type="button" class="btn btn-default btn-sm" title="<?php echo mswSafeDisplay($adlang4[1]); ?>" onclick="jQuery('#mm_searchBox').slideToggle()"><i class="fa fa-search fa-fw"></i></button>
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('moss&amp;export=yes<?php echo ($fromTo[0] ? '&amp;fr='.$fromTo[0] : '').($fromTo[1] ? '&amp;to='.$fromTo[1] : ''); ?>')" title="<?php echo mswSafeDisplay($adlang9[11]); ?>"><i class="fa fa-save fa-fw"></i></button>
                     </span>
                     <?php echo substr($titleBar,0,-2) . ' (' . $adlang24[5] . ')'; ?>
                    </h1>
                </div>
            </div>
            <?php
            include(PATH.'templates/search-box-moss.php');
            ?>
            <div class="alert alert-info">
              <i class="fa fa-calendar fa-fw mm_cursor" onclick="jQuery('#mm_searchBox').slideToggle()"></i> <?php echo $dispFT[0]; ?> - <?php echo $dispFT[1]; ?>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-body panel-body-padding">
						                <div class="table-responsive" style="margin-bottom:0">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $adlang24[0]; ?></th>
                                            <th><?php echo $adlang24[1]; ?></th>
                                            <th><?php echo $adlang24[3]; ?></th>
                                            <th><?php echo $adlang24[2]; ?></th>
                                            <th><?php echo $adlang24[4]; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($moss)) {
                                      foreach (array_keys($moss) AS $mossKey) {
                                        if (!empty($moss[$mossKey][1])) {
                                          $split = explode('-', $mossKey);
                                          $Q     = $DB->db_query("SELECT ROUND(SUM(`cost`), 2) AS `sumSale`
                                                   FROM `" . DB_PREFIX . "sales_items`
                                                   WHERE `sale` IN(" . implode(',', $moss[$mossKey][1]) . ")
                                                   AND `physical` = 'no'
                                                   ");
                                          $TL    = $DB->db_object($Q);
                                          $total = (isset($TL->sumSale) ? $TL->sumSale : '0.00');
                                          $sum   = @number_format(($split[1] * $total) / 100, 2, '.', '');
                                          $cost  = @number_format(($total + $sum), 2, '.', '');
                                          ?>
                                          <tr>
                                            <td><?php echo $moss[$mossKey][0]; ?></td>
                                            <td><?php echo mswCurrencyFormat($cost,$SETTINGS->curdisplay); ?></td>
                                            <td><?php echo $split[1]; ?>%</td>
                                            <td><?php echo mswCurrencyFormat($total,$SETTINGS->curdisplay); ?></td>
                                            <td><?php echo mswCurrencyFormat($sum,$SETTINGS->curdisplay); ?></td>
                                          </tr>
                                          <?php
                                        }
                                      }
									                  } else {
                                    ?>
                                    <tr><td colspan="5"><?php echo $adlang24[7]; ?></td></tr>
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
