<?php if (!defined('PARENT')) { exit; } ?>
            <div class="col-lg-9 col-md-9 col-sm-12">

              <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo $this->TXT[0]; ?></span>
            	</div>

              <div class="cartWrapper">
	            <?php
              // SEARCH
              // html/collection.tpl
              echo $this->SEARCH;
              ?>
              </div>

              <div class="col-lg-12 col-sm-12 pagesdiv">
              <?php
              // PAGINATION
              // control/classes/class.page.php
              echo $this->PAGINATION;
              ?>
              </div>

            </div>