jQuery(document).on('change', '#payment_table_roles input[type="radio"]', function(e){
    setRenewOptions();
    pushPrice();
}).on('change', '#period-select', function(e){
    var period = jQuery(e.target).val();
    setPeriod(period);
    setPeriodLevels(period)
    setRenewOptions();
    pushPrice();
}).on('change', '#renewal-options input[name="rt"]', function(e){
    pushPrice();
}).on('ready', function(e){
    setPeriod(jQuery('#period-select').val());
    setRenewOptions();
    pushPrice();
});

function setRenewOptions(){

    var period = jQuery('#period-select').val();

    jQuery('#renewal-options > div').hide();

    if(period == 'year'){
        jQuery('.coupon-block').show();
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
        jQuery('.coupon-block').hide();
        jQuery('#payment_coupon_store').val('');
        jQuery('#payment_coupon').val('');
        TotalCart.coupon_code = '';
        TotalCart.coupon_price = 0;
        TotalCart.update();
    }
}

function pushPrice(){

    var rows = jQuery("#payment_table_roles input[type='radio']:not([value='none']):checked");

    var renew_price = 0;
    var new_price = 0;
    var old_price = 0;
    var valued_sites = 0;

    var type = jQuery('#renewal-options input[name="rt"]:checked').val();

    jQuery.each(rows, function(key, value){
        value = jQuery(value);
        var tr = jQuery(value).parents('tr');
        var site_price = parseInt(value.parents('td').attr('data-price'));
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

    var left_rows = jQuery("#payment_table_roles tr[data-left] input[type='radio'][value='none']:checked");

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

function setPeriod(period){
    jQuery("#payment_table_roles td[data-period]").hide();
    jQuery("#payment_table_roles td[data-period='"+period+"']").show();
}

function setPeriodLevels(period){

    var hidden = jQuery("#payment_table_roles td:has(input:not([value='none']):checked):hidden");

    jQuery.each(hidden, function(key, value){
        value = jQuery(value);

        value.find('input').prop('checked', false);

        value.siblings("td[data-period='"+period+"'][data-partnership='"+value.attr('data-partnership')+"']").find('input').prop('checked', true);
    });
}

function is_new_selected(){
    return jQuery("#payment_table_roles tbody tr:not([data-left]) td input[type='radio']:checked:not([value='none'])").length > 0;
}

function is_old_removed(){
    return jQuery("#payment_table_roles tbody tr[data-left] td input[type='radio'][value='none']:checked").length > 0;
}