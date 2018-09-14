<?php if (!defined('PARENT')) { exit; }
if ($SETTINGS->prodkey=='') {
  $key               = mswGenerateProductKey();
  $SETTINGS->prodkey = $key;
  $DB->db_query("UPDATE `".DB_PREFIX."settings` SET `prodkey` = '{$key}', `version` = '".SCRIPT_VERSION."'");
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

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo substr($titleBar,0,-2); ?></h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
					    <div class="panel-body">
						 If you would like show your support for this software and the developer and enjoy the benefits of the commercial version of <?php echo SCRIPT_NAME; ?>, including copyright removal, please consider purchasing a licence. This unlocks the software and <b>all future upgrades are FREE</b>. To purchase please complete the following:<br><br>
						 <span class="badge">1</span> - Please visit the <a href="http://www.<?php echo SCRIPT_URL; ?>" title="<?php echo SCRIPT_NAME; ?>" onclick="window.open(this);return false"><?php echo SCRIPT_NAME; ?></a> Website and use the "<a href="http://www.<?php echo SCRIPT_URL; ?>/purchase.html" onclick="window.open(this);return false">Purchase</a>" option.<br><br>
						 <span class="badge">2</span> - Once payment has been completed you will be redirected to the <a href="https://www.maiangateway.com/login.html" onclick="window.open(this);return false">Maian Script World Licence Centre</a>. If you aren`t directed there after payment, wait awhile for the confirmation e-mail and click the link.<br><br>
						 <span class="badge">3</span> - Generate your 'licence.lic' licence file using the onscreen instructions. To generate a licence file you will need the 60 character product key shown below.<br><br>
						 <span class="badge">4</span> - Enter your footer information on the main <a href="index.php?p=settings">settings</a> page. Other > Footers. (Not shown in the free version).
                        </div>
					</div>
          <div class="panel panel-default">
          <div class="panel-heading">
            Commercial Version Benefits
          </div>
          <div class="panel-body">
            Besides unlocking ALL the free restrictions, the full version has the following benefits:<br><br>
            <i class="fa fa-check fa-fw"></i> ALL Future upgrades FREE of Charge<br>
            <i class="fa fa-check fa-fw"></i> Notifications of new version releases<br>
            <i class="fa fa-check fa-fw"></i> All features unlocked and unlimited<br>
            <i class="fa fa-check fa-fw"></i> Copyright removal included in price<br>
            <i class="fa fa-check fa-fw"></i> Free 12 months priority support<br>
            <i class="fa fa-check fa-fw"></i> No links in email footers<br>
            <i class="fa fa-check fa-fw"></i> One off payment, no subscriptions<br><br>
            A <a href="http://www.<?php echo SCRIPT_URL; ?>/white-label.html" onclick="window.open(this);return false">white label licence</a> is also available for you to sell the system as your own with no reference to Maian Script World.<br><br>
            Check out the <a href="http://www.<?php echo SCRIPT_URL; ?>/features.html" onclick="window.open(this);return false">feature comparison matrix</a> on the <?php echo SCRIPT_NAME; ?> website. If you have any questions, please <a href="http://www.maianscriptworld.co.uk/contact" onclick="window.open(this);return false">contact me</a>.
          </div>
        </div>
					<div class="panel panel-default">
					    <div class="panel-heading">
						  Unique Product Key
						</div>
					    <div class="panel-body" style="overflow-y:auto">
						  <?php echo $SETTINGS->prodkey; ?>
					    </div>
						<div class="panel-footer" style="text-align:center">
						  <button type="button" class="btn btn-primary" onclick="mm_windowLoc('http://www.<?php echo SCRIPT_URL; ?>/purchase.html','new')"><i class="fa fa-shopping-cart fa-fw"></i> <?php echo substr($titleBar,0,-2); ?></button>
					    </div>
					</div>
				</div>
			</div>
			<?php
			include(PATH.'templates/cp.php');
			?>
        </div>

    </div>
