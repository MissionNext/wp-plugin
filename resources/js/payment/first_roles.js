jQuery(document).on('change', '#payment_table_roles input[type="radio"]', function(e){
    pushPrice();
}).on('ready', function(e){
    pushPrice();
});

function pushPrice(){

    var rows = jQuery("#payment_table_roles input[type='radio']:not([value='none']):checked");

    var price = 0;
    var sites = 0;

    jQuery.each(rows, function(key, value){

        var site_price = parseInt(jQuery(value).parents('td').attr('data-price'));

        if(site_price){
            price += site_price;
            sites++;
        }

    });

    TotalCart.renew_price = price;
    TotalCart.discount_active = sites > 1;
    TotalCart.update();
}