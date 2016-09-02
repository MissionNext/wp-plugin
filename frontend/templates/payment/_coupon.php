<div id="coupon_checker">

    <div class="col-sm-offset-3 col-sm-3">
        <p class="error"></p>
    </div>

    <div class="col-sm-6">
        <label for="payment_coupon"><?php echo __("Coupon:", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></label>

        <input type="hidden" id="payment_coupon_store" name="c"/>
        <input id="payment_coupon" type="text"/>

        <button id="coupon_check_button" class="btn btn-default" type="button"><?php echo __("Apply", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>

    </div>
</div>

<script>
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
                    jQuery('#coupon_checker .error').show().text('<?php echo __("Wrong code", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>');
                }
            }
        });
    });
</script>