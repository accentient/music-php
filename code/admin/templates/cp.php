<?php if (!defined('PARENT')) { exit; }
?>
<div class="row footerCenter">
<?php
if (LICENCE_VER=='unlocked' && $SETTINGS->afoot) {
  echo trim($SETTINGS->afoot);
} else {
?>
 <div class="defaultFooter">
 <a href="https://www.facebook.com/david.bennett.hk" onclick="window.open(this);return false"><img src="templates/images/social/facebook.png" alt="Maian Script World on Facebook"></a>
 <a href="https://twitter.com/#!/maianscripts" onclick="window.open(this);return false"><img src="templates/images/social/twitter.png" alt="Maian Script World on Twitter"></a>
 <a href="http://www.dailymotion.com/maianmedia" onclick="window.open(this);return false"><img src="templates/images/social/videos.png" alt="Maian Script World on DailyMotion"></a>
 <a href="http://www.<?php echo SCRIPT_URL; ?>/rss.html" onclick="window.open(this);return false"><img src="templates/images/social/rssfeeds.png" alt="<?php echo SCRIPT_NAME; ?> Updates"></a>
 <p>Powered by <a href="http://www.<?php echo SCRIPT_URL; ?>" title="<?php echo SCRIPT_NAME; ?>" onclick="window.open(this);return false"><?php echo SCRIPT_NAME; ?></a> (v<?php echo SCRIPT_VERSION; ?>)<br>&copy; 2007-<?php echo date('Y'); ?> Maian Script World. All Rights Reserved.</p>
 </div>
<?php
}
?>
</div>