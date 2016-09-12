<div class="page-header">
    <h1><?php echo __("Password recovery", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
    <p><?php echo __("Please enter your username or email address. You will receive a link to create a new password via email.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></p>
</div>
<div class="page-content">
    <form role="form" class="form-horizontal" action="<?php echo $_SERVER[ 'REQUEST_URI' ] ?>" method="post">

        <?php renderTemplate('_inline_form', array('form' => $form)) ?>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-success"><?php echo __("Get New Password", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </div>
    </form>
</div>