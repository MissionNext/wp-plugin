<?php

/**
 * @var \MissionNext\lib\form\Form $form
 */

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

<script>

    jQuery(document).on('change', '[data-dependant]', function (e) {
        var show_flag = null;
        var field = jQuery(e.target);
        var groups_key = field.data('key');
        var groups = jQuery('.dependent-group[data-depends-on="' + groups_key + '"]');
        var subgroups_key = jQuery('div[id="' + groups.data('key') + '"] *[data-dependant="1"]').data('key');
        var subgroups = jQuery('.dependent-group[data-depends-on="' + subgroups_key + '"]');
        var element = jQuery('[data-key="' + subgroups_key + '"]');

        if (field.length == 1 && field.attr('type') == 'checkbox') {
            if (field.is(':checked')) {
                groups.show();
                show_flag = true;
            } else {
                groups.hide();
                subgroups.hide();
                element.prop('checked', false);
                show_flag = false;
            }
        } else if (field.length == 1 && field.attr('custom-field') == '1') {
            if (field.val() == 'Yes' && field.is(":checked")) {
                groups.show();
                show_flag = true;
            } else if (field.val() == 'No' && field.is(":checked")) {
                groups.hide();
                subgroups.hide();
                show_flag = false;
            }
        } else if (field.length == 1 && field.attr('custom-field') == '2') {
            if (field.val() == 'Married') {
                groups.show();
                show_flag = true;
            } else {
                groups.hide();
                subgroups.hide();
                show_flag = false;
            }
        } else {
            jQuery.each(groups, function(index, value) {
                var dependant_option = jQuery(value).data('depends-on-option');
                if ('radio' == field.attr('type') && field.length == 1 && dependant_option.length > 0) {
                    if (field.is(':checked') && field.val() == dependant_option) {
                        jQuery(value).show();
                        show_flag = true;
                    } else if (field.is(':checked') && field.val() != dependant_option) {
                        jQuery(value).hide();
                        show_flag = false;
                    }
                } else if (field.is('select') && field.length == 1 && dependant_option.length > 0 && field.val() == dependant_option) {
                    jQuery(value).show();
                    show_flag = true;
                } else if (dependant_option.length == 0 && field.val()) {
                    jQuery(value).show();
                    show_flag = true;
                } else {
                    jQuery(value).hide();
                    show_flag = false;

                    var current_subgroups_key = jQuery('div[id="' + jQuery(value).data('key') + '"] *[data-dependant="1"]').data('key');
                    var current_subgroups = jQuery('.dependent-group[data-depends-on="' + current_subgroups_key + '"]');
                    var current_element = jQuery('[data-key="' + current_subgroups_key + '"]');

                    current_subgroups.hide();
                    current_element.val('');
                }
            });
        }

        if (show_flag) {
            groups.each(function(){
                var data_key = jQuery(this).data('key');
                jQuery("[id='" + data_key + "'] [data-dependant='1']").trigger('change');
            });
        } else {
            groups.each(function(){
                var data_key = jQuery(this).data('key');
                var dependant_fields = jQuery("[id='" + data_key + "'] [data-dependant='1']");
                hideTabsReccurcivile(dependant_fields);
            });
        }
    });

    jQuery(document).on('click', 'button[type="submit"]', function(e){
        e.preventDefault();
        var tabsAnchorns = jQuery('.ui-tabs-anchor');
        jQuery.each(tabsAnchorns, function(index, value) {
            var hrefAttr = jQuery(value).attr('href');
            var groupId = hrefAttr.replace('#', '');
            if (jQuery(value).is(':hidden')) {
//                alert(index + ' ## ' + value);
                var hiddenGroup = jQuery("div[id='" + groupId + "']");
                var inputs = jQuery(hiddenGroup).find(':input');
                jQuery(inputs).val('').removeAttr('checked').removeAttr('selected');
            } else {
//                alert(index + ' ## ' + hrefAttr);
                var subGroups = jQuery("div[id='" + groupId + "']").find('.dependent-group');
                if (subGroups.length > 0) {
                    jQuery.each(subGroups, function(subIndex, subValue){
                        var subGroupName = jQuery(subValue).attr('data-depends-on');
                        var subGroupSwitch = jQuery("div[id='" + groupId + "']").find('input[data-key="'+subGroupName+'"][value="No"]');

                        if(subGroupSwitch.is(':checked')){
                            var inputs = jQuery(subValue).find(':input');
                            jQuery(inputs).val('').removeAttr('checked').removeAttr('selected');
                        }
                    });
                }
            }
        });

        jQuery('.page-content form').submit();
    });

    jQuery(document).on('click', '#populate-from-btn', function(e){
        jQuery('#populate-from').submit();
    });

    function hideTabsReccurcivile(fields)
    {
        if (fields.length) {
            fields.each(function(){
                var data_key = jQuery(this).data('key');
                var groups = jQuery('.dependent-group[data-depends-on="' + data_key + '"]');
                groups.hide();
                groups.each(function(){
                    var group_key = jQuery(this).data('key');
                    var dependant_fields = jQuery("[id='" + group_key + "'] [data-dependant='1']");
                    hideTabsReccurcivile(dependant_fields);
                });
            });
        }
    }

    jQuery(document).ready(function () {

        var index = jQuery('#tabs > ul li a').index(jQuery('#tabs > ul li a:not(.dependent-group)'));

        jQuery('#tabs').tabs({active: index});

        jQuery('.dependent-group').hide();

        jQuery('.ui-tabs-nav li a').each(function(){
            if (jQuery(this).is(":visible")) {
                var data_key = jQuery(this).parent("li").attr('aria-controls');
                jQuery("[id='" + data_key + "'] [data-dependant='1']").trigger('change');
            }

        });

    });

</script>