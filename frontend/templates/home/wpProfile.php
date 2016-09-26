<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var \MissionNext\lib\form\Form $form
 */
$group = reset($form->groups);
global $shortcode_tags;
?>
<div class="page-header">
    <h1><?php echo __('User Account', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>
<div class="page-content">
    <div class="row">
        <div class="col-sm-12">

            <form action="<?php echo $_SERVER[ 'REQUEST_URI' ] ?>" method="POST" role="form" class="form-horizontal">

                <input type="hidden" name="action" value="profile"/>

                <div id="<?php echo $group->group['symbol_key'] ?>"  class="group" data-key="<?php echo $group->group['symbol_key'] ?>">

                    <div class="form-group">

                        <?php echo $group->fields['username']->printLabel(array('class' => 'col-sm-3 control-label')) ?>
                        <div class="col-sm-9">
                            <?php echo $group->fields['username']->printField(array('disabled' => "disabled")) ?>
                        </div>
                    </div>

                    <div class="form-group">

                        <?php if($group->fields['email']->hasError()): ?>
                            <?php foreach($group->fields['email']->getError() as $error): ?>
                                <div class="col-sm-offset-2 col-sm-10 text-danger">
                                    <?php echo $error ?>
                                </div>
                            <?php endforeach ?>
                        <?php endif; ?>

                        <?php echo $group->fields['email']->printLabel(array('class' => 'col-sm-3 control-label')) ?>
                        <div class="col-sm-9">
                            <?php echo $group->fields['email']->printField() ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php if($group->fields['old_password']->hasError()): ?>
                            <?php foreach($group->fields['old_password']->getError() as $error): ?>
                                <div class="col-sm-offset-2 col-sm-10 text-danger">
                                    <?php echo $error ?>
                                </div>
                            <?php endforeach ?>
                        <?php endif; ?>

                        <?php echo $group->fields['old_password']->printLabel(array('class' => 'col-sm-3 control-label')) ?>
                        <div class="col-sm-9">
                            <?php echo $group->fields['old_password']->printField() ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php if($group->fields['password']->hasError()): ?>
                            <?php foreach($group->fields['password']->getError() as $error): ?>
                                <div class="col-sm-offset-2 col-sm-10 text-danger">
                                    <?php echo $error ?>
                                </div>
                            <?php endforeach ?>
                        <?php endif; ?>

                        <?php echo $group->fields['password']->printLabel(array('class' => 'col-sm-3 control-label')) ?>
                        <div class="col-sm-9">
                            <?php echo $group->fields['password']->printField() ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success"><?php echo __("Save", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>