<?php

/**
 * @var $form \MissionNext\lib\form\Form
 */

?>

<div><?php echo $form->getIntro(); ?></div>

<?php foreach($form->groups as $group): ?>

<div id="<?php echo $group->group['symbol_key'] ?>"  class="group" data-key="<?php echo $group->group['symbol_key'] ?>">

    <?php if($group->name): ?>
    <h2><?php echo $group->name ?></h2>
    <?php endif; ?>

    <?php if(!$group->isInnerDependent()) : ?>
    <?php foreach($group->fields as $field): ?>

    <?php if($group->isOuterDependent()): ?>
    <div class="form-group">
    <?php else: ?>
    <div class="form-group<?php if($group->isOuterDependent()) echo ' dependent-group' ?>">
        <?php endif; ?>

        <?php if($field->hasError()): ?>
            <?php foreach($field->getError() as $error): ?>
                <div class="col-sm-offset-3 col-sm-9 text-danger">
                    <?php echo $error ?>
                </div>
            <?php endforeach ?>
        <?php endif; ?>

        <?php if($field->notes['before']): ?>
            <div class="col-sm-12 before-note">
                <?php echo $field->notes['before'] ?>
            </div>
        <?php endif; ?>

        <div class="col-sm-3">
            <?php echo $field->printLabel(array('class' => 'control-label')) ?>
            <?php if($field->tooltip): ?>
                <img src="<?php echo getResourceUrl('/resources/images/tooltip.png') ?>" class="field-tooltip"  alt="tooltip" title="<?php echo $field->tooltip ?>"/>
            <?php endif; ?>
        </div>

        <div class="col-sm-9">
            <?php $class = isset($field->field['class']) ? $field->field['class'] : 'mn-'.$field->field['type'];?>
            <?php echo $field->printField($field->hasDependentGroup()?array('data-dependant' => $field->dependentGroup->group['symbol_key'], 'class' => $class):array('class' => $class)) ?>
        </div>

        <?php if($field->notes['after']): ?>
            <div class="col-sm-offset-3 col-sm-9 after-note">
                <?php echo $field->notes['after'] ?>
            </div>
        <?php endif; ?>

    </div>
    <?php if($field->hasDependentGroup() && $field->dependentGroup->isInnerDependent()) : $innerGroup = $field->dependentGroup;?>
        <div style="display: none" class="subgroup dependent-group" data-key="<?php echo $innerGroup->group['symbol_key'] ?>">
            <?php foreach($innerGroup->fields as $innerField): ?>
                <div class="form-group">
                    <?php if($innerField->hasError()): ?>
                        <?php foreach($innerField->getError() as $error): ?>
                            <div class="col-sm-offset-3 col-sm-9 text-danger">
                                <?php echo $error ?>
                            </div>
                        <?php endforeach ?>
                    <?php endif; ?>

                    <?php if($innerField->notes['before']): ?>
                        <div class="col-sm-12 before-note">
                            <?php echo $innerField->notes['before'] ?>
                        </div>
                    <?php endif; ?>

                    <?php echo $innerField->printLabel(array('class' => 'col-sm-3 control-label')) ?>
                    <div class="col-sm-3">
                        <?php echo $innerField->printField(array('class' => 'mn-' . $innerField->field['type'])) ?>
                        <?php if($innerField->tooltip): ?>
                            <img src="<?php echo getResourceUrl('/resources/images/tooltip.png') ?>" class="field-tooltip"  alt="tooltip" title="<?php echo $innerField->tooltip ?>"/>
                        <?php endif; ?>
                    </div>

                    <?php if($innerField->notes['after']): ?>
                        <div class="col-sm-offset-3 col-sm-9 after-note">
                            <?php echo $innerField->notes['after'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif ?>

    <?php endforeach; ?>
<?php endif; ?>

    </div>

<?php endforeach; ?>

<div><?php echo $form->getOutro(); ?></div>
