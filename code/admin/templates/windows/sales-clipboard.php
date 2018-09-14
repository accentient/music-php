<?php if (!defined('PARENT')) { exit; }
$Q    = $DB->db_query("SELECT * FROM `".DB_PREFIX."sales_clipboard` ORDER BY `id` DESC");
$r    = $DB->db_rows($Q);
$ccnt = 0;
?>
<div class="iboxWindow">

<h2 class="cliph2">
  <?php
  if ($r>0) {
  ?>
  <span style="float:right" id="clearer">
    <a href="#" onclick="mm_clearClipBoard('all');return false" title="<?php echo mswSafeDisplay($adlang9[44]); ?>"><i class="fa fa-times fa-fw"></i><?php echo mswSafeDisplay($adlang9[44]); ?></a>
  </span>
  <?php
  }
  echo $adlang9[41]; ?> (<span id="clipcount"><?php echo $r; ?></span>)
</h2>

<div class="clipBoard" id="clipBoardWrapper">

  <?php
  if ($r>0) {
  ?>
  <div class="table-responsive" style="margin-bottom:0">
  <table class="table table-striped table-hover">
  <thead>
   <tr>
    <th style="width:110px !important"><?php echo $adlang4[46]; ?></th>
	<th><?php echo $adlang4[47]; ?></th>
	<th>&nbsp;</th>
   </tr>
  </thead>
  <tbody>
  <?php
  while ($CB = $DB->db_object($Q)) {
  switch ($CB->type) {
    case 'collection':
    $Q_C    = $DB->db_query("SELECT `name`,`coverart`,`cost`,`costcd` FROM `".DB_PREFIX."collections` WHERE `id` = '{$CB->trackcol}'");
    $CTION  = $DB->db_object($Q_C);
	if (isset($CTION->name)) {
      $name   = mswSafeDisplay($CTION->name).'<span class="colTrack">&nbsp;</span>';
      $alt    = mswSafeDisplay($CTION->name);
	  $bcost  = ($CB->physical=='no' ? $adlang4[49] : $adlang4[50]);
	  $cost   = ($CB->physical=='no' ? $CTION->cost : $CTION->costcd);
	  $phys   = ' <i class="fa fa-pencil fa-fw mm_cursor" title="'.mswSafeDisplay($adlang4[51]).'" onclick="mm_clipboardOptions(\''.$CB->id.'\')"></i>';
	  $select = '<select onchange="mm_clipboardOptSelector(\''.$CB->id.'\',this.value)"><option value="no"'.($CB->physical=='no' ? ' selected="selected"' : '').'>'.$adlang4[49].' '.mswCurrencyFormat($cost,$SETTINGS->curdisplay).'</option><option value="yes"'.($CB->physical=='yes' ? ' selected="selected"' : '').'>'.$adlang4[50].' '.mswCurrencyFormat($CTION->costcd,$SETTINGS->curdisplay).'</option><option value="nothing">'.$adlang4[52].'</option></select>';
    }
	break;
    case 'track':
    $Q_T    = $DB->db_query("SELECT `collection`,`title`,`cost` FROM `".DB_PREFIX."music` WHERE `id` = '{$CB->trackcol}'");
    $CTK    = $DB->db_object($Q_T);
	if (isset($CTK->title)) {
      $Q_C    = $DB->db_query("SELECT `name`,`coverart` FROM `".DB_PREFIX."collections` WHERE `id` = '{$CTK->collection}'");
      $CTION  = $DB->db_object($Q_C);
	  if (isset($CTION->name)) {
        $name   = mswSafeDisplay($CTION->name).'<span class="colTrack">'.mswSafeDisplay($CTK->title).'</span>';
        $alt    = mswSafeDisplay($CTION->name).' ('.mswSafeDisplay($CTK->title).')';
	    $bcost  = $adlang4[48];
	    $cost   = $CTK->cost;
	    $phys   = '';
	    $select = '';
	  }
	}
    break;
  }
  if ($name) {
  ++$ccnt;
  ?>
  <tr id="clipitem_<?php echo $CB->id; ?>">
    <td><img class="clipart" src="<?php echo mswCoverArtLoader($CTION->coverart,$SETTINGS->httppath); ?>" title="<?php echo $alt; ?>" alt="<?php echo $alt; ?>"></td>
    <td class="middle"><?php echo $name; ?><div id="sel_<?php echo $CB->id; ?>" style="display:none"><?php echo $select; ?></div><div id="desc_<?php echo $CB->id; ?>"><span class="clipcost_<?php echo $CB->id; ?>"><span class="costarea"><?php echo $bcost.' '.mswCurrencyFormat($cost,$SETTINGS->curdisplay).'</span>'.$phys; ?></span></div></td>
    <td><a href="#" onclick="mm_clearClipBoard('<?php echo $CB->id; ?>');return false"><i class="fa fa-trash-o fa-fw mm_red" title="<?php echo mswSafeDisplay($adlang9[46]); ?>"></i></a></td>
  </tr>
  <?php
  }
  }
  ?>
  </tbody>
  </table>
  </div>
  <?php
  }
  ?>

</div>

<p class="nothing" id="clipnone"<?php echo ($r>0 && $ccnt>0 ? ' style="display:none"' : ''); ?>><?php echo $adlang9[45]; ?></p>

</div>