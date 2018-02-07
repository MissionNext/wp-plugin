<?php
/**
 * @var $form \MissionNext\lib\form\Form
 */

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/search/search_block', 'search/search_block.js', array( 'jquery' ));
?>

<div id="search_block">
    <p class="options-link-block">
        <a class="options-link" href="javascript:void(0)">Search options (click to show/hide search criterias)</a>
    </p>
    <div id="search_options" style="display: none;">
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
                                            <?php
                                                $show_string = str_replace("\\", "", $value);
                                            ?>
                                            <span><?php echo str_replace("(!) ","", $show_string); ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php
                                        $show_string = str_replace("\\", "", $field->getDefault());
                                        ?>
                                        <?php echo str_replace("(!) ","", $show_string); ?>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>