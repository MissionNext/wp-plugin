jQuery(document).on('change', '.languages .language input[type="checkbox"]', function(e){
    updateDefaultLanguages();
});

function updateDefaultLanguages(){
    var select = jQuery("#default_language");
    var default_value = select.val();
    var inputs = jQuery(".languages .language input[type='checkbox']:checked");

    select.empty();
    jQuery.each(inputs, function(key, value){

        value = jQuery(value);
        var label = value.siblings('label').text();
        var val = value.val();

        if(val == default_value){
            select.append("<option selected='selected' value="+val+">"+label+"</option>");
        } else {
            select.append("<option value="+val+">"+label+"</option>");
        }
    });
}