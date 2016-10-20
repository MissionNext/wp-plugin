<?php
/**
 * @var $profileForm \MissionNext\lib\form\Form
 * @var $registrationForm \MissionNext\lib\form\RegistrationForm
 * @var $role String
 */

?>

<div class="page-header">
    <h1><?php echo sprintf(__("%s Registration"), ucfirst(getCustomTranslation($role))) ?></h1>
</div>
<div class="page-content">

    <form role="form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST" class="form-horizontal">

        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render("_inline_form", array('form' => $registrationForm)) ?>

        <?php if($registrationForm->captcha_image): ?>
        <div class="form-group">
            <?php if(isset($registrationForm->errors['captcha'])): ?>
                <?php foreach($registrationForm->errors['captcha'] as $error): ?>
                    <div class="col-sm-offset-2 col-sm-10 text-danger">
                        <?php echo $error ?>
                    </div>
                <?php endforeach ?>
            <?php endif; ?>

            <label class="col-sm-3 control-label" for="captcha"><?php echo __('Captcha', \MissionNext\lib\Constants::TEXT_DOMAIN) ?> </label>

            <div class="col-sm-9">
                <img src="<?php echo $registrationForm->captcha_image ?>" title="Completely Automated Public Test to tell Computers and Humans Apart" alt="<?php echo __("Captcha", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>"/>
                <input name="captcha[prefix]" type="hidden" value="<?php echo $registrationForm->captcha_prefix ?>"/>
            </div>

            <div class="col-sm-9 col-sm-offset-3">
                <input id="captcha" name="captcha[value]" type="text"/> &nbsp; (Case Sensitive) <br>To prove you are a real person
            </div>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-default"><?php echo __("Sign up", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </div>
    </form>
</div>


<script>
    var userrole = '<?php echo $role; ?>';

    jQuery(document).on('change', '[data-dependant]', function(e){
        var field = jQuery('[data-dependant="'+jQuery(e.target).attr('data-dependant')+'"]');
        var subgroup = jQuery('.dependent-group[data-key="' + field.attr('data-dependant') + '"]');

        if(field.length == 1 && field.attr('type') != 'checkbox'){
            if(field.val()){
                subgroup.show();
            } else {
                subgroup.hide();
            }
        } else {
            if(field.is(':checked')){
                subgroup.show();
            } else {
                subgroup.hide();
            }
        }
    });

    jQuery(document).ready(function(){
        jQuery('#tabs').tabs();

        jQuery('[data-dependant]').trigger('change');
    });

</script>
