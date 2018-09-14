<?php
// =========================================================================================================
// CUSTOM PAGE EXAMPLE - PLEASE READ
// =========================================================================================================
// Custom template pages should have the .tpl.php extension.
// You can create any code here, including custom PHP. You can reference any data about the page via the
//  $this->PAGE; object. Examples:
//
// echo $this->PAGE->name;
// echo $this->PAGE->title;
//
// Please read up on Bootstrap class usage before adding your own code
// http://getbootstrap.com/
//
// 1. You MUST use bootstrap code or else the responsive system will break when this page loads
// 2. Observe coding standards when adding your own code.
//
// The "mswSafeDisplay" function can be used to filter input (ie: htmlspecialchars)
//
// =========================================================================================================
?>
            <div class="col-lg-9 col-md-9 col-sm-12">

              <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo mswSafeDisplay($this->PAGE->name); ?></span>
            	</div>

              <div class="col-lg-12 col-sm-12">
                Lorem ipsum dolor sit amet consectetuer quis est at felis dui. Dictum vitae sollicitudin condimentum condimentum Vivamus enim venenatis at nec
                consequat. Euismod Sed laoreet libero urna Aenean Pellentesque adipiscing Curabitur tortor neque. Quisque magna elit urna leo a Pellentesque
                accumsan mus In ut. Risus Maecenas ligula ullamcorper eros eu fringilla tellus eget condimentum.
              </div>

            </div>