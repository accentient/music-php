/*
 * CART OPS
 * DO NOT change unless you know what you are doing!
 */

if (typeof jQuery === 'undefined') {
  throw new Error('This software requires jQuery')
}

(function($) {

  function mMusicOps(el, settings) {
    this.$el = $(el);
    this.settings = $.extend({}, jQuery.fn.mMusicOps.defaults, settings);
  }

  function mmAlertDialog(txt, msg, type) {
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

  mMusicOps.prototype.add = function(id, ele) {
    var path = this.settings.tmppath;
    var colc = id.split('_');
    if (ele != undefined) {
      var current = jQuery('#' + ele + ' i').attr('class');
      jQuery('#' + ele + ' i').attr('class', 'fa fa-spinner fa-spin fa-fw');
    } else {
      // Grab the current content and replace with spinner..
      switch(colc[1]) {
        case 'MP3':
        var current = jQuery('#col_box_' + colc[0] + ' tr:first td:nth-child(2)').html();
        jQuery('#col_box_' + colc[0] + ' tr:first td:nth-child(2)').html('').css('background', 'url(' + path + '/images/adding.gif) no-repeat 50% 50%');
        break;
        default:
        var current = jQuery('#col_box_' + colc[0] + ' tr:nth-child(2) td:nth-child(2)').html();
        jQuery('#col_box_' + colc[0] + ' tr:nth-child(2) td:nth-child(2)').html('').css('background', 'url(' + path + '/images/adding.gif) no-repeat 50% 50%');
        break;
      }
    }
    setTimeout(function() {
      jQuery.ajax({
        url: 'index.php',
        data: 'ajax=add&id=' + id,
        dataType: 'json',
        success: function(data) {
          if (ele != undefined) {
            jQuery('#' + ele + ' i').attr('class', current);
          } else {
            // Reset back..
            switch(colc[1]) {
              case 'MP3':
              jQuery('#col_box_' + colc[0] + ' tr:first  td:nth-child(2)').css('background-image', 'none');
              jQuery('#col_box_' + colc[0] + ' tr:first  td:nth-child(2)').html(current);
              break;
              default:
              jQuery('#col_box_' + colc[0] + ' tr:nth-child(2) td:nth-child(2)').css('background-image', 'none');
              jQuery('#col_box_' + colc[0] + ' tr:nth-child(2) td:nth-child(2)').html(current);
              break;
            }
          }
          if (data['resp'] == 'err') {
            mmAlertDialog(
              data['title'],
              data['msg'],
              'err'
            );
          } else {
            if (jQuery('#mmModalBox')) {
              jQuery('#mmModalBox #myModalLabel').html(data['modal']['label']);
              jQuery('#mmModalBox button[class="btn btn-primary"]').html(data['modal']['button_text']);
              jQuery('#mmModalBox div[class="modal-body"]').html(data['modal']['body']);
              jQuery('#mmModalBox div[class="modal-footer"] span').html(data['modal']['footer']);
              if (data['modal']['button_url']) {
                jQuery('#mmModalBox button[class="btn btn-primary"]').attr('onclick', "window.location='" + data['modal']['button_url'] + "'");
              }
              jQuery('#mmModalBox').modal('show');
            }
            jQuery('span[class="basket-count-items"]').html(data['count']);
          }
        }
      });
    }, 1500);
  }

  mMusicOps.prototype.tracks = function(id, ele) {
    var path = this.settings.tmppath;
    var colc = id.split('_');
    var current = jQuery('#' + ele + ' i').attr('class');
    var cnt = 0;
    jQuery("tbody input[type='checkbox']").each(function() {
      ++cnt;
    });
    jQuery('#' + ele + ' i').attr('class', 'fa fa-spinner fa-spin fa-fw');
    setTimeout(function() {
      jQuery.ajax({
        type: 'POST',
        url: 'index.php?ajax=add-tracks&id=' + id + '&trackCount=' + cnt,
        data: jQuery("#colForm > form").serialize(),
        cache: false,
        dataType: 'json',
        success: function(data) {
          jQuery('#' + ele + ' i').attr('class', current);
          if (data['resp'] == 'err') {
            mmAlertDialog(
              data['title'],
              data['msg'],
              'err'
            );
          } else {
            if (jQuery('#mmModalBox')) {
              jQuery('#mmModalBox #myModalLabel').html(data['modal']['label']);
              jQuery('#mmModalBox button[class="btn btn-primary"]').html(data['modal']['button_text']);
              jQuery('#mmModalBox div[class="modal-body"]').html(data['modal']['body']);
              jQuery('#mmModalBox div[class="modal-footer"] span').html(data['modal']['footer']);
              if (data['modal']['button_url']) {
                jQuery('#mmModalBox button[class="btn btn-primary"]').attr('onclick', "window.location='" + data['modal']['button_url'] + "'");
              }
              jQuery('#mmModalBox').modal('show');
            }
            jQuery('span[class="basket-count-items"]').html(data['count']);
          }
        }
      });
    }, 1500);
  }

  mMusicOps.prototype.remove = function(id, type) {
    var path = this.settings.tmppath;
    switch (type) {
      case 'modal':
        jQuery('#modalWrapper tr[class="modal_tr_' + id + '"] .item').css('background', 'url(' + path + '/images/wait.gif) no-repeat 50% 50%');
        setTimeout(function() {
          jQuery.ajax({
            url: 'index.php',
            data: 'ajax=rem-modal&id=' + id,
            dataType: 'json',
            success: function(data) {
              if (data['resp'] == 'OK') {
                jQuery('#modalWrapper tr[class="modal_tr_' + id + '"]').hide();
                jQuery('#mmModalBox div[class="modal-footer"] span').html(data['modal']['footer']);
              }
              if (data['count'] == '0') {
                jQuery('#mmModalBox').modal('hide');
                jQuery('span[class="basket-count-items"]').html('0');
              } else {
                jQuery('span[class="basket-count-items"]').html(data['count']);
              }
            }
          });
        }, 1500);
        break;
      case 'basket':
        jQuery('.cartWrapper tr[class="basket_tr_' + id + '"] .td-detail').css('background', 'url(' + path + '/images/wait.gif) no-repeat 50% 50%');
        setTimeout(function() {
          jQuery.ajax({
            url: 'index.php',
            data: 'ajax=rem-basket&id=' + id,
            dataType: 'json',
            success: function(data) {
              if (data['resp'] == 'OK') {
                jQuery('.cartWrapper tr[class="basket_tr_' + id + '"]').hide();
                jQuery('.cartWrapper td[class="total"]').html(data['total']);
              }
              if (data['count'] == '0') {
                jQuery('.cartWrapper').html('<p class="nothing_to_show">' + data['nothing'] + '</p>');
                jQuery('span[class="clearall"]').hide();
                jQuery('span[class="basket-count-items"]').html('0');
              } else {
                jQuery('span[class="basket-count-items"]').html(data['count']);
              }
            }
          });
        }, 1500);
        break;
    }
  }

  mMusicOps.prototype.update = function() {}

  jQuery.fn.mMusicOps = function(method) {
    var result, args = arguments;
    this.each(function() {
      var $this = $(this),
        data = $this.data('mMusicOps');
      if (data) {
        if (/^[^_]/.test(method) && typeof data[method] == 'function') {
          result = data[method].apply(data, Array.prototype.slice.call(args, 1));
          if (result !== undefined) {
            return false;
          }
        } else {
          throw new Error('Unable to find the method ' + method);
        }
      } else if (typeof method == 'object') {
        data = new mMusicOps(this, method);
        $this.data('mMusicOps', data);
      } else {
        throw new Error('Illegal arguments passed. Plugin aborted.');
      }
    })

    return result === undefined ? this : result;

  };

  jQuery.fn.mMusicOps.defaults = {
    tmppath: '-'
  };

}(jQuery));