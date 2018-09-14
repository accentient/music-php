<?php if (!defined('PARENT')) { exit; } ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <span style="float:right"><i class="fa fa-lock fa-fw"></i></span>
                        <h3 class="panel-title">- <?php echo $adlang15[0]; ?> -</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="<?php echo mswSafeDisplay($adlang15[1]); ?>" onkeyup="if(jQuery('#errors')){jQuery('#errors').slideUp()}" onkeypress="if(mm_getKeyCode(event)==13){mm_login()}" type="text" name="user" value="" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="<?php echo mswSafeDisplay($adlang15[2]); ?>" onkeyup="if(jQuery('#errors')){jQuery('#errors').slideUp()}" onkeypress="if(mm_getKeyCode(event)==13){mm_login()}" type="password" name="pass" value="">
                                </div>
                                <div class="alert alert-warning" id="errors" style="display:none">
                                 <span></span>
                                </div>
                                <button class="btn btn-lg btn-success btn-block" type="button" onclick="mm_login()"><?php echo mswSafeDisplay($adlang15[3]); ?></button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
