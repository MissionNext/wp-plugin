<?php
/**
 * @var $userRole
 * @var $defaults
 * @var $config
 * @var $days_left
 * @var $total_days
 */
?>

<div class="block">
    <h1>Please Choose Your Subscription Plan</h1>
</div>
<form action="/payment/renew/process" method="get" role="form">

    <div class="col-sm-9">

        <?php if($userRole == \MissionNext\lib\Constants::ROLE_ORGANIZATION){
            renderTemplate("payment/_renew_roles", compact('config', 'defaults', 'days_left', 'total_days'));
        } else {
            renderTemplate("payment/_renew_no_roles", compact('config', 'defaults', 'days_left', 'total_days'));
        }
        ?>

    </div>
    <div class=" col-sm-3 payment-total">
        <?php renderTemplate("payment/_total_cart") ?>
    </div>
</form>
