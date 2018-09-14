//---------------------------
// Basket
//---------------------------

function mm_basketPanel(panel) {
  jQuery('#accordion').accordion({
    active: panel
  });
}

function mm_clearAll(txt, path) {
  var confirmSub = confirm(txt);
  if (confirmSub) {
    jQuery('span[class="title baskettitle"]').css('background', 'url(' + path + 'images/adding.gif) no-repeat 50% 50%');
    mm_processor('clear-all-basket');
    return true;
  } else {
    return false;
  }
}

//---------------------------
// Search
//---------------------------

function mm_cleanSearch() {
  var q = jQuery('input[name="q"]').val();
  q = q.replace('/', ' ');
  jQuery('input[name="q"]').val(q);
}

function mm_SearchFilters() {
  jQuery(document).ready(function() {
   jQuery.post('index.php?ajax=search-filters&id=0', {
      filter : jQuery('select[name="filters"]').val()
   },
   function(data) {
      // Reload and force refresh..
      location.reload(true);
   },'json');
  });
  return false;
}

//---------------------------
// Verification resend
//---------------------------

function mm_verifyResend() {
  jQuery(document).ready(function() {
    var current = jQuery('div[class="alert alert-warning alert-dismissable"]').html();
    jQuery('div[class="alert alert-warning alert-dismissable"]').html('<p style="text-align:center"><i class="fa fa-spinner fa-spin fa-fw"></i></p>');
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=resend-verification&id=0',
      dataType: 'json',
      success: function(data) {
        jQuery('div[class="alert alert-warning alert-dismissable"]').html(current);
        switch (data['resp']) {
          case 'OK':
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

//---------------------------
// Basket Ops
//---------------------------

function mm_basketOps(section, shipping) {
  var current = jQuery('#btn_' + section).html();
  jQuery('#btn_' + section).html('<i class="fa fa-spinner fa-spin fa-fw"></i>');
  switch (section) {
    case 'account':
      if (jQuery('input[name="account"]').val() == 'no') {
        var em = jQuery('input[name="em"]').val();
        var ps = jQuery('input[name="ps"]').val();
        var nm = jQuery('input[name="nm"]').val();
        var ct = jQuery('select[name="rescountry"]').val();
        if (em == '') {
          jQuery('#btn_' + section).html(current);
          jQuery('input[name="em"]').focus();
          return false;
        }
        if (ps == '') {
          jQuery('#btn_' + section).html(current);
          jQuery('input[name="ps"]').focus();
          return false;
        }
        jQuery(document).ready(function() {
          jQuery.post('index.php?ajax=basket-login&id=0', {
              email: em,
              pass: ps,
              name: nm,
              cnt: ct,
              mthd: jQuery('select[name="method"]').val(),
              ctry: jQuery('select[name="country"]').val()
            },
            function(data) {
              jQuery('#btn_' + section).html(current);
              switch (data['resp']) {
                case 'NEW':
                  jQuery('input[name="account"]').val('yes');
                  jQuery('#login_wrap').html(data['msg']);
                  mm_buildTotals(data['sys']);
                  mm_basketPanel(2);
                  break;
                case 'VALID':
                  jQuery('input[name="account"]').val('yes');
                  jQuery('#login_wrap').html(data['msg']);
                  // Populate only if blank..
                  if (jQuery('select[name="method"]').val() == '') {
                    jQuery('select[name="method"]').val(data['sys']['method']);
                  }
                  if (jQuery('input[name="address1"]').val() == '') {
                    jQuery('input[name="address1"]').val(data['sys']['address1']);
                  }
                  if (jQuery('input[name="address2"]').val() == '') {
                    jQuery('input[name="address2"]').val(data['sys']['address2']);
                  }
                  if (jQuery('input[name="city"]').val() == '') {
                    jQuery('input[name="city"]').val(data['sys']['city']);
                  }
                  if (jQuery('input[name="county"]').val() == '') {
                    jQuery('input[name="county"]').val(data['sys']['county']);
                  }
                  if (jQuery('input[name="postcode"]').val() == '') {
                    jQuery('input[name="postcode"]').val(data['sys']['postcode']);
                  }
                  if (jQuery('select[name="country"]').val() == '') {
                    jQuery('select[name="country"]').val(data['sys']['country']);
                  }
                  mm_buildTotals(data['sys']);
                  mm_basketPanel(2);
                  break;
                case 'err':
                  mm_alert(
                    data['title'],
                    data['msg'],
                    'err'
                  );
                  break;
              }
            }, 'json');
        });
        return false;
      } else {
        jQuery('#btn_' + section).html(current);
        mm_basketPanel(2);
      }
      break;
    case 'address':
      jQuery(document).ready(function() {
        if (jQuery('input[name="account"]').val() == 'no') {
          jQuery('#btn_' + section).html(current);
          mm_basketPanel(1);
          return false;
        }
        jQuery.post('index.php?ajax=basket-shipping&id=0', {
            method: jQuery('select[name="method"]').val(),
            address1: jQuery('input[name="address1"]').val(),
            address2: jQuery('input[name="address2"]').val(),
            city: jQuery('input[name="city"]').val(),
            county: jQuery('input[name="county"]').val(),
            postcode: jQuery('input[name="postcode"]').val(),
            country: jQuery('select[name="country"]').val(),
            ship: shipping
          },
          function(data) {
            jQuery('#btn_' + section).html(current);
            switch (data['resp']) {
              case 'OK':
                mm_buildTotals(data['sys']);
                mm_basketPanel(3);
                break;
              case 'err':
                mm_alert(
                  data['title'],
                  data['msg'],
                  'err'
                );
                break;
            }
          }, 'json');
      });
      return false;
      break;
    case 'coupon':
      jQuery(document).ready(function() {
        jQuery.post('index.php?ajax=basket-coupon&id=0', {
            coupon: jQuery('input[name="coupon"]').val(),
            method: jQuery('select[name="method"]').val()
          },
          function(data) {
            jQuery('#btn_' + section).html(current);
            switch (data['resp']) {
              case 'OK':
              case 'CLEARED':
                mm_buildTotals(data['sys']);
                mm_basketPanel((shipping == 'yes' ? 4 : 3));
                break;
              case 'err':
                mm_alert(
                  data['title'],
                  data['msg'],
                  'err'
                );
                break;
            }
          }, 'json');
      });
      return false;
      break;
    case 'checkout':
      jQuery(document).ready(function() {
        jQuery.ajax('index.php?ajax=basket-checkout&id=0', {
          type: 'POST',
          url: 'index.php?ajax=basket-checkout&id=0',
          data: jQuery('#basketFormArea > form').serialize(),
          cache: false,
          dataType: 'json',
          success: function (data) {
            jQuery('#btn_' + section).html(current);
            switch (data['resp']) {
              case 'OK':
                if (jQuery('input[name="account"]').val() == 'no') {
                  jQuery('#btn_' + section).html(current);
                  mm_basketPanel(1);
                } else {
                  jQuery('#btn_' + section).html(current);
                  // If shipping is set, are shipping fields filled in?
                  if (shipping == 'yes') {
                    if (jQuery('select[name="method"]').val() == '' ||
                      jQuery('input[name="address1"]').val() == '' ||
                      jQuery('input[name="city"]').val() == '' ||
                      jQuery('input[name="county"]').val() == '' ||
                      jQuery('input[name="postcode"]').val() == '' ||
                      jQuery('select[name="country"]').val() == '') {
                      mm_basketPanel(2);
                    } else {
                      jQuery('#basketform').submit();
                    }
                  } else {
                    jQuery('#basketform').submit();
                  }
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
      break;
  }
  // Always keep basket items div open, no matter what..
  jQuery('.basket_items_list').show();
}

//---------------------------
// Build totals
//---------------------------

function mm_buildTotals(data) {
  var rowspan = 2;
  // Show shipping if its applicable..
  if (jQuery('#tr_ship_wrap').html()) {
    if (data['ship'] != '0.00') {
      jQuery('#tr_ship_wrap').show();
      jQuery('td[class="align-right sub-total"]').html(data['sub']);
      jQuery('td[class="align-right ship-total"]').html(data['ship']);
      ++rowspan;
    } else {
      jQuery('#tr_ship_wrap').hide();
    }
  }
  // Show tax if its applicable..
  if (data['tax'] != 'no' && data['tax'] != '0.00') {
    jQuery('#tr_tax_wrap').show();
    jQuery('td[class="align-right tax-total"]').html(data['tax']);
    ++rowspan;
  } else {
    jQuery('#tr_tax_wrap').hide();
  }
  // Has a coupon been applied?
  if (data['couponhtml'] != undefined && data['couponhtml'] != '') {
    // If coupon area existed, remove it now to prevent dupes..
    if (jQuery('#tr_coupon_wrap').html()) {
      jQuery('#tr_coupon_wrap').remove();
    }
    jQuery('.tbl-totals tr:first').after(data['couponhtml']);
    ++rowspan;
  } else {
    // If the coupon was applied, then removed, remove box..
    if (data['couponhtml'] == '') {
      if (jQuery('#tr_coupon_wrap').html()) {
        jQuery('#tr_coupon_wrap').remove();
      }
    }
  }
  jQuery('td[class="align-right total-amount"]').html(data['total']);
  // Re-adjust rowspan..
  jQuery('.tbl-totals tr:first td:first').attr('rowspan', rowspan);
}

//---------------------------
// Tax Info
//---------------------------

function mm_taxInfo() {
  var cur = jQuery('#tr_tax_wrap td:first').html();
  jQuery('#tr_tax_wrap td:first').html('<p style="text-align:center;margin:0;padding:0"><i class="fa fa-spinner fa-spin fa-fw"></i></p>')
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=tax-info&id=0',
      dataType: 'json',
      success: function(data) {
        jQuery('#tr_tax_wrap td:first').html(cur);
        if (data['resp'] == 'OK') {
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
        }
      }
    });
  });
  return false;
}

//---------------------------
// Method reload
//---------------------------

function mm_methodReload(method, path) {
  jQuery('td[class="pay-method"]').css('background', 'url(' + path + 'images/wait.gif) no-repeat 75% 50%');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=method-reload&id=' + method,
      dataType: 'json',
      success: function(data) {
        jQuery('td[class="pay-method"]').css('background-image', 'none');
        switch (data['resp']) {
          case 'OK':
            jQuery('input[name="gateway"]').val(method);
            jQuery('#method-image').attr('src', data['method'][0]['img']);
            jQuery('#method-image').attr('alt', data['method'][0]['name']);
            jQuery('#method-image').attr('title', data['method'][0]['name']);
            var b = jQuery('#btn_checkout span').html('(' + data['method'][0]['name'] + ')');
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

//---------------------------
// Load Address
//---------------------------

function mm_populateData(acc) {
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=address&id=' + acc,
      dataType: 'json',
      success: function(data) {
        if (data['resp'] == 'OK') {
          jQuery('select[name="method"]').val(data['sys']['method']);
          jQuery('input[name="address1"]').val(data['sys']['address1']);
          jQuery('input[name="address2"]').val(data['sys']['address2']);
          jQuery('input[name="city"]').val(data['sys']['city']);
          jQuery('input[name="county"]').val(data['sys']['county']);
          jQuery('input[name="postcode"]').val(data['sys']['postcode']);
          jQuery('select[name="country"]').val(data['sys']['country']);
        }
      }
    });
  });
  return false;
}

//---------------------------
// Download
//---------------------------

function mm_dl(id, txt, path) {
  var cur = jQuery('#trdl_' + id + ' td.tb-detail').html();
  jQuery('#trdl_' + id + ' td.tb-detail').html('<img src="' + path + 'images/hor-spinner.gif" alt=""><span class="prepdown">' + txt + '</span>');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'dmf=' + id,
      dataType: 'json',
      success: function(data) {
        jQuery('#trdl_' + id + ' td.tb-detail').html(cur);
        switch (data['resp']) {
          case 'TOKEN':
            window.location = 'index.php?dmf=' + data['itemid'] + '&t=' + data['token'];
            break;
          case 'LOCK':
            window.location = 'index.php?msg=6';
            break;
          case 'RDR-INDEX':
            window.location = 'index.php';
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

//---------------------------
// Processor
//---------------------------

function mm_processor(action, obj) {
  // Button class..
  var cl = jQuery(obj).attr('class');
  // Get italics class..
  var cur = jQuery('button[class="' + cl + '"] i').attr('class');
  jQuery('button[class="' + cl + '"] i').attr('class', 'fa fa-spinner fa-spin fa-fw');
  jQuery('button[class="' + cl + '"] i').prop('disabled', true);
  jQuery(document).ready(function() {
    jQuery.ajax({
      type: 'POST',
      url: 'index.php?ajax=' + action + '&id=0',
      data: jQuery("#formarea > form").serialize(),
      cache: false,
      dataType: 'json',
      success: function(data) {
        jQuery('button[class="' + cl + '"] i').removeProp('disabled');
        jQuery('button[class="' + cl + '"] i').attr('class', cur);
        switch (data['resp']) {
          case 'OK':
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
          case 'OK-BASKET':
            jQuery('span[class="title baskettitle"]').css('background-image','none');
            jQuery('.cartWrapper').html('<p class="nothing_to_show">' + data['nothing'] + '</p>');
            jQuery('span[class="clearall"]').hide();
            jQuery('span[class="basket-count-items"]').html('0');
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

//---------------------------
// Login/forgot password
//---------------------------

function mswLogin(action) {
  switch (action) {
    case 'forgot':
    case 'enter':
      switch (action) {
        case 'forgot':
          if (jQuery('input[name="e"]').val() == '') {
            jQuery('input[name="e"]').focus();
            return false;
          }
          break;
        case 'enter':
          if (jQuery('input[name="e"]').val() == '') {
            jQuery('input[name="e"]').focus();
            return false;
          } else {
            if (jQuery('input[name="p"]').val() == '') {
              jQuery('input[name="p"]').focus();
              return false;
            }
          }
          break;
      }
      jQuery(document).ready(function() {
        var cur = jQuery('#b' + action + ' i').attr('class');
        jQuery('#b' + action + ' i').attr('class', 'fa fa-spinner fa-spin fa-fw');
        jQuery('#b' + action + ' i').prop('disabled', true);
        jQuery.post('index.php?ajax=login&id=' + action, {
            e: jQuery('input[name="e"]').val(),
            p: jQuery('input[name="p"]').val()
          },
          function(data) {
            jQuery('#b' + action + ' i').removeProp('disabled');
            jQuery('#b' + action + ' i').attr('class', cur);
            switch (data['resp']) {
              case 'OK':
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
              case 'rdr':
                window.location = data['wind'];
                break;
              case 'err':
                mm_alert(
                  data['title'],
                  data['msg'],
                  'err'
                );
                break;
            }
          }, 'json');
      });
      return false;
      break;
    case 'forgot-load':
      jQuery('tr[class="cell-reload"]').show();
      jQuery('#bforgot').show();
      jQuery('tr[class="cell-pass"]').hide();
      jQuery('tr[class="cell-forgot"]').hide();
      jQuery('#benter').hide();
      if (jQuery('input[name="e"]').val() == '') {
        jQuery('input[name="e"]').focus();
      }
      break;
    case 'forgot-cancel':
      jQuery('tr[class="cell-reload"]').hide();
      jQuery('#bforgot').hide();
      jQuery('tr[class="cell-pass"]').show();
      jQuery('tr[class="cell-forgot"]').show();
      jQuery('#benter').show();
      if (jQuery('input[name="e"]').val()) {
        if (jQuery('input[name="p"]').val() == '') {
          jQuery('input[name="p"]').focus();
        }
      } else {
        jQuery('input[name="e"]').focus();
      }
      break;
  }
}

//----------------------
// Bootstrap Alert
//----------------------

function mm_alert(msg, txt, type) {
  jQuery('div[class="modal-backdrop fade in"]').remove();
  jQuery('#bootlogbox').remove();
  switch(type) {
    case 'err':
      BootstrapDialog.show({
        title     : '<i class="fa fa-warning fa-fw"></i> ' + msg,
        message   : txt,
        type      : BootstrapDialog.TYPE_DANGER,
        id        : 'bootlogbox',
        draggable : true
      });
      break;
    default:
      BootstrapDialog.show({
        title     : msg,
        message   : txt,
        type      : BootstrapDialog.TYPE_PRIMARY,
        id        : 'bootlogbox',
        draggable : true
      });
      break;
  }
}

//-----------------------
// Sound Manager Player
//-----------------------

function mm_Player(id, file, ipath) {
  // We populate the href programatically to prevent validation errors..
  // HTML doesn`t support mp3 files in the href tag..
  if (jQuery('#play-' + id).attr('href') == '#') {
    jQuery('#play-' + id).attr('href', file)
  }
  soundManager.stopAll();
  var current = jQuery('#play-' + id + ' i').attr('class');
  if (current == 'fa fa-play fa-fw') {
    // Reset all except current id..
    jQuery("tbody .sm2_button").each(function() {
      var tr = jQuery(this).attr('id');
      var trid = tr.substring(5);
      if (trid != id) {
        jQuery('#play-' + trid + ' i').attr('class', 'fa fa-play fa-fw');
        jQuery('#col_track_' + trid + ' .td-track').css('background-image', 'none');
      }
    });
    jQuery('#play-' + id + ' i').attr('class', 'fa fa-stop fa-fw');
    soundManager.setup({
      url: ipath + 'swf/',
      debugMode: false,
      onready: function() {
        soundManager.createSound({
          id: 'play-' + id,
          url: jQuery('#play-' + id).attr('href'),
          onfinish: function() {
            jQuery('#' + this.id + ' i').attr('class', 'fa fa-play fa-fw');
            jQuery('#col_track_' + id + ' .td-track').css('background-image', 'none');
          }
        });
        soundManager.play('play-' + id);
      }
    });
    jQuery('#col_track_' + id + ' .td-track').css('background', 'url(' + ipath + 'images/equalizer.gif) no-repeat 98% 50%');
  } else {
    jQuery('#play-' + id + ' i').attr('class', 'fa fa-play fa-fw');
    jQuery('#col_track_' + id + ' .td-track').css('background-image', 'none');
  }
}

//-----------------------
// Terms and conditions
//-----------------------

function mm_tacInfo() {
  jQuery('#tacarea').removeClass('terms_and_conditions').addClass('terms_and_conditions_info');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=tac&id=0',
      dataType: 'json',
      success: function(data) {
        jQuery('#tacarea').removeClass('terms_and_conditions_info').addClass('terms_and_conditions');
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
      }
    });
  });
  return false;
}

//---------------------
// Key code
//---------------------

function mm_getKeyCode(e) {
  var unicode = (e.keyCode ? e.keyCode : e.charCode);
  return unicode;
}

//-----------------------
// Menu chevron toggle
//-----------------------

jQuery(document).ready(function(){
  jQuery('#mainmenulist .list-group-item').click(function() {
    var curstate = jQuery(this).find('span i').attr('class');
    switch (curstate) {
      case 'fa fa-chevron-right fa-fw':
        jQuery(this).find('span i').removeClass('fa fa-chevron-right fa-fw').addClass('fa fa-chevron-down fa-fw');
        break;
      default:
        jQuery(this).find('span i').removeClass('fa fa-chevron-down fa-fw').addClass('fa fa-chevron-right fa-fw');
        break;
    }
  });
});