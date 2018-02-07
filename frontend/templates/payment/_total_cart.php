<?php
$fee = \MissionNext\lib\GlobalConfig::getSubscriptionFee();
?>
<div id="payment_total_cart">
    <h4>Purchase Summary:</h4>
    <p class="subtotal"><?php echo __("Subtotal", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>: $<span>0.00</span></p>
    <p class="discount"><?php echo __("Discount", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>: <span>10.00</span>%</p>
    <p class="coupon"><?php echo __("Coupon", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>: $<span>0.00</span></p>
    <?php if($fee > 0): ?>
        <p class="fee"><?php echo __("Fee", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>: $<span><?php echo sprintf( "%.2f", $fee) ?></span></p>
    <?php endif; ?>
    <p class="first_payment"><?php echo __("First payment", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>: $<span>0.00</span></p>
    <p class="monthly"><?php echo __("Monthly", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>: $<span>0.00</span></p>
    <p class="total"><?php echo __("Total", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>: $<span>0.00</span></p>
    <div>
        <button class="btn btn-success"><?php echo __("Checkout", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
    </div>
</div>

<script>
    var fee = '<?php echo $fee ?>';
    var discount = '<?php echo \MissionNext\lib\GlobalConfig::getSubscriptionDiscount() ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/payment/total_cart', 'payment/total_cart.js', array( 'jquery' ));
?>