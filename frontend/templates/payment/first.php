<?php
/**
 * @var $config
 * @var $userRole
 * @var $app_id
 */
?>
<div class="block">
    <h1>Please Choose Your Subscription Plan</h1>
</div>
<form action="/payment/first/process" method="get">

    <div class="col-sm-9">

        <?php renderTemplate('payment/_choose_subscription', compact('userRole', 'config', 'app_id', 'fees_domain')) ?>

        <div class="col-sm-12 coupon-block">
            <?php renderTemplate('payment/_coupon') ?>
        </div>
    </div>
    <div class=" col-sm-3 payment-total">
        <?php renderTemplate("payment/_total_cart") ?>
    </div>
</form>

