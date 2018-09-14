function upgradeRoutines(version,stage) {
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'upgrade.php',
      data: 'upgrade=1&action='+stage+'&sv='+version,
      dataType: 'html',
      success: function (data) {
        if (data=='done') {
          window.location='upgrade.php?completed=yes';
        } else {
          if (stage=='start') {
            jQuery('#op_start').removeClass('running').addClass('done');
            jQuery('#op_start').html('Completed');
            jQuery('#op_1').removeClass('pleasewait').addClass('running');
            jQuery('#op_1').html('Running..');
          } else {
            jQuery('#op_'+stage).removeClass('running').addClass('done');
            jQuery('#op_'+stage).html('Completed');
            jQuery('#op_'+data).removeClass('pleasewait').addClass('running');
            jQuery('#op_'+data).html('Running..');
          }
          upgradeRoutines(version,data);
        }
      }
    });
  });
  return false;
}

function checkFormAdmin() {
  var message = '';
  if (jQuery('#user').val()=='') {
    jQuery('#user').addClass('errorbox');
    message = 'Please enter username..\n';
  }
  if (jQuery('#email').val()=='') {
    jQuery('#email').addClass('errorbox');
    message += 'Please enter email address..\n';
  } else {
    if (jQuery('#email').val()!=jQuery('#email2').val()) {
      jQuery('#email2').addClass('errorbox');
      message += 'E-mail addresses do not match, try again..\n';
    }
  }
  if (jQuery('#pass').val()=='') {
    jQuery('#pass').addClass('errorbox');
    message += 'Please enter password..\n';
  } else {
    if (jQuery('#pass').val()!=jQuery('#pass2').val()) {
      jQuery('#pass2').addClass('errorbox');
      message += 'Passwords do not match, try again..\n';
    }
  }
  if (message) {
    alert(message);
    return false;
  }
}

function checkForm() {
  var message = '';
  if (jQuery('#website').val()=='') {
    jQuery('#website').addClass('errorbox');
    message = 'Please enter license centre name..\n';
  }
  if (jQuery('#email').val()=='') {
    jQuery('#email').addClass('errorbox');
    message += 'Please enter main email address..\n';
  }
  if (message) {
    alert(message);
    return false;
  }
}

function connectionTest() {
  jQuery('#test').val('Please wait..');
  jQuery('#test').attr('disabled','disabled');
  jQuery(document).ready(function() {
    jQuery.ajax({
      url: 'index.php',
      data: 'connectionTest=yes',
      dataType: 'html',
      success: function (data) {
        alert(data);
        jQuery('#test').val('Test Connection');
        jQuery('#test').removeAttr('disabled','');
      }
    });
  });
}
