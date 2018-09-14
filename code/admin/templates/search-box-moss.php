<?php if (!defined('PARENT')) { exit; }
$_GET['p'] = mswSafeDisplay($_GET['p']);
$windowLoc = '';
?>
     <div class="row" id="mm_searchBox"<?php echo (!isset($_GET['q']) ? ' style="display:none"' : ''); ?>>
		   <form method="get" action="index.php">
			    <div class="col-lg-12">
             <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>">
			       <div class="panel panel-default">
               <div class="panel-body">
                 <div class="form-group">
                   <label><?php echo $adlang21[6]; ?></label>
                   <input type="text" name="fr" id="from" value="<?php echo $fromTo[0]; ?>" class="form-control">
                 </div>
                 <div class="form-group">
                   <label><?php echo $adlang21[7]; ?></label>
                   <input type="text" name="to" id="to" value="<?php echo $fromTo[1]; ?>" class="form-control">
                 </div>
                 <div style="margin-top:5px">
                   <button type="submit" class="btn btn-primary"><?php echo $gblang[21]; ?></button>
                   <button type="button" class="btn btn-link" onclick="mm_windowLoc('<?php echo $_GET['p'].$windowLoc; ?>')"><?php echo $gblang[13]; ?></button>
                 </div>
               </div>
				     </div>
				  </div>
				</form>
			</div>