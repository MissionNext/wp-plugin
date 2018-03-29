<?php
$first_title = $form->job['name'];
$second_title = $form->job['profileData']['second_title'];
?>

<div class="page-header">
    <h1><?php echo sprintf(__('Edit %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB))) ?></h1>
</div>
<div class="page-content job-form">

    <form role="form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
        <p><?php echo "EDITING JOB: $first_title&#151;$second_title"; ?></p>
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

<script type="text/javascript">
    var job_id = <?php echo $form->job['id']; ?>;
    var job_title_field = '<?php echo $job_title_field; ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/job/edit', 'job/edit.js', array( 'jquery' ), false, true);
?>