<?php if (!defined('PARENT')) { exit; } ?>
  <script src="templates/js/bootstrap.js"></script>
  <script src="templates/js/plugins/bootstrap.dialog.js"></script>
  <script src="templates/js/plugins/jquery.metismenu.js"></script>
  <script src="templates/js/plugins/jquery.ibox.js"></script>

  <?php
  // Load slugify plugin..
	if (isset($loadSlugify)) {
  ?>
  <script src="templates/js/plugins/jquery.slugify.js"></script>
	<script>
	//<![CDATA[
    jQuery(document).ready(function() {
	  jQuery('input[name="slug"]').slugify('input[name="name"]');
	});
	//]]>
	</script>
  <?php
  }

	// Load form plugin..
  if (isset($loadFormPlugin)) {
  ?>
  <script src="templates/js/plugins/jquery.form.js"></script>
  <?php
  }

  // Load flot plugin..
  if (isset($loadFlotPlugin)) {
  ?>
  <script src="templates/js/plugins/flot/jquery.flot.min.js"></script>
  <script src="templates/js/plugins/flot/jquery.flot.tooltip.js"></script>
  <script src="templates/js/plugins/flot/jquery.flot.resize.min.js"></script>
  <script src="templates/js/plugins/flot/jquery.flot.pie.min.js"></script>
  <script src="templates/js/plugins/flot/jquery.flot.time.min.js"></script>
  <script src="templates/js/plugins/flot/jquery.flot.categories.js"></script>
  <script>
  //<![CDATA[
  function months(slot) {
    var month = <?php echo $jslang[0]; ?>;
    return month[slot];
  }
  //]]>
  </script>
  <?php
  }
	?>

  <script src="templates/js/mm-admin.js"></script>

</body>

</html>