<?php
    $types = \MissionNext\lib\form\fields\BaseField::$types;

    $symbol_keys = array();

    foreach ( $fields as $field ) {
        $symbol_keys[] = $field['symbol_key'];
    }


?>

<style>
    #new_field > div {
        margin: 10px;
    }
    #new_field label{
        vertical-align: top;

        display: inline-block;
        min-width: 100px;
    }
    #new_field input {
        border: 1px solid #ccc;
    }
    div#field_default_value {
        display: inline-block;
    }

    #ui-datepicker-div {
        padding: 10px;
        background: #eee;
        border-radius: 10px;
        border: 1px solid #ccc;
    }
    .text-danger{
        color: red;
    }
    #field_choices{
        display: inline-block;
    }
    #choices{
        padding-bottom: 10px;
    }
    #choices .choice{
        padding-left: 10px;
        margin: 5px 0;
        border-left: 10px solid #ccc;
    }
    #choices .group{
        padding-left: 10px;
        margin: 5px 0;
        border-left: 10px solid #369;
    }
    /*#add_field_choice{*/
        /*padding-left: 20px;*/
    /*}*/
</style>

<button class="button" onclick="jQuery('#field_dialog').dialog('open')"><?php echo 'New field' ?></button>

<div id="field_dialog" style="display: none">
    <form id="new_field" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">

        <input type="hidden" name="form" value="field"/>
        <input type="hidden" name="action" value="create"/>
        <input type="hidden" name="id"/>

        <div>
            <label for="field_type">Type</label>
            <select name="type" id="field_type">
                <?php foreach($types as $type): ?>
                    <option value="<?php echo $type['key'] ?>" data-key="<?php echo $type['key'] ?>"><?php echo $type['label'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="field_label">Label</label>
            <input autocomplete="off" type="text" name="label" id="field_label"/>
        </div>

        <div>
            <label for="field_symbol_key">Unique key</label>
            <input type="text" name="symbol_key" id="field_symbol_key"/>
        </div>

        <div>
            <label for="field_default_value">Default value</label>
            <input type="text" name="default_value" id="field_default_value"/>
        </div>

        <div>
            <label for="field_size">Size</label>
            <select name="size" id="field_size">
                <option value="small">Small</option>
                <option value="medium" selected="selected" >Medium</option>
                <option value="large">Large</option>
            </select>
        </div>

        <div style="display: none" >
            <label for="field_add_empty">Add Empty</label>
            <input type="checkbox" name="add_empty" id="field_add_empty" value="1"/>
        </div>

        <div class="text-danger" id="no-preference-help" style="display: none;">
            Choice started with "(!)" will be treated as "No preference" choice
        </div>

        <div style="display: none;">
            <label for="field_choice">Choices</label>
            <div id="field_choices">
                <div id="choices"></div>
                <div id="add_field_choice">
                    <textarea id="field_choice"></textarea>
                    <button id="field_choice_add_button" class="button" type="button">Add choice</button>
                </div>
                <div id="add_field_choice_group">
                    <textarea id="field_choice_group"></textarea>
                    <button id="field_choice_group_add_button" class="button" type="button">Add group</button>
                </div>
            </div>
        </div>

        <div id="field-tooltip">
            <label for="field_tooltip_input">Tooltip</label>
            <textarea name="tooltip" id="field_tooltip_input"></textarea>
        </div>

    </form>
</div>

<script type="text/javascript">

    var fields = <?php echo json_encode($symbol_keys) ?>;

    jQuery(document).ready(function(){
        jQuery( "#field_dialog" ).dialog({
            dialogClass : 'wp-dialog',
            closeOnEscape : true,
            autoOpen: false,
            height: 'auto',
            width: 'auto',
            modal: true,
            buttons: {
                Save: function() {

                    var symbol_key = jQuery('#field_symbol_key');
                    var label = jQuery('#field_label');
                    var type = jQuery('#field_type');
                    var submit = symbol_key.val().length > 0 && label.val().length > 0 && type.val().length > 0 && isUniqueSymbolKey(symbol_key);

                    if(submit){
                        jQuery(this).find(':input:disabled').removeAttr('disabled');
                        jQuery(this).find('form').submit();
                    }
                },
                Close: function() {
                    jQuery( this ).dialog( "close" );
                }
            },
            close: function() {
                jQuery(this).find(':input:not([name="form"])').val("").removeClass( "ui-state-error" );
                jQuery(this).find('input[name="action"]').val('create');
                jQuery(this).find(':input:disabled').removeAttr('disabled');
                resetFieldView();
            }
        });

        jQuery(document).on('change', '#field_type', function(e){updateFieldView(jQuery(e.target).find('option:selected').attr('data-key'))})
                        .on('change', '#field_add_empty', function(e){
                            var inputs = jQuery('#choices').find('.choice input');
                            var values = [];

                            if(jQuery('#field_add_empty').prop('checked')){
                                values.push('');
                            }

                            jQuery.each(inputs, function(key, value){
                                values.push(jQuery(value).val());
                            });

                            updateFieldDefaults(values);
                        })
                        .on('keyup change', '#field_label', function(e){updateSymbolKey(jQuery(e.target).val())})
                        .on('keyup', '#field_symbol_key', function(e){checkUniqueSymbolKey(e.target)})
                        .on('click', '#field_choice_add_button', function(e){
                            var textarea = jQuery('#field_choice');
                            var new_choices = textarea.val().split("\n");
                            jQuery.each(new_choices, function(key, value){

                                value = htmlEscape(value);

                                if(value.length > 0){
                                    addChoice(value);
                                }
                            });
                            textarea.val('');

                            var choices = jQuery('#choices');
                            var inputs = choices.find('.choice input');
                            var values = [];

                            jQuery.each(inputs, function(key, value){
                                values.push(jQuery(value).val());
                            });

                            updateFieldDefaults(values);

                            choices.sortable();
                        })
                        .on('click', '#field_choice_group_add_button', function(e)
                        {
                            var textarea = jQuery('#field_choice_group');
                            var new_choices = textarea.val().split("\n");
                            jQuery.each(new_choices, function(key, value){

                                value = htmlEscape(value);

                                if(value.length > 0){
                                    addChoiceGroup(value);
                                }
                            });
                            textarea.val('');

                            var choices = jQuery('#choices');
                            var inputs = choices.find('.choice input');
                            var values = [];

                            jQuery.each(inputs, function(key, value){
                                values.push(jQuery(value).val());
                            });

                            updateFieldDefaults(values);

                            choices.sortable();
                        })
                        .on('click', '#choices .choice button.delete', function(e){
                            jQuery(e.target).parent('.choice').remove();

                            var inputs = jQuery('#choices').find('.choice input');
                            var values = [];

                            jQuery.each(inputs, function(key, value)
                            {
                                values.push(jQuery(value).val());
                            });

                            updateFieldDefaults(values);
                        })
                        .on('click', '#choices .group button.delete', function(e)
                        {
                            jQuery(e.target).parent('.group').remove();

                            var inputs = jQuery('#choices').find('.choice input');
                            var values = [];

                            jQuery.each(inputs, function(key, value){
                                values.push(jQuery(value).val());
                            });

                            updateFieldDefaults(values);
                        });

        jQuery('#field_type').trigger('change');
    });

    function setExistingChoices(choices){
        var container = jQuery('#choices');
        container.empty();

        jQuery.each(choices, function(key, value)
        {
            if(value['group'].length > 0)
            {
                container.append("<div class='group'><input type='text' name='choices[][group]' value='"+htmlEscape(value['group'])+"'/><button class='button delete' type='button'>Delete</button></div>");
            }

            if(value['name'].length > 0)
            {
                container.append("<div class='choice'><input type='text' name='choices[]["+value['id']+"]' value='"+htmlEscape(value['name'])+"'/><button class='button delete' type='button'>Delete</button></div>");
            }
        });
        container.sortable();
    }

    function addChoice(value){
        var container = jQuery('#choices');

        container.append("<div class='choice'><input type='text' name='choices[][new]' value='"+value+"'/><button class='button delete' type='button'>Delete</button></div>");
    }

    function addDisabledChoice(value){
        var container = jQuery('#choices');

        container.append("<div class='choice'><input type='text' disabled='disabled' name='choices[][new]' value='"+value+"'/></div>");
    }

    function addChoiceGroup(value){
        var container = jQuery('#choices');

        container.append("<div class='group'><input type='text' name='choices[][group]' value='"+value+"'/><button class='button delete' type='button'>Delete</button></div>");
    }

    function updateFieldView(type){

        resetFieldView();

        var def_value = jQuery('#field_default_value');
        var choices = jQuery('#field_choices');
        var add_empty = jQuery('#field_add_empty');
        var choice_group = jQuery('#add_field_choice_group');
        var add_choice = jQuery('#add_field_choice');
        var help = jQuery('#no-preference-help');
        var example = jQuery('#example');
        var size = jQuery('#field_size');

        if( type == 'select' || type == 'select_multiple' || type == 'radio' || type == 'checkbox' || type == 'custom_marital'){
            choices.parent().show();
        }

        switch ( type ){
            case 'date' : {
                def_value.prop('type', 'text');
                example.show();
                break;
            }
            case 'select' : {
                def_value.replaceWith('<select name="default_value" id="field_default_value"></select>');
                add_empty.parent().show();
                help.show();
                break;
            }
            case 'input' : {
                def_value.prop('type', 'text');
                break;
            }
            case 'select_multiple' : {
                def_value.replaceWith('<select multiple="multiple" name="default_value[]" id="field_default_value"></select>');
                add_empty.parent().show();
                help.show();
                break;
            }
            case 'text' : {
                def_value.replaceWith('<textarea name="default_value" id="field_default_value"></textarea>');
                break;
            }
            case 'radio' : {
                def_value.replaceWith('<div id="field_default_value"></div>');
                help.show();
                break;
            }
            case 'boolean' : {
                def_value.prop('type', 'checkbox');
                def_value.attr('value', '1');
                break;
            }
            case 'checkbox' : {
                def_value.replaceWith('<div id="field_default_value"></div>');
                choice_group.show();
                help.show();
                break;
            }
            case 'file' : {
                def_value.parent('div').hide();
                break;
            }
            case 'radio_yes_no' : {
                def_value.parent('div').hide();
                size.parent('div').hide();
                def_value.val("No");
                break;
            }
            case 'custom_marital' : {
                def_value.parent('div').hide();
                def_value.val("Single");
                size.parent('div').hide();
                add_choice.hide();
                var choices = ["Single", "Married", "Separated"];
                choices.forEach(function(item, i, choices){
                    addDisabledChoice(item);
                });

                break;
            }
        }


        var inputs = jQuery('#choices').find('input');
        var values = [];

        jQuery.each(inputs, function(key, value){
            values.push(jQuery(value).val());
        });

        updateFieldDefaults(values);

    }


    function resetFieldView(){

        var def_value = jQuery('#field_default_value');
        var choices = jQuery('#choices');
        var add_empty = jQuery('#field_add_empty');
        var add_choice = jQuery('#add_field_choice');
        var choice_group = jQuery('#add_field_choice_group');
        var help = jQuery('#no-preference-help');
        var tooltip = jQuery('#field_tooltip_input');
        var example = jQuery('#example');
        var size = jQuery('#field_size');


        def_value.parent('div').show();
        def_value.val('');
        def_value.replaceWith('<input type="text" name="default_value" id="field_default_value"/>');
        def_value.prop('type', 'text');

        add_choice.show();

        choices.empty();
        choices.parent('#field_choices').parent().hide();

        add_empty.prop('checked', false);
        add_empty.parent().hide();

        tooltip.val('');

        choice_group.hide();
        help.hide();
        example.hide();

        size.parent('div').show();
        jQuery('#field_symbol_key').removeAttr('style');
    }

    function setDefaultValueField(data){

        var type = jQuery('#field_type option:selected').attr('data-key');

        var def_value = jQuery('#field_default_value');

        switch ( type ){
            case 'boolean': {
                def_value.attr("checked", data == 1);
                break;
            }
            case 'radio' : {
                if(data){
                    def_value.find('[value="' + data[0] + '"]').attr("checked", true);
                }
                break;
            }
            case 'select' : {
                if(data){
                    def_value.find('[value="' + data[0] + '"]').attr("selected", "selected");
                } else {
                    def_value.find('option:first').attr("selected", "selected");
                }
                break;
            }
            case 'checkbox' : {

                if(data){
                    jQuery.each(data, function(key, value){
                        def_value.find('[value="' + value + '"]').prop('checked', true);
                    });
                }
                break;
            }
            default : {
                def_value.val(data);
            }
        }
    }

    function updateFieldDefaults(c){

        var type = jQuery('#field_type option:selected').attr('data-key');
        var def_value = jQuery('#field_default_value');
        var add_empty = jQuery('#field_add_empty');

        switch ( type ){
            case 'date': {
                if(!Modernizr.inputtypes.date) {
                    console.log("The 'date' input type is not supported, so using JQueryUI datepicker instead.");
                    jQuery(def_value).datepicker();
                }
                break;
            }
            case 'select' : {
                var value = (add_empty.prop('checked')) ? null : def_value.val();
                def_value.empty();

                for(var i = 0; i < c.length; i++){
                    def_value.append('<option value="' + c[i] + '">' + c[i] + '</option>')
                }
                def_value.val(value);

                if(def_value.val() == null){
                    def_value.find('option:first').prop('selected', true)
                }

                break;
            }
            case 'select_multiple' : {
                var value = def_value.val();
                def_value.empty();

                for(var i = 0; i < c.length; i++){
                    def_value.append('<option value="' + c[i] + '">' + c[i] + '</option>')
                }

                def_value.val(value);

                break;
            }
            case 'radio' : {
                def_value.empty();

                for(var i = 0; i < c.length; i++){
                    def_value.append('<div><input name="default_value" type="radio" value="' + c[i] + '"/><label for="default_value' + i + '">' + c[i] + '</label></div>');
                }
                break;
            }
            case 'checkbox' : {
                def_value.empty();

                for(var i = 0; i < c.length; i++)
                {
                    def_value.append('<div><input name="default_value[]" type="checkbox" value="' + c[i] + '"/><label for="default_value' + i + '">' + c[i] + '</label></div>');
                }
                break;
            }
        }

    }

    function isUniqueSymbolKey(input){
        var value = jQuery(input).val();
        var unique = true;

        for(var i = 0; i < fields.length; i++){
            unique = unique && fields[i] != value;
        }

        var is_edit = jQuery('#field_dialog').find('input[name="id"]').val();

        return unique || is_edit;
    }

    function checkUniqueSymbolKey(input){

        var unique = isUniqueSymbolKey(input);

        if(unique){
            jQuery(input).css('border', '1px solid #ccc');

        } else {
            jQuery(input).css('border', '1px solid red');
        }
    }

    function updateSymbolKey(value){
        var field = jQuery('#field_symbol_key');
        if(!field.attr('disabled')){
            field.val(value.replace(/\s+/g, '_').toLowerCase()).trigger('keyup');
        }
    }

    function htmlEscape(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

</script>