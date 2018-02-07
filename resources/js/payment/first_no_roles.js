jQuery(document).on('change', "#payment_no_roles tbody td input[type='checkbox']", function(e){
    push_price();
}).on('ready', function(e){
    push_price();
});

function push_price(){
    var rows = jQuery("#payment_no_roles tbody td input[type='checkbox']:checked");

    var price = 0;
    var valued_sites = 0;

    jQuery.each(rows, function(key, value){
        var site_price = parseInt(jQuery(value).parents('tr').find('td.price').attr('data-price'));

        if(site_price > 0){
            valued_sites++;
        }

        price += site_price;
    });

    TotalCart.renew_price = price;
    TotalCart.discount_active = valued_sites > 1;
    TotalCart.update();
}