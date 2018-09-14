<?php if (!defined('PARENT') || !isset($_GET['id'])) { exit; }
define('CALBOX','from|to');
include(PATH.'templates/date-picker.php');
$SQL    = '';
$fromTo = array('','');
$ID     = (int)$_GET['id'];
$Q      = $DB->db_query("SELECT * FROM `".DB_PREFIX."accounts` WHERE `id` = '{$ID}'");
$ACC    = $DB->db_object($Q);
if (!isset($ACC->id)) {
  die('<p style="padding:30px">Account not found, invalid ID</p>');
}
if (isset($_GET['f'],$_GET['t'])) {
  if ($_GET['f'] && $_GET['t']) {
    $from = $DT->dateToTS($_GET['f']);
    $to   = $DT->dateToTS($_GET['t']);
    if ($from > 0 && $to > 0) {
      $fromTo[0] = $_GET['f'];
      $fromTo[1] = $_GET['t'];
      $SQL       = 'AND (DATE(FROM_UNIXTIME(`ts`)) BETWEEN \'' . date('Y-m-d', $from) . '\' AND \'' . date('Y-m-d', $to) . '\')';
    }
  }
}
include(REL_PATH . 'control/classes/class.ip.php');
$IPGEO           = new geoIP();
$IPGEO->settings = $SETTINGS;
?>
      <div id="wrapper">
        <script>
        //<![CDATA[
        function mm_clearAll() {
          var confirmSub = confirm('<?php echo str_replace("'", "\'",$jslang[21]); ?>');
          if (confirmSub) {
            window.location = 'index.php?p=login-history&clearall=<?php echo $ID; ?>';
          } else {
            return false;
          }
        }
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
        $Q  = $DB->db_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".DB_PREFIX."accounts_login` WHERE `account` = '{$ID}' $SQL ORDER BY `id` DESC LIMIT $limit,".PER_PAGE);
        $r  = $DB->db_foundrows($Q);
        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                     <span style="float:right">
                      <?php
                      if ($r > 0) {
                      ?>
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('login-history&amp;id=<?php echo $ID; ?>&amp;export=<?php echo $ID.($fromTo[0] ? '&amp;f='.$fromTo[0] : '').($fromTo[1] ? '&amp;t='.$fromTo[1] : ''); ?>')" title="<?php echo mswSafeDisplay($adlang21[1]); ?>"><i class="fa fa-save fa-fw"></i></button>
                      <button type="button" class="btn btn-default btn-sm" title="<?php echo mswSafeDisplay($adlang4[1]); ?>" onclick="jQuery('#mm_searchBox').slideToggle()"><i class="fa fa-calendar fa-fw"></i></button>
                      <button type="button" class="btn btn-danger btn-sm" onclick="mm_clearAll()" title="<?php echo mswSafeDisplay($adlang21[1]); ?>"><i class="fa fa-times fa-fw"></i></button>
                      <?php
                      }
                      ?>
                     </span>
                     <?php echo substr($titleBar,0,-2); ?>: <?php echo mswSafeDisplay($ACC->name); ?> (<?php echo @number_format($r); ?>)
                    </h1>
                </div>
            </div>
      <?php
      include(PATH.'templates/search-box-acc-login.php');
      ?>
      <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-body panel-body-padding">
						    <div class="table-responsive" style="margin-bottom:0">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $adlang21[2]; ?></th>
                                            <th><?php echo $adlang21[3]; ?></th>
                                            <th><?php echo $adlang21[4]; ?></th>
											                      <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    while ($H = $DB->db_object($Q)) {
	                                   $ipData = array(
                                       'iso' => $H->iso,
                                       'conly' => 'no',
                                       'path' => 'templates/images/flags/',
                                       'ip' => $H->ip,
                                       'country' => $H->country,
                                       'unknown' => $gblang[19],
                                       'showip' => 'no'
                                     );
                                    ?>
										                  <tr id="loghistory-<?php echo $H->id; ?>">
                                            <td><?php echo $H->ip; ?></td>
                                            <td><?php echo $IPGEO->flag($ipData); ?>&nbsp;&nbsp;<?php echo mswSafeDisplay($H->country); ?></td>
                                            <td><?php echo $DT->dateTimeDisplay($H->ts,$SETTINGS->dateformat); ?> @ <?php echo $DT->dateTimeDisplay($H->ts,$SETTINGS->timeformat); ?></td>
                                            <td>
                                             <a href="#" onclick="mm_del_confirm('loghistory','<?php echo $H->id; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
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
					<button type="button" class="btn btn-link" onclick="mm_windowLoc('accounts')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
					<?php
          $PTION = new pagination(array($r,$gblang[15],$page),'?p='.$_GET['p'].'&amp;id='.$ID.'&amp;next=');
          echo $PTION->display();
          ?>
				</div>
			</div>
			<?php
			include(PATH.'templates/cp.php');
			?>
      </div>

    </div>
