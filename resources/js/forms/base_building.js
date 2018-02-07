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

        if(canHaveInnerDependencies) {
            if (dragged.find('input.dependant').length != 0) {
                dragged.find('input.dependant').remove();
            }
        }

        if(canHaveExpandedFields) {
            if (dragged.find('div.option.expanded').length != 0) {
                dragged.find('div.option.expanded').remove();
            }
        }

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

        if(canHaveInnerDependencies) {
            if (dragged.find('input.dependant').length == 0 && jQuery(this).parents('div.group').length == 0) {
                field.append("<input type='checkbox' class='dependant' title='Dependant' />");
            } else if (dragged.find('input.dependant').length != 0 && jQuery(this).parents('div.group').length != 0) {
                dragged.find('input.dependant').remove();
            }
        }

        if(canHaveExpandedFields) {
            if (dragged.find('div.option.expanded').length == 0 && ( type == 'select' || type == 'radio' || 'custom_marital' == type )) {
                field.append("<div class='option expanded'><span>Expanded</span><input type='checkbox' value='1' name='" + jQuery(this).attr('data-name') + "[fields][" + input.attr('data-orig-name') + "][is_expanded]'/>");
            }
        }

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

if(canHavePrivateGroups) {
    group.append('<span class="private">Private</span>');
    group.append("<input class='is-private' type='checkbox' name='" + name + "[is_private]' value='1' />");
}

if(canHaveOuterDependencies) {
    group.append("<input type='hidden' name='" + name + "[is_outer_dependent]' value='" + (+outer) + "'>");
    if (outer) {
        group.append('<select class="depend-select" name="' + name + '[depends_on]" ></select>');
        var innerHtml = '<div class="depends-option-wrapper" style="display: none;">';
        innerHtml += '<label>Depends on option</label>';
        innerHtml += '<select class="depend-option-select" name="' + name + '[depends_on_option]" ></select>';
        innerHtml += '</div>';
        group.append(innerHtml);
    }
}
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
        var plusButton = '<a class="add-sub-group"><img src="' + resourceUrl + '"></a>';
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