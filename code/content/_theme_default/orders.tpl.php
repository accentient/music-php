<?php if (!defined('PARENT')) { exit; } ?>
            <div class="col-lg-9 col-md-9 col-sm-12">

              <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo $this->TXT[0]; ?></span>
            	</div>

				      <?php
				      if ($this->ORDERS) {
				      ?>
	            <div class="col-lg-12 col-sm-12">
					     <table class="table table-bordered tbl-orders table-hover">
                <thead>
                 <tr>
                  <td><?php echo $this->TXT[1]; ?></td>
                  <td><?php echo $this->TXT[2]; ?></td>
                  <td><?php echo $this->TXT[3]; ?></td>
                  <td><?php echo $this->TXT[4]; ?></td>
                 </tr>
                </thead>
                <tbody>
						    <?php
						    // ORDERS
						    // html/order-item.tpl
						    echo $this->ORDERS;
						    ?>
                </tbody>
               </table>

               <div class="pagesdiv">
				       <?php
				       // PAGINATION
				       // control/classes/class.page.php
				       echo $this->PAGINATION;
				       ?>
					     </div>

              </div>
				      <?php
				      }
				      ?>

            </div>