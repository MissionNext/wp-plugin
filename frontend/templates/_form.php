<?php

/**
 * @var \MissionNext\lib\form\Form $form
 */

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/form', 'form.js', array( 'jquery',  'jquery-ui-tabs' ));
?>

<div><?php echo $form->getIntro(); ?></div>

<div id="tabs">

    <ul>
        <?php foreach ($form->groups as $group): ?>
            <?php if (!$group->isInnerDependent()): ?>
                <li <?php if ($group->hasErrors()) echo 'class="error"' ?> >
                    <?php if ($group->isOuterDependent()): ?>
                        <a href="#<?php echo $group->group['symbol_key'] ?>" class="dependent-group"
                           data-key="<?php echo $group->group['symbol_key'] ?>"
                           data-depends-on="<?php echo $group->group['depends_on'] ?>"
                           data-depends-on-option="<?php echo $group->group['depends_on_option']; ?>"><?php echo $group->group['name'] ?></a>
                    <?php else: ?>
                        <a href="#<?php echo $group->group['symbol_key'] ?>"><?php echo $group->group['name'] ?></a>
                    <?php endif; ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <?php foreach ($form->groups as $group): ?>
        <div id="<?php echo $group->group['symbol_key'] ?>">

        <?php if(!$group->isInnerDependent()) : ?>
            <?php foreach ($group->fields as $field): ?>

                <?php if ($field->field['type'] == 'hidden'): ?>

                    <?php echo $field->printField() ?>

                <?php else: ?>
                <?php if ($group->isOuterDependent()): ?>
                    <div class="form-group">
                <?php else: ?>
                    <div class="form-group<?php if ($group->isOuterDependent()) echo ' dependent-group' ?>">
                <?php endif; ?>

                <?php if ($field->notes['before']): ?>
                    <div class="col-sm-12 before-note">
                        <?php echo $field->notes['before'] ?>
                    </div>
                <?php endif; ?>

                <div class="col-sm-3">
                    <?php echo $field->printLabel(array('class' => 'control-label')) ?>
                    <?php if ($field->tooltip): ?>
                        <img src="<?php echo getResourceUrl('/resources/images/tooltip.png') ?>" class="field-tooltip"
                             alt="tooltip" title="<?php echo $field->tooltip ?>"/>
                    <?php endif; ?>
                </div>
                <div class="col-sm-9">
                    <?php echo $field->printField($field->hasDependentGroup() ? array('data-dependant' => 1) : array()) ?>
                </div>

                        <?php if ($field->hasError()): ?>
                            <?php foreach ($field->getError() as $error): ?>
                                <div class="col-sm-offset-3 col-sm-9 text-danger">
                                    <?php echo ucfirst($error); ?>
                                </div>
                            <?php endforeach ?>
                        <?php endif; ?>

                <?php if ($field->notes['after']): ?>
                    <div class="col-sm-offset-3 col-sm-9 after-note">
                        <?php echo $field->notes['after'] ?>
                    </div>
                <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if ($field->hasDependentGroup()) { ?>
                    <?php if (is_array($field->dependentGroup)) { ?>
                            <?php foreach($field->dependentGroup as $innerGroup) { ?>
                                <?php if ($innerGroup->isInnerDependent()) { ?>
                                    <div style="display: none" class="subgroup dependent-group"
                                         data-key="<?php echo $innerGroup->group['symbol_key'] ?>"
                                         data-depends-on="<?php echo $field->field['symbol_key'] ?>"
                                         data-depends-on-option="<?php echo $innerGroup->group['depends_on_option']?>">
                                        <?php foreach ($innerGroup->fields as $innerField): ?>
                                            <div class="form-group">
                                                <?php if ($innerField->hasError()): ?>
                                                    <?php foreach ($innerField->getError() as $error): ?>
                                                        <div class="col-sm-offset-3 col-sm-9 text-danger">
                                                            <?php echo $error ?>
                                                        </div>
                                                    <?php endforeach ?>
                                                <?php endif; ?>

                                                <?php if ($innerField->notes['before']): ?>
                                                    <div class="col-sm-12 before-note">
                                                        <?php echo $innerField->notes['before'] ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php echo $innerField->printLabel(array('class' => 'col-sm-3 control-label')) ?>
                                                <div class="col-sm-9">
                                                    <?php echo $innerField->printField() ?>
                                                    <?php if ($innerField->tooltip): ?>
                                                        <img src="<?php echo getResourceUrl('/resources/images/tooltip.png') ?>"
                                                             class="field-tooltip" alt="tooltip" title="<?php echo $field->tooltip ?>"/>
                                                    <?php endif; ?>
                                                </div>

                                                <?php if ($innerField->notes['after']): ?>
                                                    <div class="col-sm-offset-3 col-sm-9 after-note">
                                                        <?php echo $innerField->notes['after'] ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                    <?php } elseif ($field->dependentGroup->isInnerDependent()) { ?>
                        <?php $innerGroup = $field->dependentGroup; ?>
                        <div style="display: none" class="subgroup dependent-group"
                             data-key="<?php echo $innerGroup->group['symbol_key'] ?>"
                             data-depends-on="<?php echo $field->field['symbol_key'] ?>"
                             data-depends-on-option="<?php echo $innerGroup->group['depends_on_option']?>">
                            <?php foreach ($innerGroup->fields as $innerField): ?>
                                <div class="form-group">
                                    <?php if ($innerField->hasError()): ?>
                                        <?php foreach ($innerField->getError() as $error): ?>
                                            <div class="col-sm-offset-3 col-sm-9 text-danger">
                                                <?php echo $error ?>
                                            </div>
                                        <?php endforeach ?>
                                    <?php endif; ?>

                                    <?php if ($innerField->notes['before']): ?>
                                        <div class="col-sm-12 before-note">
                                            <?php echo $innerField->notes['before'] ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php echo $innerField->printLabel(array('class' => 'col-sm-3 control-label')) ?>
                                    <div class="col-sm-9">
                                        <?php echo $innerField->printField() ?>
                                        <?php if ($innerField->tooltip): ?>
                                            <img src="<?php echo getResourceUrl('/resources/images/tooltip.png') ?>"
                                                 class="field-tooltip" alt="tooltip" title="<?php echo $field->tooltip ?>"/>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($innerField->notes['after']): ?>
                                        <div class="col-sm-offset-3 col-sm-9 after-note">
                                            <?php echo $innerField->notes['after'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php endforeach; ?>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>

</div>

<div><?php echo $form->getOutro(); ?></div>