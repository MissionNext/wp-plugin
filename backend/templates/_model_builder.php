<style>
    .field {
        margin: 5px 5px;
        padding: 5px 5px;
        font-weight: bold;
        font-size: 12px;
        border: 1px solid #ccc;
        position: relative;
    }
    .field.required > label{
        color: red;
    }
    .field .constraints{
        font-size: 10px;
        margin-left: 30px;
        font-weight: normal;
    }
    .field .constraints li *{
        vertical-align: middle;
    }
    .field .constraints li button{
        margin-left: 10px;
    }
    .field .add_constraint .additional_data{
        padding: 10px;
    }
    .field .add_constraint .additional_data span{
        margin: 0 15px;
    }
    div.check-all{
        margin: 10px 15px;
    }
    .field .tooltip{
        position: absolute;
        top: 5px;
        left: 125px;
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background: #83A508;
        z-index: 100;
    }
    .field .tooltip > *{
        display: inline-block;
        vertical-align: middle;
    }
    #delete-dialog{
        padding: 15px;
    }
    #delete-dialog p{
        text-align: center;
    }
</style>

<div>
    <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('_new_field_form', array("fields" => $fields)) ?>
</div>

<div class="check-all">
    <!--<input type="checkbox" id="check-all" onchange="check_all(this)"/>
    <label for="check-all">Check All</label>-->
    Search: 
    <input id="search" type="text" onkeyup="search_field(this)">
</div>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">

    <input type="hidden" name="form" value="model"/>

    <?php

    foreach($fields as $field){

        $isset = isset($defaults[$field['symbol_key']]);

        $checked = $isset?'checked="checked"':'';
        ?>

        <div class="field <?php if(in_array($field['symbol_key'], \MissionNext\lib\Constants::$predefinedFields[$role])) echo 'required' ?>" data-key="<?php echo $field['symbol_key'] ?>" data-id="<?php echo $field['id'] ?>" data-params='<?php echo htmlspecialchars(json_encode($field), ENT_QUOTES, 'UTF-8') ?>'>
            <input id="<?php echo $field['symbol_key'] ?>" type="checkbox" value="<?php echo $field['id'] ?>" name="model[<?php echo $field['symbol_key'] ?>][id]" <?php echo $checked ?> />
            <label for="<?php echo $field['symbol_key'] ?>"> <?php echo $field['name'] ?></label>

            <div style="display: none;" class="tooltip">
                <?php echo $fieldsGroup->fields[$field['symbol_key']]->printLabel(); ?>
                <div>
                    <?php echo $fieldsGroup->fields[$field['symbol_key']]->printField(array('disabled' => 'disabled')); ?>
                </div>
            </div>

            <button class="button edit" type="button"><?php echo "Edit" ?></button>
            <button class="button translate" type="button"><?php echo "Translate" ?></button>
            <?php if(!in_array($field['symbol_key'], \MissionNext\lib\Constants::$predefinedFields[$role])): ?>
            <button class="button delete" type="button"><?php echo "Delete" ?></button>
            <?php endif; ?>

            <button class="button toggle<?php if( isset($defaults[$field['symbol_key']]) && $defaults[$field['symbol_key']]['constraints']) echo ' button-primary' ?>" type="button" <?php  if(!$isset) echo 'style="display:none;"'; ?>>V</button>

            <div class="hide" style="display: none">
                <div class="constraints" >
                    <ul>
                        <?php if($isset): foreach($defaults[$field['symbol_key']]['constraints'] as $constraint): ?>
                            <li>
                                <input type="hidden" name="model[<?php echo $field['symbol_key'] ?>][constraints][<?php echo $constraint['key'] ?>]" value="<?php echo $constraint['orig'] ?>" data-key="<?php echo $constraint['key'] ?>"/>
                                <span><?php echo $constraint['orig'] ?></span>
                                <button type="button" class="button">X</button>
                            </li>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>

                <div class="add_constraint">
                    <select>

                        <option value="">Select constraint:</option>

                        <?php foreach($validators as $validator): ?>

                            <?php if( !isset($validator['types']) || in_array($field['type'], $validator['types']) ):

                                $options = isset($validator['options'])?"data-options='" . json_encode($validator['options']) . "'":'';

                                $hide = $isset && in_array($validator['key'], array_keys($defaults[$field['symbol_key']]['constraints']));

                                ?>
                                <option <?php if($hide) echo 'style="display:none;"' ?> value="<?php echo $validator['key'] ?>" <?php echo $options ?>><?php echo $validator['label'] ?></option>
                            <?php endif; ?>

                        <?php endforeach ?>
                    </select>
                    <button type="button" class="button">Add constraint</button>
                    <div class="additional_data">
                    </div>
                </div>
            </div>


        </div>

    <?php
    }

    ?>
    <button type="submit" class="button button-primary" value="model">Save</button>
</form>

<div id="delete-dialog" title="Are you sure?">
    <p>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
        <p>
            This field will be deleted from all websites and user profiles.
        </p>
        <p>
            Are you sure?
        </p>
    </p>
</div>

<?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('_field_translation_modal', array('role' => $role)) ?>

<script>


    jQuery(document).ready(function(){
        jQuery(document).on('click', 'form div.field button.edit', function(e){
                jQuery('#field_dialog').dialog('open');
                fillEditForm(JSON.parse(jQuery(e.target).parents('div.field').attr('data-params')));
            })
            .on('click', 'form div.field button.delete', function(e){
                jQuery('#delete-dialog')
                    .data('row', jQuery(e.target).parents('div.field'))
                    .dialog('open');
            })
            .on('click', 'form div.field button.translate', function(e){
                FieldTranslation.open(jQuery(e.target).parent('.field').attr('data-id'));
            })
            .on('click', 'form div.field .constraints li button', function(e){
                var field = jQuery(e.target).parents('div.field');

                removeConstraint(e.target);
                highlightConstraintButton(field.find('button.toggle'), field.find('div.constraints'))
            })
            .on('click', 'form div.field button.toggle', function(e){toggleConstraints(e.target)})
            .on('click', 'form div.field .add_constraint button', function(e){
                var field = jQuery(e.target).parents('div.field');

                addConstraint(e.target);
                highlightConstraintButton(field.find('button.toggle'), field.find('div.constraints'))
            })
            .on('change', 'form div.field .add_constraint select', function(e){addAdditionalData(e.target)})
            .on('change', 'form div.field > input[type=checkbox]', function(e){toggleConstraintButton(e.target)})
            .on('hover', '.field > label', function(e){
                var tooltip = jQuery(e.target).siblings('.tooltip');
                tooltip.show();
                jQuery(e.target).mouseout(function(){tooltip.fadeOut(400, function(){})});
                jQuery(tooltip).on('focus', 'select', function(e){e.preventDefault();tooltip.stop()})
                tooltip.hover(function(e){tooltip.stop();}, function(){tooltip.hide()});
            });

        jQuery( "#delete-dialog" ).dialog({
            dialogClass : 'wp-dialog',
            closeOnEscape : true,
            autoOpen: false,
            height: 'auto',
            width: '250px',
            modal: true,
            buttons: {
                Delete: function() {
                    deleteField(jQuery(this).data('row'));
                    jQuery( this ).dialog( "close" );
                },
                Cancel: function() {
                    jQuery( this ).dialog( "close" );
                }
            }
        });

    });

    function fillEditForm(data){
        var dialog = jQuery('#field_dialog');

        dialog.find('input[name="action"]').val('update');
        dialog.find('input[name="id"]').val(data['id']);
        dialog.find('#field_type').val(data['type']);
        dialog.find('#field_type').trigger('change');
        dialog.find('#field_label').val(data['name']);
        dialog.find('#field_symbol_key').val(data['symbol_key']);
        dialog.find('#field_tooltip_input').val(data['note']);

        var sortedChoices = [];

        if(data['choices']){
            sortedChoices = sortChoices(data['choices']);

            setExistingChoices(sortedChoices);

            if(sortedChoices.length > 0 && sortedChoices[0]['name'] == ''){
                dialog.find('#field_add_empty').prop('checked', true);
            }

            updateFieldDefaults(mapChoices(data['choices']));
        }

        setDefaultValueField(data['default_value']);

        dialog.find('#field_type').attr('disabled', true);
        dialog.find('#field_symbol_key').attr('disabled', true);

        if(data.meta){
            dialog.find('#field_size').val(data.meta.size)
        }

    }

    function mapChoices(choices){
        var _choices = [];

        choices.sort(function(a, b){
            return a['dictionary_order'] > b['dictionary_order'] ? 1 : -1;
        });

        for(var i = 0; i < choices.length; i++){
            _choices.push(choices[i]['default_value']);
        }

        return _choices;
    }

    function sortChoices(choices){

        var _choices = [];

        for(var i = 0; i < choices.length; i++){

            var group = '';

            if(choices[i]['dictionary_meta'])
            {
                var meta = choices[i]['dictionary_meta'];

                if(meta.group)
                {
                    group = meta.group[0];
                }
            }

            _choices.push({
                id : choices[i]['id'],
                name : choices[i]['default_value'],
                order : parseInt(choices[i]['dictionary_order']),
                group : group
            });
        }

        _choices = _choices.sort(function(a, b){
            return a['order'] > b['order'] ? 1 : -1;
        });

        return _choices;
    }

    function check_all(context){

        var cvalue = jQuery(context).prop('checked');

        var inputs = jQuery('form div.field > input[type=checkbox]');

        jQuery.each(inputs, function(key, value){
            jQuery(value).prop('checked', cvalue);
        });

    }

    function toggleConstraintButton(checkbox){

        var value = jQuery(checkbox).prop('checked');
        var button = jQuery(checkbox).siblings('button.toggle');

        if(value){
            button.show();
        } else {
            button.hide();
            jQuery(button).siblings('.hide').hide();
        }

    }

    function toggleConstraints(button){
        jQuery(button).siblings('.hide').toggle();
    }

    function addConstraint(context){

        var option = jQuery(context).siblings('select').find('option:selected');

        var key = option.val();
        var value = option.val();

        if(!key){
            return;
        }

        var options_inputs = jQuery(context).siblings('div.additional_data').find('input');
        var options = [];
        var errors = [];

        jQuery.each(options_inputs, function(key, value){

            value = jQuery(value);

            if( eval(value.attr('data-required')) && !value.val()){
                errors.push(value);
            } else {
                options.push(value.val());
            }

        });

        if(errors.length > 0){
            return;
        }

        if(options.length > 0){
            value += ':' + options.join(',');
        }

        var field_div = jQuery(context).parents('div.field');
        var data_key = field_div.attr('data-key');
        var ul = field_div.find('div.constraints ul');

        var li = document.createElement('li');

        var input = document.createElement("input");
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', 'model[' + data_key + '][constraints][' + key + ']');
        input.setAttribute('value', value);
        input.setAttribute('data-key', key);

        var span = document.createElement('span');
        span.innerHTML = value;

        var button = document.createElement('button');
        button.setAttribute('class', 'button');
        button.setAttribute('type', 'button');
        button.textContent = 'X';

        li.appendChild(input);
        li.appendChild(span);
        li.appendChild(button);

        ul.append(li);

        option.hide();

        option.parents('select').prop('selectedIndex', 0).trigger('change');

    }

    function removeConstraint(context){

        var li = jQuery(context).parents('li');
        var key = li.find('input[type="hidden"]').attr('data-key');
        var option = li.parents('div.hide').find('.add_constraint select option[value="' + key + '"]');

        li.remove();
        option.show();

    }

    function addAdditionalData(context){

        var div = jQuery(context).siblings('div.additional_data');
        var option = jQuery(context).find('option:selected');
        var options = eval(option.attr('data-options'));

        div.empty();

        if(!options){
            return;
        }

        for( var i = 0; i < options.length; i++){

            var data = '';

            data += '<div>';
            data += '<span>' + options[i]['name'] + '</span>';
            data += "<input type='" + options[i]['type'] + "' data-required='" + options[i]['required'] + "' />";
            data += '</div>';

            div.append(data);
        }

    }

    function search_field(input){

        var value = jQuery(input).val();

        var fields = jQuery('form .field');

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

    function deleteField(row){

        var params = JSON.parse(jQuery(row).attr('data-params'));

        var data = {
            action: 'mn',
            route: 'model/field/delete',
            role: '<?php echo $role ?>',
            id: params['id']
        };

        jQuery.post(ajaxurl, data, function(response){
            if(response != 0){
                jQuery(row).remove();
                if(fields !== undefined){
                    fields.splice(fields.indexOf(params['symbol_key']), 1);
                }
            } else {
                console.log(response);
            }
        });
    }

    function highlightConstraintButton(button, area){

        if(area.find('li').length > 0){
            if(!button.hasClass('button-primary')){
                button.addClass('button-primary');
            }
        } else {
            if(button.hasClass('button-primary')){
                button.removeClass('button-primary');
            }
        }

    }


</script>