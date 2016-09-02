<div class="page-header">
    <h1><?php echo __("Log In", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>

<div class="page-content">
    <form name="loginform" id="loginform" class="form-horizontal" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">

        <?php if(isset($errors['username'])): ?>
        <div class="block bg-danger error"><?php echo $errors['username'] ?></div>
        <?php endif; ?>

        <div class="form-group">
            <label for="user_login" class="col-sm-2 control-label"><?php _e('Username') ?></label>
            <div class="col-sm-10">
                <input type="text" name="log" id="user_login" size="20" />
            </div>
        </div>

        <?php if(isset($errors['password'])): ?>
            <div class="block bg-danger error"><?php echo $errors['password'] ?></div>
        <?php endif; ?>

        <div class="form-group">
            <label for="user_pass" class="col-sm-2 control-label"><?php _e('Password') ?></label>
            <div class="col-sm-10">
                <input type="password" name="pwd" id="user_pass" value="" size="20" />
            </div>
        </div>

        <div class="form-group">
            <label for="rememberme" class="col-sm-2 control-label"><?php _e('Remember Me') ?></label>
            <div class="col-sm-10">
                <input name="rememberme" type="checkbox" id="rememberme" value="forever"/>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-success"><?php echo __("Log In", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </div>

    </form>
</div>