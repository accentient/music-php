<?php if (!defined('PARENT')) { exit; }
$gset  = ($SETTINGS->statistics ? unserialize($SETTINGS->statistics) : array());
//------------------------------
// DEFAULTS
//------------------------------
$defy1 = date('Y',strtotime('-1 year'));
$defy2 = date('Y');
$defm  = date('m-Y');
$defb  = 10;
$defl  = 12;
if (isset($gset['years'])) {
  $chop = explode(',',$gset['years']);
  if (isset($chop[0],$chop[1])) {
    $defy1 = (int) $chop[0];
    $defy2 = (int) $chop[1];
  }
}
if (isset($gset['month'])) {
  switch($gset['month']) {
    case 'this':
    $defm = date('m-Y');
    break;
    case 'last':
    $defm = date('m-Y',strtotime('-1 month'));
    break;
  }
}
if (isset($gset['best']) && $gset['best'] > 0) {
  $defb = (int) $gset['best'];
}
if (isset($gset['legacy']) && $gset['legacy'] > 0) {
  $defl = (int) $gset['legacy'];
}
//------------------------------
?>
      <div id="wrapper">
        <script src="templates/js/mm-stats.js"></script>
        <script>
        //<![CDATA[
        jQuery(document).ready(function() {
          jQuery('input[name="y1"]').keyup(function () {
            this.value = this.value.replace(/[^0-9\.]/g,'');
          });
          jQuery('input[name="y2"]').keyup(function () {
            this.value = this.value.replace(/[^0-9\.]/g,'');
          });
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

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                     <span style="float:right">
                      <button type="button" class="btn btn-success btn-sm" onclick="iBox.showURL('?p=statistics&amp;settings=view','',{width:450,height:400});return false" title="<?php echo mswSafeDisplay($adlang19[10]); ?>"><i class="fa fa-cog fa-fw"></i></button>
                     </span>
                     <?php echo substr($titleBar,0,-2); ?>
                    </h1>
                </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $adlang19[0]; ?>
                            <div class="pull-right">
                              <button class="btn btn-default btn-xs" type="button" onclick="showReloadYearArea()">
                                <?php echo $adlang19[8]; ?>
                              </button>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row year_reload_area">
                              <div class="col-lg-1 col-md-1 col-sm-0 col-xs-0">&nbsp;</div>
                              <div class="col-lg-1 col-md-1 col-sm-0 col-xs-0">&nbsp;</div>
                              <div class="col-lg-1 col-md-1 col-sm-0 col-xs-0">&nbsp;</div>
                              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                <input type="text" name="y1" value="<?php echo $defy1; ?>" class="form-control">
                              </div>
                              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                <input type="text" name="y2" value="<?php echo $defy2; ?>" class="form-control">
                              </div>
                              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                <button type="button" class="btn btn-info" onclick="statsYearly('yes');return false"><?php echo mswSafeDisplay($adlang19[9]); ?></button>
                              </div>
                              <div class="col-lg-1 col-md-1 col-sm-0 col-xs-0">&nbsp;</div>
                              <div class="col-lg-1 col-md-1 col-sm-0 col-xs-0">&nbsp;</div>
                              <div class="col-lg-1 col-md-1 col-sm-0 col-xs-0">&nbsp;</div>
                            </div>
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-yearly"></div>
                            </div>
                        </div>
                    </div>
              </div>
              <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $adlang19[2]; ?>
                            <div class="pull-right">
                              <div class="btn-group">
                                <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" type="button">
                                  <?php echo $adlang19[5]; ?>
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu" id="mth">
                                 <?php
                                 for ($i=0; $i<$defl; $i++) {
                                 $check = '';
                                 $m = date('m',strtotime('-'.$i.' month'.($i>1 || $i==0 ? 's' : '')));
                                 $y = date('Y',strtotime('-'.$i.' month'.($i>1 || $i==0 ? 's' : '')));
                                 $d = (date('n',strtotime('-'.$i.' month'.($i>1 || $i==0 ? 's' : '')))-1);
                                 switch($i) {
                                   case '0':
                                     $t = $adlang19[14];
                                     $f = 'last';
                                     break;
                                   case '1':
                                     $t = $adlang19[15];
                                     $f = 'this';
                                     break;
                                   default:
                                     $t = $gbdates[0][$d].' '.$y;
                                     $f = $m.'_'.$y;
                                     break;
                                 }
                                 if ($defm==$m.'-'.$y) {
                                   $check = '<i class="fa fa-check fa-fw"></i>';
                                 }
                                 ?>
                                 <li><a href="#" onclick="statsMonthly('<?php echo $m.'-'.$y; ?>','yes');return false" id="mth_<?php echo $f; ?>"><?php echo $t.' '.$check; ?></a></li>
                                 <?php
                                 }
                                 ?>
                                </ul>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-month"></div>
                            </div>
                        </div>
                    </div>
              </div>
              <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $adlang19[1]; ?>
                            <div class="pull-right">
                              <div class="btn-group">
                                <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" type="button">
                                  <?php echo $adlang19[5]; ?>
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu" id="mthr">
                                 <?php
                                 for ($i=0; $i<$defl; $i++) {
                                 $check = '';
                                 $m = date('m',strtotime('-'.$i.' month'.($i>1 || $i==0 ? 's' : '')));
                                 $y = date('Y',strtotime('-'.$i.' month'.($i>1 || $i==0 ? 's' : '')));
                                 $d = (date('n',strtotime('-'.$i.' month'.($i>1 || $i==0 ? 's' : '')))-1);
                                 switch($i) {
                                   case '0':
                                     $t = $adlang19[14];
                                     $f = 'last';
                                     break;
                                   case '1':
                                     $t = $adlang19[15];
                                     $f = 'this';
                                     break;
                                   default:
                                     $t = $gbdates[0][$d].' '.$y;
                                     $f = $m.'_'.$y;
                                     break;
                                 }
                                 if ($defm==$m.'-'.$y) {
                                   $check = '<i class="fa fa-check fa-fw"></i>';
                                 }
                                 ?>
                                 <li><a href="#" onclick="statsRevenue('<?php echo $m.'-'.$y; ?>','yes');return false" id="mthr_<?php echo $f; ?>"><?php echo $t.' '.$check; ?></a></li>
                                 <?php
                                 }
                                 ?>
                                </ul>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-revenue"></div>
                            </div>
                        </div>
                    </div>
              </div>
              <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $adlang19[3]; ?>
                            <div class="pull-right">
                              <div class="btn-group">
                                <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" type="button">
                                  <?php echo $adlang19[5]; ?>
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu" id="cldd">
                                 <?php
                                 foreach ($adlang19[7] AS $v) {
                                 $check = '';
                                 if ($v==$defb) {
                                   $check = '<i class="fa fa-check fa-fw"></i>';
                                 }
                                 ?>
                                 <li><a href="#" onclick="statsTopCollections('<?php echo $v; ?>','yes');return false" id="cldd_<?php echo $v; ?>"><?php echo $v.' '.$check; ?></a></li>
                                 <?php
                                 }
                                 ?>
                                </ul>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body">
                          <div class="stats-top-ten" id="stats-collections"></div>
                        </div>
                    </div>
              </div>
              <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $adlang19[4]; ?>
                            <div class="pull-right">
                              <div class="btn-group">
                                <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" type="button">
                                  <?php echo $adlang19[5]; ?>
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu" id="tkdd">
                                 <?php
                                 foreach ($adlang19[7] AS $v) {
                                 $check = '';
                                 if ($v==$defb) {
                                   $check = '<i class="fa fa-check fa-fw"></i>';
                                 }
                                 ?>
                                 <li><a href="#" onclick="statsTopTracks('<?php echo $v; ?>','yes');return false" id="tkdd_<?php echo $v; ?>"><?php echo $v.' '.$check; ?></a></li>
                                 <?php
                                 }
                                 ?>
                                </ul>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body">
                          <div class="stats-top-ten" id="stats-tracks"></div>
                        </div>
                    </div>
              </div>
              <?php
              // Only show if geo functions are enabled, if disabled, not accurate
              if ($SETTINGS->geoip=='yes') {
              ?>
              <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $adlang19[18]; ?>
                        </div>
                        <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content piearea" id="flot-countries"></div>
                            </div>
                        </div>
                    </div>
              </div>
              <?php
              }
              ?>
              <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $adlang19[19]; ?>
                        </div>
                        <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content piearea" id="flot-gateway"></div>
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
      <?php
      // If no sales, stats don`t run..
      if ($DB->db_rowcount('sales WHERE `enabled` = \'yes\'') > 0) {
      ?>
      <script>
      //<![CDATA[
      jQuery(document).ready(function() {
        setTimeout(function() {
          statsYearly('no');
          statsMonthly('<?php echo $defm; ?>','no');
          statsRevenue('<?php echo $defm; ?>','no');
          statsTopTracks(<?php echo $defb; ?>,'no');
          statsTopCollections(<?php echo $defb; ?>,'no');
          <?php
          if ($SETTINGS->geoip=='yes') {
          ?>
          statsCountries('no');
          <?php
          }
          ?>
          statsGateway('no');
        }, 1500);
      });
      //]]>
      </script>
      <?php
      }
      ?>
      <div id="tooltip"></div>