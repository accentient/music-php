<?php if (!defined('PARENT')) { exit; } ?>
            <div class="col-lg-9 col-md-9 col-sm-12" id="formarea">

              <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo $this->TXT[0]; ?></span>
            	</div>

              <form method="post" action="#">
	            <div class="col-lg-12 col-sm-12">
              <table class="table table-bordered table-hover tbl-profile">
               <tbody>
                <tr>
                 <td><?php echo $this->TXT[1]; ?></td>
                 <td><input type="text" name="name" class="form-control" value="<?php echo mswSafeDisplay($this->ACCOUNT['name']); ?>"></td>
							  </tr>
							  <tr>
                 <td><?php echo $this->TXT[2]; ?></td>
                 <td><input type="text" name="email" class="form-control" value="<?php echo mswSafeDisplay($this->ACCOUNT['email']); ?>"></td>
                </tr>
                <tr>
                 <td><?php echo $this->TXT[3]; ?></td>
                 <td>
                  <select name="timezone" class="form-control">
									 <option value="">- - -</option>
								   <?php
									 if (!empty($this->TIMEZONES)) {
								     foreach ($this->TIMEZONES AS $k => $v) {
								     ?>
								     <option value="<?php echo $k; ?>"<?php echo ($this->ACCOUNT['timezone']==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								     <?php
								     }
									 }
								   ?>
									</select>
								 </td>
                </tr>
							  <tr>
                 <td><?php echo $this->TXT[12]; ?></td>
                 <td>
                  <select name="accCountry" class="form-control">
									 <?php
									 if (!empty($this->COUNTRIES)) {
								     foreach ($this->COUNTRIES AS $k => $v) {
								     ?>
								     <option value="<?php echo $k; ?>"<?php echo ($this->ACCOUNT['accCountry']==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								     <?php
								     }
									 }
								   ?>
									</select>
                 </td>
							  </tr>
							  <tr>
                 <td><?php echo $this->TXT[4]; ?></td>
                 <td><input type="password" name="passwd" class="form-control"></td>
							  </tr>
							  <tr>
                 <td><?php echo $this->TXT[5]; ?></td>
                 <td><input type="password" name="passwd2" class="form-control"></td>
                </tr>
               </tbody>
					    </table>

	            </div>

				      <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo $this->TXT[6]; ?></span>
            	</div>

	            <div class="col-lg-12 col-sm-12">
					    <table class="table table-bordered table-hover tbl-profile">
               <tbody>
                <tr>
                 <td><?php echo $this->TXT[7]; ?></td>
                 <td><input type="text" name="address1" class="form-control" value="<?php echo mswSafeDisplay($this->ACCOUNT['address1']); ?>"></td>
                </tr>
							  <tr>
                 <td><?php echo $this->TXT[8]; ?></td>
                 <td><input type="text" name="address2" class="form-control" value="<?php echo mswSafeDisplay($this->ACCOUNT['address2']); ?>"></td>
                </tr>
                <tr>
                 <td><?php echo $this->TXT[9]; ?></td>
                 <td><input type="text" name="city" class="form-control" value="<?php echo mswSafeDisplay($this->ACCOUNT['city']); ?>"></td>
							  </tr>
							  <tr>
                 <td><?php echo $this->TXT[10]; ?></td>
                 <td><input type="text" name="county" class="form-control" value="<?php echo mswSafeDisplay($this->ACCOUNT['county']); ?>"></td>
                </tr>
                <tr>
                 <td><?php echo $this->TXT[11]; ?></td>
                 <td><input type="text" name="postcode" class="form-control" value="<?php echo mswSafeDisplay($this->ACCOUNT['postcode']); ?>"></td>
                </tr>
							  <tr>
                 <td><?php echo $this->TXT[16]; ?></td>
                 <td>
                  <select name="addCountry" class="form-control">
									 <option value="">- - -</option>
								   <?php
									 if (!empty($this->COUNTRIES)) {
								     foreach ($this->COUNTRIES AS $k => $v) {
								     ?>
								     <option value="<?php echo $k; ?>"<?php echo ($this->ACCOUNT['addCountry']==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								     <?php
								     }
									 }
								   ?>
									</select>
                 </td>
							  </tr>
						   </tbody>
					    </table>
              </div>

				      <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo $this->TXT[15]; ?></span>
            	</div>

	            <div class="col-lg-12 col-sm-12">
					    <table class="table table-bordered table-hover tbl-profile">
						   <tbody>
                <tr>
                 <td><?php echo $this->TXT[14]; ?></td>
                 <td>
                  <select name="method" class="form-control">
									 <option value="">- - -</option>
								   <?php
									 if (!empty($this->RATES)) {
								     foreach ($this->RATES AS $k => $v) {
								     ?>
								     <option value="<?php echo $k; ?>"<?php echo ($this->ACCOUNT['shipping']==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
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
					     <div class="btns-checkout">
						    <button type="button" class="btn btn-primary" onclick="mm_processor('profile',this)"><i class="fa fa-check fa-fw"></i> <?php echo $this->TXT[13]; ?></button>
					     </div>
					    </div>

	            </div>
				      </form>

        	</div>