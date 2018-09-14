<?php if (!defined('PARENT')) { exit; }
$_GET['p'] = mswSafeDisplay($_GET['p']);
$windowLoc = '';
// Overrides..
if ($_GET['p']=='history' && isset($_GET['id'])) {
  $windowLoc = '&amp;id='.(int)$_GET['id'];
}
if ($_GET['p']=='tracks' && isset($_GET['id'])) {
  $windowLoc = '&amp;id='.(int)$_GET['id'];
}
if ($_GET['p']=='styles' && isset($_GET['sub'])) {
  $windowLoc = '&amp;sub='.(int) $_GET['sub'];
}
?>
     <div class="row" id="mm_searchBox"<?php echo (!isset($_GET['q']) ? ' style="display:none"' : ''); ?>>
		   <form method="get" action="index.php">
			    <div class="col-lg-12">
			       <div class="panel panel-default">
               <div class="panel-body">
                 <label><?php echo $gblang[35]; ?></label>
                 <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>">
                 <?php
                 // For styles..
                 if ($_GET['p']=='styles' && isset($_GET['sub'])) {
                 ?>
                 <input type="hidden" name="sub" value="<?php echo (int) $_GET['sub']; ?>">
                 <?php
                 }
                 ?>
                 <input type="text" name="q" value="<?php echo (isset($_GET['q']) ? mswSafeDisplay($_GET['q']) : ''); ?>" class="form-control">
                 <div style="margin-top:5px">
                   <button type="submit" class="btn btn-primary"><?php echo $gblang[21]; ?></button>
                   <button type="button" class="btn btn-link" onclick="mm_windowLoc('<?php echo $_GET['p'].$windowLoc; ?>')"><?php echo $gblang[13]; ?></button>
                 </div>
               </div>
				     </div>
				  </div>
				</form>
			</div>