<?php if (!defined('PARENT')) { exit; }
$SQL = '';
if (isset($_GET['q']) && $_GET['q']) {
  $sString = mswSafeString($_GET['q'],$DB);
  $SQL     = "WHERE `name` LIKE '%{$sString}%' OR `title` LIKE '%{$sString}%' OR `keys` LIKE '%{$sString}%' OR `desc` LIKE '%{$sString}%' OR `info` LIKE '%{$sString}%'";
}
?>
      <div id="wrapper">
        <script>
        //<![CDATA[
		jQuery(document).ready(function() {
		  // Apply width to prevent shrinkage on drag/drop..
		  jQuery('td').each(function(){
		    jQuery(this).css('width',jQuery(this).width()+'px');
		  });
		  mm_reOrderData('dragArea','page-order');
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

        <?php
        $Q  = $DB->db_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".DB_PREFIX."pages` $SQL ORDER BY `orderby` LIMIT $limit,".PER_PAGE);
        $r  = $DB->db_foundrows($Q);
        ?>

		<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                     <span style="float:right">
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('new-page')" title="<?php echo mswSafeDisplay($adlang12[0]); ?>"><i class="fa fa-plus fa-fw"></i></button>
                      <?php
                      if ($r > 0) {
                      ?>
                      <button type="button" class="btn btn-default btn-sm" title="<?php echo mswSafeDisplay($adlang4[1]); ?>" onclick="jQuery('#mm_searchBox').slideToggle()"><i class="fa fa-search fa-fw"></i></button>
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
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                    <?php
                      if ($r > 0) {
                      ?>
                      <div class="panel-heading">
					   <span style="float:right" class="italics small-font"><i class="fa fa-reorder fa-fw"></i> <?php echo $adlang4[27]; ?></span>
					   &nbsp;
					  </div>
            <?php
            }
            ?>
					  <div class="panel-body panel-body-padding">
						    <div class="table-responsive" style="margin-bottom:0" id="dragArea">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $adlang12[2]; ?></th>
                                            <th><?php echo $adlang12[4]; ?></th>
                                            <th><?php echo $adlang12[3]; ?></th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        while ($P = $DB->db_object($Q)) {
	                                    ?>
										<tr id="pages-<?php echo $P->id; ?>">
                                            <td><?php echo mswSafeDisplay($P->name); ?></td>
                                            <td><?php echo ($P->landing=='yes' ? $gblang[7] : $gblang[8]); ?></td>
                                            <td><?php echo mswGetStatus($P->enabled,$gblang); ?></td>
                                            <td>
											 <a href="<?php echo $SETTINGS->httppath; ?>?pg=<?php echo $P->id; ?>" title="<?php echo mswSafeDisplay($adlang12[18]); ?>" onclick="window.open(this);return false"><i class="fa fa-search fa-fw"></i></a>
											 <a href="?p=new-page&amp;edit=<?php echo $P->id; ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a>
											 <a href="#" onclick="mm_del_confirm('pages','<?php echo $P->id; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
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
