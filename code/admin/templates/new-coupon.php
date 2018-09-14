<?php if (!defined('PARENT')) { exit; }
if (isset($_GET['edit'])) {
  $ID    = (int)$_GET['edit'];
  $Q     = $DB->db_query("SELECT * FROM `".DB_PREFIX."coupons` WHERE `id` = '{$ID}'");
  $EDIT  = $DB->db_object($Q);
  if (!isset($EDIT->id)) {
    die('<p style="padding:30px">Coupon not found, invalid ID</p>');
  }
  $acc   = ($EDIT->accounts ? explode(',',$EDIT->accounts) : array());
}
define('CALBOX','expiry');
include(PATH.'templates/date-picker.php');
?>
      <div id="wrapper">
      <script>
      //<![CDATA[
      function mm_remAccount(id) {
        jQuery('div[class="accounts"]').css('background','url(templates/images/spinner.gif) no-repeat 50% 50%');
		    jQuery('div[class="accounts"]').css('background-image','none');
        jQuery('div[class="accounts"] #acc-'+id).slideUp(1000,
          function(){
            jQuery('div[class="accounts"] #acc-'+id).remove();
          }
        );
      }
      function mm_couponAccounts(id,name) {
        var h = '<p id="acc-'+id+'"><input type="hidden" name="accounts[]" value="'+id+'"><a href="#" onclick="mm_remAccount(\''+id+'\');return false"><i class="fa fa-times fa-fw mm_red"></i></a> '+name+'</p>';
        var n = jQuery('div[class="accounts"] p').length;
        if (n>0) {
          jQuery('div[class="accounts"] p').last().after(h);
        } else {
          jQuery('div[class="accounts"]').html(h);
        }
        return false;
      }
      <?php
      if (AUTO_COMPLETE_ENABLE) {
      ?>
      jQuery(document).ready(function() {
        jQuery('input[name="search-accounts"]').autocomplete({
           source: 'index.php?ajax=auto-account-coupons',
           minLength: <?php echo AUTO_COMPLETE_MIN_LENGTH; ?>,
           select: function(event,ui) {
             mm_couponAccounts(ui.item.value,ui.item.label);
           },
           close: function(event,ui) {
            jQuery('input[name="search-accounts"]').val('');
           }
         });
      });
      <?php
      }
      ?>
      //]]>
      </script>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <?php
			// Load top bar and navigation menu..
            include(PATH.'templates/header-top-bar.php');
			include(PATH.'templates/header-nav-bar.php');
			?>
		</nav>

        <form method="post" action="#">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo (isset($EDIT->id) ? $adlang16[5] : $adlang16[0]); ?></h1>
                </div>
            </div>
			      <div class="row">
                <div class="col-lg-12">
                 <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#one" data-toggle="tab"><?php echo $adlang9[59]; ?></a></li>
                                <li><a href="#two" data-toggle="tab"><?php echo $adlang16[20]; ?></a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="one">
                                   <div class="form-group">
                                    <label><?php echo $adlang16[3]; ?></label>
                                    <input type="text" name="code" value="<?php echo (isset($EDIT->code) ? mswSafeDisplay($EDIT->code) : ''); ?>" maxlength="30" class="form-control">
                                   </div>
                                   <div class="form-group">
                                    <label><?php echo $adlang16[1]; ?></label>
                                    <input type="text" name="discount" value="<?php echo (isset($EDIT->discount) ? mswSafeDisplay($EDIT->discount) : ''); ?>" class="form-control">
                                   </div>
                                   <div class="form-group">
                                    <label><?php echo $adlang16[2]; ?></label>
                                    <input type="text" name="expiry" id="expiry" value="<?php echo (isset($EDIT->expiry) && $EDIT->expiry>0 ? $DT->tsToDate($EDIT->expiry,$SETTINGS->jsformat) : ''); ?>" class="form-control">
                                   </div>
                                 </div>
                                 <div class="tab-pane fade" id="two">
                                  <div class="form-group">
                                    <label><?php echo $adlang16[21]; ?></label>
                                    <input type="text" name="search-accounts" value="" class="form-control">
                                   </div>
                                   <div class="accounts">
                                    <?php
                                    if (!empty($acc)) {
                                    $Q  = $DB->db_query("SELECT `id`,`name`,`email` FROM `".DB_PREFIX."accounts` WHERE `id` IN(".mswSafeString(implode(',',$acc),$DB).") ORDER BY FIELD(`id`,".mswSafeString(implode(',',$acc),$DB).")");
                                    while ($A = $DB->db_object($Q)) {
                                    ?>
                                    <p id="acc-<?php echo $A->id; ?>"><input type="hidden" name="accounts[]" value="<?php echo $A->id; ?>"><a href="#" onclick="mm_remAccount('<?php echo $A->id; ?>');return false"><i class="fa fa-times fa-fw mm_red"></i></a> <?php echo mswSafeDisplay($A->name); ?> (<?php echo mswSafeDisplay($A->email); ?>)</p>
                                    <?php
                                    }
                                    }
                                    ?>
                                   </div>
                                 </div>
								            </div>
							           </div>
                         <div class="panel-footer">
                          <?php
                          if (isset($EDIT->id)) {
                          ?>
                          <input type="hidden" name="edit" value="<?php echo $EDIT->id; ?>">
                          <?php
                          }
                          ?>
                          <button type="button" class="btn btn-primary" onclick="mm_processor('coupons')"><?php echo mswSafeDisplay((isset($EDIT->id) ? $adlang16[5] : $adlang16[0])); ?></button>
                          <button type="button" class="btn btn-link" onclick="mm_windowLoc('coupons')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
                          <span class="actionMsg"></span>
                         </div>
                      </div>
						    </div>
					  </div>
        <?php
        include(PATH.'templates/cp.php');
        ?>
        </div>
		    </form>

    </div>
