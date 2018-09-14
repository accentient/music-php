<?php if (!defined('PARENT')) { exit; }
include(REL_PATH . 'control/currencies.php');
define('CALBOX','ae');
include(PATH.'templates/date-picker.php');
$access   = ($SETTINGS->access ? unserialize($SETTINGS->access) : array('30','min','7','days','no','5','yes','tmp'));
$featured = ($SETTINGS->featured ? unserialize($SETTINGS->featured) : array());
$notify   = ($SETTINGS->emnotify ? unserialize($SETTINGS->emnotify) : array());
$social   = ($SETTINGS->social ? unserialize($SETTINGS->social) : array());
include(REL_PATH . 'control/countries.php');
$api      = $SBDR->params();
?>
      <div id="wrapper">
        <script>
		//<![CDATA[
		jQuery(document).ready(function() {
      jQuery('div[class="featured"]').sortable({
		   opacity: 0.6,
		   cursor: 'move',
		   update: function() {
		    var order = jQuery(this).sortable("serialize");
			  jQuery.post('index.php?ajax=order-featured',
			   order,
			   function(data){
			   // Nothing doing..add custom ops if necessary..
			   },
			  'json');
		   }
		  });
		});
		function mm_remFeatured(id) {
		  jQuery('div[class="featured ui-sortable"]').css('background','url(templates/images/spinner.gif) no-repeat 50% 50%');
		  jQuery(document).ready(function() {
		    jQuery.ajax({
			   url: 'index.php',
			   data: 'ajax=rem-featured&id='+id,
			   dataType: 'json',
			   success: function (data) {
			    jQuery('div[class="featured ui-sortable"]').css('background-image','none');
			    jQuery('div[class="featured ui-sortable"] #col-'+id).slideUp(1000,
			     function(){
			       jQuery('div[class="featured ui-sortable"] #col-'+id).remove();
			     }
			    );
			   }
			  });
		    return false;
		  });
		}
		function mm_featured(id,name) {
		  jQuery('div[class="featured ui-sortable"]').css('background','url(templates/images/spinner.gif) no-repeat 50% 50%');
		  jQuery(document).ready(function() {
		   jQuery.ajax({
        url: 'index.php',
        data: 'ajax=featured&id='+id,
        dataType: 'json',
        success: function (data) {
          jQuery('div[class="featured ui-sortable"]').css('background-image','none');
          if (data[0]=='OK') {
            var h = '<p id="col-'+id+'"><a href="#" onclick="mm_remFeatured(\''+id+'\');return false"><i class="fa fa-times fa-fw mm_red"></i></a> '+name+'</p>';
            var n = jQuery('div[class="featured ui-sortable"] p').length;
            if (n>0) {
              jQuery('div[class="featured ui-sortable"] p').last().after(h);
            } else {
              jQuery('div[class="featured ui-sortable"]').html(h);
            }
          }
        }
		   });
		  });
		  return false;
		}
    <?php
		if (AUTO_COMPLETE_ENABLE) {
		?>
		jQuery(document).ready(function() {
      jQuery('input[name="search-featured"]').autocomplete({
		     source: 'index.php?ajax=auto-featured',
			   minLength: <?php echo AUTO_COMPLETE_MIN_LENGTH; ?>,
			   select: function(event,ui) {
			     mm_featured(ui.item.value,ui.item.label);
			   },
			   close: function(event,ui) {
			    jQuery('input[name="search-featured"]').val('');
			   }
       });
		});
    <?php
    }
    ?>
    function mm_checkSecPath() {
      var secp = jQuery('input[name="secfolder"]').val();
      if (secp=='') {
        jQuery('input[name="secfolder"]').focus();
        return false;
      }
      jQuery('input[name="secfolder"]').css('background','url(templates/images/spinner.gif) no-repeat 98% 50%');
      jQuery(document).ready(function() {
        jQuery.ajax({
			    url: 'index.php',
			    data: 'ajax=check-sec-path&id=0&path='+secp,
		      dataType: 'json',
		      success: function (data) {
            var img = (data[0]=='OK' ? 'accept' : 'error');
            jQuery('input[name="secfolder"]').css('background','url(templates/images/'+img+'.png) no-repeat 98% 50%');
		      }
		    });
      });
    }
    function pushTest() {
      if (jQuery('input[name="pushuser"]').val()=='') {
        jQuery('input[name="pushuser"]').focus();
        return false;
      }
      if (jQuery('input[name="pushtoken"]').val()=='') {
        jQuery('input[name="pushtoken"]').focus();
        return false;
      }
      iBox.showURL('?p=settings&pushover=view','',{width:250,height:100});
    }
    //]]>
		</script>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <?php
			// Load top bar and navigation menu..
            include(PATH.'templates/header-top-bar.php');
			include(PATH.'templates/header-nav-bar.php');
			?>
		</nav>

        <form method="post" action="index.php?ajax=settings" enctype="multipart/form-data" id="fsettings">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo substr($titleBar,0,-2); ?></h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#one" data-toggle="tab"><?php echo $adlang2[0]; ?></a></li>
                                <li><a href="#two" data-toggle="tab"><?php echo $adlang2[20]; ?></a></li>
                                <li><a href="#three" data-toggle="tab"><?php echo $adlang2[37]; ?></a></li>
                                <li><a href="#eleven" data-toggle="tab"><?php echo $adlang2[62]; ?></a></li>
                                <li><a href="#four" data-toggle="tab"><?php echo $adlang2[12]; ?></a></li>
                                <li><a href="#sixteen" data-toggle="tab"><?php echo $adlang2[133]; ?></a></li>
								                <li class="dropdown">
								                 <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $adlang2[49]; ?> <span class="caret"></span></a>
                                 <ul class="dropdown-menu" role="menu">
                                  <li><a href="#thirteen" data-toggle="tab"><?php echo $adlang2[78]; ?></a></li>
                                  <li><a href="#five" data-toggle="tab"><?php echo $adlang2[26]; ?></a></li>
                                  <li><a href="#nine" data-toggle="tab"><?php echo $adlang2[57]; ?></a></li>
                                  <li><a href="#ten" data-toggle="tab"><?php echo $adlang2[60]; ?></a></li>
                                  <li><a href="#six" data-toggle="tab"><?php echo $adlang2[50]; ?></a></li>
                                  <li><a href="#seven" data-toggle="tab"><?php echo $adlang2[51]; ?></a></li>
                                  <li><a href="#seventeen" data-toggle="tab"><?php echo $adlang2[139]; ?></a></li>
                                  <li><a href="#fourteen" data-toggle="tab"><?php echo $adlang2[90]; ?></a></li>
                                  <li><a href="#fifteen" data-toggle="tab"><?php echo $adlang2[55]; ?></a></li>
                                  <?php
                                  if (LICENCE_VER=='unlocked') {
                                  ?>
                                  <li><a href="#eight" data-toggle="tab"><?php echo $adlang2[47]; ?></a></li>
                                  <?php
                                  }
                                  ?>
                                 </ul>
								                </li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="one">
								  <div class="form-group">
								   <label><?php echo $adlang2[1]; ?></label>
								   <input type="text" name="website" value="<?php echo mswSafeDisplay($SETTINGS->website); ?>" class="form-control" maxlength="100">
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang2[4]; ?></label>
								   <input type="text" name="email" value="<?php echo mswSafeDisplay($SETTINGS->email); ?>" class="form-control" maxlength="250">
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang2[2]; ?></label>
								   <input type="text" name="httppath" value="<?php echo mswSafeDisplay($SETTINGS->httppath); ?>" class="form-control" maxlength="250">
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang2[9]; ?> <span class="no-font-weight margin_left_20"><a href="#" onclick="mm_checkSecPath();return false"><i class="fa fa-folder-o fa-fw"></i> <?php echo $adlang2[106]; ?></a></span></label>
								   <input type="text" name="secfolder" value="<?php echo mswSafeDisplay($SETTINGS->secfolder); ?>" class="form-control"  maxlength="250">
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang2[36]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="rewrite" value="yes"<?php echo ($SETTINGS->rewrite=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="rewrite" value="no"<?php echo ($SETTINGS->rewrite=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								  </div>
								</div>
								<div class="tab-pane fade" id="two">
								  <div class="form-group">
								   <label><?php echo $adlang2[3]; ?></label>
								   <input type="text" name="dateformat" value="<?php echo mswSafeDisplay($SETTINGS->dateformat); ?>" class="form-control" maxlength="20" style="width:30%">
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang2[44]; ?></label>
								   <input type="text" name="timeformat" value="<?php echo mswSafeDisplay($SETTINGS->timeformat); ?>" class="form-control" maxlength="10" style="width:25%">
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang2[5]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="weekstart" value="sun"<?php echo ($SETTINGS->weekstart=='sun' ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[6]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="weekstart" value="mon"<?php echo ($SETTINGS->weekstart=='mon' ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[7]; ?></label>
								   </div>
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang2[10]; ?></label>
								   <select name="timezone" class="form-control">
								   <?php
								   foreach ($timezones AS $k => $v) {
								   ?>
								   <option value="<?php echo $k; ?>"<?php echo ($SETTINGS->timezone==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								   <?php
								   }
								   ?>
								   </select>
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang2[11]; ?></label>
								   <select name="jsformat" class="form-control">
								   <?php
								   foreach (array('DD-MM-YYYY','DD/MM/YYYY','YYYY-MM-DD','YYYY/MM/DD','MM-DD-YYYY','MM/DD/YYYY') AS $dateToTS) {
								   ?>
								   <option value="<?php echo $dateToTS; ?>"<?php echo ($SETTINGS->jsformat==$dateToTS ? ' selected="selected"' : ''); ?>><?php echo $dateToTS; ?></option>
								   <?php
								   }
								   ?>
								   </select>
								  </div>
                                </div>
								<div class="tab-pane fade" id="three">
								 <div class="form-group">
								  <label><?php echo $adlang2[58]; ?></label>
								  <input type="text" name="metakeys" value="<?php echo mswSafeDisplay($SETTINGS->metakeys); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[59]; ?></label>
								  <input type="text" name="metadesc" value="<?php echo mswSafeDisplay($SETTINGS->metadesc); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								   <label><?php echo $adlang2[56]; ?></label>
								   <select name="theme" class="form-control">
								   <?php
								   $dir = opendir(MM_BASE_PATH.'content/');
								   while (false!==($t=readdir($dir))) {
								   if (substr(strtolower($t),0,6)=='_theme' && is_dir(MM_BASE_PATH.'content/'.$t)) {
								   ?>
								   <option value="<?php echo $t; ?>"<?php echo ($SETTINGS->theme==$t ? ' selected="selected"' : ''); ?>><?php echo $t; ?></option>
								   <?php
								   }
								   }
								   ?>
								   </select>
								 </div>
								 <div class="form-group">
								   <label><?php echo $adlang2[67]; ?></label>
								   <div class="checkbox">
								    <label><input type="checkbox" name="notify[salecus]" value="1"<?php echo (isset($notify['salecus']) ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[74]; ?></label>
								   </div>
								   <div class="checkbox">
								    <label><input type="checkbox" name="notify[saleweb]" value="1"<?php echo (isset($notify['saleweb']) ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[75]; ?> </label>
								   </div>
								   <div class="checkbox">
								    <label><input type="checkbox" name="notify[salecuspen]" value="1"<?php echo (isset($notify['salecuspen']) ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[76]; ?></label>
								   </div>
								   <div class="checkbox">
								    <label><input type="checkbox" name="notify[salewebpen]" value="1"<?php echo (isset($notify['salewebpen']) ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[77]; ?> </label>
								   </div>
								 </div>
                 <div class="form-group">
								   <label><?php echo $adlang2[130]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="cdpur" value="yes"<?php echo ($SETTINGS->cdpur=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="cdpur" value="no"<?php echo ($SETTINGS->cdpur=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
                 <div class="form-group">
								   <label><?php echo $adlang2[131]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="rss" value="yes"<?php echo ($SETTINGS->rss=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="rss" value="no"<?php echo ($SETTINGS->rss=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
								</div>
								<div class="tab-pane fade" id="four">
								  <div class="form-group">
								   <label><?php echo $adlang2[13]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="paymode" value="test"<?php echo ($SETTINGS->paymode=='test' ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[14]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="paymode" value="live"<?php echo ($SETTINGS->paymode=='live' ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[15]; ?></label>
								   </div>
								  </div>
								  <div class="form-group">
								  <label><?php echo $adlang2[18]; ?></label>
								   <select name="currency" class="form-control">
								   <?php
								   foreach ($currencies AS $key => $value) {
								     echo '<option value="'.$key.'"'.($SETTINGS->currency==$key ? ' selected="selected"' : '').'>'.$value.'</option>'.mswDefineNewline();
								   }
								   ?>
								   </select>
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang2[34]; ?></label>
								   <input type="text" name="curdisplay" value="<?php echo mswSafeDisplay($SETTINGS->curdisplay); ?>" class="form-control" maxlength="250">
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang2[54]; ?></label>
								   <input type="text" name="invoice" value="<?php echo ($SETTINGS->invoice > 0 ? mswSaleInvoiceNumber(mswSafeDisplay($SETTINGS->invoice)) : '1'); ?>" class="form-control" maxlength="8">
								  </div>
                  <div class="form-group">
								   <label><?php echo $adlang2[118]; ?></label>
								   <input type="text" name="minpurchase" value="<?php echo mswSafeDisplay($SETTINGS->minpurchase); ?>" class="form-control" maxlength="20">
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang2[17]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="responselog" value="yes"<?php echo ($SETTINGS->responselog=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="responselog" value="no"<?php echo ($SETTINGS->responselog=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								  </div>
								  <div class="form-group">
								   <label><?php echo $adlang2[16]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="propend" value="yes"<?php echo ($SETTINGS->propend=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="propend" value="no"<?php echo ($SETTINGS->propend=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								  </div>
                  <div class="form-group">
								   <label><?php echo $adlang2[132]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="hideparams" value="yes"<?php echo ($SETTINGS->hideparams=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="hideparams" value="no"<?php echo ($SETTINGS->hideparams=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								  </div>
								</div>
								<div class="tab-pane fade" id="five">
								 <div class="form-group">
								   <label><?php echo $adlang2[28]; ?></label>
								   <input type="text" name="smtp_host" value="<?php echo mswCleanData($SETTINGS->smtp_host); ?>" class="form-control" maxlength="100">
								 </div>
								 <div class="form-group">
								   <label><?php echo $adlang2[29]; ?></label>
								   <input type="text" name="smtp_user" value="<?php echo mswCleanData($SETTINGS->smtp_user); ?>" class="form-control" maxlength="100">
								 </div>
								 <div class="form-group">
								   <label><?php echo $adlang2[30]; ?></label>
								   <input type="password" name="smtp_pass" value="<?php echo mswSafeDisplay($SETTINGS->smtp_pass); ?>" class="form-control" maxlength="100">
								 </div>
								 <div class="form-group">
								   <label><?php echo $adlang2[27]; ?></label>
								   <div class="row">
									<div class="col-xs-1">
									 <select class="form-control" name="smtp_security">
		                              <option value=""<?php echo ($SETTINGS->smtp_security=='' ? ' selected="selected"' : ''); ?>>- - -</option>
		                              <option value="tls"<?php echo ($SETTINGS->smtp_security=='tls' ? ' selected="selected"' : ''); ?>>TLS</option>
		                              <option value="ssl"<?php echo ($SETTINGS->smtp_security=='ssl' ? ' selected="selected"' : ''); ?>>SSL</option>
		                             </select>
									</div>
									<div class="col-xs-2">
									  <input type="text" name="smtp_port" value="<?php echo $SETTINGS->smtp_port; ?>" class="form-control" maxlength="10">
									</div>
								   </div>
								 </div>
								 <div class="form-group">
								   <label><?php echo $adlang2[45]; ?></label>
                   <input type="text" name="smtp_from" value="<?php echo mswCleanData($SETTINGS->smtp_from); ?>" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								   <label><?php echo $adlang2[46]; ?></label>
                   <input type="text" name="smtp_email" value="<?php echo mswCleanData($SETTINGS->smtp_email); ?>" class="form-control" maxlength="250">
								 </div>
                 <div class="form-group">
								   <label><?php echo $adlang2[107]; ?></label>
                   <input type="text" name="smtp_other" value="<?php echo mswCleanData($SETTINGS->smtp_other); ?>" class="form-control" maxlength="250">
								 </div>
                 <p style="text-align:center">
                  <button type="submit" class="btn btn-success btn-sm" onclick="iBox.showURL('?p=settings&amp;test=yes','',{width:450,height:400});return false"><i class="fa fa-envelope-o fa-fw"></i> <?php echo $adlang2[119]; ?></button>
                 </p>
								</div>
								<div class="tab-pane fade" id="six">
								 <div class="form-group">
								  <label><?php echo $adlang2[21]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="sysstatus" value="yes"<?php echo ($SETTINGS->sysstatus=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="sysstatus" value="no"<?php echo ($SETTINGS->sysstatus=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[23]; ?></label>
								  <input type="text" name="autoenable" id="ae" value="<?php echo ($SETTINGS->autoenable>0 ? $DT->tsToDate($SETTINGS->autoenable,$SETTINGS->jsformat) : ''); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[22]; ?></label>
								  <textarea name="reason" rows="5" cols="40" class="form-control"><?php echo mswSafeDisplay($SETTINGS->reason); ?></textarea>
								 </div>
                 <div class="form-group">
								   <label><?php echo $adlang2[117]; ?></label>
                   <input type="text" name="allowip" value="<?php echo mswCleanData($SETTINGS->allowip); ?>" class="form-control">
								 </div>
								</div>
								<div class="tab-pane fade" id="seven">
								 <?php
								 $QZ  = $DB->db_query("SELECT * FROM `".DB_PREFIX."shipping` ORDER BY `id`");
								 if ($DB->db_rows($QZ)>0) {
								 while ($Z = $DB->db_object($QZ)) {
								 ?>
								 <input type="hidden" name="zID[]" value="<?php echo $Z->id; ?>">
								 <div class="row" style="margin-top:5px">
								  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								   <input type="text" name="zname[]" value="<?php echo mswSafeDisplay($Z->name); ?>" class="form-control">
								  </div>
								  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								   <input type="text" name="zcost[]" value="<?php echo mswSafeDisplay($Z->cost); ?>" class="form-control">
								  </div>
								 </div>
								 <?php
								 }
								 } else {
								 ?>
								 <div class="row">
								  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								   <input type="text" name="zname[]" value="" class="form-control" placeholder="<?php echo mswSafeDisplay($adlang2[52]); ?>">
								  </div>
								  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								   <input type="text" name="zcost[]" value="" class="form-control" placeholder="<?php echo mswSafeDisplay($adlang2[53]); ?>">
								  </div>
								 </div>
								 <?php
								 }
								 ?>
								 <div style="text-align:right;margin-top:20px">
								  <button onclick="mm_shipZone('add')" type="button" class="btn btn-success" title="<?php echo mswSafeDisplay($gblang[40]); ?>"><i class="fa fa-plus fa-fw"></i></button>
								  <button onclick="mm_shipZone('rem')" type="button" class="btn btn-success" title="<?php echo mswSafeDisplay($gblang[26]); ?>"><i class="fa fa-minus fa-fw"></i></button>
								 </div>
								</div>
								<?php
								if (LICENCE_VER=='unlocked') {
								?>
								<div class="tab-pane fade" id="eight">
								 <div class="form-group">
                                  <label><?php echo $adlang2[48]; ?></label>
								  <textarea name="pfoot" rows="5" cols="40" class="form-control"><?php echo ($SETTINGS->pfoot ? mswSafeDisplay($SETTINGS->pfoot) : ''); ?></textarea>
								 </div>
								 <div class="form-group">
                                  <label><?php echo $adlang2[32]; ?></label>
								  <textarea name="afoot" rows="5" cols="40" class="form-control"><?php echo ($SETTINGS->afoot ? mswSafeDisplay($SETTINGS->afoot) : ''); ?></textarea>
								 </div>
								</div>
								<?php
								}
								?>
								<div class="tab-pane fade" id="nine">
								 <div class="form-group">
								  <label><?php echo $adlang2[35]; ?> <a href="http://www.addthis.com" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <input type="text" name="api[addthis][code]" value="<?php echo (isset($api['addthis']['code']) ? mswSafeDisplay($api['addthis']['code']) : ''); ?>" class="form-control" maxlength="100">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[31]; ?> <a href="http://www.disqus.com" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <input type="text" name="api[disqus][disname]" value="<?php echo (isset($api['disqus']['disname']) ? mswSafeDisplay($api['disqus']['disname']) : ''); ?>" class="form-control" maxlength="150">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[25]; ?></label>
								  <input type="text" name="api[disqus][discat]" value="<?php echo (isset($api['disqus']['discat']) ? mswSafeDisplay($api['disqus']['discat']) : ''); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[64]; ?> <a href="https://pushover.net/" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-xs" onclick="pushTest()"><i class="fa fa-mobile fa-fw"></i> <?php echo $adlang2[125]; ?></button></label>
								  <input type="text" name="api[pushover][pushuser]" value="<?php echo (isset($api['pushover']['pushuser']) ? mswSafeDisplay($api['pushover']['pushuser']) : ''); ?>" class="form-control" maxlength="150">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[65]; ?></label>
								  <input type="text" name="api[pushover][pushtoken]" value="<?php echo (isset($api['pushover']['pushtoken']) ? mswSafeDisplay($api['pushover']['pushtoken']) : ''); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[80]; ?> <a href="https://developers.facebook.com/docs/sharing/best-practices?locale=en_GB" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <div class="radio">
								    <label><input type="radio" name="facebook" value="yes"<?php echo (isset($SETTINGS->facebook) && $SETTINGS->facebook=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="facebook" value="no"<?php echo (isset($SETTINGS->facebook) && $SETTINGS->facebook=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[81]; ?></label>
								  <input type="text" name="api[facebook][fbimage]" value="<?php echo (isset($api['facebook']['fbimage']) ? mswSafeDisplay($api['facebook']['fbimage']) : ''); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[82]; ?> <a href="https://developers.facebook.com/docs/platforminsights?locale=en_GB" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <input type="text" name="api[facebook][fbinsights]" value="<?php echo (isset($api['facebook']['fbinsights']) ? mswSafeDisplay($api['facebook']['fbinsights']) : ''); ?>" class="form-control">
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang2[142]; ?> <a href="https://dev.twitter.com/" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <input type="text" name="api[twitter][conkey]" value="<?php echo (isset($api['twitter']['conkey']) ? mswSafeDisplay($api['twitter']['conkey']) : ''); ?>" class="form-control" maxlength="100">
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang2[143]; ?></label>
								  <input type="text" name="api[twitter][consecret]" value="<?php echo (isset($api['twitter']['consecret']) ? mswSafeDisplay($api['twitter']['consecret']) : ''); ?>" class="form-control" maxlength="100">
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang2[144]; ?></label>
								  <input type="text" name="api[twitter][token]" value="<?php echo (isset($api['twitter']['token']) ? mswSafeDisplay($api['twitter']['token']) : ''); ?>" class="form-control" maxlength="100">
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang2[145]; ?></label>
								  <input type="text" name="api[twitter][key]" value="<?php echo (isset($api['twitter']['key']) ? mswSafeDisplay($api['twitter']['key']) : ''); ?>" class="form-control" maxlength="100">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[83]; ?> <a href="https://www.facebook.com" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <div class="form-group input-group">
								   <span class="input-group-addon"><i class="fa fa-facebook fa-fw"></i></span>
								   <input type="text" name="social[fb]" value="<?php echo (isset($social['fb']) ? mswSafeDisplay($social['fb']) : ''); ?>" class="form-control">
								  </div>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[84]; ?> <a href="https://plus.google.com/" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <div class="form-group input-group">
								   <span class="input-group-addon"><i class="fa fa-google-plus fa-fw"></i></span>
								   <input type="text" name="social[gg]" value="<?php echo (isset($social['gg']) ? mswSafeDisplay($social['gg']) : ''); ?>" class="form-control">
								  </div>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[85]; ?> <a href="https://www.twitter.com" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <div class="form-group input-group">
								   <span class="input-group-addon"><i class="fa fa-twitter fa-fw"></i></span>
								   <input type="text" name="social[tw]" value="<?php echo (isset($social['tw']) ? mswSafeDisplay($social['tw']) : ''); ?>" class="form-control">
								  </div>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[86]; ?> <a href="https://www.linkedin.com" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <div class="form-group input-group">
								   <span class="input-group-addon"><i class="fa fa-linkedin fa-fw"></i></span>
								   <input type="text" name="social[li]" value="<?php echo (isset($social['li']) ? mswSafeDisplay($social['li']) : ''); ?>" class="form-control">
								  </div>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[87]; ?> <a href="https://www.youtube.com" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <div class="form-group input-group">
								   <span class="input-group-addon"><i class="fa fa-youtube fa-fw"></i></span>
								   <input type="text" name="social[yt]" value="<?php echo (isset($social['yt']) ? mswSafeDisplay($social['yt']) : ''); ?>" class="form-control">
								  </div>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[88]; ?> <a href="https://www.soundcloud.com" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <div class="form-group input-group">
								   <span class="input-group-addon"><i class="fa fa-soundcloud fa-fw"></i></span>
								   <input type="text" name="social[sc]" value="<?php echo (isset($social['sc']) ? mswSafeDisplay($social['sc']) : ''); ?>" class="form-control">
								  </div>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[89]; ?> <a href="https://www.spotify.com" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <div class="form-group input-group">
								   <span class="input-group-addon"><i class="fa fa-spotify fa-fw"></i></span>
								   <input type="text" name="social[sp]" value="<?php echo (isset($social['sp']) ? mswSafeDisplay($social['sp']) : ''); ?>" class="form-control">
								  </div>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[96]; ?> <a href="https://www.last.fm" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i></a></label>
								  <div class="form-group input-group">
								   <span class="input-group-addon"><i class="fa fa-lastfm fa-fw"></i></span>
								   <input type="text" name="social[fm]" value="<?php echo (isset($social['fm']) ? mswSafeDisplay($social['fm']) : ''); ?>" class="form-control">
								  </div>
								 </div>
								</div>
								<div class="tab-pane fade" id="ten">
								 <div class="form-group">
								  <label><?php echo $adlang2[61]; ?></label>
								  <input type="text" name="search-featured" value="" class="form-control">
								 </div>
								 <div class="featured">
								  <?php
								  if (!empty($featured)) {
								  $Q  = $DB->db_query("SELECT `id`,`name` FROM `".DB_PREFIX."collections` WHERE `id` IN(".mswSafeString(implode(',',$featured),$DB).") ORDER BY FIELD(`id`,".mswSafeString(implode(',',$featured),$DB).")");
								  while ($C = $DB->db_object($Q)) {
								  ?>
								  <p id="col-<?php echo $C->id; ?>"><a href="#" onclick="mm_remFeatured('<?php echo $C->id; ?>');return false"><i class="fa fa-times fa-fw mm_red"></i></a> <?php echo mswSafeDisplay($C->name); ?></p>
								  <?php
								  }
								  }
								  ?>
								 </div>
								</div>
								<div class="tab-pane fade" id="eleven">
								 <div class="form-group">
								  <label><?php echo $adlang2[66]; ?></label>
								  <input type="text" name="minpass" value="<?php echo $SETTINGS->minpass; ?>" class="form-control">
								 </div>
								 <div class="form-group">
								   <label><?php echo $adlang2[67]; ?></label>
								   <div class="checkbox">
								    <label><input type="checkbox" name="notify[cusprof]" value="1"<?php echo (isset($notify['cusprof']) ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[68]; ?></label>
								   </div>
								   <div class="checkbox">
								    <label><input type="checkbox" name="notify[webprof]" value="1"<?php echo (isset($notify['webprof']) ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[69]; ?> </label>
								   </div>
								   <div class="checkbox">
								    <label><input type="checkbox" name="notify[cuscr]" value="1"<?php echo (isset($notify['cuscr']) ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[70]; ?> </label>
								   </div>
								   <div class="checkbox">
								    <label><input type="checkbox" name="notify[webcr]" value="1"<?php echo (isset($notify['webcr']) ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[71]; ?> </label>
								   </div>
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang2[137]; ?></label>
								  <div class="radio">
								    <label><input type="radio" name="acclogin" value="yes"<?php echo (isset($SETTINGS->acclogin) && $SETTINGS->acclogin=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="acclogin" value="no"<?php echo (isset($SETTINGS->acclogin) && $SETTINGS->acclogin=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang2[138]; ?></label>
								  <input type="text" name="accloginflag" value="<?php echo $SETTINGS->accloginflag; ?>" maxlength="5" class="form-control">
								 </div>
								</div>
								<div class="tab-pane fade" id="thirteen">
								 <div class="form-group">
								  <label><?php echo $adlang2[38]; ?></label>
								  <div class="form-group input-group">
								  <input type="text" name="access[0]" value="<?php echo (isset($access[0]) ? $access[0] : ''); ?>" class="form-control">
								  <span class="input-group-addon">
								  <select name="access[1]">
								  <?php
								  foreach (
								   array(
                    'min'   => $adlang2[42],
                    'hrs'   => $adlang2[41],
                    'day'   => $adlang2[40],
                    'week'  => $adlang2[109],
                    'month' => $adlang2[110]
								   ) AS $k => $v) {
								  ?>
								  <option value="<?php echo $k; ?>"<?php echo (isset($access[1]) && $access[1]==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								  <?php
								  }
								  ?>
								  </select>
								  </span>
								  </div>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[108]; ?></label>
								  <input type="text" name="access[5]" value="<?php echo (isset($access[5]) ? $access[5] : ''); ?>" class="form-control">
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang2[39]; ?></label>
								  <input type="text" name="access[2]" value="<?php echo (isset($access[2]) ? $access[2] : ''); ?>" class="form-control">
								 </div>
                 <div class="form-group">
								   <label><?php echo $adlang2[43]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="access[3]" value="yes"<?php echo (isset($access[3]) && $access[3]=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="access[3]" value="no"<?php echo (isset($access[3]) && $access[3]=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?> </label>
								   </div>
								 </div>
								 <div class="form-group">
								   <label><?php echo $adlang2[79]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="access[4]" value="yes"<?php echo (isset($access[4]) && $access[4]=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="access[4]" value="no"<?php echo (isset($access[4]) && $access[4]=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?> </label>
								   </div>
								 </div>
                 <div class="form-group">
								   <label><?php echo $adlang2[112]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="access[7]" value="tmp"<?php echo (isset($access[7]) && $access[7]=='tmp' ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[113]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="access[7]" value="log"<?php echo (isset($access[7]) && $access[7]=='log' ? ' checked="checked"' : ''); ?>> <?php echo $adlang2[100]; ?> </label>
								   </div>
								 </div>
                 <div class="form-group">
								   <label><?php echo $adlang2[111]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="access[6]" value="yes"<?php echo (isset($access[6]) && $access[6]=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="access[6]" value="no"<?php echo (isset($access[6]) && $access[6]=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?> </label>
								   </div>
								 </div>
								</div>
								<div class="tab-pane fade" id="fourteen">
								 <div class="form-group">
								   <label><?php echo $adlang2[91]; ?></label>
								   <input type="text" name="licsubj" value="<?php echo ($SETTINGS->licsubj ? mswSafeDisplay($SETTINGS->licsubj) : mswSafeDisplay(str_replace('{website}',$SETTINGS->website,$adlang2[95]))); ?>" class="form-control" maxlength="100">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang2[92]; ?></label>
								  <textarea name="licmsg" rows="5" cols="40" class="form-control textarea-200"><?php echo mswSafeDisplay($SETTINGS->licmsg); ?></textarea>
								  <span class="mailtags"><?php echo $adlang2[94]; ?></span>
								 </div>
								 <div class="form-group">
								   <label><?php echo $adlang2[93]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="licenable" value="yes"<?php echo ($SETTINGS->licenable=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="licenable" value="no"<?php echo ($SETTINGS->licenable=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?> </label>
								   </div>
								 </div>
							   </div>
                 <div class="tab-pane fade" id="fifteen">
                  <div class="form-group">
								   <label><?php echo $adlang2[98]; ?>&nbsp;&nbsp;&nbsp;<span class="small-font no-font-weight">(<a href="http://geolite.maxmind.com/download/geoip/database/GeoIPCountryCSV.zip"><i class="fa fa-download fa-fw"></i> GeoIPCountryCSV.zip</a>)</span></label>
								   <input type="file" name="maxmind[ipv4]">
								  </div>
                  <div class="form-group">
								   <label><?php echo $adlang2[99]; ?>&nbsp;&nbsp;&nbsp;<span class="small-font no-font-weight">(<a href="http://geolite.maxmind.com/download/geoip/database/GeoIPv6.csv.gz"><i class="fa fa-download fa-fw"></i> GeoIPv6.csv.gz</a>)</span></label>
								   <input type="file" name="maxmind[ipv6]">
								  </div>
                  <div class="form-group">
								   <label><?php echo $adlang2[116]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="geoip" value="yes"<?php echo ($SETTINGS->geoip=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="geoip" value="no"<?php echo ($SETTINGS->geoip=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?> </label>
								   </div>
								 </div>
                  <p style="float:right">
                   <?php echo $adlang2[102]; ?><br>
                   <?php echo $adlang2[114]; ?>
                  </p>
                  <span class="small-font italics">
                   <?php echo $adlang2[101]; ?>: <span class="attachInfo"><?php echo ($SETTINGS->maxupdate>0 ? $DT->dateTimeDisplay($SETTINGS->maxupdate,$SETTINGS->dateformat).' @ '.$DT->dateTimeDisplay($SETTINGS->maxupdate,$SETTINGS->timeformat) : 'N/A'); ?></span><br>
                   <?php echo $adlang2[103]; ?>: <span class="attachInfo"><?php echo (@ini_get('post_max_size')>0 ? @ini_get('post_max_size') : 'N/A'); ?></span><br>
                   <?php echo $adlang2[105]; ?>: <span class="attachInfo"><?php echo (@ini_get('upload_max_filesize')>0 ? @ini_get('upload_max_filesize') : 'N/A'); ?></span><br>
                   <?php echo str_replace(array('{count}','{count2}'),array($DB->db_rowcount('geo_ipv4'),$DB->db_rowcount('geo_ipv6')),$adlang2[115]); ?>
                  </span><br><br>
                  <div class="alert alert-info"><i class="fa fa-warning fa-fw"></i> <?php echo $adlang2[104]; ?></div>
                 </div>
                 <div class="tab-pane fade" id="sixteen">
                  <div class="form-group">
								   <label><?php echo $adlang2[72]; ?></label>
								   <input type="text" name="deftax" value="<?php echo mswSafeDisplay($SETTINGS->deftax); ?>" class="form-control" maxlength="2" style="width:30%">
								  </div>
                  <div class="form-group">
								   <label><?php echo $adlang2[135]; ?></label>
								   <select name="defCountry" class="form-control">
								    <option value="0">- - -</option>
								    <?php
								    foreach ($countries AS $k => $v) {
								    ?>
								    <option value="<?php echo $k; ?>"<?php echo ($SETTINGS->defCountry==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								    <?php
								    }
								    ?>
								    </select>
								  </div>
                  <div class="form-group">
								   <label><?php echo $adlang2[134]; ?></label>
								   <input type="text" name="deftax2" value="<?php echo mswSafeDisplay($SETTINGS->deftax2); ?>" class="form-control" maxlength="2" style="width:30%">
								  </div>
                  <div class="form-group">
								   <label><?php echo $adlang2[136]; ?></label>
								   <select name="defCountry2" class="form-control">
								    <option value="0">- - -</option>
								    <?php
								    foreach ($countries AS $k => $v) {
								    ?>
								    <option value="<?php echo $k; ?>"<?php echo ($SETTINGS->defCountry2==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								    <?php
								    }
								    ?>
								    </select>
								  </div>
                 </div>
                 <div class="tab-pane fade" id="seventeen">
								 <div class="form-group">
								  <label><?php echo $adlang2[140]; ?></label>
								  <textarea name="termsmsg" rows="5" cols="40" class="form-control textarea-200"><?php echo mswSafeDisplay($SETTINGS->termsmsg); ?></textarea>
								 </div>
								 <div class="form-group">
								   <label><?php echo $adlang2[141]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="termsenable" value="yes"<?php echo ($SETTINGS->termsenable=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="termsenable" value="no"<?php echo ($SETTINGS->termsenable=='no' ? ' checked="checked"' : ''); ?>> <?php echo $gblang[8]; ?> </label>
								   </div>
								 </div>
							   </div>
							</div>
						</div>
						<div class="panel-footer">
             <button type="submit" class="btn btn-primary" onclick="mm_processorFileUpload('fsettings','actionMsg')"><?php echo $adlang2[19]; ?></button>
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

	<script>
	//<![CDATA[
    function mm_shipZone(type) {
	  var dcnt = jQuery('#seven .row').length;
	  switch(type) {
	    case 'add':
		var clone = jQuery('#seven .row').last().html();
		jQuery('#seven .row').last().after('<div class="row" style="margin-top:5px">'+clone+'</div>');
		jQuery('#seven input[name="zname[]"]').last().val('');
		jQuery('#seven input[name="zcost[]"]').last().val('');
		break;
		case 'rem':
		if (dcnt>1) {
		  jQuery('#seven .row').last().remove();
		} else {
		  jQuery('#seven input[name="zname[]"]').last().val('');
		  jQuery('#seven input[name="zcost[]"]').last().val('');
		}
		break;
	  }
	}
    //]]>
	</script>