jQuery(document).on('change', "#payment_no_roles tbody td input[type='checkbox']", function(e){
    setRenewOptions();
    push_price();
}).on('change', '#period-select', function(e){
    setPeriod(jQuery(e.target).val());
    setRenewOptions();
    push_price();
}).on('change', '#renewal-options input[name="rt"]', function(e){
    push_price();
}).on('ready', function(e){
    setPeriod(jQuery('#period-select').val());
    setRenewOptions();
    push_price();
});

function setRenewOptions(){

    var period = jQuery('#period-select').val();

    jQuery('#renewal-options > div').hide();

    if(period == 'year'){
        if(is_new_selected()){
            jQuery('#renewal-options .renewal-keep, #renewal-options .renewal-today').show();
            if(jQuery('#renewal-options .renewal-keep input:checked').length > 0){
                jQuery('#renewal-options .renewal-keep input').prop('checked', true);
            } else {
                jQuery('#renewal-options .renewal-today input').prop('checked', true);
            }
        } else {
            jQuery('#renewal-options .renewal-end').show().find('input').prop('checked', true);
        }

        jQuery('.payment-type').hide();
        jQuery('#type-select').val('cc');
    } else {
        jQuery('.payment-type').show();
        jQuery('#renewal-options .renewal-month').show().find('input').prop('checked', true);
    }

}

function setPeriod(val){
    jQuery('th.period, td.price').hide();
    jQuery('th.period.period-'+val+', td.price.price-'+val).show();
}

function push_price(){
    var rows = jQuery("#payment_no_roles tbody td input[type='checkbox']:checked");

    var renew_price = 0;
    var new_price = 0;
    var old_price = 0;
    var valued_sites = 0;

    var type = jQuery('#renewal-options input[name="rt"]:checked').val();

    jQuery.each(rows, function(key, value){
        var tr = jQuery(value).parents('tr');
        var site_price = parseInt(tr.find('td.price:visible').attr('data-price'));
        var isNew = !tr.is('[data-left]');
        var left_price = parseInt(tr.attr('data-left'));

        if( site_price > 0 ){
            valued_sites++;
        }

        switch (type) {
            case 'k' :
                if(isNew){
                    new_price += multiplier * site_price;
                }
                break;
            case 't' :
                if(isNew){
                    new_price += site_price;
                } else {
                    renew_price += site_price - left_price;
                }
                break;
            case 'e' :
                if(isNew){
                    new_price += multiplier * site_price + site_price;
                } else {
                    renew_price += site_price;
                }
                break;
            case 'm' :
                if(!isNew){
                    renew_price += site_price;
                    old_price += site_price > left_price ? left_price : site_price ;
                } else {
                    new_price += site_price;
                }
                break;
        }

    });

    var left_rows = jQuery("#payment_no_roles tbody tr[data-left] td input[type='checkbox']:not(:checked)");

    jQuery.each(left_rows, function(key, value){

        old_price += parseInt(jQuery(value).parents('tr').attr('data-left'));
    });


    TotalCart.new_price = new_price;
    TotalCart.old_price = old_price;
    TotalCart.renew_price = renew_price;
    TotalCart.type = type;
    TotalCart.discount_active = valued_sites > 1;
    TotalCart.update();
}

function is_new_selected(){
    return jQuery("#payment_no_roles tbody tr:not([data-left]) td input[type='checkbox']:checked").length > 0;
}

function is_old_removed(){
    return jQuery("#payment_no_roles tbody tr[data-left] td input[type='checkbox']:not(:checked)").length > 0;
}