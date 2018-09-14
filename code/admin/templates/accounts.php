<?php if (!defined('PARENT')) { exit; }
$SQL        = '';
$FLTR       = 'ORDER BY `name`';
if (isset($_GET['q']) && $_GET['q']) {
  $sString = mswSafeString($_GET['q'],$DB);
  $SQL     = "WHERE `name` LIKE '%{$sString}%' OR `email` LIKE '%{$sString}%'";
}
if (isset($_GET['f'])) {
  switch ($_GET['f']) {
    case 'name_asc':
     $FLTR  = 'ORDER BY `name`';
    break;
    case 'name_dsc':
     $FLTR  = 'ORDER BY `name` DESC';
    break;
    case 'email_asc':
     $FLTR  = 'ORDER BY `email`';
    break;
    case 'email_dsc':
     $FLTR  = 'ORDER BY `email` DESC';
    break;
    case 'date_asc':
     $FLTR  = 'ORDER BY `ts`';
    break;
    case 'date_dsc':
     $FLTR  = 'ORDER BY `ts` DESC';
    break;
  }
}
$filters = array(
 'name_asc'   => $adlang6[32],
 'name_dsc'   => $adlang6[33],
 'email_asc'  => $adlang6[34],
 'email_dsc'  => $adlang6[35],
 'date_asc'   => $adlang6[36],
 'date_dsc'   => $adlang6[37]
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
        $Q  = $DB->db_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".DB_PREFIX."accounts` $SQL $FLTR LIMIT $limit,".PER_PAGE);
        $r  = $DB->db_foundrows($Q);
        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                     <span style="float:right">
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('new-account')" title="<?php echo mswSafeDisplay($adlang6[0]); ?>"><i class="fa fa-plus fa-fw"></i></button>
                      <?php
                      if ($r > 0) {
                      ?>
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('export-accounts')" title="<?php echo mswSafeDisplay($adlang6[1]); ?>"><i class="fa fa-save fa-fw"></i></button>
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('mailer')" title="<?php echo mswSafeDisplay($adlang13[0]); ?>"><i class="fa fa-envelope-o fa-fw"></i></button>
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
											 <a href="?p=login-history&amp;id=<?php echo $A->id; ?>" title="<?php echo mswSafeDisplay($adlang21[0]); ?>"><i class="fa fa-history fa-fw"></i></a>
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
