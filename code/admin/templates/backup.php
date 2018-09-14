<?php if (!defined('PARENT')) { exit; }
$totalBackup  = 0;
$mrSPScheme   = mswDBSchemaArray($DB);
?>
      <div id="wrapper">

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <?php
			// Load top bar and navigation menu..
            include(PATH.'templates/header-top-bar.php');
			include(PATH.'templates/header-nav-bar.php');
			?>
		</nav>

		<form method="post" action="?p=backup">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo substr($titleBar,0,-2); ?> (<?php echo count($mrSPScheme).' '.$adlang7[8]; ?>)</h1>
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
                                            <th><?php echo $adlang7[0]; ?></th>
                                            <th><?php echo $adlang7[1]; ?></th>
											<th><?php echo $adlang7[2]; ?></th>
											<th><?php echo $adlang7[3]; ?></th>
                                            <th><?php echo $adlang7[4]; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
									    <?php
                                        $Q = $DB->db_query("SHOW TABLE STATUS FROM `".DB_NAME."`");
                                        while ($BCK = $DB->db_object($Q)) {
                                        $SCHEMA = (array)$BCK;
										if (in_array($SCHEMA['Name'],$mrSPScheme)) {
										$size   = ($SCHEMA['Rows']>0 ? $SCHEMA['Data_length']+$SCHEMA['Index_length'] : '0');
										$ctTS   = strtotime($SCHEMA['Create_time']);
										$utTS   = strtotime($SCHEMA['Update_time']);
                                        ?>
										<tr>
                                            <td><?php echo $SCHEMA['Name']; ?></td>
                                            <td><?php echo @number_format($SCHEMA['Rows']); ?></td>
                                            <td><?php echo ($SCHEMA['Rows']>0 ? mswFileSizeConversion($size) : '0'); ?></td>
											<td><?php echo $DT->dateTimeDisplay($utTS,$SETTINGS->dateformat).' @ '.$DT->dateTimeDisplay($utTS,$SETTINGS->timeformat); ?></td>
											<td><?php echo $SCHEMA['Engine']; ?></td>
                                        </tr>
										<?php
										$totalBackup = ($totalBackup+$size);
										}
										}
										?>
                                    </tbody>
                                </table>
                            </div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" id="back-foot">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body panel-body-padding">
						    <div class="row">
                              <div class="col-md-6">
							      <div class="form-group">
							        <label><?php echo $adlang7[5]; ?></label>
								    <div class="radio">
								     <label><input type="radio" name="download" value="yes" checked="checked"> <?php echo $gblang[7]; ?></label>
								    </div>
								    <div class="radio">
								     <label><input type="radio" name="download" value="no"> <?php echo $gblang[8]; ?></label>
								    </div>
								  </div>
							  </div>
                              <div class="col-md-6">
							      <div class="form-group">
							        <label><?php echo $adlang7[6]; ?></label>
								    <div class="radio">
								     <label><input type="radio" name="compress" value="yes" checked="checked"> <?php echo $gblang[7]; ?></label>
								    </div>
								    <div class="radio">
								     <label><input type="radio" name="compress" value="no"> <?php echo $gblang[8]; ?></label>
								    </div>
								  </div>
								</div>
							</div>
						</div>
						<div class="panel-footer">
						    <input type="hidden" name="process" value="yes">
                            <button type="submit" class="btn btn-primary"><?php echo $adlang7[9].' ('.mswFileSizeConversion($totalBackup); ?>)</button>
                            <span class="actionMsg"></span>
						</div>
					</div>
				</div>
			</div>
			<?php
			include(PATH.'templates/cp.php');
			?>
        </div>
		</form>

    </div>

	<?php
    if (isset($_GET['done'])) {
    ?>
    <script>
    //<![CDATA[
    jQuery(document).ready(function() {
	  mr_scrollToArea('back-foot');
      jQuery('span[class="actionMsg"]').html(mr_actioned('<?php echo $jslang[8]; ?>'));
    });
    //]]>
    </script>
    <?php
    }
    ?>
