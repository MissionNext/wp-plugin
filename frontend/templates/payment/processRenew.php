<?php
/**
 * @var $data
 * @var $coupon
 * @var $total
 * @var $fee
 * @var $discount
 * @var $form
 * @var $first_payment
 * @var $type
 */
// print_r($data); 
?>
<div id="payment_cart" class="block">
    <h2><?php echo __("Sites:", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h2>
    <?php foreach($data as $site): 
    	// Tiers only apply to ExploreNext 
    	if ($site['name'] == "ExploreNext") { 
    	if ($site['subscription']['partnership'] == "limited")   { $tier = "Tier 1"; }
    	elseif ($site['subscription']['partnership'] == "basic") { $tier = "Tier 2"; }
    	elseif ($site['subscription']['partnership'] == "plus")  { $tier = "Tier 3"; }
    	} else { 
    	$tier = ""; 
    	}
    // report site names and registartion tier 
    ?>
    <p><?php echo $site['name']; if($tier) echo ' / ' . $tier ?></p>
    <?php endforeach; ?>
    <h2><?php echo __("Price") ?>:</h2>
    <?php if($discount): ?>
    <p class="discount"><?php echo __("Discount") ?>: -<?php echo $discount ?>%</p>
    <?php endif; ?>
    <?php if($fee): ?>
        <p class="fee"><?php echo __("Convenience fee") ?>: $<?php echo sprintf( "%.2f", $fee) ?></p>
    <?php endif; ?>
    <?php if($coupon): ?>
    <p class="coupon"><?php echo __("Coupon") ?>: -$<?php echo sprintf( "%.2f", $coupon['value']) ?></p>
    <?php endif; ?>
    <?php if($first_payment !== null): ?>
    <p class="first_payment"> <?php echo __("First payment") ?>: $<?php echo sprintf( "%.2f", $first_payment) ?></p>
    <?php endif; ?>
    <p class="total"> <?php echo $type == 'm' ? __("Monthly") :__("Total") ?>: $<span><?php echo sprintf( "%.2f", $total)  ?></span></p>
</div>

<div class="block">
    <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="form-horizontal">
        <?php renderTemplate("_inline_form", compact('form')) ?>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-success"><?php echo __("Submit", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </div>

    </form>

</div>