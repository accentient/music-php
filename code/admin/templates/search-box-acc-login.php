<?php if (!defined('PARENT') || !isset($_GET['id'])) { exit; }
$_GET['p'] = mswSafeDisplay($_GET['p']);
?>
     <div class="row" id="mm_searchBox"<?php echo (!isset($_GET['f']) ? ' style="display:none"' : ''); ?>>
		   <form method="get" action="index.php">
			    <div class="col-lg-12">
			       <div class="panel panel-default">
               <div class="panel-body">
                 <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>">
                 <input type="hidden" name="id" value="<?php echo (int) $_GET['id']; ?>">
                 <div class="form-group">
                   <label><?php echo $adlang21[6]; ?></label>
                   <input type="text" name="f" id="from" value="<?php echo $fromTo[0]; ?>" class="form-control">
                 </div>
                 <div class="form-group">
                   <label><?php echo $adlang21[7]; ?></label>
                   <input type="text" name="t" id="to" value="<?php echo $fromTo[1]; ?>" class="form-control">
                 </div>
                 <div style="margin-top:5px">
                   <button type="submit" class="btn btn-primary"><?php echo $adlang21[8]; ?></button>
                   <button type="button" class="btn btn-link" onclick="mm_windowLoc('<?php echo $_GET['p']; ?>&amp;id=<?php echo $_GET['id']; ?>')"><?php echo $gblang[13]; ?></button>
                 </div>
               </div>
				     </div>
				  </div>
				</form>
			</div>