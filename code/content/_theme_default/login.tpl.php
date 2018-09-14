<?php if (!defined('PARENT')) { exit; } ?>
            <div class="col-lg-9 col-md-9 col-sm-12">

             <script>
				     //<![CDATA[
             jQuery(document).ready(function() {
				       if (jQuery('input[name="e"]').val()=='') {
                 jQuery('input[name="e"]').focus();
				       } else {
				         if (jQuery('input[name="p"]').val()=='') {
                   jQuery('input[name="p"]').focus();
				         }
				       }
				     });
				     //]]>
				     </script>

             <div class="col-lg-12 col-sm-12">
				      <span class="title"><span class="create-link"><a rel="nofollow" href="<?php echo $this->CREATE_URL; ?>"><i class="fa fa-pencil fa-fw"></i> <?php echo $this->TXT[1]; ?></a></span><?php echo $this->TXT[0]; ?></span>
				     </div>

				     <div class="col-lg-12 col-sm-12">
					    <table class="table table-bordered tbl-profile">
						   <tbody>
                <tr>
                 <td><?php echo $this->TXT[2]; ?></td>
                 <td><input type="text" name="e" class="form-control" value="" onkeypress="if(mm_getKeyCode(event)==13){mswLogin('enter')}"></td>
                </tr>
                <tr class="cell-pass">
                 <td><?php echo $this->TXT[3]; ?></td>
                 <td><input type="password" name="p" class="form-control" value="" onkeypress="if(mm_getKeyCode(event)==13){mswLogin('enter')}"></td>
							  </tr>
							  <tr class="cell-forgot">
                 <td>&nbsp;</td>
                 <td><a class="forgot" href="#" onclick="mswLogin('forgot-load');return false"><?php echo $this->TXT[5]; ?></a></td>
							  </tr>
							  <tr class="cell-reload" style="display:none">
                 <td>&nbsp;</td>
                 <td><a class="forgot" href="#" onclick="mswLogin('forgot-cancel');return false"><i class="fa fa-rotate-left fa-fw"></i></a></td>
							  </tr>
						   </tbody>
					    </table>

					    <div style="text-align:center">
					     <div class="btns">
						    <button type="button" class="btn btn-primary" onclick="mswLogin('forgot')" id="bforgot" style="display:none"><i class="fa fa-envelope-o fa-fw"></i> <?php echo $this->TXT[6]; ?></button>
					      <button type="button" class="btn btn-primary" onclick="mswLogin('enter')" id="benter"><i class="fa fa-lock fa-fw"></i> <?php echo $this->TXT[4]; ?></button>
					     </div>
					    </div>
	           </div>

           </div>