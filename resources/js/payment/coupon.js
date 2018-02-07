jQuery(document).on('click', '#coupon_check_button', function(e){

    var code = jQuery('#payment_coupon').val();

    if(!code){
        return;
    }

    jQuery.ajax({
        url: "/payment/coupon/check",
        method: 'post',
        dataType: 'json',
        data: {
            code: code
        },
        success: function(data){
            if(data.is_active && data.value){
                TotalCart.coupon_code = code;
                TotalCart.coupon_price = data.value;
                TotalCart.update();
                jQuery('#payment_coupon_store').val(code);
                jQuery('#coupon_checker .error').hide().text('');
            } else {
                jQuery('#coupon_checker .error').show().text(wrong_code);
            }
        }
    });
});