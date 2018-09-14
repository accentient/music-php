function loadLeftMenu() {
  var html  = '<ul class="nav" id="side-menu">';
  html     += '  <li><a href="index.html" title="Dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>';
  html     += '  <li><a href="versions.html" title="Free v Commercial"><i class="fa fa-search-plus fa-fw"></i> Free v Commercial</a></li>';
  html     += '  <li><a href="white.html" title="White Label Licence"><i class="fa fa-tag fa-fw"></i> White Label Licence</a></li>';
  html     += '  <li><a href="install.html" title="Maian Music Setup"><i class="fa fa-cog fa-fw"></i> Maian Music Setup</a></li>';
  html     += '  <li><a href="music.html" title="MP3 Music Setup"><i class="fa fa-music fa-fw"></i> MP3 Music Setup</a></li>';
  html     += '  <li><a href="gateways.html" title="Payment Gateways"><i class="fa fa-credit-card fa-fw"></i> Payment Gateways</a></li>';
  html     += '  <li><a href="language.html" title="Templates/Language"><i class="fa fa-file-text-o fa-fw"></i> Templates/Language</a></li>';
  html     += '  <li><a href="faq.html" title="F.A.Q"><i class="fa fa-question-circle fa-fw"></i> F.A.Q</a></li>';
  html     += '  <li><a href="info.html" title="Software Info"><i class="fa fa-info-circle fa-fw"></i> Software Info</a></li>';
  html     += '</ul>';
  jQuery('div[class="sidebar-collapse"]').html(html);
}

function loadTopMenu() {
  var html  = '<ul class="nav navbar-top-links navbar-right hidden-xs">';
  html     += ' <li><a href="http://www.maianmusic.com/purchase.html" title="Purchase Licence"><i class="fa fa-shopping-cart fa-fw"></i> Purchase Licence</a></li>';
  html     += ' <li><a href="bugs.html" title="Bug Reports"><i class="fa fa-bug fa-fw"></i> Bug Reports</a></li>';
  html     += ' <li><a href="upgrades.html" title="Upgrades"><i class="fa fa-history fa-fw"></i> Upgrades</a></li>';
  html     += ' <li><a href="support.html" title="Support"><i class="fa fa-life-saver fa-fw"></i> Support</a></li>';
  html     += ' <li><a href="http://www.maianscriptworld.co.uk/" title="Other Software" onclick="window.open(this);return false"><i class="fa fa-external-link fa-fw"></i> Other Software</a></li>';
  html     += '</ul>';
  jQuery('div[class="navbar-header"]').after(html);
}

function loadFooter() {
  var d     = new Date();
  var year  = d.getFullYear();
  var html  = '<a href="https://www.facebook.com/david.bennett.hk" onclick="window.open(this);return false"><img src="templates/images/facebook.png" alt="Maian Script World on Facebook"></a>';
  html     += '<a href="https://twitter.com/#!/maianscripts" onclick="window.open(this);return false"><img src="templates/images/twitter.png" alt="Maian Script World on Twitter"></a>';
  html     += '<a href="http://www.dailymotion.com/maianmedia" onclick="window.open(this);return false"><img src="templates/images/videos.png" alt="Maian Script World on DailyMotion"></a>';
  html     += '<a href="http://www.maianmusic.com/rss.html" onclick="window.open(this);return false"><img src="templates/images/rssfeeds.png" alt="Maian Music Updates"></a>';
  html     += '<p>Powered by <a href="http://www.maianmusic.com" title="Maian Music" onclick="window.open(this);return false">Maian Music</a><br>&copy; '+(year==2015 ? '2015' : '2015-'+year)+' Maian Script World. All Rights Reserved.</p>';
  jQuery('div[class="row footerArea"]').html(html);
}