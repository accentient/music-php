<?php if (!defined('PARENT')) { exit; } ?>
      <div id="wrapper">

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <?php
			// Load top bar and navigation menu..
            include(PATH.'templates/header-top-bar.php');
			include(PATH.'templates/header-nav-bar.php');
			?>
		</nav>

        <?php
        $Q  = $DB->db_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".DB_PREFIX."offers` ORDER BY `id` LIMIT $limit,".PER_PAGE);
        $r  = $DB->db_foundrows($Q);
        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
					 <span style="float:right">
					  <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('new-offer')" title="<?php echo mswSafeDisplay($adlang11[0]); ?>"><i class="fa fa-plus fa-fw"></i></button>
					 </span>
					 <?php echo substr($titleBar,0,-2); ?> (<?php echo @number_format($r); ?>)
					</h1>
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
                                            <th><?php echo $adlang11[1]; ?></th>
                                            <th><?php echo $adlang11[3]; ?></th>
                                            <th><?php echo $adlang11[2]; ?></th>
                                            <th><?php echo $adlang11[8]; ?></th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									                      <?php
                                        while ($O = $DB->db_object($Q)) {
	                                      ?>
										                    <tr id="offers-<?php echo $O->id; ?>">
                                            <td><?php echo mswSafeDisplay($O->discount); ?></td>
                                            <td><?php echo ($O->type=='all' ? $adlang11[7] : ($O->type=='collections' ? $adlang11[5] : ($O->type=='tracks' ? $adlang11[6] : $adlang11[4]))); ?></td>
                                            <td><?php echo ($O->expiry>0 ? $DT->dateTimeDisplay($O->expiry,$SETTINGS->dateformat) : $gblang[39]); ?></td>
                                            <td><?php echo mswGetStatus($O->enabled,$gblang); ?></td>
                                            <td>
                                             <a href="?p=new-offer&amp;edit=<?php echo $O->id; ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a>
                                             <a href="#" onclick="mm_del_confirm('offers','<?php echo $O->id; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
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
