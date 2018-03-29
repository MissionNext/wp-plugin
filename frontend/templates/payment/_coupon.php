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
    var wrong_code = '<?php echo __("Wrong code", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/payment/coupon', 'payment/coupon.js', array( 'jquery' ), false, true);
?>