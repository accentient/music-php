<?php if (!defined('PARENT')) { exit; } ?>
<!DOCTYPE html>
<html lang="<?php echo $this->LANG; ?>">
<head>
  <meta charset="<?php echo $this->CHARSET; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $this->TITLE; ?></title>
  <link href="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/css/bootstrap.css" rel="stylesheet">
  <link href="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/css/font-awesome.css" rel="stylesheet">
  <link href="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/css/style.css" rel="stylesheet">
	<meta http-equiv="refresh" content="<?php echo REDIRECT_TIME; ?>;url=<?php echo $this->META_REFRESH; ?>">
</head>

<body>

    <div class="container" style="margin-top:50px">
        <div class="row">
            <div class="col-lg-12">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                      <h3 class="panel-title"><i class="fa fa-refresh fa-fw"></i> <?php echo $this->TXT[0]; ?></h3>
                    </div>
                    <div class="panel-body">
					            <img src="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/images/hor-spinner.gif" alt=""><br><br>
					            <?php echo $this->TXT[1]; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
	// Twitter Bootstrap Framework Must NOT be removed unless you know what you are doing
	?>
  <script src="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/js/jquery.js"></script>
  <script src="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/js/bootstrap.js"></script>

</body>

</html>
