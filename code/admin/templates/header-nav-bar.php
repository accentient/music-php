<?php if (!defined('PARENT')) { exit; }
$hide  = 'yes';
$twapi = $SBDR->params('twitter');
if (isset($twapi['twitter']['conkey']) &&
    $twapi['twitter']['conkey'] &&
    $twapi['twitter']['consecret'] &&
    $twapi['twitter']['token'] &&
    $twapi['twitter']['key']) {
  $hide = 'no';
}
?>
            <div class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav" id="side-menu">
                       <li class="hidden-md hidden-lg hidden-sm">
                          <a href="index.php" title="<?php echo mswSafeDisplay($adlang1[2]); ?>"><i class="fa fa-dashboard fa-fw"></i> <?php echo $adlang1[2]; ?></a>
                       </li>
                       <li class="hidden-md hidden-lg hidden-sm">
                            <a href="?p=settings" title="<?php echo mswSafeDisplay($adlang1[5]); ?>"><i class="fa fa-cogs fa-fw"></i> <?php echo $adlang1[5]; ?></a>
                       </li>
                       <?php
                       if ($hide == 'no') {
                       ?>
                       <li class="posttweet">
                            <a href="#" onclick="iBox.showURL('?p=api&amp;load=tweet','',{width:450,height:400});return false" title="<?php echo mswSafeDisplay($adlang1[32]); ?>"><i class="fam-application-edit"></i> <?php echo $adlang1[32]; ?> <span style="float:right"><i class="fa fa-twitter fa-fw"></i></span></a>
							         </li>
                       <?php
                       }
                       ?>
                       <li>
                            <a href="#" title="<?php echo mswSafeDisplay($adlang1[21]); ?>"><i class="fam-music"></i> <?php echo $adlang1[21]; ?> <span class="fa arrow"></span></a>
							              <ul class="nav nav-second-level">
                            <li>
                                <a href="?p=new" title="<?php echo mswSafeDisplay($adlang4[0]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang4[0]; ?></a>
                            </li>
                            <li>
                                <a href="?p=collections" title="<?php echo mswSafeDisplay($adlang1[23]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[23]; ?></a>
                            </li>
                            <li>
                                <a href="?p=new-style" title="<?php echo mswSafeDisplay($adlang5[0]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang5[0]; ?></a>
                            </li>
                            <li>
                                <a href="?p=styles" title="<?php echo mswSafeDisplay($adlang1[24]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[24]; ?></a>
                            </li>
                            </ul>
                       </li>
                       <li>
                            <a href="#" title="<?php echo mswSafeDisplay($adlang1[10]); ?>"><i class="fam-user"></i> <?php echo $adlang1[10]; ?> <span class="fa arrow"></span></a>
							              <ul class="nav nav-second-level">
                            <li>
                                <a href="?p=new-account" title="<?php echo mswSafeDisplay($adlang6[0]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang6[0]; ?></a>
                            </li>
                            <li>
                                <a href="?p=accounts" title="<?php echo mswSafeDisplay($adlang1[25]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[25]; ?></a>
                            </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" title="<?php echo mswSafeDisplay($adlang1[8]); ?>"><i class="fam-money"></i> <?php echo $adlang1[8]; ?> <span class="fa arrow"></span></a>
							              <ul class="nav nav-second-level">
                              <li>
                                <a href="?p=new-sale" title="<?php echo mswSafeDisplay($adlang9[0]); ?>"><i class="fa fa-plus fa-fw"></i> <?php echo mswCleanData($adlang9[0]); ?></a>
                              </li>
                              <li>
                                <a href="?p=revenue" title="<?php echo mswSafeDisplay($adlang1[20]); ?>"><i class="fa fa-money fa-fw"></i> <?php echo mswCleanData($adlang1[20]); ?></a>
                              </li>
                              <li>
                                <a href="?p=moss" title="<?php echo mswSafeDisplay($adlang1[37]); ?>"><i class="fa fa-save fa-fw"></i> <?php echo mswCleanData($adlang1[37]); ?></a>
                              </li>
                              <li>
                                <a href="?p=sales&amp;st=Completed" title="<?php echo mswSafeDisplay($gblang[45]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo mswCleanData($gblang[45]); ?></a>
                              </li>
                              <?php
                              // Sales statuses..
                              $q  = $DB->db_query("SELECT `status` FROM `".DB_PREFIX."sales`
                                    WHERE `enabled`  = 'yes'
                                    AND `status`    != 'Completed'
                                    GROUP BY `status`
                                    ORDER BY FIELD(`status`,'')
                                    ");
                              if ($DB->db_rows($q)>0) {
                              while ($S = $DB->db_object($q)) {
                              $class   = '';
                              $nothing = 'no';
                              if ($S->status=='') {
                                $S->status = $gblang[47];
                                $nothing   = 'yes';
                                $class     = ' class="no-status-flag"';
                              }
                              ?>
                              <li<?php echo $class; ?>>
                                <a href="?p=sales&amp;st=<?php echo ($nothing=='no' ? urlencode($S->status) : ''); ?>" title="<?php echo mswSafeDisplay($S->status); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo mswCleanData($S->status); ?></a>
                              </li>
                              <?php
                              }
                              }
                              ?>
                            </ul>
                        </li>
                        <li>
                            <a href="#" title="<?php echo mswSafeDisplay($adlang1[22]); ?>"><i class="fam-star"></i> <?php echo $adlang1[22]; ?> <span class="fa arrow"></span></a>
							              <ul class="nav nav-second-level">
                            <li>
                                <a href="?p=new-offer" title="<?php echo mswSafeDisplay($adlang11[0]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang11[0]; ?></a>
                            </li>
                            <li>
                                <a href="?p=offers" title="<?php echo mswSafeDisplay($adlang1[28]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[28]; ?></a>
                            </li>
                            <li>
                                <a href="?p=new-coupon" title="<?php echo mswSafeDisplay($adlang16[0]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang16[0]; ?></a>
                            </li>
                            <li>
                                <a href="?p=coupons" title="<?php echo mswSafeDisplay($adlang1[13]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[13]; ?></a>
                            </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" title="<?php echo mswSafeDisplay($adlang1[6]); ?>"><i class="fam-creditcards"></i> <?php echo $adlang1[6]; ?> <span class="fa arrow"></span></a>
							              <ul class="nav nav-second-level">
                            <li>
                                <a href="?p=new-gateway" title="<?php echo mswSafeDisplay($adlang3[8]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang3[8]; ?></a>
                            </li>
                            <li>
                                <a href="?p=gateways" title="<?php echo mswSafeDisplay($adlang1[26]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[26]; ?></a>
                            </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" title="<?php echo mswSafeDisplay($adlang1[16]); ?>"><i class="fam-page"></i> <?php echo $adlang1[16]; ?> <span class="fa arrow"></span></a>
							              <ul class="nav nav-second-level">
                            <li>
                                <a href="?p=new-page" title="<?php echo mswSafeDisplay($adlang12[0]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang12[0]; ?></a>
                            </li>
                            <li>
                                <a href="?p=pages" title="<?php echo mswSafeDisplay($adlang1[27]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[27]; ?></a>
                            </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" title="<?php echo mswSafeDisplay($adlang1[18]); ?>"><i class="fam-world"></i> <?php echo $adlang1[18]; ?> <span class="fa arrow"></span></a>
							              <ul class="nav nav-second-level">
                            <li>
                                <a href="?p=new-country" title="<?php echo mswSafeDisplay($adlang18[6]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang18[6]; ?></a>
                            </li>
                            <li>
                                <a href="?p=countries" title="<?php echo mswSafeDisplay($adlang1[31]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[31]; ?></a>
                            </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" title="<?php echo mswSafeDisplay($adlang1[35]); ?>"><i class="fam-wrench"></i> <?php echo $adlang1[35]; ?> <span class="fa arrow"></span></a>
							              <ul class="nav nav-second-level">
                            <li>
                                <a href="?p=settings" title="<?php echo mswSafeDisplay($adlang1[30]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[30]; ?></a>
                            </li>
                            <li>
                                <a href="?p=impexp" title="<?php echo mswSafeDisplay($adlang1[34]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[34]; ?></a>
                            </li>
                            <li>
                                <a href="?p=paths" title="<?php echo mswSafeDisplay($adlang1[36]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[36]; ?></a>
                            </li>
                            <li>
                                <a href="?p=backup" title="<?php echo mswSafeDisplay($adlang1[11]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[11]; ?></a>
                            </li>
                            <?php
                            // Version check..
                            if (ENABLE_VER_CHECK && !defined('LIC_BETA')) {
                            ?>
                            <li>
                                <a href="?p=vc" title="<?php echo mswSafeDisplay($adlang1[15]); ?>"><i class="fa fa-angle-right fa-fw"></i> <?php echo $adlang1[15]; ?></a>
                            </li>
                            <?php
                            }
                            ?>
                            </ul>
                        </li>
                        <li>
                            <a href="?p=statistics" title="<?php echo mswSafeDisplay($adlang1[9]); ?>"><i class="fam-chart-bar"></i> <?php echo $adlang1[9]; ?></a>
                        </li>
                        <?php
						            // Version check..
		                    if (ENABLE_VER_CHECK && !defined('LIC_BETA')) {
                        ?>
                        <li class="hidden-md hidden-lg hidden-sm">
                            <a href="?p=vc" title="<?php echo mswSafeDisplay($adlang1[15]); ?>"><i class="fa fa-refresh fa-fw"></i> <?php echo $adlang1[15]; ?></a>
                        </li>
                        <?php
                        }
                        if (LICENCE_VER == 'locked' || defined('LIC_DEV')) {
                        ?>
                        <li class="hidden-md hidden-lg hidden-sm">
                            <a href="?p=purchase" title="<?php echo mswSafeDisplay($adlang1[1]); ?>"><i class="fam-basket-add"></i> <?php echo $adlang1[1]; ?></a>
                        </li>
                        <?php
                        }
                        ?>
                        <li class="hidden-md hidden-lg hidden-sm">
                            <a href="../index.php" title="<?php echo mswSafeDisplay($adlang1[19]); ?>" onclick="window.open(this);return false"><i class="fa fa-music fa-fw"></i> <?php echo $adlang1[19]; ?></a>
                        </li>
                        <?php
                        if (ENABLE_DOCS_LINK) {
                        ?>
                        <li class="hidden-md hidden-lg hidden-sm">
                        <a href="../docs/<?php echo mswDocTopic($cmd); ?>.html" title="<?php echo mswSafeDisplay($adlang1[3]); ?>" onclick="window.open(this);return false"><i class="fa fa-support fa-fw"></i> <?php echo $adlang1[3]; ?></a>
                        </li>
                        <?php
                        }
                        ?>
                        <li class="hidden-md hidden-lg hidden-sm">
                            <a href="?logout=yes" title="<?php echo mswSafeDisplay($adlang1[4]); ?>"><i class="fa fa-unlock fa-fw"></i> <?php echo $adlang1[4]; ?></a>
                        </li>
					           </ul>
                </div>
            </div>