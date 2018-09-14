<?php if (!defined('PARENT')) { exit; }
$ID = (isset($_GET['id']) ? (int)$_GET['id'] : '0');
if ($ID==0) {
  die('<p style="padding:30px">Invalid ID</p>');
}
$Q    = $DB->db_query("SELECT * FROM `".DB_PREFIX."collections` WHERE `id` = '{$ID}'");
$COL  = $DB->db_object($Q);
if (!isset($COL->id)) {
  die('<p style="padding:30px">Collection not found, invalid ID</p>');
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
		  mm_reOrderData('dragArea','track-order');
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
        $Q  = $DB->db_query("SELECT * FROM `".DB_PREFIX."music` WHERE `collection` = '{$ID}' ORDER BY `order`");
        $r  = $DB->db_rows($Q);
		    ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
					 <span style="float:right">
					  <button type="button" class="btn btn-primary btn-sm" onclick="mm_windowLoc('new-tracks&amp;id=<?php echo $ID; ?>')" title="<?php echo mswSafeDisplay($adlang4[41]); ?>"><i class="fa fa-plus fa-fw"></i></button>
					  <?php
					  if ($r>0) {
			      ?>
					  <button type="button" class="btn btn-info btn-sm" onclick="mm_windowLoc('new-tracks&amp;edit=<?php echo $ID; ?>')" title="<?php echo mswSafeDisplay($adlang4[22]); ?>"><i class="fa fa-pencil fa-fw"></i></button>
					  <button type="button" class="btn btn-success btn-sm" onclick="iBox.showURL('?p=collections&amp;clipBoard=view','',{width:700,height:500});return false" title="<?php echo mswSafeDisplay($adlang4[45]); ?>"><i class="fa fa-shopping-cart fa-fw"></i></button>
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
			if ($r>0) {
			?>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-heading">
					   <span style="float:right" class="italics small-font"><i class="fa fa-reorder fa-fw"></i> <?php echo $adlang4[27]; ?></span>
					   <i class="fa fa-music fa-fw"></i> <?php echo mswSafeDisplay($COL->name); ?>
					  </div>
					  <div class="panel-body panel-body-padding">
						    <div class="table-responsive" style="margin-bottom:0" id="dragArea">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $adlang4[25]; ?></th>
                                            <th><?php echo $adlang4[42]; ?></th>
                                            <th><?php echo $adlang4[43]; ?></th>
											<th><?php echo $adlang4[44]; ?></th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									    <?php
										while ($T = $DB->db_object($Q)) {
	                                    ?>
										<tr id="tracks-<?php echo $T->id; ?>">
                                            <td><?php echo mswSafeDisplay($T->title); ?></td>
                                            <td><?php echo mswSafeDisplay($T->length); ?></td>
                                            <td><?php echo mswSafeDisplay($T->bitrate); ?></td>
											<td><?php echo mswCurrencyFormat($T->cost,$SETTINGS->curdisplay); ?></td>
                                            <td>
											 <a href="#" onclick="iBox.showURL('?p=collections&amp;clipBoard=t<?php echo $T->id; ?>','',{width:700,height:500});return false" title="<?php echo mswSafeDisplay($adlang4[30]); ?>" style="margin-right:10px"><i class="fa fa-cart-plus fa-fw mm_black mm_cursor"></i></a>
											 <a onclick="mm_changePlayState('<?php echo $T->id; ?>','single','../<?php echo PREVIEW_FOLDER; ?>/<?php echo str_replace("'","\'",$T->previewfile); ?>');return false" href="#" title="<?php echo mswSafeDisplay($adlang8[19]); ?>" class="sm2_button" id="play-<?php echo $T->id; ?>"><i class="fa fa-play fa-fw"></i></a>
											 <a href="#" onclick="mm_del_confirm('tracks','<?php echo $T->id; ?>');return false;" title="<?php echo mswSafeDisplay($gblang[14]); ?>"><i class="fa fa-times fa-fw mm_red"></i></a>
											</td>
                                        </tr>
										<?php
										}
										?>
                                    </tbody>
                                </table>
                            </div>
						</div>
						<div class="panel-footer">
                            <button type="button" class="btn btn-link" onclick="mm_windowLoc('collections')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
						</div>
					</div>
				</div>
			</div>
			<?php
			} else {
			?>
			<div class="row">
			 <p class="nothing"><?php echo $adlang4[24]; ?></p>
			</div>
			<?php
			}
			include(PATH.'templates/cp.php');
			?>
        </div>

    </div>
