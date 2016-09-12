<?php
/**
 * @var $form \MissionNext\lib\form\Form object if no errors
 */
?>
<?php if(isset($form)): ?>
<div class="page-header">
    <h1><?php echo __('Reset Password', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>

<div class="page-content">

    <form role="form" class="form-horizontal" action="<?php echo $_SERVER[ 'REQUEST_URI' ] ?>" method="post">

        <?php renderTemplate('_inline_form', array('form' => $form)) ?>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-success"><?php echo __("Reset Password", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </div>
    </form>

</div>
<?php endif; ?>