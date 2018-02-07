jQuery(document).on('change', '[data-dependant]', function(e){
    var field = jQuery('[data-dependant="'+jQuery(e.target).attr('data-dependant')+'"]');
    var subgroup = jQuery('.dependent-group[data-key="' + field.attr('data-dependant') + '"]');

    if(field.length == 1 && field.attr('type') != 'checkbox'){
        if(field.val()){
            subgroup.show();
        } else {
            subgroup.hide();
        }
    } else {
        if(field.is(':checked')){
            subgroup.show();
        } else {
            subgroup.hide();
        }
    }
});

jQuery(document).ready(function(){
    jQuery('#tabs').tabs();

    jQuery('[data-dependant]').trigger('change');
});