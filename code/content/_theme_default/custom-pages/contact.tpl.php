<?php
// =========================================================================================================
// CONTACT PAGE CUSTOM TEMPLATE
// =========================================================================================================
//
// Please refer to the notes in the 'test.tpl.php' file.
//
// The "$this->ACCOUNT['name']" can be referenced for account data. To view all do print_r($this->ACCOUNT)
// Below the name and email are auto populated if someone is logged in.
//
// =========================================================================================================

?>
            <div class="col-lg-9 col-md-9 col-sm-12" id="formarea">
			        <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo mswSafeDisplay($this->PAGE->name); ?></span>
            	</div>

              <div class="col-lg-12 col-sm-12">
            		<?php echo $this->CONTACT[0]; ?>
            	</div>

				      <form method="post" action="#">
	             <div class="col-lg-12 col-sm-12" style="margin-top:20px">
					      <table class="table table-bordered table-hover">
						     <tbody>
                  <tr>
                   <td><?php echo $this->CONTACT[1]; ?></td>
                   <td><input type="text" name="nm" value="<?php echo (isset($this->ACCOUNT['name']) ? mswSafeDisplay($this->ACCOUNT['name']) : ''); ?>" class="form-control"></td>
							    </tr>
							    <tr>
                   <td><?php echo $this->CONTACT[2]; ?></td>
                   <td><input type="text" name="em" value="<?php echo (isset($this->ACCOUNT['email']) ? mswSafeDisplay($this->ACCOUNT['email']) : ''); ?>" class="form-control"></td>
                  </tr>
                  <tr>
                   <td><?php echo $this->CONTACT[3]; ?></td>
                   <td><input type="text" name="sb" value="" class="form-control"></td>
                  </tr>
                  <tr>
                   <td><?php echo $this->CONTACT[4]; ?></td>
                   <td><textarea rows="5" cols="40" class="form-control" name="cm"></textarea></td>
                  </tr>
                 </tbody>
					      </table>

                <div style="text-align:center">
					        <div class="btns-checkout">
						       <?php
                   // For spam prevention. This is hidden and never shown.
                   // A bot will always fill the info, so if it has data we reject the form
                   ?>
                   <input type="text" name="sp_rb_chk" value="" class="robotest">
                   <button type="button" class="btn btn-primary" onclick="mm_contactProcessor(this)"><i class="fa fa-envelope fa-fw"></i> <?php echo $this->CONTACT[5]; ?></button>
					        </div>
					      </div>

	            </div>
             </form>

            </div>
            <?php
            // PROCESSOR FOR CONTACT FORM AND SET FOCUS
            // PHP processor found in 'control/system/ajax.php'
            // DO NOT change if you don`t understand the code below!
            ?>
            <script>
            //<![CDATA[
            jQuery(document).ready(function() {
              if (jQuery('input[name="nm"]').val()=='') {
                jQuery('input[name="nm"]').focus();
              } else if (jQuery('input[name="em"]').val()=='') {
                jQuery('input[name="em"]').focus();
              } else if (jQuery('input[name="sb"]').val()=='') {
                jQuery('input[name="sb"]').focus();
              } else {
                if (jQuery('textarea[name="cm"]').val()=='') {
                  jQuery('textarea[name="cm"]').focus();
                }
              }
            });
            function mm_contactProcessor(obj) {
              // Button class..
              var cl = jQuery(obj).attr('class');
              // Get italics class..
              var cur = jQuery('button[class="btn btn-primary"] i').attr('class');
              jQuery('button[class="btn btn-primary"] i').attr('class', 'fa fa-spinner fa-fw');
              jQuery('button[class="btn btn-primary"] i').prop('disabled', true);
              jQuery(document).ready(function() {
                jQuery.ajax({
                  type: 'POST',
                  url: 'index.php?ajax=contact-page&id=0',
                  data: jQuery("#formarea > form").serialize(),
                  cache: false,
                  dataType: 'json',
                  success: function(data) {
                    jQuery('button[class="btn btn-primary"] i').removeProp('disabled');
                    jQuery('button[class="btn btn-primary"] i').attr('class', cur);
                    switch (data['resp']) {
                      case 'OK':
                        jQuery('input[name="nm"]').val('');
                        jQuery('input[name="em"]').val('');
                        jQuery('input[name="sb"]').val('');
                        jQuery('textarea[name="cm"]').val('');
                        if (jQuery('#mmModalBox')) {
                          jQuery('#mmModalBox #myModalLabel').html(data['modal']['label']);
                          jQuery('#mmModalBox div[class="modal-body"]').html(data['modal']['body']);
                          if (data['modal']['button_text']) {
                            jQuery('#mmModalBox button[class="btn btn-primary"]').html(data['modal']['button_text']);
                          }
                          if (data['modal']['footer']) {
                            jQuery('#mmModalBox div[class="modal-footer"] span').html(data['modal']['footer']);
                          }
                          if (data['modal']['button_url']) {
                            jQuery('#mmModalBox button[class="btn btn-primary"]').attr('onclick', "window.location='" + data['modal']['button_url'] + "'");
                          }
                          if (data['modal']['footer'] == 'hide') {
                            jQuery('#mmModalBox div[class="modal-footer"]').hide();
                          }
                          jQuery('#mmModalBox').modal('show');
                        }
                        break;
                      case 'err':
                        mm_alert(
                          data['title'],
                          data['msg'],
                          'err'
                        );
                        break;
                    }
                  }
                });
              });
              return false;
            }
            //]]>
            </script>