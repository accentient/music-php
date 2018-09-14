<?php if (!defined('PARENT')) { exit; } ?>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">xx</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><i class="fa fa-lock fa-fw mm_panel"></i> <?php echo $adlang1[0].(defined('LIC_BETA') ? ' <span style="text-transform:none;font-size:12px">(Beta '.LIC_BETA.', Expires: '.date('j/M/Y',LIC_BETA_VER).')</span>' : ''); ?></a>
            </div>

			      <ul class="nav navbar-top-links navbar-right">
                <?php
                if (LICENCE_VER == 'locked' || defined('LIC_DEV')) {
                ?>
                <li>
                    <a href="?p=purchase" title="<?php echo mswSafeDisplay($adlang1[1]); ?>"><i class="fa fa-shopping-cart fa-fw"></i> <?php echo $adlang1[1]; ?></a>
                </li>
                <?php
                }
                ?>
                <li>
                    <a href="index.php" title="<?php echo mswSafeDisplay($adlang1[2]); ?>"><i class="fa fa-dashboard fa-fw"></i> <?php echo $adlang1[2]; ?></a>
                </li>
                <li class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-cog fa-fw"></i> <?php echo $adlang1[5]; ?>
                            <i class="fa fa-caret-down fa-fw"></i>
                        </a>
                        <ul class="dropdown-menu">
                        <li>
                            <a href="?p=settings" title="<?php echo mswSafeDisplay($adlang1[30]); ?>"><i class="fa fa-cogs fa-fw"></i> <?php echo $adlang1[30]; ?></a>
                        </li>
                        <?php
						            // Version check..
		                    if (ENABLE_VER_CHECK && !defined('LIC_BETA')) {
                        ?>
                        <li>
                            <a href="?p=vc" title="<?php echo mswSafeDisplay($adlang1[15]); ?>"><i class="fa fa-refresh fa-fw"></i> <?php echo $adlang1[15]; ?></a>
                        </li>
                        <?php
                        }
                        ?>
                        </ul>
                </li>
                <li>
                    <a href="../index.php" title="<?php echo mswSafeDisplay($adlang1[19]); ?>" onclick="window.open(this);return false"><i class="fa fa-music fa-fw"></i> <?php echo $adlang1[19]; ?></a>
                </li>
                <?php
                if (ENABLE_DOCS_LINK) {
                ?>
                <li>
				        <a href="../docs/<?php echo mswDocTopic($cmd); ?>.html" title="<?php echo mswSafeDisplay($adlang1[3]); ?>" onclick="window.open(this);return false"><i class="fa fa-support fa-fw"></i> <?php echo $adlang1[3]; ?></a>
                </li>
				        <?php
                }
                ?>
                <li>
                    <a href="?logout=yes" title="<?php echo mswSafeDisplay($adlang1[4]); ?>"><i class="fa fa-unlock fa-fw"></i> <?php echo $adlang1[4]; ?></a>
                </li>
            </ul>