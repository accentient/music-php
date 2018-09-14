//----------------------
// Login
//----------------------

function mm_login() {
  jQuery(document).ready(function() {
    var lU = jQuery('input[name="user"]').val();
    var lP = jQuery('input[name="pass"]').val();
    if (lU == '' || lP == '') {
      return false;
    }
    jQuery('input[name="user"]').css('background', 'url(templates/images/spinner.gif) no-repeat 98% 50%');
    jQuery.post('index.php?ajax=login', {
        u: lU,
        p: lP
      },
      function(data) {
        if (data[0] == 'OK') {
          window.location = 'index.php';
        } else {
          jQuery('input[name="user"]').css('background-image', 'none');
          jQuery('#errors span').html('<i class="fa fa-warning fa-fw"></i> ' + data[0]);
          jQuery('#errors').slideDown();
        }
      }, 'json');
  });
  return false;
}

//----------------------
// Version check
//----------------------

function mm_progressBar(progress) {
  jQuery('div[class="progress-bar"]').attr('style', 'width:' + progress + '%');
}

function mm_versionCheck() {
  jQuery.ajax({
    url: 'index.php',
    data: 'p=vc&vck=yes',
    dataType: 'json',
    success: function(data) {
      mm_progressBar(100);
      setTimeout(function() {
        jQuery('div[class="row versioncheck"]').html(data['html']);
        jQuery('span[class="help-block"]').hide();
      }, 1500);
    }
  });
  return false;
}

//----------------------
// Cover Art
//----------------------

function mm_selectCoverArt(image, rel) {
  jQuery('input[name="coverart"]').val(image);
  jQuery('.cover_art').attr('src', 'templates/images/tempart-loading.png?' + new Date().getTime());
  setTimeout(function() {
    jQuery('.cover_art').attr('src', rel + image + '?' + new Date().getTime());
    jQuery('#clearArt').show();
  }, 1500);
}

function mm_clearCoverArt() {
  jQuery('input[name="coverart"]').val('');
  jQuery('.cover_art').attr('src', 'templates/images/tempart.png?' + new Date().getTime());
}

function mm_reloadCoverArt(folder) {
  jQuery('#winCoverArt').html('<p class="spinner"></p>');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=cover-art&folder=' + folder,
      dataType: 'json',
      success: function(data) {
        jQuery('#winCoverArt').html(data['resp']);
      }
    });
  });
  return false;
}

//--------------------------
// Re-order via drag/drop
//--------------------------

function mm_reOrderData(area, page) {
  jQuery(function() {
    jQuery('#' + area + ' tbody').sortable({
      opacity: 0.6,
      cursor: 'move',
      update: function() {
        var order = jQuery(this).sortable("serialize");
        jQuery.post('index.php?ajax=' + page,
          order,
          function(data) {
            // Nothing doing..add custom ops if necessary..
          },
          'json');
      }
    });
  });
}

//----------------------
// Music Player Options
//----------------------

function mm_changePlayState(id, type, file) {
  if (file == '' && jQuery('#prev_music_' + id)) {
    if (jQuery('#prev_music_' + id).val() != '') {
      var file = jQuery('#prev_music_' + id).val();
    }
  }
  if (file == '') {
    if (jQuery('#prev_music_' + id)) {
      var file = jQuery('#prev_music_' + id).focus();
    }
    return false;
  }
  if (jQuery('#play-' + id).attr('href') == '#') {
    jQuery('#play-' + id).attr('href', file)
  }
  switch (type) {
    case 'single':
      soundManager.stopAll();
      var current = jQuery('#play-' + id + ' i').attr('class');
      if (current == 'fa fa-play fa-fw') {
        // Reset all except current id..
        jQuery("#dragArea tbody tr").each(function() {
          var tr = jQuery(this).attr('id');
          var trid = tr.substring(7);
          if (trid != id) {
            jQuery('#play-' + trid + ' i').attr('class', 'fa fa-play fa-fw');
          }
        });
        jQuery('#play-' + id + ' i').attr('class', 'fa fa-stop fa-fw');
        soundManager.createSound({
          id: 'play-' + id,
          url: jQuery('#play-' + id).attr('href'),
          onfinish: function() {
            jQuery('#' + this.id + ' i').attr('class', 'fa fa-play fa-fw');
          }
        });
        soundManager.play('play-' + id);
      } else {
        jQuery('#play-' + id + ' i').attr('class', 'fa fa-play fa-fw');
      }
      break;
    case 'tracks':
      soundManager.stopAll();
      var current = jQuery('#play-' + id + ' i').attr('class');
      if (current == 'fa fa-play fa-fw') {
        // Reset all except current id..
        jQuery("#trackArea .sm2_button").each(function() {
          var tr = jQuery(this).attr('id');
          var trid = tr.substring(5);
          if (trid != id) {
            jQuery('#play-' + trid + ' i').attr('class', 'fa fa-play fa-fw');
          }
        });
        jQuery('#play-' + id + ' i').attr('class', 'fa fa-stop fa-fw');
        soundManager.createSound({
          id: 'play-' + id,
          url: jQuery('#play-' + id).attr('href'),
          onfinish: function() {
            jQuery('#' + this.id + ' i').attr('class', 'fa fa-play fa-fw');
          }
        });
        soundManager.play('play-' + id);
      } else {
        jQuery('#play-' + id + ' i').attr('class', 'fa fa-play fa-fw');
      }
      break;
  }
}

//----------------------
// Search Actions
//----------------------

function mm_doSearchEvent(page, event) {
  if (mm_getKeyCode(event) == 13) {
    var sKeys = jQuery('input[name="q"]').val();
    if (sKeys == '') {
      jQuery('input[name="q"]').focus();
      return false;
    }
    // Are there hidden vars?
    var add = '';
    jQuery('input[type="hidden"]').each(function() {
      add += '&' + jQuery(this).attr('name') + '=' + jQuery(this).attr('value');
    });
    window.location = '?p=' + page + '&q=' + sKeys + add;
  }
}

function mm_doSearch(page) {
  var sKeys = jQuery('input[name="q"]').val();
  if (sKeys == '') {
    jQuery('input[name="q"]').focus();
    return false;
  }
  // Are there hidden vars?
  var add = '';
  jQuery('input[type="hidden"]').each(function() {
    add += '&' + jQuery(this).attr('name') + '=' + jQuery(this).attr('value');
  });
  window.location = '?p=' + page + '&q=' + sKeys + add;
}

function mm_showSearch() {
  jQuery('#sbox').toggle('slow',
    function() {
      jQuery('input[name="q"]').focus()
    }
  );
  return false;
}

function mm_doSearchDateEvent(page, event) {
  if (mm_getKeyCode(event) == 13) {
    var from = jQuery('input[name="f"]').val();
    var to = jQuery('input[name="t"]').val();
    if (from == '' || to == '') {
      if (from == '') {
        jQuery('input[name="f"]').focus();
      } else {
        jQuery('input[name="t"]').focus();
      }
      return false;
    }
    window.location = '?p=' + page + '&f=' + from + '&to=' + to;
  }
}

function mm_doSearchDate(page) {
  var from = jQuery('input[name="f"]').val();
  var to = jQuery('input[name="t"]').val();
  if (from == '' || to == '') {
    if (from == '') {
      jQuery('input[name="f"]').focus();
    } else {
      jQuery('input[name="t"]').focus();
    }
    return false;
  }
  window.location = '?p=' + page + '&f=' + from + '&to=' + to;
}

//---------------------------
// Clipboard
//---------------------------

function mm_clipboardOptSelector(id, value) {
  if (value == 'nothing') {
    jQuery('#sel_' + id).hide();
    jQuery('#desc_' + id).show();
  } else {
    jQuery('#clipitem_' + id + ' .middle select').css('background', '#fff url(templates/images/spinner.gif) no-repeat 90% 50%');
    jQuery(document).ready(function() {
      jQuery.ajax({
        url: 'index.php',
        data: 'ajax=clipboard-choice&id=' + id + '&choice=' + value,
        dataType: 'json',
        success: function(data) {
          if (data[0] == 'OK') {
            jQuery('#clipitem_' + id + ' .middle select').css('background-image', 'none');
            jQuery('.clipcost_' + id + ' .costarea').html(jQuery('#sel_' + id + ' option[value="' + value + '"]').html());
            jQuery('#sel_' + id).hide();
            jQuery('#desc_' + id).show();
          }
        }
      });
    });
    return false;
  }
}

function mm_clipboardOptions(id) {
  jQuery('#sel_' + id).show();
  jQuery('#desc_' + id).hide();
}

function mm_clearClipBoard(id) {
  // Spinners..
  if (id == 'all') {
    jQuery('.cliph2').css('background', '#fff url(templates/images/spinner.gif) no-repeat 75% 50%');
  } else {
    jQuery('#clipitem_' + id + ' .middle').css('background', 'url(templates/images/spinner.gif) no-repeat 75% 50%');
  }
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=clear-clipboard&id=' + id,
      dataType: 'json',
      success: function(data) {
        switch (id) {
          case 'all':
            jQuery('.cliph2').css('background-image', 'none');
            jQuery('#clearer').hide();
            jQuery('#clipBoardWrapper').html('');
            jQuery('#clipnone').show();
            jQuery('#clipcount').html('0');
            break;
          default:
            if (data['count'] > 0) {
              jQuery('#clipitem_' + id).slideUp();
              jQuery('#clipcount').html(data['count']);
            } else {
              jQuery('#clearer').hide();
              jQuery('#clipBoardWrapper').html('');
              jQuery('#clipnone').show();
              jQuery('#clipcount').html('0');
            }
            break;
        }
      }
    });
  });
  return false;
}

//---------------------------
// Mail Tags Add/Remove
//---------------------------

function mm_tagBuilder(opt) {
  var n = jQuery('div[class="row tags"]').length;
  switch (opt) {
    case 'add':
      var htm = jQuery('div[class="row tags"]').first().html();
      jQuery('div[class="row tags"]:last').after('<div class="row tags" style="margin-top:5px">' + htm + '</div>');
      // Clear input data for new entry..
      jQuery('div[class="row tags"]:last input[name="tname[]"]').val('');
      jQuery('div[class="row tags"]:last input[name="tvalue[]"]').val('');
      break;
    case 'rem':
      if (n > 1) {
        jQuery('div[class="row tags"]').last().remove();
      }
      break;
    case 'add-att':
      var htm = jQuery('div[class="row tags"]').first().html();
      jQuery('div[class="row tags"]:last').after('<div class="row tags" style="margin-top:5px">' + htm + '</div>');
      // Clear input data for new entry..
      jQuery('div[class="row tags"]:last input[name="name[]"]').val('');
      jQuery('div[class="row tags"]:last input[name="file[]"]').val('');
      break;
    case 'rem-att':
      if (n > 1) {
        jQuery('div[class="row tags"]').last().remove();
      }
      break;
  }
}

//----------------------
// Get Tax Rate
//----------------------

function mm_getTaxRate(id, dest, type) {
  jQuery('input[name="'+dest+'"]').css('background', 'url(templates/images/spinner.gif) no-repeat 98% 50%');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=tax-rate&id=' + id + '&t=' + type,
      dataType: 'json',
      success: function(data) {
        jQuery('input[name="'+dest+'"]').css('background-image', 'none');
        jQuery('input[name="'+dest+'"]').val(data[0]);
      }
    });
  });
  return false;
}

//----------------------
// API Handler
//----------------------

function mm_apiHandler(job) {
  jQuery(document).ready(function() {
    switch (job) {
      case 'tweet':
        var tweeted = jQuery('textarea[name="tweet"]').val();
        if (tweeted == '') {
          jQuery('textarea[name="tweet"]').focus();
          return false;
        }
        jQuery('textarea[name="tweet"]').css({
         'background' : 'url(templates/images/generating.gif) no-repeat 50% 50%',
         'color'      : '#c0c0c0'
        });
        jQuery.post('index.php?ajax=api-tweet', {
          tweet : tweeted
        },
        function(data) {
          jQuery('textarea[name="tweet"]').css('background','url(templates/images/generating-ok.png) no-repeat 50% 50%');
          jQuery('textarea[name="tweet"]').val('');
          setTimeout(function() {
            jQuery('textarea[name="tweet"]').css('background','none');
            jQuery('textarea[name="tweet"]').focus();
          }, 2000);
          if (data[0] == 'OK') {
          } else {
            mm_alert(data[1], data[2], 'err');
          }
        },'json');
        break;
    }
  });
  return false;
}

function mm_processor(area) {
  jQuery(document).ready(function() {
    switch(area) {
      case 'agreement':
        if (jQuery('#spinID').html()) {
          jQuery('#spinID').remove();
        }
        jQuery('button[class="btn btn-success resent"]').after('&nbsp;&nbsp;<span id="spinID"><img src="templates/images/spinner.gif" alt=""></span>');
        break;
      default:
        jQuery('div[class="panel-footer"]').css('background', 'url(templates/images/doing-something.gif) no-repeat 97% 50%');
        break;
    }
    jQuery('span[class="actionMsg"]').html('');
    setTimeout(function() {
      jQuery.ajax({
        type: 'POST',
        url: 'index.php?ajax=' + area,
        data: jQuery('#wrapper > form').serialize(),
        cache: false,
        dataType: 'json',
        success: function(data) {
          switch(area) {
            case 'agreement':
              break;
            default:
              jQuery('div[class="panel-footer"]').css({
                'background-image': 'none',
                'background': '#f5f5f5'
              });
              break;
          }
          switch (area) {
            case 'tracks':
              if (data[0] == 'OK') {
                window.location = 'index.php?p=new-tracks&edit=' + jQuery('input[name="collection"]').val() + '&ok=' + data[2];
              } else {
                mm_alert(data[1], data[2], 'err');
              }
              break;
            default:
              switch(area) {
                case 'agreement':
                  if (data[0] == 'OK') {
                    jQuery('#spinID').html('<i class="fa fa-check fa-fw"></i>');
                  } else {
                    mm_alert(data[1], data[2], 'err');
                  }
                  break;
                default:
                  if (data[0] == 'OK') {
                    jQuery('span[class="actionMsg"]').html(mm_actioned(data[1]));
                  } else {
                    mm_alert(data[1], data[2], 'err');
                  }
                  break;
              }
              break;
          }
        }
      });
    }, 1500);
  });
  return false;
}

function mm_processorFileUpload(area,actbox) {
  jQuery(document).ready(function() {
    jQuery('#' + area + ' div[class="panel-footer"]').css('background', 'url(templates/images/doing-something.gif) no-repeat 97% 50%');
    jQuery('span[class="' + actbox + '"]').html('');
    var options = {
      dataType: 'json',
      success: mm_showResponse
    };
    // Bind to form submit event..
    jQuery('#' + area).submit(function() {
      jQuery(this).ajaxSubmit(options);
      return false;
    });
  });
  return false;
}

// Post-submit callback
function mm_showResponse(responseText, statusText, xhr, $form) {
  jQuery('div[class="panel-footer"]').each(function(){
    jQuery(this).css({
      'background-image': 'none',
      'background': '#f5f5f5'
    });
  });
  if (responseText[0] == 'OK') {
    jQuery('span[class="' + responseText[2] + '"]').html(mm_actioned(responseText[1]));
  } else {
    mm_alert(responseText[1], responseText[2], 'err');
  }
}

//----------------------
// Actioned
//----------------------

function mm_actioned(msg) {
  return '<span class="msgDisplay" onclick="jQuery(this).hide()"><i class="fa fa-check fa-fw"></i> ' + msg + '</span>';
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
        title     : '<i class="fa fa-warning fa-fw"></i> ' + txt,
        message   : msg,
        type      : BootstrapDialog.TYPE_DANGER,
        id        : 'bootlogbox',
        draggable : true
      });
      break;
    default:
      BootstrapDialog.show({
        title     : txt,
        message   : msg,
        type      : BootstrapDialog.TYPE_PRIMARY,
        id        : 'bootlogbox',
        draggable : true
      });
      break;
  }
}

//----------------------
// Delete routines
//----------------------

function mm_delete(row, table, id) {
  jQuery('.delete_message_' + id + ' div').html('<img src="templates/images/doing-something.gif" alt="">');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=delete&table=' + table + '&id=' + id,
      dataType: 'json',
      success: function(data) {
        if (data[0] == 'OK') {
          jQuery('#' + table + '-' + id).hide('slow');
          jQuery(row).hide('slow');
        }
      }
    });
  });
  return false;
}

function mm_del_confirm(table, id) {
  // Make sure delete confirmation row doesn`t exist to prevent dupes..
  if (jQuery('.delete_message_' + id)) {
    jQuery('.delete_message_' + id).remove();
  }
  // How many table rows are there..for colspan..
  var trs = jQuery('.table-responsive th').length;
  jQuery('#' + table + '-' + id).after('<tr class="delete_message_' + id + '"><td colspan="' + trs + '" style="padding:3px 0 3px 0"><div class="alert alert-warning mm_spinner" style="margin:0;padding:10px"><img src="templates/images/doing-something.gif" alt=""></div></td></tr>').show();
  setTimeout(function() {
    jQuery(document).ready(function() {
      jQuery.ajax({
        url: 'index.php',
        data: 'ajax=delete-confirm&table=' + table + '&id=' + id,
        dataType: 'json',
        success: function(data) {
          var delHTML = data[2] + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-xs" onclick="mm_delete(\'.delete_message_' + id + '\',\'' + table + '\',\'' + id + '\')" title="' + data[0] + '"><i class="fa fa-check fa-fw"></i> ' + data[0] + '</button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-info btn-xs" onclick="jQuery(\'.delete_message_' + id + '\').remove()" title="' + data[1] + '"><i class="fa fa-times fa-fw"></i> ' + data[1] + '</button></p>';
          jQuery('.delete_message_' + id + ' div').html(delHTML);
        }
      });
    });
  }, 200);
  return false;
}

//----------------------
// Confirm message..
//----------------------

function mm_confirm(txt) {
  var confirmSub = confirm(txt);
  if (confirmSub) {
    return true;
  } else {
    return false;
  }
}

//-------------------------
// Select all
//-------------------------

function mm_selectAll(area, val, checked) {
  if (checked == 'link') {
    jQuery('#' + area + ' input[name="' + val + '[]"]').each(function() {
      if (!jQuery(this).prop('checked')) {
        jQuery(this).prop('checked', true);
      } else {
        jQuery(this).prop('checked', false);
      }
    });
  } else {
    jQuery('#' + area + ' input[name="' + val + '[]"]').each(function() {
      if (jQuery(this).attr('name') != 'all') {
        if (!checked) {
          jQuery(this).prop('checked', false);
        } else {
          jQuery(this).prop('checked', true);
        }
      }
    });
  }
}

//---------------------
// Sale changes
//---------------------

function mm_markFree(id) {
  var checkval = jQuery('input[name="cbcheck_' + id + '"]:checked').val();
  jQuery('#freetd_' + id).html((checkval ? jQuery('input[name="free_' + id + '"]').val() : jQuery('input[name="cost_' + id + '"]').val()));
}

function mm_changePrice(id, cost, td) {
  iBox.showURL('?p=new-sale&changePrice=' + id + '_' + cost + '_' + td, '', {
    width: 250,
    height: 100
  });
}

function mm_changePriceSave(id, td) {
  var newp = jQuery('input[name="new-price"]').val();
  jQuery('tr[id="' + td + '_' + id + '"] .mm_cursor').html(newp);
  jQuery('tr[id="' + td + '_' + id + '"] .price').attr('onclick', 'mm_changePrice(' + id + ',\'' + newp + '\',\'' + td + '\')');
  jQuery('tr[id="' + td + '_' + id + '"] input[name="price[' + id + ']"]').val(newp);
  iBox.hide();
}

function mm_downloadReset() {
  jQuery('#reseticon').attr('class', 'fa fa-spinner fa-fw');
  jQuery(document).ready(function() {
    jQuery.ajax({
      type: 'POST',
      url: 'index.php?ajax=reset',
      data: jQuery('#wrapper > form').serialize(),
      cache: false,
      dataType: 'json',
      success: function(data) {
        jQuery('#reseticon').attr('class', 'fa fa-download fa-fw');
        if (data[0] == 'OK') {
          iBox.showURL('?p=new-sale&msg=reset' + data[1] + '&option=' + data[2], '', {
            width: 350,
            height: 100
          });
        } else {
          mm_alert(data[1], data[2], 'err');
        }
      }
    });
  });
  return false;
}

function mm_newInvoiceNo() {
  jQuery('input[name="invoice"]').val('');
  jQuery('input[name="invoice"]').css('background', 'url(templates/images/spinner.gif) no-repeat 98% 50%');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=next-invoice',
      dataType: 'json',
      success: function(data) {
        jQuery('input[name="invoice"]').css('background-image', 'none');
        jQuery('input[name="invoice"]').val(data['inv']);
      }
    });
  });
  return false;
}

function mm_confHisDelete(txt, id, history) {
  var confirmSub = confirm(txt);
  if (confirmSub) {
    mm_removeHistory('all' + id, history, 'no');
  } else {
    return false;
  }
}

function mm_removeHistory(his, sale, single) {
  switch (single) {
    case 'yes':
      jQuery('#his_' + his + ' i').attr('class', 'fa fa-spinner fa-fw');
      break;
    case 'no':
      jQuery('button[class="btn btn-warning btn-sm"] i').attr('class', 'fa fa-spinner fa-fw');
      break;
  }
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=clear-history&id=' + his + '&sale=' + sale,
      dataType: 'json',
      success: function(data) {
        switch (data[0]) {
          case 'all':
            jQuery('div[class="history"] tbody').html('<tr>' + data[1] + '</tr>');
            jQuery('#his_buttons').hide();
            jQuery('span[class="his_counter"]').html('0');
            break;
          default:
            jQuery('#his_' + his).remove();
            var n = jQuery('div[class="history"] tbody tr').length;
            if (n == 0) {
              jQuery('div[class="history"] tbody').html('<tr>' + data[1] + '</tr>');
              jQuery('#his_buttons').hide();
              jQuery('span[class="his_counter"]').html('0');
            } else {
              jQuery('span[class="his_counter"]').html(n);
            }
            break;
        }
      }
    });
  });
  return false;
}

function mm_lockSalePage(id) {
  var cur = jQuery('#lock-' + id).attr('class');
  var status = (cur == 'fa fa-unlock-alt fa-fw mm_green' ? 'lock' : 'unlock');
  if (status == 'lock') {
    jQuery('#lock-' + id).attr('class', 'fa fa-lock fa-fw mm_red');
  } else {
    jQuery('#lock-' + id).attr('class', 'fa fa-unlock-alt fa-fw mm_green');
  }
  iBox.showURL('?p=sales&lock=' + id + '&st=' + status, '', {
    width: 650,
    height: 400
  });
}

function mm_updateLockReason(id) {
  jQuery('textarea[name="lockreason"]').css('background', 'url(templates/images/generating.gif) no-repeat 50% 50%');
  jQuery(document).ready(function() {
    jQuery.post('index.php?ajax=lock-reason&id=' + id, {
        reason: jQuery('textarea[name="lockreason"]').val()
      },
      function(data) {
        if (data[0] == 'OK') {
          jQuery('textarea[name="lockreason"]').css('background-image', 'none');
        }
      }, 'json');
  });
  return false;
}

//---------------------
// Test Send
//---------------------

function mm_sender(type, id) {
  var testMail = jQuery('input[name="testemails"]').val();
  if (testMail == '') {
    jQuery('input[name="testemails"]').focus();
    return false;
  }
  var camp = jQuery('select[name="ar"]').val();
  jQuery('div[class="panel-footer"]').css('background', 'url(templates/images/doing-something.gif) no-repeat 97% 50%');
  jQuery('span[class="actionMsg"]').html('');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: type + '=' + id + '&send=yes&emails=' + encodeURIComponent(testMail) + '&campaign=' + camp,
      dataType: 'json',
      success: function(data) {
        jQuery('div[class="panel-footer"]').css({
          'background-image': 'none',
          'background': '#f5f5f5'
        });
        if (data[0] == 'OK') {
          jQuery('span[class="actionMsg"]').html(mm_actioned(data[1]));
        }
      }
    });
  });
  return false;
}

//---------------------
// Slug cleaner
//---------------------

function mm_slugger() {
  if (jQuery('input[name="slug"]').val() == '') {
    return '';
  }
  jQuery(document).ready(function() {
    jQuery('input[name="slug"]').css('background', 'url(templates/images/spinner.gif) no-repeat 98% 50%');
    jQuery.post('index.php?ajax=clean-slug', {
        slug: jQuery('input[name="slug"]').val()
      },
      function(data) {
        jQuery('input[name="slug"]').css('background-image', 'none');
        jQuery('input[name="slug"]').val(data['slug'])
      }, 'json');
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

//-------------------
// Scroll to
//-------------------

function mm_scrollToArea(divArea) {
  jQuery('html,body').animate({
    scrollTop: jQuery('#' + divArea).offset().top
  }, 2000);
}

// Window location..
function mm_windowLoc(page, type) {
  switch (page) {
    case 'backwards':
      window.history.back();
      break;
    default:
      if (type == 'new') {
        window.open(page);
      } else {
        window.location = '?p=' + page;
      }
      break;
  }
}

//------------------
// Menu
//------------------

jQuery(function() {
  jQuery('#side-menu').metisMenu();
  jQuery(window).bind("load resize", function() {
    width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
    if (width < 768) {
      jQuery('div.sidebar-collapse').addClass('collapse')
    } else {
      jQuery('div.sidebar-collapse').removeClass('collapse')
    }
  });
});

//--------------------
// Test Mail
//--------------------

function mm_sendTestMail() {
  jQuery('#mail_test_area').html('<p style="height:200px">&nbsp;</p>');
  jQuery('#mail_test_area').css('background', 'url(templates/images/doing-something.gif) no-repeat 50% 50%');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'ajax=send-mail-test',
      dataType: 'json',
      success: function(data) {
        if (data['msg']) {
          jQuery('#mail_test_area').css('background-image', 'none');
          jQuery('#mail_test_area').html('<p style="text-align:center;margin-top:30px"><i class="fa fa-check fa-fw bigfont"></i><br><br>' + data['msg'] + '</p>');
        }
      }
    });
  });
  return false;
}