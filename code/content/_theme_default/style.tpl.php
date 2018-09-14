<?php if (!defined('PARENT')) { exit; } ?>
            <div class="col-lg-9 col-md-9 col-sm-12">

              <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo ($this->SETTINGS->rss=='yes' ? '<span class="pull-right rss_feed"><a href="'.$this->FEED_URL.'" onclick="window.open(this);return false"><i class="fa fa-rss fa-fw"></i></a></span>' : ''); ?><?php echo $this->TXT[0]; ?></span>
            	</div>

              <div class="cartWrapper">
              <?php
              // COLLECTIONS
              // html/collection.tpl
              echo $this->COLLECTIONS;
              ?>

              <div class="pagesdiv margin-right-20">
              <?php
              // PAGINATION
              // control/classes/class.page.php
              echo $this->PAGINATION;
              ?>
              </div>
              </div>

            </div>