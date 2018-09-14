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
        $Q  = $DB->db_query("SELECT * FROM `".DB_PREFIX."gateways` ORDER BY `display`");
        $r  = $DB->db_rows($Q);
        ?>

		    <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                     <span style="float:right">
                      <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('new-gateway')" title="<?php echo mswSafeDisplay($adlang3[8]); ?>"><i class="fa fa-plus fa-fw"></i></button>
                     </span>
                     <?php echo substr($titleBar,0,-2); ?> (<?php echo $r; ?>)
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
                                            <th>&nbsp;</th>
                                            <th><?php echo $adlang4[2]; ?></th>
                                            <th><?php echo $adlang4[19]; ?></th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									                      <?php
                                        while ($G = $DB->db_object($Q)) {
	                                      ?>
                                        <tr>
                                            <?php
                                            if ($G->image && file_exists(PATH.'templates/images/gateways/'.$G->image)) {
                                            ?>
                                            <td class="gatewayImg"><img src="templates/images/gateways/<?php echo $G->image; ?>" alt="<?php echo mswSafeDisplay($G->display); ?>"></td>
                                            <?php
                                            } else {
                                            ?>
                                            <td class="gatewayImg"><img src="templates/images/new-gateway.png" alt="<?php echo mswSafeDisplay($G->display); ?>"></td>
                                            <?php
                                            }
                                            ?>
                                            <td><?php echo mswSafeDisplay($G->display); ?> <a href="<?php echo $G->webpage; ?>" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a>
                                            <?php
                                            if ($G->default=='yes') {
                                            ?>
                                            <span class="defaultGW"><?php echo $adlang3[16]; ?></span>
                                            <?php
                                            }
                                            ?>
                                            </td>
                                            <td><?php echo mswGetStatus($G->status,$gblang); ?></td>
                                            <td>
                                             <a href="?p=new-gateway&amp;edit=<?php echo $G->id; ?>" title="<?php echo mswSafeDisplay($gblang[12]); ?>"><i class="fa fa-pencil fa-fw"></i></a>
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
