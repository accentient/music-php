<?php if (!defined('PARENT')) { exit; }
if (isset($_GET['edit'])) {
  $ID    = (int)$_GET['edit'];
  $Q     = $DB->db_query("SELECT * FROM `".DB_PREFIX."sales` WHERE `id` = '{$ID}'");
  $EDIT  = $DB->db_object($Q);
  if (!isset($EDIT->id)) {
    die('<p style="padding:30px">Sale not found, invalid ID</p>');
  }
  $QA   = $DB->db_query("SELECT `name`,`email` FROM `".DB_PREFIX."accounts` WHERE `id` = '{$EDIT->account}'");
  $ACC  = $DB->db_object($QA);
}
define('CALBOX','ts');
include(PATH.'templates/date-picker.php');
include(REL_PATH.'control/countries.php');
?>
      <div id="wrapper">
        <?php
		if (AUTO_COMPLETE_ENABLE && !isset($EDIT->id)) {
		?>
        <script>
		//<![CDATA[
		function mm_addressLoader(email) {
		  jQuery('textarea[name="shippingAddr"]').css('background','url(templates/images/generating.gif) no-repeat 50% 50%');
		  jQuery(document).ready(function() {
		   jQuery.ajax({
			url: 'index.php',
			data: 'ajax=address-loader&em='+email,
			dataType: 'json',
			success: function (data) {
			  jQuery('textarea[name="shippingAddr"]').css('background-image','none');
			  jQuery('textarea[name="shippingAddr"]').val(data['addr'][1]);
			  jQuery('input[name="name"]').val(jQuery.trim(data['addr'][0]));
        jQuery('input[name="ip"]').val(data['addr'][2]);
        jQuery('select[name="shipping"]').val(data['addr'][3]);
			}
		   });
		  });
		  return false;
		}
        jQuery(document).ready(function() {
           jQuery('input[name="name"]').autocomplete({
		     source: 'index.php?ajax=auto-name',
			 minLength: <?php echo AUTO_COMPLETE_MIN_LENGTH; ?>,
			 select: function(event,ui) {
			   var val  = ui.item.value;
			   var chop = val.split('(');
			   var em   = jQuery.trim(chop[1]);
			   em       = em.substring(0,em.length-1);
			   jQuery('input[name="email"]').val(jQuery.trim(em));
			   mm_addressLoader(em);
			 }
           });
		   jQuery('input[name="email"]').autocomplete({
		     source: 'index.php?ajax=auto-email',
			 minLength: <?php echo AUTO_COMPLETE_MIN_LENGTH; ?>,
			 select: function(event,ui) {
			   var val  = ui.item.value;
			   var chop = val.split('(');
			   var em   = jQuery.trim(chop[1]);
			   em       = em.substring(0,em.length-1);
			   jQuery('input[name="email"]').val(jQuery.trim(em));
			   mm_addressLoader(em);
			 }
           });
		   jQuery('input[name="status"]').autocomplete({
		     minLength: 1,
         source: 'index.php?ajax=pay-statuses',
			   select: function(event,ui) {
			   }
       });
		});
		//]]>
		</script>
		<?php
		} else {
		?>
		<script>
		//<![CDATA[
        jQuery(document).ready(function() {
          jQuery('input[name="status"]').autocomplete({
		    source: 'index.php?ajax=pay-statuses',
			minLength: 1,
			select: function(event,ui) {
			}
          });
		});
		//]]>
		</script>
		<?php
		}
		?>
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
                    <h1 class="page-header">
					 <?php
					 if (!isset($_GET['edit'])) {
					 ?>
					 <span style="float:right">
					  <button type="button" class="btn btn-success btn-sm" onclick="iBox.showURL('?p=collections&amp;clipBoard=view','',{width:700,height:500});return false" title="<?php echo mswSafeDisplay($adlang4[45]); ?>"><i class="fa fa-shopping-cart fa-fw"></i></button>
					 </span>
					 <?php
					 } echo (isset($EDIT->id) ? $adlang9[2] : $adlang9[0]); ?>
					</h1>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-bg">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#one" data-toggle="tab"><?php echo $adlang9[59]; ?></a></li>
                                <?php
                                if (isset($EDIT->id)) {
                                ?>
                                <li><a href="#two" data-toggle="tab"><?php echo $adlang9[60]; ?></a></li>
                                <li><a href="#three" data-toggle="tab"><?php echo $adlang9[32]; ?></a></li>
                                <?php
                                }
                                ?>
                                <li><a href="#four" data-toggle="tab"><?php echo $adlang9[41]; ?></a></li>
                                <li class="dropdown">
								                 <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $adlang2[49]; ?> <span class="caret"></span></a>
                                 <ul class="dropdown-menu" role="menu">
                                  <li><a href="#six" data-toggle="tab"><?php echo $adlang9[99]; ?></a></li>
                                  <li><a href="#five" data-toggle="tab"><?php echo $adlang9[33]; ?></a></li>
                                  <?php
                                  if (isset($EDIT->id) && $SETTINGS->licenable == 'yes' && $SETTINGS->licsubj && $SETTINGS->licmsg) {
                                  ?>
                                  <li><a href="#seven" data-toggle="tab"><?php echo $adlang9[103]; ?></a></li>
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
								  <label><?php echo $adlang9[21]; ?></label>
								  <input<?php echo (isset($EDIT->id) ? ' readonly="readonly" ' : ' '); ?>type="text" name="name" value="<?php echo (isset($ACC->name) ? mswSafeDisplay($ACC->name) : ''); ?>" class="form-control" maxlength="100">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang9[22]; ?></label>
								  <input<?php echo (isset($EDIT->id) ? ' readonly="readonly" ' : ' '); ?>type="text" name="email" value="<?php echo (isset($ACC->email) ? mswSafeDisplay($ACC->email) : ''); ?>" class="form-control"  maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang9[23]; ?></label>
								  <textarea name="shippingAddr" rows="5" cols="40" class="form-control"><?php echo (isset($EDIT->shippingAddr) ? mswSafeDisplay($EDIT->shippingAddr) : ''); ?></textarea>
								 </div>
                 <?php
								 if (!isset($EDIT->id)) {
								 ?>
								 <div class="form-group">
								   <label><?php echo $adlang9[48]; ?></label>
								   <div class="radio">
								    <label><input type="radio" name="mailer" value="yes" checked="checked"> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="mailer" value="no"> <?php echo $gblang[8]; ?></label>
								   </div>
								 </div>
								 <?php
								 }
								 ?>
								</div>
								<?php
								if (isset($EDIT->id)) {
								?>
								<div class="tab-pane fade" id="two">
								 <?php
								 $PD = $DB->db_query("SELECT * FROM `".DB_PREFIX."sales_items`
								       WHERE `sale`   = '{$EDIT->id}'
									   AND `physical` = 'no'
									   ORDER BY `id`
									   ");
								 if ($DB->db_rows($PD)>0) {
								 ?>
								 <div class="table-responsive clipboardarea" style="margin-bottom:0" id="i-down">
								  <table class="table table-striped table-hover">
								  <thead>
								   <tr>
									<th style="width:130px !important"><?php echo $adlang4[46]; ?></th>
									<th><?php echo $adlang9[62]; ?></th>
									<th><?php echo $adlang9[63]; ?></th>
									<th><a href="#" onclick="mm_selectAll('i-down','rem','link');return false"><?php echo $adlang9[82]; ?><i class="fa fa-ban fa-fw mm_red"></i></a></th>
								   </tr>
								  </thead>
								  <tbody>
								  <?php
								  while ($DSI = $DB->db_object($PD)) {
								   switch ($DSI->type) {
									case 'collection':
									$Q_C    = $DB->db_query("SELECT `id`,`name`,`coverart`,`cost`,`costcd` FROM `".DB_PREFIX."collections` WHERE `id` = '{$DSI->item}'");
									$CTION  = $DB->db_object($Q_C);
									if (isset($CTION->name)) {
									  $name  = mswSafeDisplay($CTION->name).'<span class="colTrack">&nbsp;<span class="history"><i class="fa fa-clock-o fa-fw"></i><a href="#" onclick="iBox.showURL(\'?p=new-sale&amp;history='.$EDIT->id.'&amp;saleItem='.$DSI->id.'\',\'\',{width:750,height:400});return false" title="'.mswSafeDisplay($adlang9[37]).'">'.$adlang9[37].'</a></span></span>';
								      $alt   = mswSafeDisplay($CTION->name);
									  $cost  = $DSI->cost;
									  $desc  = $adlang9[67];
									}
									break;
									case 'track':
									$Q_T  = $DB->db_query("SELECT `collection`,`title`,`cost` FROM `".DB_PREFIX."music` WHERE `id` = '{$DSI->item}'");
									$CTK  = $DB->db_object($Q_T);
									if (isset($CTK->title)) {
									  $Q_C    = $DB->db_query("SELECT `name`,`coverart` FROM `".DB_PREFIX."collections` WHERE `id` = '{$CTK->collection}'");
									  $CTION  = $DB->db_object($Q_C);
									  if (isset($CTION->name)) {
										$name  = mswSafeDisplay($CTION->name).'<span class="colTrack">'.mswSafeDisplay($CTK->title).'<span class="history"><i class="fa fa-clock-o fa-fw"></i><a href="#" onclick="iBox.showURL(\'?p=new-sale&amp;history='.$EDIT->id.'&amp;saleItem='.$DSI->id.'\',\'\',{width:650,height:400});return false" title="'.mswSafeDisplay($adlang9[37]).'">'.$adlang9[37].'</a></span></span>';
										$alt   = mswSafeDisplay($CTION->name).' ('.mswSafeDisplay($CTK->title).')';
										$cost  = $DSI->cost;
										$desc  = $adlang9[66];
									  }
									}
									break;
								   }
								   if ($name) {
								  ?>
								  <tr id="cadl_<?php echo $DSI->id; ?>">
									<td><img class="clipart" src="<?php echo mswCoverArtLoader($CTION->coverart,$SETTINGS->httppath); ?>" title="<?php echo $alt; ?>" alt="<?php echo $alt; ?>"></td>
									<td><?php echo $name; ?><span class="desc"><?php echo $desc; ?></span></td>
									<td class="price" title="<?php echo mswSafeDisplay($adlang9[81]); ?>"><span class="mm_cursor" onclick="mm_changePrice('<?php echo $DSI->id; ?>','<?php echo $cost; ?>','cadl')"><?php echo $cost; ?></span></td>
									<td><input type="hidden" name="saleItemID[]" value="<?php echo $DSI->id; ?>"><input type="hidden" name="price[<?php echo $DSI->id; ?>]" value="<?php echo $cost; ?>"><input type="checkbox" name="rem[]" value="<?php echo $DSI->id; ?>"<?php echo (!isset($EDIT->id) ? ' checked="checked"' : ''); ?>></td>
								  </tr>
								  <?php
								  }
								  }
								  ?>
								  </tbody>
								  </table>
								  <div style="text-align:right;padding-right:20px">
								   <input type="checkbox" name="send-reset" value="yes" checked="checked"> <?php echo $adlang9[48]; ?><br><br>
								   <button type="button" class="btn btn-success" onclick="mm_downloadReset()"><i class="fa fa-download fa-fw" id="reseticon"></i> <?php echo $adlang9[39]; ?></button>
								  </div>
								 </div>
								 <?php
								 } else {
								   echo '<p class="nothing">'.$adlang9[31].'</p>';
								 }
								 ?>
								</div>
								<div class="tab-pane fade" id="three">
								 <?php
								 $PP = $DB->db_query("SELECT * FROM `".DB_PREFIX."sales_items`
								       WHERE `sale`   = '{$EDIT->id}'
									   AND `physical` = 'yes'
									   ORDER BY `id`
									   ");
								 if ($DB->db_rows($PP)>0) {
								 ?>
								 <div class="table-responsive clipboardarea" style="margin-bottom:0" id="i-cd">
								  <table class="table table-striped table-hover">
								  <thead>
								   <tr>
									<th style="width:130px !important"><?php echo $adlang4[46]; ?></th>
									<th><?php echo $adlang9[62]; ?></th>
									<th><?php echo $adlang9[63]; ?></th>
									<th><a href="#" onclick="mm_selectAll('i-cd','remcd','link');return false"><?php echo $adlang9[68]; ?><i class="fa fa-ban fa-fw mm_red"></i></a></th>
								   </tr>
								  </thead>
								  <tbody>
								  <?php
								  while ($DCD = $DB->db_object($PP)) {
								   switch ($DCD->type) {
									case 'collection':
									$Q_C    = $DB->db_query("SELECT `id`,`name`,`coverart`,`cost`,`costcd` FROM `".DB_PREFIX."collections` WHERE `id` = '{$DCD->item}'");
									$CTION  = $DB->db_object($Q_C);
									if (isset($CTION->name)) {
									  $name  = mswSafeDisplay($CTION->name).'<span class="colTrack">&nbsp;</span>';
									  $alt   = mswSafeDisplay($CTION->name);
									  $cost  = $DCD->cost;
									  $desc  = $adlang9[67];
									}
									break;
									case 'track':
									$Q_T  = $DB->db_query("SELECT `collection`,`title`,`cost` FROM `".DB_PREFIX."music` WHERE `id` = '{$DCD->item}'");
									$CTK  = $DB->db_object($Q_T);
									if (isset($CTK->title)) {
									  $Q_C    = $DB->db_query("SELECT `name`,`coverart` FROM `".DB_PREFIX."collections` WHERE `id` = '{$CTK->collection}'");
									  $CTION  = $DB->db_object($Q_C);
									  if (isset($CTION->name)) {
										$name  = mswSafeDisplay($CTION->name).'<span class="colTrack">'.mswSafeDisplay($CTK->title).'</span>';
										$alt   = mswSafeDisplay($CTION->name).' ('.mswSafeDisplay($CTK->title).')';
										$cost  = $DCD->cost;
										$desc  = $adlang9[66];
									  }
									}
									break;
								   }
								   if ($name) {
								  ?>
								  <tr id="cacd_<?php echo $DCD->id; ?>">
									<td><img class="clipart" src="<?php echo mswCoverArtLoader($CTION->coverart,$SETTINGS->httppath); ?>" title="<?php echo $alt; ?>" alt="<?php echo $alt; ?>"></td>
									<td><?php echo $name; ?><span class="desc"><?php echo $desc; ?></span></td>
									<td class="price" title="<?php echo mswSafeDisplay($adlang9[81]); ?>"><span class="mm_cursor" onclick="mm_changePrice('<?php echo $DCD->id; ?>','<?php echo $cost; ?>','cacd')"><?php echo $cost; ?></span></td>
									<td><input type="hidden" name="saleItemID[]" value="<?php echo $DCD->id; ?>"><input type="hidden" name="price[<?php echo $DCD->id; ?>]" value="<?php echo $cost; ?>"><input type="checkbox" name="remcd[]" value="<?php echo $DCD->id; ?>"<?php echo (!isset($EDIT->id) ? ' checked="checked"' : ''); ?>></td>
								  </tr>
								  <?php
								  }
								  }
								  ?>
								  </tbody>
								  </table>
								 </div>
								 <?php
								 } else {
								   echo '<p class="nothing">'.$adlang9[34].'</p>';
								 }
								 ?>
								</div>
								<?php
								}
								?>
								<div class="tab-pane fade" id="four">
								 <?php
								 $Q = $DB->db_query("SELECT * FROM `".DB_PREFIX."sales_clipboard` ORDER BY `id` DESC");
								 if ($DB->db_rows($Q)>0) {
								 ?>
								 <div class="table-responsive clipboardarea" style="margin-bottom:0" id="clipboardid">
								  <table class="table table-striped table-hover">
								  <thead>
								   <tr>
									<th style="width:130px !important"><?php echo $adlang4[46]; ?></th>
									<th><?php echo $adlang9[62]; ?></th>
									<th><?php echo $adlang9[63]; ?></th>
									<th><a href="#" onclick="mm_selectAll('clipboardid','include','link');return false"><?php echo $adlang9[47]; ?><i class="fa fa-plus fa-fw mm_black"></i></a></th>
								   </tr>
								  </thead>
								  <tbody>
								  <?php
								  while ($CB = $DB->db_object($Q)) {
								   $name = '';
								   switch ($CB->type) {
                     case 'collection':
                     $Q_C    = $DB->db_query("SELECT `id`,`name`,`coverart`,`cost`,`costcd` FROM `".DB_PREFIX."collections` WHERE `id` = '{$CB->trackcol}'");
                     $CTION  = $DB->db_object($Q_C);
                     if (isset($CTION->name)) {
                       $name  = mswSafeDisplay($CTION->name).'<span class="colTrack">&nbsp;</span>';
                       $alt   = mswSafeDisplay($CTION->name);
                       $cost  = ($CB->physical=='no' ? $CTION->cost : $CTION->costcd);
                       $desc  = ($CB->physical=='yes' ? $adlang9[65] : $adlang9[64]);
                       $isCD  = ($CB->physical=='yes' ? 'cd' : 'no');
                     }
                     break;
                     case 'track':
                     $Q_T  = $DB->db_query("SELECT `collection`,`title`,`cost` FROM `".DB_PREFIX."music` WHERE `id` = '{$CB->trackcol}'");
                     $CTK  = $DB->db_object($Q_T);
                     if (isset($CTK->title)) {
                       $Q_C    = $DB->db_query("SELECT `name`,`coverart` FROM `".DB_PREFIX."collections` WHERE `id` = '{$CTK->collection}'");
                       $CTION  = $DB->db_object($Q_C);
                       if (isset($CTION->name)) {
                         $name  = mswSafeDisplay($CTION->name).'<span class="colTrack">'.mswSafeDisplay($CTK->title).'</span>';
                         $alt   = mswSafeDisplay($CTION->name).' ('.mswSafeDisplay($CTK->title).')';
                         $cost  = $CTK->cost;
                         $desc  = $adlang9[66];
                         $isCD  = 'no';
                       }
                     }
                     break;
								   }
								   if ($name) {
								  ?>
								  <tr>
									<td><img class="clipart" src="<?php echo mswCoverArtLoader($CTION->coverart,$SETTINGS->httppath); ?>" title="<?php echo $alt; ?>" alt="<?php echo $alt; ?>"></td>
									<td><?php echo $name; ?><span class="desc"><?php echo $desc; ?></span></td>
									<td><input type="hidden" name="free_<?php echo $CB->id; ?>" value="<?php echo mswCurrencyFormat('0.00',$SETTINGS->curdisplay); ?>"><input type="hidden" name="cost_<?php echo $CB->id; ?>" value="<?php echo mswCurrencyFormat($cost,$SETTINGS->curdisplay); ?>"><span id="freetd_<?php echo $CB->id; ?>"><?php echo mswCurrencyFormat($cost,$SETTINGS->curdisplay); ?></span><br><br><input type="checkbox" name="cbcheck_<?php echo $CB->id; ?>" value="<?php echo $CB->id; ?>" onclick="mm_markFree('<?php echo $CB->id; ?>')"><br><?php echo $adlang9[79]; ?></td>
									<td><input type="checkbox" name="include[]" value="<?php echo $isCD.'-'.$CB->trackcol.'-'.$CB->type.'-'.$cost.'-'.$CB->id; ?>"<?php echo (!isset($EDIT->id) ? ' checked="checked"' : ''); ?>></td>
								  </tr>
								  <?php
								  }
								  }
								  ?>
								  </tbody>
								  </table>
								  <div class="form-group">
								   <label><?php echo (isset($EDIT->id) ? $adlang9[50] : $adlang9[49]); ?></label>
								   <div class="radio">
								    <label><input type="radio" name="clear" value="yes"> <?php echo $gblang[7]; ?></label>
								   </div>
								   <div class="radio">
								    <label><input type="radio" name="clear" value="no" checked="checked"> <?php echo $gblang[8]; ?></label>
								   </div>
								  </div>
								  </div>
								  <?php
								 } else {
								   echo $adlang9[45];
								 }
								 ?>
								</div>
								<div class="tab-pane fade" id="five">
								 <div class="form-group">
								  <label><?php echo $adlang9[27]; ?></label>
								  <input type="text" name="ts" id="ts" value="<?php echo (isset($EDIT->ts) && $EDIT->ts>0 ? $DT->tsToDate($EDIT->ts,$SETTINGS->jsformat) : $DT->tsToDate($DT->utcTime(),$SETTINGS->jsformat)); ?>" class="form-control">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang9[53]; ?></label>
								  <div class="form-group input-group">
								   <span class="input-group-addon"><i class="fa fa-refresh fa-fw" style="cursor:pointer" onclick="mm_newInvoiceNo()" title="<?php echo mswSafeDisplay($adlang9[83]); ?>"></i></span>
								   <input type="text" name="invoice" value="<?php echo (isset($EDIT->invoice) ? mswSaleInvoiceNumber(mswSafeDisplay($EDIT->invoice)) : ''); ?>" class="form-control" maxlength="50">
								  </div>
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang9[29]; ?></label>
								  <input type="text" name="transaction" value="<?php echo (isset($EDIT->transaction) ? mswSafeDisplay($EDIT->transaction) : ''); ?>" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang9[26]; ?></label>
								  <input type="text" name="ip" value="<?php echo (isset($EDIT->ip) ? mswSafeDisplay($EDIT->ip) : ''); ?>" class="form-control" maxlength="250">
								 </div>
								 <div class="form-group">
								  <label><?php echo $adlang9[24]; ?></label>
								  <textarea name="notes" rows="5" cols="40" class="form-control"><?php echo (isset($EDIT->notes) ? mswSafeDisplay($EDIT->notes) : ''); ?></textarea>
								 </div>
								</div>
                <div class="tab-pane fade" id="six">
                 <div class="form-group">
								  <label><?php echo $adlang9[80]; ?></label>
								  <select name="shipping" class="form-control">
								   <option value="0">- - -</option>
								   <?php
								   $QSP  = $DB->db_query("SELECT * FROM `".DB_PREFIX."shipping` ORDER BY `name`");
								   while ($SP = $DB->db_object($QSP)) {
								   ?>
								   <option value="<?php echo $SP->id; ?>"<?php echo (isset($EDIT->shipID) && $EDIT->shipID==$SP->id ? ' selected="selected"' : ''); ?>><?php echo mswSafeDisplay($SP->name); ?> (<?php echo (substr($SP->cost,-1)=='%' ? $SP->cost : ($SP->cost>0 ? mswCurrencyFormat($SP->cost,$SETTINGS->curdisplay) : $adlang6[22])); ?>)</option>
								   <?php
								   }
								   ?>
								   </select>
								 </div>
                 <?php
                 if (isset($EDIT->id)) {
                 ?>
								 <div class="form-group">
								  <label><?php echo $adlang9[61]; ?></label>
								  <select name="gateway" class="form-control">
								   <option value="manual"<?php echo (isset($EDIT->gateway) && $EDIT->gateway=='manual' ? ' selected="selected"' : ''); ?>><?php echo $adlang9[86]; ?></option>
								   <option value="" disabled="disabled">&nbsp;</option>
								   <?php
								   $QGW  = $DB->db_query("SELECT `id`,`display` FROM `".DB_PREFIX."gateways` ORDER BY `display`");
								   while ($GW = $DB->db_object($QGW)) {
								   ?>
								   <option value="<?php echo $GW->id; ?>"<?php echo (isset($EDIT->gateway) && $EDIT->gateway==$GW->id ? ' selected="selected"' : ''); ?>><?php echo $GW->display; ?></option>
								   <?php
								   }
								   ?>
								  </select>
                 </div>
								 <?php
                 }
                 ?>
                 <div class="form-group">
								  <label><?php echo $adlang9[100]; ?></label>
								  <select name="taxCountry" class="form-control" onchange="mm_getTaxRate(this.value,'taxRate','t')">
                  <option value="0">- - - - - -</option>
								  <?php
								  foreach ($countries AS $k => $v) {
								  ?>
								  <option value="<?php echo $k; ?>"<?php echo (isset($EDIT->taxCountry) && $EDIT->taxCountry==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								  <?php
								  }
								  ?>
                  </select>
                 </div>
                 <div class="form-group">
								  <label><?php echo $adlang9[92]; ?></label>
								  <input type="text" name="taxRate" value="<?php echo (isset($EDIT->taxRate) ? mswSafeDisplay($EDIT->taxRate) : $SETTINGS->deftax); ?>" class="form-control" maxlength="10">
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang9[101]; ?></label>
								  <select name="taxCountry2" class="form-control" onchange="mm_getTaxRate(this.value,'taxRate2','d')">
                  <option value="0">- - - - - -</option>
								  <?php
								  foreach ($countries AS $k => $v) {
								  ?>
								  <option value="<?php echo $k; ?>"<?php echo (isset($EDIT->taxCountry2) && $EDIT->taxCountry2==$k ? ' selected="selected"' : ''); ?>><?php echo $v; ?></option>
								  <?php
								  }
								  ?>
                  </select>
                 </div>
                 <div class="form-group">
								  <label><?php echo $adlang9[93]; ?></label>
								  <input type="text" name="taxRate2" value="<?php echo (isset($EDIT->taxRate2) ? mswSafeDisplay($EDIT->taxRate2) : $SETTINGS->deftax2); ?>" class="form-control" maxlength="10">
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang9[18]; ?></label>
								  <input type="text" name="status" value="<?php echo (isset($EDIT->status) ? mswSafeDisplay($EDIT->status) : 'Completed'); ?>" class="form-control"  maxlength="250">
								 </div>
                </div>
                <?php
                if (isset($EDIT->id) && $SETTINGS->licenable == 'yes' && $SETTINGS->licsubj && $SETTINGS->licmsg) {
                ?>
                <div class="tab-pane fade" id="seven">
                 <div class="form-group">
								  <label><?php echo $adlang9[105]; ?></label>
								  <div><?php echo mswSafeDisplay($ACC->email); ?></div>
								 </div>
                 <div class="form-group">
								  <label><?php echo $adlang9[104]; ?></label>
								  <input type="text" name="copyTo" value="" class="form-control">
								 </div>
                 <button type="button" class="btn btn-success resent" onclick="mm_processor('agreement')"><i class="fa fa-envelope fa-fw"></i> <?php echo mswSafeDisplay($adlang9[106]); ?></button>
                </div>
                <?php
                }
                ?>
							</div>
						</div>
						<div class="panel-footer">
                            <?php
							if (isset($EDIT->id)) {
							?>
							<input type="hidden" name="edit" value="<?php echo $EDIT->id; ?>">
							<input type="hidden" name="e_name" value="<?php echo mswSafeDisplay($ACC->name); ?>">
							<input type="hidden" name="e_mail" value="<?php echo mswSafeDisplay($ACC->email); ?>">
              <input type="hidden" name="e_ts" value="<?php echo $EDIT->ts; ?>">
							<?php
							} else {
							?>
              <input type="hidden" name="gateway" value="manual">
              <?php
              }
              ?>
							<button type="button" class="btn btn-primary" onclick="mm_processor('sales')"><?php echo mswSafeDisplay((isset($EDIT->id) ? $adlang9[2] : $adlang9[0])); ?></button>
              <?php
							$winLoc = 'sales';
							if (isset($_GET['his']) && $_GET['his']>0) {
							  $winLoc = 'history&amp;id='.$_GET['his'];
							}
							if (isset($_GET['st'])) {
							  $winLoc = 'sales&amp;st='.mswSafeDisplay(urlencode($_GET['st']));
							} else {
                $winLoc = 'sales&amp;st=Completed';
							}
							?>
							<button type="button" class="btn btn-link" onclick="mm_windowLoc('<?php echo $winLoc; ?>')"><?php echo mswSafeDisplay($gblang[13]); ?></button>
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
