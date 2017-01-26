<div class="page-header">
    <h1><?php echo sprintf(__('Edit %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB))) ?></h1>
</div>
<div class="page-content job-form">

    <form role="form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST" class="form-horizontal" enctype="multipart/form-data">

        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render("_form", array('form' => $form)) ?>

        <div class="control-buttons">
            <div class="left">
                <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                <a class="btn btn-default" href="/job"><?php echo __("Jobs", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>
            <div class="right">
                <button type="submit" class="btn btn-success"><?php echo __("Save", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </div>
    </form>
</div>