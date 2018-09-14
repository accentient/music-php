<?php if (!defined('PARENT')) { exit; }
$_GET['p'] = mswSafeDisplay($_GET['p']);
$windowLoc = '';
// Overrides..
if ($_GET['p']=='history' && isset($_GET['id'])) {
  $windowLoc = '&amp;id='.(int)$_GET['id'];
}
if ($_GET['p']=='sales' && isset($_GET['st'])) {
  $windowLoc = '&amp;st='.urlencode($_GET['st']);
}
if ($_GET['p']=='styles' && isset($_GET['sub'])) {
  $windowLoc = '&amp;sub='.(int) $_GET['sub'];
}
?>
      <div class="row" id="mm_filters"<?php echo (!isset($_GET['f']) ? ' style="display:none"' : ''); ?>>
			  <div class="col-lg-12">
			     <div class="panel panel-default">
                <div class="panel-body">
			              <label><?php echo $gblang[46]; ?></label>
						        <select name="f" class="form-control" onchange="if(this.value!=0){location=this.options[this.selectedIndex].value}">
                     <option value="0">- - - -</option>
                     <?php
                     if (!empty($filters)) {
                       foreach ($filters AS $fK => $fV) {
                       ?>
                       <option value="index.php?p=<?php echo $_GET['p'].$windowLoc; ?>&amp;f=<?php echo $fK.(isset($_GET['q']) ? '&amp;q='.mswSafeDisplay(urlencode($_GET['q'])) : ''); ?>"<?php echo (isset($_GET['f']) && $_GET['f']==$fK ? ' selected="selected"' : ''); ?>><?php echo $fV; ?></option>
                       <?php
                       }
                     }
                     ?>
                    </select>
						        <div style="margin-top:5px">
						          <button type="button" class="btn btn-link" onclick="mm_windowLoc('<?php echo $_GET['p'].$windowLoc; ?>')"><?php echo $gblang[13]; ?></button>
                    </div>
                </div>
				   </div>
				</div>
			</div>