<?php
/**
 * @var $user
 */
?>
<div id="subscription-info">
    <span class="subscription-title"><?php echo __("Subscription", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></span>
    <?php if($user['subscription']['is_recurrent']): ?>
    <p><?php echo __("On Monthly Basis", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></p>
    <?php else: ?>
    <p><?php echo sprintf(__("Days left: %s", \MissionNext\lib\Constants::TEXT_DOMAIN), $user['subscription']['days_left']) ?></p>
    <?php endif; ?>

    <?php if($user['role'] == \MissionNext\lib\Constants::ROLE_ORGANIZATION): 
    $raw_level = ucfirst($user['subscription']['partnership']);
    if ($raw_level == "Limited")	{ $level = "Tier 1"; }
    elseif ($raw_level == "Basic")	{ $level = "Tier 2"; }
    elseif ($raw_level == "Plus")	{ $level = "Tier 3"; }
    else { $level = $raw_level; }
    
    ?>
    <p><?php echo "Partnership Level: $level"; ?></p>
    <!--<p><?php echo sprintf(__("Partnership Level: %s", \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst($user['subscription']['partnership'])) ?></p>-->
    <?php endif; ?>

    <a href="/payment/renew" class="btn btn-success" ><?php echo __("Renew Now", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
</div>