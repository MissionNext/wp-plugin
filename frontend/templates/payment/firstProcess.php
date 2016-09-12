<?php
/**
 * @var $data
 * @var $coupon
 * @var $total
 * @var $fee
 * @var $discount
 * @var $form
 */
?>

<div id="payment_cart" class="block">
    <h2><?php echo __("Selected sites") ?>:</h2>
    <?php foreach($data as $site): ?>
    <p><?php echo $site['name']; if($site['subscription']['partnership']) echo ' / '. $site['subscription']['partnership']?>: $<span><?php echo sprintf( "%.2f", $site['subscription']['price_year']) ?></span></p>
    <?php endforeach; ?>
    <?php if($discount): ?>
    <p class="discount"><?php echo __("Discount") ?>: -<?php echo $discount ?>%</p>
    <?php endif; ?>
    <?php if($coupon): ?>
    <p class="coupon"><?php echo __("Coupon") ?>: -$<?php echo sprintf( "%.2f", $coupon['value']) ?></p>
    <?php endif; ?>
    <p class="total" data-raw="<?php echo $total ?>" data-fee="<?php echo $total + $fee ?>"><?php echo __("Total") ?>: $<span><?php echo sprintf( "%.2f", $total) ?></span></p>
</div>

<div class="block">
    <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="form-horizontal">
       <p><font color="#ffffff">mntemplates/payment/firstProcess.php</font> <strong>MissionNext Partnership Payment Form</strong></p>
        <?php renderTemplate("_inline_form", compact('form')) ?>

        <div class="form-group">
            <div class="col-sm-12">
                <font color="#ffffff">__templates/payment/firstProcess.php</font> <button type="submit" class="btn btn-success"><?php echo __("Submit", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </div>

    </form>

</div>