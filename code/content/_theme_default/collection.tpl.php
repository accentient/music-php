<?php if (!defined('PARENT')) { exit; } ?>
            <div class="col-lg-9 col-md-9 col-sm-12" id="colForm">

              <div class="col-lg-12 col-sm-12">
            		<span class="title" style="margin:0"><?php echo $this->TXT[0]; ?></span>
            	</div>

				      <div class="col-lg-12 col-sm-12 margin-bottom-20">
				       <div class="row no-right-margin">
				        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 padding-top-20">

				        <?php
				        // Collection description..
				        echo mswNL2BR(mswSafeDisplay($this->COLLECTION->information));
				        ?>
				        <br><br>

				        <?php
				        // Show styles if there are any..
				        if ($this->STYLES) {
				        ?>
				        <b><?php echo $this->TXT[1]; ?></b>: <?php echo $this->STYLES; ?><br>
				        <?php
				        }

                // Show release date if there is one..
				        if ($this->COLLECTION->released>0) {
				        ?>
				        <b><?php echo $this->TXT[2]; ?></b>: <?php echo $this->DT->dateTimeDisplay($this->COLLECTION->released,$this->SETTINGS->dateformat); ?><br>
				        <?php
				        }

				        // Show cat. number if there is one..
				        if ($this->COLLECTION->catnumber) {
				        ?>
				        <b><?php echo $this->TXT[3]; ?></b>: <?php echo mswSafeDisplay($this->COLLECTION->catnumber);
				        }

                // Social/sharing buttons via AddThis..
				        if ($this->API['addthis']['code']) {
				        ?><br><br>
				        <div class="addthis_sharing_toolbox"></div>
				        <script src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $this->API['addthis']['code']; ?>" async></script>
				        <?php
				        }
				        ?>

				        </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 cost-option-bg align-center">
				        <?php
				        $nothing = 0;
				        // Is CD as download enabled?
				        if ($this->COST!='') {
				        ?>
				        <button type="button" id="mp3Button" class="btn btn-primary" onclick="jQuery('.main-container').mMusicOps('add','<?php echo $this->COLLECTION->id; ?>_MP3','mp3Button')"><i class="fa fa-cart-plus fa-fw"></i> <?php echo $this->TXT[16]; ?><br><hr class="colcostline"> <span class="colcost"><?php echo $this->COST; ?></span></button><br><br>
				        <?php
				        ++$nothing;
				        }
				        // Is CD as purchase enabled?
				        if ($this->SETTINGS->cdpur=='yes' && $this->COSTCD!='') {
				        ?>
				        <button type="button" id="cdButton" class="btn btn-success" onclick="jQuery('.main-container').mMusicOps('add','<?php echo $this->COLLECTION->id; ?>_CD','cdButton')"><i class="fa fa-cart-plus fa-fw"></i> <?php echo $this->TXT[17]; ?>: <?php echo $this->COSTCD; ?></button><br><br>
                <?php
				        ++$nothing;
				        }
				        // If neither download full cd or purchase are cd are available, show message..
				        if ($nothing==0) {
				          echo $this->TXT[14];
				        }
                // If tracks aren`t available, but a purchase option is, show message..
                if ($nothing > 0 && !$this->TRACKS) {
                  echo $this->TXT[15];
                }
				        ?>
                </div>
				       </div>
				      </div>

				      <?php
              // Show tracks..
				      if ($this->TRACKS) {
				      ?>
	            <form method="post" action="#">
				       <div class="col-lg-12 col-sm-12">
					      <table class="table table-bordered table-hover tbl-collection">
						     <thead>
                  <tr>
                   <td class="hd-play-button"><i class="fa fa-music fa-fw"></i></td>
                   <td class="hd-track"><?php echo $this->TXT[6]; ?></td>
								   <td class="hd-time hidden-sm hidden-xs"><?php echo $this->TXT[7]; ?></td>
								   <td class="hd-rate hidden-sm hidden-xs"><?php echo $this->TXT[10]; ?></td>
                   <td class="hd-check"><i class="fa fa-cart-plus fa-fw"></i></td>
                   <td class="hd-cost"><?php echo $this->TXT[9]; ?></td>
                  </tr>
                 </thead>
                <tbody>
						    <?php
							  // Collection tracks..
							  // html/collection-tracks.tpl
							  // html/track-play-button.tpl
							  echo $this->TRACKS;
							  ?>
                </tbody>
					     </table>

               <div style="text-align:right">
					      <div class="btns-cart">
						    <button type="button" id="trackButton" class="btn btn-primary" onclick="jQuery('.main-container').mMusicOps('tracks','<?php echo $this->COLLECTION->id; ?>_MP3','trackButton')"><i class="fa fa-plus fa-fw"></i> <?php echo $this->TXT[8]; ?></button>
					      </div>
               </div>
				      </div>
			 	     </form>
				     <?php
				     }

				     // Comments
				     if ($this->COMMENTS) {
				     ?>
				     <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo $this->TXT[11]; ?></span>

                <div class="cartWrapper margin-bottom-20">
                <?php
                // html/disqus.tpl
                echo $this->COMMENTS;
                ?>
                </div>

             </div>
             <?php
				     }

				     // Related..
				     if ($this->RELATED) {
				     ?>
				     <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo $this->TXT[12]; ?></span>
            </div>

				    <div class="cartWrapper margin-bottom-20">
				    <?php
				    // html/collection.tpl
				    echo $this->RELATED;
				    ?>
           </div>
				   <?php
				   }

				   // Search tags..
				   if ($this->TAGS) {
				   ?>
				   <div class="col-lg-12 col-sm-12">
            		<span class="title"><?php echo $this->TXT[13]; ?></span>
           </div>

				   <div class="col-lg-12 col-sm-12 margin-bottom-20">
				    <div class="cartWrapper margin-bottom-20">
				    <?php
				    // html/collection.tpl
				    echo $this->TAGS;
				    ?>
				    </div>
				   </div>
				   <?php
				   }
				  ?>

				  <div class="col-lg-12 col-sm-12 margin-bottom-20">
				   <div class="cartWrapper margin-bottom-20">
				    <p class="views"><i class="fa fa-binoculars fa-fw"></i>
				    <?php
				    // Hit counter..
				    echo $this->HIT_COUNTER;
				    ?>
				    </p>
				   </div>
				  </div>

        </div>