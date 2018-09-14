<?php if (!defined('PARENT')) { exit; }
$currentYear = date('Y');
$SQL         = 'WHERE `enabled` = \'yes\'';
if (isset($_GET['q']) && strlen($_GET['q'])==4) {
  $filterYear  = (int) $_GET['q'];
  if (@checkdate(12, 31, $filterYear)) {
    $currentYear = $filterYear;
  }
  if (isset($_GET['country']) && (int) $_GET['country'] > 0) {
    if (isset($_GET['pref']) && in_array($_GET['pref'],array('tangible','digital'))) {
      switch($_GET['pref']) {
        case 'tangible':
          $SQL .= ' AND `taxCountry` = \''.(int) $_GET['country'].'\'';
          break;
        case 'digital':
          $SQL .= ' AND `taxCountry2` = \''.(int) $_GET['country'].'\'';
          break;
      }
    }
  }
}
$SQL .= ' AND YEAR(FROM_UNIXTIME(`ts`)) = \''.$currentYear.'\'';
?>
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
                       <button type="button" class="btn btn-default btn-sm" title="<?php echo mswSafeDisplay($adlang4[1]); ?>" onclick="jQuery('#mm_searchBox').slideToggle();jQuery('#mm_filters').hide()"><i class="fa fa-search fa-fw"></i></button>
                     </span>
                     <?php echo substr($titleBar,0,-2). ' ('.mswSafeDisplay($currentYear).')'; ?>
                    </h1>
                </div>
            </div>
            <?php
            include(PATH.'templates/search-box-revenue.php');
            ?>
            <form method="post" action="?p=<?php echo $_GET['p']; ?>">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-body panel-body-padding">
						                <div class="table-responsive" style="margin-bottom:0">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $adlang20[0]; ?></th>
                                            <th><?php echo $adlang20[12]; ?></th>
                                            <th><?php echo $adlang20[1]; ?></th>
                                            <th><?php echo $adlang20[2]; ?></th>
                                            <th><?php echo $adlang20[3]; ?></th>
                                            <th><?php echo $adlang20[4]; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach (range(0,11) AS $months) {
                                        $thisMonth = ($months + 1);
                                        $Q  = $DB->db_query("SELECT
                                              ROUND(SUM(`paytotal`), 2) AS `pTotal`,
                                              ROUND(SUM(`subtotal`), 2) AS `sTotal`,
                                              ROUND(SUM(`tax`), 2) AS `tTotal`,
                                              ROUND(SUM(`tax2`), 2) AS `tTotal2`,
                                              ROUND(SUM(`shipping`), 2) AS `shTotal`
                                              FROM `".DB_PREFIX."sales`
                                              $SQL
                                              AND MONTH(FROM_UNIXTIME(`ts`)) = '{$thisMonth}'
                                              ");
                                        $RV = $DB->db_object($Q)
                                        ?>
                                        <tr>
                                            <td><?php echo $gbdates[1][$months].' '.$currentYear; ?></td>
                                            <td><?php echo mswCurrencyFormat($RV->sTotal,$SETTINGS->curdisplay); ?></td>
                                            <td><?php echo mswCurrencyFormat($RV->shTotal,$SETTINGS->curdisplay); ?></td>
                                            <td><?php echo mswCurrencyFormat($RV->tTotal,$SETTINGS->curdisplay); ?></td>
                                            <td><?php echo mswCurrencyFormat($RV->tTotal2,$SETTINGS->curdisplay); ?></td>
                                            <td><?php echo mswCurrencyFormat($RV->pTotal,$SETTINGS->curdisplay); ?></td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
									                  </tbody>
                                </table>
                            </div>
                      </div>
                      <div class="panel-footer">
                       <input type="hidden" name="q" value="<?php echo (isset($_GET['q']) ? (int) $_GET['q'] : $currentYear); ?>">
                       <input type="hidden" name="country" value="<?php echo (isset($_GET['country']) ? (int) $_GET['country'] : '0'); ?>">
                       <input type="hidden" name="pref" value="<?php echo (isset($_GET['pref']) && in_array($_GET['pref'],array('tangible','digital')) ? $_GET['country'] : ''); ?>">
                       <button type="submit" class="btn btn-primary"><i class="fa fa-save fa-fw"></i> <?php echo $adlang20[11]; ?></button>
                       <span class="actionMsg"></span>
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
