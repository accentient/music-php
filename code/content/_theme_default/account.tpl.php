<?php if (!defined('PARENT')) { exit; } ?>
            <div class="col-lg-9 col-md-9 col-sm-12">

              <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo $this->TXT[0]; ?> (<?php echo mswSafeDisplay($this->ACCOUNT['name']); ?>)</span>
            	</div>

				      <?php
				      // if account hasn`t been verified, we show this message until it is.
				      if ($this->ACCOUNT['enabled']=='no') {
				      ?>
				      <div class="col-lg-12 col-sm-12">

				       <div class="alert alert-warning alert-dismissable">
				        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				        <?php
				        echo $this->TXT[8];
				        ?>
				       </div>

				      </div>
				      <?php
				      }
				      ?>

              <div class="col-lg-12 col-sm-12">

                <p><?php echo $this->TXT[2]; ?></p>

              </div>

				      <div class="col-lg-12 col-sm-12">
            		<span class="title"><span style="float:right"><a href="<?php echo $this->URL[0]; ?>"><i class="fa fa-music fa-fw"></i> <?php echo $this->TXT[3]; ?></a></span><?php echo $this->TXT[1]; ?></span>
            	</div>

              <?php
              if ($this->ORDERS) {
              ?>
	            <div class="col-lg-12 col-sm-12">
					     <table class="table table-bordered tbl-orders table-hover">
                <thead>
                 <tr>
                  <td><?php echo $this->TXT[4]; ?></td>
                  <td><?php echo $this->TXT[5]; ?></td>
                  <td><?php echo $this->TXT[6]; ?></td>
                  <td><?php echo $this->TXT[7]; ?></td>
                 </tr>
                </thead>
                <tbody>
						    <?php
						    // LATEST ORDERS
						    // html/order-item.tpl
						    echo $this->ORDERS;
						    ?>
                </tbody>
               </table>
              </div>
				      <?php
			     	  }
				      ?>

            </div>