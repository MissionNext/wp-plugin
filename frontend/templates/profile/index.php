<?php
/**
 * @var $form \MissionNext\lib\form\Form
 */

?>
<div class="page-header">
    <h1><?php echo __('Profile', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>
<div class="page-content <?php echo $userRole; ?>-form">
    <form role="form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('_form', compact('form')) ?>

        <div class="form-group">
            <div class="col-sm-12">
                <?php if ($profileCompleted) { ?>
                    <input type="submit" name="submit" class="btn btn-success" title="Allows Program to Continue. All required fields must be completed." value="<?php echo __("Submit", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>" />
                <?php } else { ?>
                    <input type="submit" name="submit" class="btn btn-success" title="Allows Program to Continue. All required fields must be completed." value="<?php echo __("Complete? Submit", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>" />
                    <!--<input type="submit" name="savelater" class="btn btn-success" title="Saves Entries Only" value="<?php echo __("Save for Later", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>" />-->
                    <br><font color="red">Note: No information is saved until all REQUIRED* fields are completed on all tabs. (You can always edit later.)</font>
                <?php }?>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    var userId = <?php echo $userId; ?>;
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/profile/index', '/profile/index.js', array( 'jquery' ), false, true);
?>