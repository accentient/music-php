function statsGateway(reload) {
  jQuery('#flot-gateway').css('background', 'url(templates/images/doing-something.gif) no-repeat 50% 50%');
  var options = {
    series: {
      pie: {
        show: true,
        tilt: 0.5,
        label: {
          show: true,
          radius: 1,
          formatter: labelFormatter,
          background: {
            opacity: 0.8
          }
        },
        combine: {
          color: '#999',
          threshold: 0.03
        }
      }
    }
  };
  jQuery.ajax({
    url: 'index.php?ajax=graph-gateway',
    dataType: 'json',
    success: function(data) {
      jQuery('#flot-gateway').css('background-image', 'none');
      jQuery.plot('#flot-gateway', data['data'], options);
    }
  });
}

function statsCountries(reload) {
  jQuery('#flot-countries').css('background', 'url(templates/images/doing-something.gif) no-repeat 50% 50%');
  var options = {
    series: {
      pie: {
        show: true,
        tilt: 0.5,
        label: {
          show: true,
          radius: 1,
          formatter: labelFormatter,
          background: {
            opacity: 0.8
          }
        },
        combine: {
          color: '#999',
          threshold: 0.03
        }
      }
    }
  };
  jQuery.ajax({
    url: 'index.php?ajax=graph-countries',
    dataType: 'json',
    success: function(data) {
      jQuery('#flot-countries').css('background-image', 'none');
      jQuery.plot('#flot-countries', data['data'], options);
    }
  });
}

function statsTopTracks(total, reload) {
  // Clear existing check marks..
  if (reload == 'yes') {
    jQuery('#tkdd li i').each(function() {
      jQuery(this).remove();
    });
  }
  jQuery('#stats-tracks').css('background', 'url(templates/images/doing-something.gif) no-repeat 50% 50%');
  jQuery.ajax({
    url: 'index.php?ajax=stats-tracks&c=' + total,
    dataType: 'json',
    success: function(data) {
      jQuery('#stats-tracks').css('background-image', 'none');
      // Update check mark..
      if (reload == 'yes') {
        jQuery('#tkdd_' + total).append(' <i class="fa fa-check fa-fw"></i>');
      }
      jQuery('#stats-tracks').html(data['data']);
    }
  });
}

function statsTopCollections(total, reload) {
  // Clear existing check marks..
  if (reload == 'yes') {
    jQuery('#cldd li i').each(function() {
      jQuery(this).remove();
    });
  }
  jQuery('#stats-collections').css('background', 'url(templates/images/doing-something.gif) no-repeat 50% 50%');
  jQuery.ajax({
    url: 'index.php?ajax=stats-collections&c=' + total,
    dataType: 'json',
    success: function(data) {
      jQuery('#stats-collections').css('background-image', 'none');
      // Update check mark..
      if (reload == 'yes') {
        jQuery('#cldd_' + total).append(' <i class="fa fa-check fa-fw"></i>');
      }
      jQuery('#stats-collections').html(data['data']);
    }
  });
}

function statsYearly(manual) {
  var year1 = '';
  var year2 = '';
  if (manual == 'yes') {
    var year1 = jQuery('input[name="y1"]').val();
    var year2 = jQuery('input[name="y2"]').val();
    if (year1 == '') {
      jQuery('input[name="y1"]').focus();
      return false;
    }
    if (year2 == '') {
      jQuery('input[name="y2"]').focus();
      return false;
    }
    showReloadYearArea();
  }
  jQuery('#flot-yearly').css('background', 'url(templates/images/doing-something.gif) no-repeat 50% 50%');
  var options = {
    lines: {
      show: true
    },
    points: {
      show: true
    },
    xaxis: {
      mode: 'categories',
      tickDecimals: 0,
      tickSize: 1
    },
    grid: {
      hoverable: true,
      borderColor: '#ddd',
      color: '#aaa'
    }
  };
  jQuery('#flot-yearly').bind('plothover', function (event, pos, item) {
    if (item) {
      var x = item.datapoint[0];
      var y = item.datapoint[1];
      jQuery('#tooltip').html(months(x)+' '+item.series.label+': <span class="total">'+y+'</span>').css({top: item.pageY+10, left: item.pageX+10}).fadeIn(200);
    } else {
      jQuery('#tooltip').hide();
    }
	});
  jQuery.ajax({
    url: 'index.php?ajax=graph-year&y1=' + year1 + '&y2=' + year2,
    dataType: 'json',
    success: function(data) {
      jQuery('#flot-yearly').css('background-image', 'none');
      jQuery.plot("#flot-yearly", [{
        data: data['data'],
        label: data['label']
      }, {
        data: data['data2'],
        label: data['label2']
      }], options);
    }
  });
}

function statsMonthly(myr, reload) {
  // Clear existing check marks..
  if (reload == 'yes') {
    jQuery('#mth li i').each(function() {
      jQuery(this).remove();
    });
  }
  jQuery('#flot-month').css('background', 'url(templates/images/doing-something.gif) no-repeat 50% 50%');
  var options = {
    series: {
      bars: {
        show: true,
        align: 'center',
        fillColor: '#edc240',
        lineWidth: 0
      }
    },
    yaxis: {
      tickDecimals: 0
    },
    xaxis: {
      tickSize: 1
    },
    grid: {
      hoverable: true,
      borderColor: '#ddd',
      color: '#aaa'
    }
  };
  jQuery('#flot-month').bind('plothover', function (event, pos, item) {
    if (item) {
      var x = item.datapoint[0];
      var y = item.datapoint[1];
      jQuery('#tooltip').html(x+' / <span class="total">'+y+'</span>').css({top: item.pageY+10, left: item.pageX+10}).fadeIn(200);
    } else {
      jQuery('#tooltip').hide();
    }
	});
  jQuery.ajax({
    url: 'index.php?ajax=graph-month&d=' + myr,
    dataType: 'json',
    success: function(data) {
      jQuery('#flot-month').css('background-image', 'none');
      // Update check mark..
      if (reload == 'yes') {
        var chop = myr.split('-');
        jQuery('#mth_' + chop[0]).append(' <i class="fa fa-check fa-fw"></i>');
      }
      jQuery.plot('#flot-month', [{
        data: data['data'],
        label: data['label']
      }], options);
    }
  });
}

function statsRevenue(myr, reload) {
  // Clear existing check marks..
  if (reload == 'yes') {
    jQuery('#mthr li i').each(function() {
      jQuery(this).remove();
    });
  }
  jQuery('#flot-revenue').css('background', 'url(templates/images/doing-something.gif) no-repeat 50% 50%');
  var options = {
    series: {
      bars: {
        show: true,
        align: 'center',
        fillColor: '#edc240',
        lineWidth: 0
      }
    },
    yaxis: {
      tickDecimals: 2
    },
    xaxis: {
      tickSize: 1
    },
    grid: {
      hoverable: true,
      borderColor: '#ddd',
      color: '#aaa'
    }
  };
  jQuery('#flot-revenue').bind('plothover', function (event, pos, item) {
    if (item) {
      var x = item.datapoint[0];
      var y = item.datapoint[1].toFixed(2);
      jQuery('#tooltip').html(x+' / <span class="total">'+y+'</span>').css({top: item.pageY+10, left: item.pageX+10}).fadeIn(200);
    } else {
      jQuery('#tooltip').hide();
    }
	});
  jQuery.ajax({
    url: 'index.php?ajax=graph-revenue&d=' + myr,
    dataType: 'json',
    success: function(data) {
      jQuery('#flot-revenue').css('background-image', 'none');
      // Update check mark..
      if (reload == 'yes') {
        var chop = myr.split('-');
        jQuery('#mthr_' + chop[0]).append(' <i class="fa fa-check fa-fw"></i>');
      }
      jQuery.plot('#flot-revenue', [{
        data: data['data'],
        label: data['label']
      }], options);
    }
  });
}

function mm_graphStats() {
  jQuery('#graphSetArea').css('background','url(templates/images/generating.gif) no-repeat 95% 95%');
  jQuery(document).ready(function() {
    jQuery.ajax({
      type: 'POST',
      url: 'index.php?ajax=graph-settings',
      data: jQuery("#iboxWindow > form").serialize(),
      cache: false,
      dataType: 'json',
      success: function(data) {
        jQuery('#graphSetArea').css('background-image','none');
      }
    });
  });
  return false;
}

function showReloadYearArea() {
  jQuery('div[class="row year_reload_area"]').slideToggle();
}

function labelFormatter(label, series) {
  return '<div class="pie_formatter">' + label + '<br>' + Math.round(series.percent) + '%</div>';
}