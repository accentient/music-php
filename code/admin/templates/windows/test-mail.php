<?php if (!defined('PARENT') || !isset($_GET['test'])) { exit; }
if ($SETTINGS->email!='email@example.com') {
  $emails = array($SETTINGS->email);
} else {
  $emails = array();
}
if ($SETTINGS->smtp_other) {
  foreach (explode(',',$SETTINGS->smtp_other) AS $oe) {
    $emails[] = trim($oe);
  }
}
?>
<div id="iboxWindow">

<h2 class="cliph2"><i class="fa fa-envelope-o fa-fw"></i> <?php echo $adlang2[119]; ?></h2>

<div style="padding-top:30px;border-top:1px dashed #ccc" id="mail_test_area">

<?php
if (!empty($emails)) {

echo $adlang2[120];
?><br><br>
<?php
foreach ($emails AS $e) {
?>
<i class="fa fa-envelope fa-fw"></i> <?php echo $e; ?><br>
<?php
}
echo '<br>'.$adlang2[121].'<br><br>';
?>
<p style="text-align:center">
 <button type="submit" class="btn btn-info btn-sm" onclick="mm_sendTestMail()"><i class="fa fa-envelope-o fa-fw"></i> <?php echo $adlang2[119]; ?></button>
</p>
<?php
} else {

echo '<span class="mm_red"><i class="fa fa-warning fa-fw"></i> '.$adlang2[122].'</span>';

}
?>

</div>

</div>