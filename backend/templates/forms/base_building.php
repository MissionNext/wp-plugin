<?php

/**
 * @var $fields Array
 * @var $defaults Array
 * @var $restFields Array
 * @var $formName String
 * @var $predefinedFields Array
 * @var $canHaveOuterDependencies Boolean
 * @var $canHaveInnerDependencies Boolean
 * @var $canHaveExpandedFields Boolean
 * @var $canHavePrivateGroups Boolean
 * @var $languages
 * @var $translations
 * @var string $form_intro
 * @var string $form_outro
 * @var string $main_fields
 * @var string $main_fields_translations
 */

?>

<?php
function getFieldBySymbolKey($fields, $symbol_key) {
    foreach ($fields as $group) {
        foreach ($group['fields'] as $fieldItem) {
            if ($fieldItem['symbol_key'] == $symbol_key) {

                return $fieldItem['filtered_choices'];
            }
        }
    }
}
?>
<style>

    #fields, form {
        margin-top: 25px;
    }

    #fields {
        display: inline-block;
        vertical-align: top;
        border: 1px solid #ccc;
        width: 250px;
        min-height: 100px;
    }

    #search{
        margin: 5px;
        width: 240px;
    }

    #fields .field,
    form div.group .field
    {
        border: 1px solid #cccccc;
        margin: 5px;
        padding: 10px 25px;
    }

    form div.group .field{
        min-height: 28px;
    }

    form div.group .field > input[type=checkbox]{
        margin: 5px ;
    }

    .tooltip,
    .notes
    {
        float: right;
    }

    form {
        vertical-align: top;
        display: inline-block;
        width: 650px;
        margin: 15px;
        padding: 15px;
        border: 1px solid #ccc;
    }

    form div.group {
        margin: 20px 0;
        padding: 15px;
        min-height: 100px;
        border: 1px solid #ccc;
    }

    form div.group .dependant{
        float: right;
        margin: auto;
    }

    form div.group > select.depend-select,
    form div.group > input.is-private
    {
        float: right;
        vertical-align: middle;
    }
    form div.group > select.depend-select
    {
        width: 220px;
    }

    form div.group > input.is-private{
        margin: 6px 10px;
    }

    form div.group > span.private
    {
        padding: 4px 0;

        float: right;
    }

    form div.predefined-group div
    {
        border: 1px solid #cccccc;
        margin: 5px;
        padding: 10px 25px;
    }

    form div.predefined-group div.predefined-field
    {
        min-height: 28px;
    }

    form div.field .option {
        display: inline-block;
        float: right;
    }

    form div.field .option > *{
        padding: 0 5px;
    }

    form div.depends-option-wrapper {
        overflow: hidden;
        margin: 5px 0 0 180px;
    }
    form div.depends-option-wrapper .depend-option-select{
        width: 240px;
    }
    form div.subgroup-depends-option-wrapper {
        margin: 5px 0 0 0;
        overflow: hidden;
        width: 400px;
    }
    form div.subgroup-depends-option-wrapper .depend-option-select{
        width: 170px;
    }
    .dark-grey{
        background-color: #DDDDDD;
        border
    }
    .add-sub-group{
        display: block;
        margin: 5px;
        cursor: pointer;
    }

</style>

<div id="fields">

    <input type="text" id="search" onkeyup="search(this)"/>

    <?php foreach($restFields as $field): ?>

    <div class="drag" data-type="<?php echo $field['type'] ?>">
        <div class="field  <?php echo ('select' == $field['type'] || 'radio' == $field['type']) ? 'dark-grey' : ''; ?>">
            <label for="<?php echo $field['symbol_key'] ?>"><?php echo $field['name'] ?></label>
            <input type="hidden" data-orig-name="<?php echo $field['symbol_key'] ?>" value="<?php echo $field['symbol_key'] ?>"/>
        </div>
    </div>

    <?php endforeach ?>

</div>

<form id="builder-form" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" data-name="<?php echo $formName ?>" >

    <h3>Form</h3>

    <button class="button button-default intro" type="button">Introduction</button>
    <input class="intro" type="hidden" name="<?php echo $formName; ?>[form_intro]" value="<?php echo htmlspecialchars($form_intro); ?>">

    <button class="button button-default outro" type="button">Outro</button>
    <input class="outro" type="hidden" name="<?php echo $formName; ?>[form_outro]" value="<?php echo htmlspecialchars($form_outro); ?>">

    <?php if($predefinedFields): ?>
    <div class="group">
        <?php if($formName == 'registration'): ?>
        <input type="text" name="<?php echo $formName; ?>[main_fields][group_name]" value="<?php echo $main_fields ?>">
        <button class="button button-primary translations" type="button">Translations</button>
        <input type="hidden" name="<?php echo $formName; ?>[main_fields][translations]" value="<?php echo htmlspecialchars($main_fields_translations); ?>">
        <?php endif; ?>

        <div class="predefined-group">
            <?php foreach($predefinedFields as $predefinedField): ?>
            <div class="predefined-field">
                <?php echo $predefinedField['field']; ?>
                <input type="hidden" name="<?php echo $formName; ?>[<?php echo $predefinedField['key']; ?>][tooltip]" value="<?php echo htmlspecialchars($predefinedField['tooltip']); ?>">
                <button class="button button-default tooltip" type="button">Tooltip</button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="groups">
        <?php foreach($fields as $group ): ?>

            <div class="group" data-name="<?php echo $formName . '[' . $group['symbol_key'] . ']' ?>">

                <input type="text" name="<?php echo $formName . "[" . $group['symbol_key'] . "][group_name]" ?>" value="<?php echo $group['name'] ?>"/>
                <button class="button button-primary translations" type="button">Translations</button>
                <input type="hidden" name="<?php echo $formName . "[" . $group['symbol_key'] . "][translations]" ?>" value="<?php echo isset($translations[$group['id']])?htmlspecialchars(json_encode($translations[$group['id']])):'' ?>"/>

                <?php if($canHavePrivateGroups): ?>
                <span class="private">Private</span>
                <input class="is-private" type="checkbox" name="<?php echo $formName . "[" . $group['symbol_key'] . "][is_private]" ?>" value="1" <?php if(isset($group['meta']['is_private']) && $group['meta']['is_private']) echo 'checked=""checked' ?>/>
                <?php endif;?>

                <?php if($canHaveOuterDependencies): ?>
                <input type="hidden" name="<?php echo $formName . "[" . $group['symbol_key'] . "][is_outer_dependent]" ?>" value="<?php echo $group['is_outer_dependent'] ?>"/>
                <select class="depend-select" name="<?php echo $formName . "[" . $group['symbol_key'] . "][depends_on]" ?>" data-default="<?php echo $group['depends_on'] ?>"></select>
                <div class="depends-option-wrapper" style="<?php echo $group['depends_on_option'] ? "display: block" : "display: none"?>;">
                    <label>Depends on option</label>
                    <?php if ($group['depends_on_option']) {
                        $field_choices = getFieldBySymbolKey($fields, $group['depends_on']);
                        ?>
                        <select class="depend-option-select" name="<?php echo $formName . "[" . $group['symbol_key'] . "][depends_on_option]" ?>" data-default="<?php echo $group['depends_on_option'] ?>">
                            <option value=""></option>
                            <?php foreach ($field_choices as $choiceItem) { ?>
                                <option value="<?php echo $choiceItem?>" <?php echo ($choiceItem == $group['depends_on_option']) ? 'selected="selected"' : ''; ?>><?php echo $choiceItem?></option>
                            <?php } ?>
                        </select>
                    <?php } else { ?>
                        <select class="depend-option-select" name="<?php echo $formName . "[" . $group['symbol_key'] . "][depends_on_option]" ?>" data-default="<?php echo $group['depends_on_option'] ?>"></select>
                    <?php } ?>
                </div>

                <?php endif; ?>

                <?php foreach($group['fields'] as $field): ?>

                    <div class="drag" data-type="<?php echo $field['type'] ?>">

                        <div class="field  <?php echo ('select' == $field['type'] || 'radio' == $field['type']) ? 'dark-grey' : ''; ?>" data-type="<?php echo $field['type'] ?>" data-choices='
                         <?php if (("radio" == $field['type'] || "select" == $field['type']) && $field['filtered_choices']) {
                            echo json_encode($field['filtered_choices']);
                        } ?>'>

                            <label for="<?php echo $field['symbol_key'] ?>"><?php echo $field['name'] ?></label>
                            <input name="<?php echo $formName . '[' . $group['symbol_key'] . '][fields][' . $field['symbol_key'] . '][symbol_key]' ?>" type="hidden" data-orig-name="<?php echo $field['symbol_key'] ?>" value="<?php echo $field['symbol_key'] ?>"/>

                            <?php if(isset($defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['meta']['before_notes'], $defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['meta']['after_notes'])): ?>
                            <input type="hidden" name="<?php echo $formName . '[' . $group['symbol_key'] . '][fields][' . $field['symbol_key'] . '][notes]' ?>" value="<?php echo htmlspecialchars(json_encode(array('before_notes' => $defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['meta']['before_notes'], 'after_notes' => $defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['meta']['after_notes']))) ?>"/>
                            <?php else: ?>
                            <input type="hidden" name="<?php echo $formName . '[' . $group['symbol_key'] . '][fields][' . $field['symbol_key'] . '][notes]' ?>" value="<?php echo htmlspecialchars(json_encode(array('before_notes' => array(), 'after_notes' => array()))); ?>"/>
                            <?php endif; ?>

                            <?php if($canHaveExpandedFields && ($field['type'] == 'radio' || $field['type'] == 'select' || 'custom_marital' == $field['type'])): ?>
                                <div class="option expanded">
                                    <span>Expanded</span>
                                    <input <?php if($defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['meta']['search_options']['is_expanded']) echo 'checked="checked"' ?> type="checkbox" value="1" name="<?php echo $formName . '[' . $group['symbol_key'] . '][fields][' . $field['symbol_key'] . '][is_expanded]' ?>"/>
                                </div>
                            <?php endif; ?>

                            <?php if($canHaveInnerDependencies): ?>
                                <input type="checkbox" class="dependant" <?php if(isset($field['group'])) echo 'checked="checked"' ?> title="Dependant">
                            <?php endif; ?>

                            <button class="button button-default notes" type="button">Notes</button>

                            <?php if( $canHaveInnerDependencies && isset($field['group'])) { ?>
                                <?php foreach ($field['group'] as $innerGroup) { ?>
                                    <div class="group" data-name="<?php echo $formName . '[' . $innerGroup['symbol_key'] . ']' ?>">

                                        <input type="text" name="<?php echo $formName . "[" . $innerGroup['symbol_key'] . "][group_name]" ?>" value="<?php echo $innerGroup['name'] ?>"/>
                                        <button class="button button-primary translations" type="button">Translations</button>
                                        <input type="hidden" name="<?php echo $formName . "[" . $innerGroup['symbol_key'] . "][is_outer_dependent]" ?>" value="<?php echo $innerGroup['is_outer_dependent'] ?>"/>
                                        <input type="hidden" name="<?php echo $formName . "[" . $innerGroup['symbol_key'] . "][depends_on]" ?>" value="<?php echo $innerGroup['depends_on'] ?>"/>
                                        <input type="hidden" name="<?php echo $formName . "[" . $innerGroup['symbol_key'] . "][translations]" ?>" value="<?php echo isset($translations[$innerGroup['id']])?htmlspecialchars(json_encode($translations[$innerGroup['id']])):'' ?>"/>

                                        <?php if($canHavePrivateGroups): ?>
                                            <span class="private">Private</span>
                                            <input class="is-private" type="checkbox" name="<?php echo $formName . "[" . $innerGroup['symbol_key'] . "][is_private]" ?>" value="1" <?php if(isset($innerGroup['meta']['is_private']) && $innerGroup['meta']['is_private']) echo 'checked=""checked' ?>/>
                                        <?php endif;?>

                                        <?php if ('radio' == $field['type'] || 'select' == $field['type']) { ?>
                                            <div class="subgroup-depends-option-wrapper">
                                                <label>Depends on option</label>
                                                <select class="depend-option-select" name="<?php echo $formName . "[" . $innerGroup['symbol_key'] . "][depends_on_option]" ?>" data-default="<?php echo $innerGroup['depends_on_option'] ?>">
                                                    <option value=""></option>
                                                    <?php foreach($field['filtered_choices'] as $choice) { ?>
                                                        <option value="<?php echo $choice; ?>" <?php if ($innerGroup['depends_on_option'] == $choice) { echo 'selected="selected"'; } ?>><?php echo $choice; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        <?php } ?>


                                        <?php foreach($innerGroup['fields'] as $innerField): ?>

                                            <div class="drag" data-type="<?php echo $field['type'] ?>">
                                                <div class="field  <?php echo ('select' == $field['type'] || 'radio' == $field['type']) ? 'dark-grey' : ''; ?>">
                                                    <label for="<?php echo $innerField['symbol_key'] ?>"><?php echo $innerField['default_name'] ?></label>
                                                    <input name="<?php echo $formName . '[' . $innerGroup['symbol_key'] . '][fields][' . $innerField['symbol_key'] . '][symbol_key]' ?>" type="hidden" data-orig-name="<?php echo $innerField['symbol_key'] ?>" value="<?php echo $innerField['symbol_key'] ?>"/>
                                                    <input type="hidden" name="<?php echo $formName . '[' . $innerGroup['symbol_key'] . '][fields][' . $innerField['symbol_key'] . '][notes]' ?>" value="<?php echo htmlspecialchars(json_encode(array( 'before_notes' => $defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['group']['fields'][$innerField['symbol_key']]['meta']['before_notes'], 'after_notes' => $defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['group']['fields'][$innerField['symbol_key']]['meta']['after_notes']))) ?>"/>

                                                    <?php if($canHaveExpandedFields && ($innerField['type'] == 'radio' || $innerField['type'] == 'select' || 'custom_marital' == $innerField['type'])): ?>
                                                        <div class="option expanded">
                                                            <span>Expanded</span>
                                                            <input <?php if($defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['group']['fields'][$innerField['symbol_key']]['meta']['search_options']['is_expanded']) echo 'checked="checked"' ?> type="checkbox" value="1" name="<?php echo $formName . '[' . $innerGroup['symbol_key'] . '][fields][' . $innerField['symbol_key'] . '][is_expanded]' ?>"/>
                                                        </div>
                                                    <?php endif; ?>

                                                    <button class="button button-default notes" type="button">Notes</button>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>

                                    </div>
                                <?php } ?>


                                <?php if ('radio' == $field['type'] || 'select' == $field['type']) { ?>
                                    <a class="add-sub-group">
                                        <img src="<?php echo getResourceUrl('/resources/images/plus_button.png') ?>" />
                                    </a>
                                <?php } ?>

                            <?php } ?>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>
        <?php endforeach; ?>

        <?php if(empty($fields)): ?>
            <div class="group" data-name="<?php echo $formName . '[group1]' ?>">

                <input type="text" name="<?php echo $formName . "[group1][group_name]" ?>" value="Group 1"/>
                <button class="button button-primary translations" type="button">Translations</button>
                <input type="hidden" name="<?php echo $formName . "[group1][translations]" ?>" value=""/>

                <?php if($canHaveOuterDependencies): ?>
                <input type="hidden" name="<?php echo $formName . "[group1][is_outer_dependent]" ?>" value="1"/>
                <select class="depend-select" name="<?php echo $formName . "[group1][depends_on]" ?>"></select>
                <div class="depends-option-wrapper">
                    <label>Depends on option</label>
                    <select class="depend-option-select" name="<?php echo $formName . "[group1][depends_on_option]" ?>"></select>
                </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

    </div>

    <button onclick="addGroup()" type="button" class="button"> <?php echo 'Add group' ?> </button>

    <?php submit_button(); ?>
</form>

<?php renderTemplate("_group_form_translation_modal", compact('languages')) ?>
<?php renderTemplate("forms/_field_tooltip_translation_modal", compact('languages')) ?>
<?php renderTemplate("forms/_field_notes_translation_modal", compact('languages')) ?>
<?php renderTemplate("forms/_form_intro_translation_modal", compact('languages')) ?>
<?php renderTemplate("forms/_form_outro_translation_modal", compact('languages')) ?>

<script type="text/javascript">

var mnStorage = {};

jQuery(document).ready(function()
{
    checkTooltipButtons();
    checkNotesStates();
    checkIntro();
    checkOutro();

    jQuery(document).on('change','form div.group div.drag .dependant', function(e){
        var checkbox = jQuery(e.target);

        if(jQuery(e.target).prop('checked')){
            addSubGroup(checkbox.parents('div.field'));
        } else {
            var confirmDialog = confirm("All subgroups will be deleted. Are you sure want to remove dependant?");
            if (confirmDialog == true) {
                removeSubGroup(checkbox.parents('div.field'));
                jQuery(checkbox.parents('div.field')).find('.add-sub-group').remove();
            } else {
                checkbox.prop("checked", true);
            }
        }
    }).on('update', 'form', function(e){

        jQuery('#fields div.drag').draggable({
            revert: 'invalid'
        });

        updateFieldsSet();

        var selects = jQuery('.group select.depend-select');

        jQuery.each(selects, function(key, select){
            select = jQuery(select);
            select.html('');

            var fields = mnStorage.fields.slice();

            var inputs = select.parents('.group').find('.field');

            jQuery.each(inputs, function(ik, input){
                fields.splice(fields.indexOf(jQuery(input).find('input[data-orig-name]').attr('data-orig-name')), 1);
            });

            select.append("<option value=''>No dependency</option>");

            jQuery.each(fields, function(fk, field){

                var def = select.attr('data-default');

                select.append("<option " + (def == field?'selected="selected"' : '') + " value='" + field + "'>" + jQuery('form .group .field input[data-orig-name="' + field + '"]').siblings('label').text() + "</option>");
            });

        });

    }).on('click', 'form div.group button.translations', function(e){
        var button = jQuery(e.target);
        var input = button.siblings("input[name$='[translations]']");
        var json_string = input.val();
        var data = json_string?JSON.parse(json_string):[];

        var en_input = button.siblings("input[name$='[group_name]']");

        data.push({
            id : 0,
            value : en_input.val()
        });

        GroupLabelTranslationModal.open(data, function(data){
            input.val(JSON.stringify(data));
        });


    })
    .on('click', 'form .field button.notes', function(e)
    {
        var button = jQuery(e.target);
        var input = button.siblings("input[name$='[notes]']");
        var json_string = input.val();
        var data = json_string ? JSON.parse(json_string) : [];

        FieldNotesTranslationModal.open(data, function(data)
        {
            input.val(JSON.stringify(data));

            checkNotesStates();
        });
    })
    .on('click', 'form button.intro', function(e)
    {
        var button = jQuery(e.target);
        var input = button.siblings("input[name$='[form_intro]']");
        var json = input.val();
        var data = json ? JSON.parse(json) : [];

        FormIntroTranslationModal.open(data, function(data)
        {
            input.val(JSON.stringify(data));

            checkIntro();
        });
    })
    .on('click', 'form button.outro', function(e)
    {
        var button = jQuery(e.target);
        var input = button.siblings("input[name$='[form_outro]']");
        var json = input.val();
        var data = json ? JSON.parse(json) : [];

        FormOutroTranslationModal.open(data, function(data)
        {
            input.val(JSON.stringify(data));

            checkOutro();
        });
    })
    .on('click', 'form button.tooltip', function(e)
    {
        var button = jQuery(e.target);
        var input = button.siblings("input[name$='[tooltip]']");
        var json = input.val();
        var data = json ? JSON.parse(json) : [];

        FieldTooltipTranslationModal.open(data, function(data)
        {
            input.val(JSON.stringify(data));

            checkTooltipButtons();
        });
    })
    .on('change', '.depend-select', function(e)
    {
        var select = jQuery(e.target);
        var selectWithOptions = jQuery(select).closest('.group').find('select.depend-option-select');
        var selectWrapper = jQuery(select).closest('.group').find('.depends-option-wrapper');
        var selectValue = select.val();
        field = jQuery('input[data-orig-name="' + selectValue + '"]');
        fieldWrapper = field.parent();

        jQuery(selectWrapper).hide();
        jQuery(selectWithOptions).find('option').remove().end();
        if ('radio' == fieldWrapper.data('type') || 'select' == fieldWrapper.data('type')) {
            var choicesStr = jQuery(fieldWrapper).data('choices');
            choicesStr = choicesStr.trim();
            if (choicesStr.length > 0) {
                jQuery(selectWrapper).show();
                choicesArray = jQuery.parseJSON(choicesStr);
                selectWithOptions.append("<option value=''></option>");
                jQuery.each(choicesArray, function(index, value) {
                    selectWithOptions.append("<option value='" + value + "'>" + value + "</option>");
                });
            }
        }

    })
    .on('click', '.add-sub-group', function(e)
    {
        var add_button = jQuery(this);

        addSubGroup(add_button.parents('div.field'), add_button);
    });

    jQuery('#fields').droppable({
        tolerance : 'pointer',
        accept : 'div.drag',
        drop : function(event, ui) {

            var dragged = jQuery(ui.draggable);
            var input = jQuery(dragged).find('input');

            <?php if($canHaveInnerDependencies): ?>
            if(dragged.find('input.dependant').length != 0){
                dragged.find('input.dependant').remove();
            }
            <?php endif; ?>

            <?php if($canHaveExpandedFields): ?>
            if(dragged.find('div.option.expanded').length != 0){
                dragged.find('div.option.expanded').remove();
            }
            <?php endif; ?>

            if(dragged.find('button.notes').length != 0){
                dragged.find("input[name$='[notes]']").remove();
                dragged.find("button.notes").remove();
            }

            input.attr( 'name' , input.attr('data-orig-name'));

            dragged.attr('style', 'position:relative;');

            if(jQuery(this).find("div.drag").length == 0){
                dragged.appendTo(jQuery(this));
            } else {
                var i=0; //used as flag to find out if element added or not

                jQuery(this).children('div.drag').each(function()
                {
                    if(jQuery(this).offset().top>=ui.offset.top)  //compare
                    {
                        dragged.insertBefore(jQuery(this));
                        i=1;
                        return false; //break loop
                    }
                });

                if(i!=1) //if element dropped at the end of cart
                {
                    dragged.appendTo(jQuery(this));
                }
            }

            jQuery('form').trigger('update');
        }
    });

    updateGroups();

    jQuery('form').trigger('update');
});

function updateGroups(){

    jQuery('form div.group div.drag').draggable({
        revert: 'invalid'
    });

    jQuery('form div.group').droppable({
        tolerance : 'pointer',
        accept : 'div.drag',
        drop : function(event, ui) {

            var dragged = jQuery(ui.draggable);
            var field = dragged.find('.field');
            var input = jQuery(dragged).find('input[data-orig-name]');
            var type = dragged.attr('data-type');

            <?php if($canHaveInnerDependencies): ?>
            if(dragged.find('input.dependant').length == 0 && jQuery(this).parents('div.group').length == 0){
                field.append("<input type='checkbox' class='dependant' title='Dependant' />");
            } else if (dragged.find('input.dependant').length != 0 && jQuery(this).parents('div.group').length != 0){
                dragged.find('input.dependant').remove();
            }
            <?php endif; ?>

            <?php if($canHaveExpandedFields): ?>
            if(dragged.find('div.option.expanded').length == 0 && ( type == 'select' || type == 'radio' || 'custom_marital' == type )){
                field.append("<div class='option expanded'><span>Expanded</span><input type='checkbox' value='1' name='" + jQuery(this).attr('data-name') + "[fields][" + input.attr('data-orig-name') + "][is_expanded]'/>");
            }
            <?php endif; ?>

            var group_name = jQuery(this).attr('data-name');
            var symbol_key = input.attr('data-orig-name');

            input.attr( 'name' , group_name + "[fields][" + symbol_key + "][symbol_key]");

            var notes_input = jQuery(dragged).find('input[name$="[notes]"]');
            notes_input.attr( 'name' , group_name + "[fields][" + symbol_key + "][notes]");

            if(field.find('button.notes').length == 0){
                field.append('<input type="hidden" name="'+group_name+'[fields]['+symbol_key+'][notes]" value=""/>');
                field.append('<button class="button button-default notes" type="button">Notes</button>');
            }

            dragged.attr('style', 'position: relative;');

            if(jQuery(this).find("div.drag").length == 0){
                dragged.appendTo(jQuery(this));
            } else {
                var i=0; //used as flag to find out if element added or not

                jQuery(this).children('div.drag').each(function()
                {
                    if(jQuery(this).offset().top>=ui.offset.top)  //compare
                    {
                        dragged.insertBefore(jQuery(this));
                        i=1;
                        return false; //break loop
                    }
                });

                if(i!=1) //if element dropped at the end of cart
                {
                    dragged.appendTo(jQuery(this));
                }
            }

            jQuery('form').trigger('update');
        }
    });

    jQuery('form div.groups > div.group').draggable({
        revert: 'invalid'
    });

    jQuery('form div.groups').droppable({
        tolerance : 'pointer',
        accept : 'div.group',
        drop : function(event, ui) {
            jQuery(ui.draggable).attr('style', 'position: relative;');

            var dragged = ui.draggable;

            if(jQuery(this).find("div.group").length == 0){
                dragged.appendTo(jQuery(this));
            } else {
                var i=0; //used as flag to find out if element added or not

                jQuery(this).children('div.group').each(function()
                {
                    if(jQuery(this).offset().top>=ui.offset.top)  //compare
                    {
                        dragged.insertBefore(jQuery(this));
                        i=1;
                        return false; //break loop
                    }
                });

                if(i!=1) //if element dropped at the end of cart
                {
                    dragged.appendTo(jQuery(this));
                }
            }
        }
    });

}

function updateFieldsSet(){
    var inputs = jQuery('form .field input[data-orig-name]');

    mnStorage.fields = [];

    jQuery.each(inputs, function(key, value){
        var name = jQuery(value).attr('data-orig-name');
        mnStorage.fields.push(name);
    });
}

function addGroup(){

    var groups = jQuery('#builder-form').find('div.groups');

    groups.append(createNewGroup(true));

    updateGroups();
}

function createNewGroup(outer){

    var form = jQuery('#builder-form');

    var groups = form.find('div.groups');

    var form_name = form.attr('data-name');

    var name = form_name + "[group-" + microtime(true) + "]";

    var group = jQuery('<div>');
    group.attr( 'class', 'group' );
    group.attr( 'data-name', name );
    group.append("<input type='text' name='"+name+"[group_name]' value='Group' />");
    group.append("<button class='button button-primary translations' type='button'>Translations</button>");

    <?php if($canHavePrivateGroups): ?>
    group.append('<span class="private">Private</span>');
    group.append("<input class='is-private' type='checkbox' name='"+name+"[is_private]' value='1' />");
    <?php endif ?>

    <?php if($canHaveOuterDependencies): ?>
    group.append("<input type='hidden' name='"+name+"[is_outer_dependent]' value='" + (+outer) + "'>");
    if(outer){
        group.append('<select class="depend-select" name="' + name + '[depends_on]" ></select>');
        var innerHtml = '<div class="depends-option-wrapper" style="display: none;">';
        innerHtml += '<label>Depends on option</label>';
        innerHtml += '<select class="depend-option-select" name="' + name + '[depends_on_option]" ></select>';
        innerHtml += '</div>';
        group.append(innerHtml);
    }
    <?php endif; ?>
    group.append("<input type='hidden' name='"+name+"[translations]' value=''>");

    return group;
}

function search(input){

    var value = jQuery(input).val();

    var fields = jQuery('#fields .drag');

    if(!value){
        fields.show();
        return;
    }

    fields.hide();

    jQuery.each(fields, function(key, item){

        if(jQuery(item).find('label').text().toLowerCase().indexOf(value.toLowerCase()) >= 0){
            jQuery(item).show();
        }

    });

}

function checkTooltipButtons()
{
    var fields = jQuery('form div.predefined-field');

    jQuery.each(fields, function(k, v)
    {
        var field  = jQuery(v);
        var input  = field.find('input[name$="[tooltip]"]');
        var button = field.find('button.tooltip');
        var json   = input.val();

        if(json.length > 0)
        {
            var status = false;
            var values = JSON.parse(json);

            jQuery.each(values, function(key, value)
            {
                status |= value.length > 0;
            });

            button.removeClass('button-default').removeClass('button-primary').addClass(status ? 'button-primary' : 'button-default');
        }
    });
}

function checkNotesStates()
{
    var fields = jQuery('form .field');

    jQuery.each(fields, function(k, v)
    {
        var field = jQuery(v);
        var input = field.find('input[name$="[notes]"]');
        var button = field.find('button.notes');

        var json = input.val();

        if(json.length > 0)
        {
            var state = false;
            var langs = JSON.parse(json);

            jQuery.each(langs['before_notes'], function(k, v)
            {
                state |= v['value'].length > 0;
            });

            jQuery.each(langs['after_notes'], function(k, v)
            {
                state |= v['value'].length > 0;
            });

            button.removeClass('button-default').removeClass('button-primary').addClass(state?'button-primary':'button-default');
        }
    });
}

function checkIntro()
{
    var input  = jQuery('input.intro');
    var button = jQuery('button.intro');
    var json   = input.val();

    if(json.length > 0)
    {
        var status = false;
        var values = JSON.parse(json);

        jQuery.each(values, function(k, v)
        {
            status |= v['value'].length > 0;
        });

        button.removeClass('button-default').removeClass('button-primary').addClass(status ? 'button-primary' : 'button-default');
    }
}

function checkOutro()
{
    var input  = jQuery('input.outro');
    var button = jQuery('button.outro');
    var json   = input.val();

    if(json.length > 0)
    {
        var status = false;
        var values = JSON.parse(json);

        jQuery.each(values, function(k, v)
        {
            status |= v['value'].length > 0;
        });

        button.removeClass('button-default').removeClass('button-primary').addClass(status ? 'button-primary' : 'button-default');
    }
}

function addSubGroup(field, addButton){
    var group = jQuery(createNewGroup(false));
    group.append("<input type='hidden' name='" + group.attr('data-name') +"[depends_on]' value='" + jQuery(field).find('input[type="hidden"]').attr('data-orig-name') + "'/>")

    var choicesStr = jQuery(field).data('choices');
    choicesStr = choicesStr.trim();
    if (choicesStr.length > 0) {
        choicesArray = jQuery.parseJSON(choicesStr);
        var innerHtml = '<div class="subgroup-depends-option-wrapper">';
        innerHtml += '<label>Depends on option</label>';
        innerHtml += "<select class='depend-option-select' name='" + group.attr('data-name') + "[depends_on_option]'><option value=''></value>";
        jQuery.each(choicesArray, function(index, value) {
            innerHtml += "<option value='" + value + "'>" + value + "</option>";
        });
        innerHtml += "</select>"
        group.append(innerHtml);
    }

    if (typeof addButton == 'undefined') {
        jQuery(field).append(group);
        var plusButton = '<a class="add-sub-group"><img src="<?php echo getResourceUrl('/resources/images/plus_button.png'); ?>"></a>';
        jQuery(field).append(plusButton);
    } else {
        jQuery(group).insertBefore(addButton);
    }

    updateGroups();
}

function removeSubGroup(field){
    var group = jQuery(field).find('div.group');
    var fields = jQuery('#fields');
    fields.append(group.find('div.drag'));
    group.remove();
}

function microtime(get_as_float) {
    var now = new Date().getTime() / 1000;
    var s = parseInt(now);

    return (get_as_float) ? now : (Math.round((now - s) * 1000) / 1000);
}

</script>