<?php if (!defined('PARENT')) { exit; } ?>
            <script>
            //<![CDATA[
            jQuery(document).ready(function() {
             jQuery('#accordion').accordion({
              collapsible : true,
              heightStyle : 'content',
              active      : 1,
              disabled    : true
			       });
			       <?php
			       // Populate shipping address on load if logged in and if shipping is applicable..
			       if ($this->IS_SHIPPING=='yes' && isset($this->ACCOUNT['id'])) {
			       ?>
			       mm_populateData('<?php echo $this->ACCOUNT['id']; ?>');
			       <?php
			       }
             // Always show basket items in accordion..
			       ?>
             jQuery('.basket_items_list').show();
			      });
			      //]]>
            </script>
			     <div class="col-lg-9 col-md-9 col-sm-12">

              <div class="col-lg-12 col-sm- 12">
            		<span class="title baskettitle">
                 <?php
                 // Only show basket if at least 1 item is in basket..
                 if ($this->CART_COUNT>0) {
                 ?>
                 <span class="clearall"><a href="#" onclick="mm_clearAll('<?php echo $this->TXT[29]; ?>', '<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/');return false"><i class="fa fa-times fa-fw mm_red"></i> <?php echo $this->TXT[28]; ?></a></span>
                 <?php
                 }
                 ?>
                 <?php echo $this->TXT[0]; ?>
                </span>
            	</div>

				<div class="cartWrapper" id="basketFormArea">
				<?php
				// Only show basket if at least 1 item is in basket..
				if ($this->CART_COUNT>0) {
				?>
				<form method="post" action="<?php echo $this->URL[0]; ?>" id="basketform">
				<div class="col-lg-12 col-sm-12">
				    <div id="accordion">
					 <h3><span style="float:right"><i class="fa fa-shopping-cart fa-fw"></i></span><?php echo $this->TXT[4]; ?></h3>
					 <div class="basket_items_list">
					 <table class="table tbl-basket">
						<tbody>
            <?php
						// Basket Items
						// html/basket.tpl
						// html/basket-item.tpl
						echo $this->BASKET_ITEMS;
						?>
            </tbody>
					 </table>
					 </div>
					 <h3><span style="float:right"><i class="fa fa-lock fa-fw"></i></span><?php echo $this->TXT[7]; ?></h3>
					 <div>
					 <?php
					 // ACCOUNT LOGIN
					 // html/basket-account-login.tpl
					 // html/basket-account-logged-in.tpl
           // html/option.tpl
					 echo $this->ACCOUNT_LOGIN;
					 ?>
					 <div style="text-align:center">
					  <div class="btns-basket">
						 <button type="button" id="btn_account" class="btn btn-primary right-button" onclick="mm_basketOps('account','<?php echo $this->IS_SHIPPING; ?>')"><?php echo $this->TXT[5]; ?> <i class="fa fa-arrow-circle-right"></i></button>
					  </div>
           </div>
					 </div>
					 <?php
					 if ($this->IS_SHIPPING=='yes') {
					 ?>
					 <h3><span style="float:right"><i class="fa fa-truck fa-fw"></i></span><?php echo $this->TXT[9]; ?></h3>
					 <div>
					 <table class="table table-bordered table-hover tbl-profile">
						<tbody>
             <tr>
              <td><?php echo $this->TXT[17]; ?></td>
              <td><select name="method" class="form-control">
							<?php
							if (!empty($this->RATES)) {
							foreach ($this->RATES AS $k => $v) {
							?>
							<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
							<?php
							}
							}
							?>
							</select>
							</td>
             </tr>
						 <tr>
              <td><?php echo $this->TXT[11]; ?></td>
              <td><input type="text" name="address1" class="form-control" value=""></td>
             </tr>
						 <tr>
              <td><?php echo $this->TXT[12]; ?></td>
              <td><input type="text" name="address2" class="form-control" value=""></td>
             </tr>
             <tr>
              <td><?php echo $this->TXT[13]; ?></td>
              <td><input type="text" name="city" class="form-control" value=""></td>
						 </tr>
						 <tr>
              <td><?php echo $this->TXT[14]; ?></td>
              <td><input type="text" name="county" class="form-control" value=""></td>
             </tr>
						 <tr>
              <td><?php echo $this->TXT[15]; ?></td>
              <td><input type="text" name="postcode" class="form-control" value=""></td>
             </tr>
						 <tr>
              <td><?php echo $this->TXT[16]; ?></td>
              <td><select name="country" class="form-control">
						  <?php
						  if (!empty($this->COUNTRIES)) {
						  foreach ($this->COUNTRIES AS $k => $v) {
						  ?>
						  <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
						  <?php
						  }
						  }
						  ?>
						  </select>
              </td>
						 </tr>
					  </tbody>
				   </table>

           <div style="text-align:center">
					  <div class="btns-basket">
						<button type="button" class="btn btn-primary right-margin-20" onclick="mm_basketPanel(1)"><i class="fa fa-arrow-circle-left"></i> <?php echo $this->TXT[8]; ?></button>
					    <button type="button" id="btn_address" class="btn btn-primary right-button" onclick="mm_basketOps('address','<?php echo $this->IS_SHIPPING; ?>')"><?php echo $this->TXT[5]; ?> <i class="fa fa-arrow-circle-right"></i></button>
					  </div>
           </div>
					 </div>
					 <?php
					 }
					 ?>
           <h3><span style="float:right"><i class="fa fa-gift fa-fw"></i></span><?php echo $this->TXT[25]; ?></h3>
           <div>
            <p><?php echo $this->TXT[26]; ?></p>
            <input type="text" name="coupon" class="form-control coupon_code" value="">
            <div style="text-align:center" class="margin_top_20">
					   <div class="btns-basket">
						 <button type="button" class="btn btn-primary right-margin-20" onclick="mm_basketPanel(<?php echo ($this->IS_SHIPPING=='yes' ? '2' : '1'); ?>)"><i class="fa fa-arrow-circle-left"></i> <?php echo $this->TXT[8]; ?></button>
					    <button type="button" id="btn_coupon" class="btn btn-primary right-button" onclick="mm_basketOps('coupon','<?php echo $this->IS_SHIPPING; ?>')"><?php echo $this->TXT[5]; ?> <i class="fa fa-arrow-circle-right"></i></button>
					   </div>
            </div>
           </div>
					 <h3><span style="float:right"><i class="fa fa-money fa-fw"></i></span><?php echo $this->TXT[10]; ?></h3>
					 <div>
					 <table class="table table-bordered tbl-totals">
						<tbody>
						 <tr>
              <td rowspan="<?php echo ($this->IS_SHIPPING=='yes' ? '4' : '3'); ?>" class="pay-method">
								<?php echo $this->TXT[19]; ?>:<br><br>
								<select onchange="mm_methodReload(this.value,'<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/')" name="payment">
								<?php
								$first     = '';
								$first_btn = '';
								if (!empty($this->METHODS)) {
								for ($i=0; $i<count($this->METHODS); $i++) {
								// Load image for first gateway..
								if ($i==0) {
								  $first     = $this->METHODS[$i]['img'];
								  $first_btn = $this->METHODS[$i]['name'];
								}
								// Display default if set..overrides above..
								if ($this->METHODS[$i]['def']=='yes') {
								  $first     = $this->METHODS[$i]['img'];
								  $first_btn = $this->METHODS[$i]['name'];
								}
								?>
								<option value="<?php echo $this->METHODS[$i]['id']; ?>"<?php echo ($this->METHODS[$i]['def']=='yes' ? ' selected="selected"' : ''); ?>><?php echo $this->METHODS[$i]['name']; ?></option>
								<?php
								}
								}
								?>
								</select>
								<?php
								if ($first) {
								?>
								<br><br>
								<img id="method-image" src="<?php echo $first; ?>" alt="<?php echo $first_btn; ?>" title="<?php echo $first_btn; ?>">
								<?php
								}
								?>
								</td>
                <td class="align-right sub"><?php echo $this->TXT[20]; ?></td>
                <td class="align-right sub-total"><?php echo $this->CHARGES['sub']; ?></td>
              </tr>
							<?php
							if ($this->IS_SHIPPING=='yes') {
							?>
							<tr id="tr_ship_wrap">
               <td class="align-right ship"><?php echo $this->TXT[21]; ?></td>
               <td class="align-right ship-total"><?php echo $this->CHARGES['ship']; ?></td>
              </tr>
							<?php
							}
							?>
							<tr id="tr_tax_wrap">
               <td class="align-right tax"><?php echo $this->TXT[22]; ?></td>
               <td class="align-right tax-total"><?php echo $this->CHARGES['tax']; ?></td>
              </tr>
							<tr>
               <td class="align-right total"><?php echo $this->TXT[23]; ?></td>
               <td class="align-right total-amount"><?php echo $this->CHARGES['total']; ?></td>
              </tr>
							<tr>
							 <td colspan="3" class="addnotes_area">
							 <label><?php echo $this->TXT[24]; ?>:</label>
					     <textarea name="notes" cols="2" rows="10" class="form-control"></textarea>
							 </td>
						  </tr>
						</tbody>
					 </table>
					 <div style="text-align:center">
					  <?php
					  if ($first_btn) {
					  ?>
					  <div class="btns-basket">

            <?php
            // Terms and conditions if enabled
            // Admin > Settings > Other > Terms and Conditions
            if ($this->SETTINGS->termsenable == 'yes') {
            ?>
            <div id="tacarea" class="terms_and_conditions">
             <input type="checkbox" name="terms" value="accept"> <a href="#" onclick="mm_tacInfo();return false"><?php echo $this->TXT[27]; ?></a>
            </div>
            <?php
            }
            ?>

						<button type="button" class="btn btn-primary right-margin-20" onclick="mm_basketPanel(<?php echo ($this->IS_SHIPPING=='yes' ? '3' : '2'); ?>)"><i class="fa fa-arrow-circle-left"></i> <?php echo $this->TXT[8]; ?></button>
					    <button type="button" id="btn_checkout" class="btn btn-success right-button" onclick="mm_basketOps('checkout','<?php echo $this->IS_SHIPPING; ?>')"><?php echo $this->TXT[18]; ?> <span>(<?php echo $first_btn; ?>)</span> <i class="fa fa-credit-card"></i></button>
					  </div>
					  <?php
					  }
					  ?>
            </div>
					 </div>
					</div>
					<input type="hidden" name="process" value="yes">
					<input type="hidden" name="gateway" value="no">
					<input type="hidden" name="account" value="<?php echo LOGGED_IN; ?>">
				</div>
				</form>
				<?php
				} else {
				?>
				<p class="nothing_to_show"><?php echo $this->TXT[6]; ?></p>
				<?php
				}
				?>
				</div>

      </div>