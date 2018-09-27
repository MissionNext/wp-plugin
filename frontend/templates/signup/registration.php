<?php
/**
 * @var $profileForm \MissionNext\lib\form\Form
 * @var $registrationForm \MissionNext\lib\form\RegistrationForm
 * @var $role String
 * @var $domain String
 */

if ($role == "candidate" && $site == 4) {
 	echo "<p>&nbsp;</p><p align='center'>Link to <a href='https://jg.$domain/signup/organization'>Journey Guide Registration</a></p>";
} else {
?>
<div class="page-header">
    <h1><?php echo sprintf(__("%s Registration"), ucfirst(getCustomTranslation($role))) ?></h1>
</div>
<div class="page-content">

    <form role="form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST" class="form-horizontal">

        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render("_inline_form", array('form' => $registrationForm)) ?>

        <?php if ($registrationForm->captcha_image) { ?>
            <div class="form-group">
                <?php if (isset($registrationForm->errors['captcha'])) {
                    foreach ($registrationForm->errors['captcha'] as $error) { ?>
                        <div class="col-sm-offset-2 col-sm-10 text-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php }
                } ?>

                <label class="col-sm-3 control-label" for="captcha"><?php echo __('Captcha', \MissionNext\lib\Constants::TEXT_DOMAIN) ?> </label>

                <div class="col-sm-9">
                    <img src="<?php echo $registrationForm->captcha_image ?>" title="Completely Automated Public Test to tell Computers and Humans Apart" alt="<?php echo __("Captcha", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>"/>
                    <input name="captcha[prefix]" type="hidden" value="<?php echo $registrationForm->captcha_prefix ?>"/>
                </div>

                <div class="col-sm-9 col-sm-offset-3">
                    <?php if ($site == 4) { ?>
                        <input id="captcha" name="captcha[value]" type="text" style="width: 150px;" /> <br />To prove you are a real person
                    <?php } else { ?>
                        <input id="captcha" name="captcha[value]" type="text"/> <br />To prove you are a real person
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-default"><?php echo __("Sign up", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </div>
    </form>
</div>
<?php } // else if ($role == "candidate" && $site == 4) ?>

<script type="text/javascript">
    var userrole = '<?php echo $role; ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/signup/registration', 'signup/registration.js', array( 'jquery', 'jquery-ui-tabs' ), false, true);
?>