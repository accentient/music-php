<?php if (!defined('PARENT')) { exit; } ?>
<!DOCTYPE html>
<html lang="<?php echo $this->LANG; ?>" dir="<?php echo $this->DIR; ?>">
<head>
  <meta charset="<?php echo $this->CHARSET; ?>">
	<base href="<?php echo BASE_HREF; ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php echo $this->META_DESC; ?>">
  <meta name="keywords" content="<?php echo $this->META_KEYS; ?>">
  <title><?php echo $this->TITLE; ?></title>
  <link href="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/css/bootstrap.css" rel="stylesheet">
  <link href="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/css/animate.css" rel="stylesheet">
  <link href="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/css/font-awesome.css" rel="stylesheet">
	<link href="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/css/jquery-ui.css" rel="stylesheet">
	<link href="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/css/style.css" rel="stylesheet">
  <link href="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/css/bootstrap-dialog.css" rel="stylesheet">
	<script src="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/js/jquery.js"></script>
	<script src="<?php echo BASE_HREF; ?>content/<?php echo THEME; ?>/js/jquery-ui.js"></script>
  <?php
	// Load plugins if required..DO NOT remove
	echo $this->PLUGIN_LOADER;
	?>
	<link rel="ICON" href="<?php echo BASE_HREF; ?>favicon.ico">
</head>

<body>
	<header>
	    <div class="container">
	        <div class="row">

	        	<div class="col-lg-7 col-md-7 hidden-sm hidden-xs">
	            	<div class="well logo">
	            		<a href="<?php echo BASE_HREF; ?>">
	            			<i class="fa fa-music fa-fw"></i> <?php echo $this->STORE; ?>
	            		</a>
	            		<div style="margin-left:53px"><?php echo $this->TAGLINE; ?></div>
	            	</div>
	          </div>

				    <div class="col-lg-5 col-md-5 col-sm-7 col-xs-12">
	            	<div class="well">
	                    <form method="get" action="<?php echo BASE_HREF; ?>">
	                        <div class="input-group">
	                            <input onkeyup="mm_cleanSearch()" type="text" name="q" class="form-control input-search" placeholder="<?php echo $this->TXT[0]; ?>" value="<?php echo $this->KEYS; ?>">
								              <span class="input-group-btn">
	                                <button class="btn btn-default no-border-left" type="submit"><i class="fa fa-search"></i></button>
	                            </span>
	                        </div>
	                    </form>
	              </div>
	          </div>

			    </div>
	    </div>
  </header>

	<nav class="navbar navbar-inverse" role="navigation">
	    <div class="container nav-container">
		    <div style="float:right" class="hidden-sm hidden-xs">
			  <div class="collapse navbar-collapse navbar-ex1-collapse">
           <ul class="nav navbar-nav<?php echo (LOGGED_IN=='yes' ? ' navbar-right margin-right-10' : ''); ?>">
				     <li><a href="<?php echo $this->URL[5]; ?>"<?php echo (PAGE_PARAM=='basket' ? ' class="active"' : ''); ?>><i class="fa fa-shopping-cart fa-fw"></i> <?php echo $this->TXT[7]; ?></a></li>
				   </ul>
        </div>
			</div>
			<?php
			// For mobile view
			?>
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
         <span class="sr-only"><?php echo $this->TXT[2]; ?></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand visible-xs" href="<?php echo $this->URL[0]; ?>"><?php echo $this->STORE; ?></a>
      </div>
      <div class="collapse navbar-collapse navbar-ex1-collapse">
         <ul class="nav navbar-nav">
          <li><a href="<?php echo $this->URL[0]; ?>"<?php echo (PAGE_PARAM=='home' ? ' class="active"' : ''); ?>><i class="fa fa-home fa-fw"></i> <?php echo $this->TXT[3]; ?></a></li>
          <li><a href="<?php echo $this->URL[2]; ?>"<?php echo (PAGE_PARAM=='latest' ? ' class="active"' : ''); ?>><i class="fa fa-calendar fa-fw"></i> <?php echo $this->TXT[4]; ?></a></li>
					<li><a href="<?php echo $this->URL[3]; ?>"<?php echo (PAGE_PARAM=='popular' ? ' class="active"' : ''); ?>><i class="fa fa-heart fa-fw"></i> <?php echo $this->TXT[5]; ?></a></li>
					<?php
					// If logged in, show account menu..
					if (LOGGED_IN == 'yes') {
					?>
					<li class="nav-dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
					   <i class="fa fa-unlock fa-fw"></i> <?php echo $this->TXT[1]; ?> <span class="caret"></span>
						</a>
					  <ul class="dropdown-menu">
					   <li><a href="<?php echo $this->URL[1]; ?>" rel="nofollow"><i class="fa fa-dashboard fa-fw"></i> <?php echo $this->TXT[14]; ?></a></li>
					   <li><a href="<?php echo $this->URL[6]; ?>" rel="nofollow"><i class="fa fa-user fa-fw"></i> <?php echo $this->TXT[11]; ?></a></li>
					   <li><a href="<?php echo $this->URL[7]; ?>" rel="nofollow"><i class="fa fa-music fa-fw"></i> <?php echo $this->TXT[12]; ?></a></li>
					   <li><a href="<?php echo $this->URL[8]; ?>" rel="nofollow"><i class="fa fa-lock fa-fw"></i><?php echo $this->TXT[13]; ?></a></li>
					  </ul>
          </li>
					<li class="hidden-lg hidden-md"><a href="<?php echo $this->URL[5]; ?>"<?php echo (PAGE_PARAM=='basket' ? ' class="active"' : ''); ?>><i class="fa fa-shopping-cart fa-fw"></i> <?php echo $this->TXT[7]; ?></a></li>
					<?php
					} else {
					?>
					<li><a href="<?php echo $this->URL[1]; ?>"<?php echo (PAGE_PARAM=='account' ? ' class="active"' : ''); ?>><i class="fa fa-lock fa-fw"></i> <?php echo $this->TXT[1]; ?></a></li>
				    <li class="hidden-lg hidden-md"><a href="<?php echo $this->URL[5]; ?>"<?php echo (PAGE_PARAM=='basket' ? ' class="active"' : ''); ?>><i class="fa fa-shopping-cart fa-fw"></i> <?php echo $this->TXT[7]; ?></a></li>
					<?php
					}
					?>
				</ul>
      </div>
    </div>
  </nav>

	<div class="container main-container">
        <div class="row">
        	<div class="col-lg-3 col-md-3 col-sm-12">

			    <?php
          // FILTERS
          // List and search screens ONLY
          if (!empty($this->FILTERS)) {
          ?>
          <div class="col-lg-12 col-md-12 col-sm-12 filtersarea">
	        		<div class="no-padding">
	            		<span class="title"><?php echo $this->TXT_GLOBAL[0]; ?></span>
	            </div>
              <div>
              <select name="filters" class="form-control" onchange="mm_SearchFilters()">
                <option value="clftrs">- - - - - - - - - -</option>
                <?php
                foreach ($this->FILTERS AS $filtersKey => $filtersVal) {
                ?>
                <option value="<?php echo $filtersKey; ?>"<?php echo (isset($_SESSION['mmFilters']) && $_SESSION['mmFilters'] == $filtersKey ? ' selected="selected"' : ''); ?>><?php echo $filtersVal; ?></option>
                <?php
                }
                ?>
              </select>
              </div>
          </div>
          <?php
          }

				  // DISPLAY COLLECTION COVER
				  // Collection page ONLY..
				  if (isset($this->COLLECTION->coverart)) {
				  ?>
			    <div class="col-lg-12 col-md-12 col-sm-6 collection-cover hidden-xs hidden-sm">
				   <img src="<?php echo $this->BUILD->cover($this->COLLECTION->coverart); ?>" alt="" class="img-responsive">
				  </div>
				  <?php
			  	}
				  ?>

        	<div class="col-lg-12 col-md-12 col-sm-6">
	        		<div class="no-padding">
	            		<span class="title"><?php echo $this->TXT[8]; ?></span>
	            </div>
					    <div class="list-group list-categories" id="mainmenulist">
                <div class="list-group">
                  <?php
                  // List styles..
                  if (!empty($this->STYLES)) {
                  foreach ($this->STYLES AS $sk => $sv) {
                  // Determine url..
                  $url = array(
                   'seo' => array(
                    ($sv['slug'] ? $sv['slug'] : $this->SEO->filter($sv['name'])),
                    ($sv['slug']=='' ? $sk : '')
                   ),
                   'standard' => array(
                    '#' => $sk
                   )
                  );
                  //-----------------------------------------------------
                  // Does this top level style have sub styles?
                  //-----------------------------------------------------
                  if (!empty($this->STYLES[$sk]['sub'])) {
                  ?>
                  <a href="#menupos<?php echo $sk; ?>" class="list-group-item" data-toggle="collapse"><?php echo mswSafeDisplay($sv['name']); ?> <span class="menu-ico-collapse"><i class="fa fa-chevron-right fa-fw"></i></span></a>
                  <div class="collapse pos-absolute" id="menupos<?php echo $sk; ?>">
                   <?php
                   foreach ($this->STYLES[$sk]['sub'] AS $sbk => $sbv) {
                    // Determine url..
                    // If linked to collection, link is already built..
                    if ($sbv['colurl']) {
                      $subUrl = $sbv['colurl'];
                    } else {
                      $url = array(
                       'seo' => array(
                        ($sbv['slug'] ? $sbv['slug'] : $this->SEO->filter($sbv['name'])),
                        ($sbv['slug']=='' ? $sbv['id'] : '')
                       ),
                       'standard' => array(
                        '#' => $sbv['id']
                       )
                      );
                      $subUrl = BASE_HREF.$this->SEO->url('style',$url);
                    }
                    // Are we showing counts?
                    if ($sbv['linked'] == 'yes') {
                      $showCount = DISPLAY_STYLE_COUNTS_LINKED;
                    } else {
                      $showCount = DISPLAY_STYLE_COUNTS;
                    }
                   ?>
                   <a href="<?php echo $subUrl; ?>" class="list-group-item sub-item"><?php echo mswSafeDisplay($sbv['name']); ?> <?php echo ($showCount ? '<span class="style_count">(' . $sbv['count'] . ')</span>' : ''); ?></a>
                   <?php
                   }
                   ?>
                  </div>
                  <?php
                  //----------------------
                  // End sub styles
                  //----------------------
                  } else {
                  ?>
                  <a href="<?php echo BASE_HREF.$this->SEO->url('style',$url); ?>" class="list-group-item"><?php echo mswSafeDisplay($sv['name']); ?> <?php echo (DISPLAY_STYLE_COUNTS ? '<span class="style_count">(' . $sv['count'] . ')</span>' : ''); ?></a>
						      <?php
                  }
                  }
                  }
                  ?>
                </div>
              </div>
				  </div>

				<div class="col-lg-12 col-md-12 col-sm-6">
	        		<div class="no-padding">
	            		<span class="title"><?php echo $this->TXT[9]; ?></span>
	            	</div>
					<div class="list-group list-categories" id="mainmenulist2">
					    <?php
						// Other pages..
						if (!empty($this->OTHER)) {
						foreach ($this->OTHER AS $ok => $ov) {
						// Determine url..
						$url = array(
						 'seo' => array(
						  ($ov['slug'] ? $ov['slug'] : $this->SEO->filter($ov['name'])),
						  ($ov['slug']=='' ? $ok : '')
						 ),
						 'standard' => array(
						  '#' => $ok
						 )
						);
						?>
						<a href="<?php echo BASE_HREF.$this->SEO->url('pg',$url); ?>" class="list-group-item"><?php echo mswSafeDisplay($ov['name']); ?></a>
						<?php
						}
						}
						?>
					</div>
				</div>

			</div>

      <div class="clearfix visible-sm"></div>