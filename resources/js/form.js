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