<?php if (!defined('PARENT')) { exit; } ?>
            <div class="col-lg-9 col-md-9 col-sm-12">

              <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo $this->TXT[0]; ?>: #<?php echo $this->INVOICE_NO; ?></span>
            	</div>

              <?php
              // Downloads..
              if ($this->ORDER_DETAIL) {
              ?>
              <div class="col-lg-12 col-sm-12">
               <p><?php echo $this->TXT[5]; ?></p>
              </div>

              <div class="col-lg-12 col-sm-12">
              <table class="table table-bordered table-hover tbl-order">
               <thead>
                <tr>
                 <td class="hidden-xs th-image">&nbsp;</td>
                 <td class="th-detail"><?php echo $this->TXT[2]; ?></td>
                 <td class="th-cost"><?php echo $this->TXT[7]; ?></td>
                 <td class="th-down"><?php echo $this->TXT[4]; ?></td>
                </tr>
               </thead>
               <tbody>
               <?php
						   // ORDER DETAIL
						   // html/order-detail-item.tpl
						   echo $this->ORDER_DETAIL;
						   ?>
               </tbody>
					    </table>
              </div>
              <?php
              }

              // Shipped items..
				      if ($this->ORDER_DETAIL2) {
				      ?>
				      <div class="col-lg-12 col-sm-12">
               <p><?php echo $this->TXT[6]; ?></p>
              </div>

				      <div class="col-lg-12 col-sm-12">
					     <table class="table table-bordered table-hover tbl-order">
						    <thead>
                 <tr>
                  <td class="hidden-xs th-image">&nbsp;</td>
                  <td class="th-detail"><?php echo $this->TXT[2]; ?></td>
                  <td class="th-cost2"><?php echo $this->TXT[7]; ?></td>
                 </tr>
                </thead>
                <tbody>
						    <?php
						    // ORDER DETAIL
						    // html/order-detail-item.tpl
						    echo $this->ORDER_DETAIL2;
						    ?>
                </tbody>
					     </table>
				      </div>
				      <?php
				      }
				      ?>

				      <div class="col-lg-12 col-sm-12">
					     <table class="table table-bordered table-striped tbl-order-stats">
						    <tbody>
						     <tr>
						      <td>
						      <?php echo $this->TXT[8]; ?>: <?php echo $this->INFO['method']; ?><br>
						      <?php echo $this->TXT[9]; ?>: <?php echo $this->INFO['date']; ?><br><br>
						      <?php echo $this->TXT[10]; ?>: <?php echo $this->INFO['sub']; ?><br>
                  <span class="discount_applied"><?php echo $this->TXT[16]; ?>: <?php echo $this->INFO['coupon']; ?></span><br>
						      <?php echo $this->TXT[11]; ?>: <?php echo $this->INFO['shipping']; ?><br><br>
                  <?php
                  // Tangible Goods Tax..
                  if ($this->INFO['taxrate'] > 0 ) {
						      echo str_replace(array('{tax}','{count}','{amount}'),array($this->INFO['taxrate'],$this->INFO['itemcnt'][0],$this->INFO['tax']),$this->TXT[12]); ?>
                  <?php
                  }
                  // Digital Goods Tax..
                  if ($this->INFO['taxrate2'] > 0 ) {
						      echo str_replace(array('{tax}','{count}','{amount}'),array($this->INFO['taxrate2'],$this->INFO['itemcnt'][1],$this->INFO['tax2']),$this->TXT[17]); ?>
                  <?php
                  }
                  ?>
						      <span class="totalOrderAmount"><?php echo $this->TXT[13]; ?>: <b><?php echo $this->INFO['total']; ?></b></span><br>
						      <span class="italics"><i class="fa fa-check fa-fw"></i> <?php echo $this->TXT[15]; ?></span>
						      </td>
						      <td>
						      <?php echo $this->TXT[14]; ?>:<br><br>
						      <?php
						      echo ($this->ORDER->shippingAddr ? $this->ORDER->accountName.'<br>' : '');
						      echo ($this->ORDER->shippingAddr ? mswNL2BR(mswSafeDisplay($this->ORDER->shippingAddr)) : '');
						      ?>
						      </td>
						     </tr>
                </tbody>
					     </table>
				      </div>

				      <div style="text-align:center;margin-bottom:50px">
				       <button type="button" class="btn btn-primary" onclick="window.location='<?php echo $this->URL[0]; ?>'"><i class="fa fa-undo"></i> <?php echo $this->TXT[1]; ?></button>
				      </div>

            </div>