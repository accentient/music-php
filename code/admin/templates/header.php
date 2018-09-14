<?php if (!defined('PARENT')) { exit; } ?>
<!DOCTYPE html>
<html lang="<?php echo $gblang[2]; ?>" dir="<?php echo $gblang[1]; ?>">

<head>

    <meta charset="<?php echo $gblang[0]; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo mswSafeDisplay($titleBar.$adlang1[0]); ?></title>

    <link href="templates/css/bootstrap.css" rel="stylesheet">
    <link href="templates/css/animate.css" rel="stylesheet">
    <link href="templates/css/font-awesome/font-awesome.css" rel="stylesheet">
	  <link href="templates/css/jquery-ui.css" rel="stylesheet">
	  <link href="templates/css/fam-icons.css" rel="stylesheet">

    <link href="templates/css/bootstrap-dialog.css" rel="stylesheet">
    <link href="templates/css/mm-admin.css" rel="stylesheet">

	  <script src="templates/js/jquery.js"></script>
	  <script src="templates/js/jquery-ui.js"></script>

    <?php
    // Load sound manager..
    if (isset($musicPlayer)) {
    ?>
    <script src="templates/js/soundmanager/soundmanager.js"></script>
    <script>
    //<![CDATA[
    jQuery(document).ready(function() {
     soundManager.setup({
       url: 'templates/swf/',
       debugMode: false
     });
    });
    //]]>
    </script>
    <?php
    }
    ?>

    <link rel="ICON" href="favicon.ico">

</head>

<body>