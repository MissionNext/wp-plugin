<?php
/**
 * @var $form \MissionNext\lib\form\Form
 */
?>

<div id="search_block">
    <?php foreach($form->groups as $group): ?>
        <?php if(array_filter($group->getDefault())): ?>
            <h3><?php echo $group->name ?></h3>
            <div class="fields">
                <?php foreach($group->fields as $field): ?>
                    <?php if($field->getDefault()): ?>
                        <dl class="dl-horizontal">
                            <dt>
                                <?php echo $field->field['name']? $field->field['name'] : $field->field['default_name'] ?>
                            </dt>
                            <dd>
                                <?php if(is_array($field->getDefault())): ?>
                                    <?php foreach($field->getDefault() as $value): ?>
                                        <span><?php echo str_replace("(!) ","", $value); ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php echo str_replace("(!) ","", $field->getDefault()); ?>
                                <?php endif; ?>
                            </dd>
                        </dl>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
