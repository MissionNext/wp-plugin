<?php

/**
 * @var $presentation
 */

?>

<div class="page-header">
    <h1><?php echo __('My Presentation', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>
<div class="page-content">
    <form class="form-horizontal" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        <div class="form-group row">
            <div class="col-xs-12">
                <?php wp_editor(stripslashes($presentation), 'presentation'); ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-xs-12">
                <button class="btn btn-success" type="submit"><?php echo __('Save', \MissionNext\lib\Constants::TEXT_DOMAIN); ?></button>
            </div>
        </div>
    </form>
</div>

<div class="presentation-content">
    <h2><?php echo __('Presentation Preview', \MissionNext\lib\Constants::TEXT_DOMAIN); ?></h2>
    <?php echo $preview_presentation; ?>
</div>