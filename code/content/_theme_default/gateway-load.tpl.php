<?php if (!defined('PARENT')) { exit; } ?>
<!DOCTYPE html>
<html lang="<?php echo $this->LANG; ?>">
<head>
  <meta charset="<?php echo $this->CHARSET; ?>">
	<base href="<?php echo BASE_HREF; ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $this->TITLE; ?></title>
  <link href="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/css/bootstrap.css" rel="stylesheet">
  <link href="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/css/font-awesome.css" rel="stylesheet">
  <link href="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/css/style.css" rel="stylesheet">
	<?php
	// Refresh for some gateways..
	if (isset($this->META_REFRESH)) {
	?>
	<meta http-equiv="refresh" content="<?php echo REDIRECT_TIME; ?>;url=<?php echo $this->META_REFRESH; ?>">
	<?php
	}
	?>
</head>
<body>

    <div class="container" style="margin-top:50px">
        <div class="row">
            <div class="col-lg-12">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-credit-card fa-fw"></i> <?php echo $this->TXT[0]; ?></h3>
                    </div>
                    <div class="panel-body">
                     <img src="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/images/hor-spinner.gif" alt="">
                     <form method="post" action="<?php echo $this->SERVER; ?>" id="payform">
                     <?php
                     // PAYMENT GATEWAY FIELDS
                     // You may add to the fields array if you wish to send additional info..(advanced users)
                     if (!empty($this->FIELDS)) {
                     foreach ($this->FIELDS AS $k => $v) {
                     ?>
                     <input type="hidden" name="<?php echo $k; ?>" value="<?php echo mswSafeDisplay($v); ?>">
                     <?php
                     }
                     }
                     ?>
                     </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/js/jquery.js"></script>
    <script src="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/js/bootstrap.js"></script>
    <?php
    // Triggers form transmission to gateway..
    if (!empty($this->FIELDS)) {
    ?>
    <script>
    //<![CDATA[
    jQuery(document).ready(function() {
      setTimeout(function() {
        jQuery('#payform').submit();
      }, 3000);
    });
    //]]>
    </script>
    <?php
    }
    ?>

</body>

</html>
