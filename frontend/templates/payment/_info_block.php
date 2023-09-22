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
    // for page https://aubdomain.missionnext.org/user/account the value for $site is incorrect. 
	$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after https:// and before first slash 
	if (preg_match("/journey/",$sniff_host)) 		{ $renew_link = "https://secure.myvanco.com/YHAQ/campaign/C-YKMD"; $site = 3; }
	elseif (preg_match("/education/",$sniff_host))  { $renew_link = "https://secure.myvanco.com/YHAQ/campaign/C-YKRF"; $site = 6; }
	elseif (preg_match("/technology/",$sniff_host)) { $renew_link = "https://secure.myvanco.com/YHAQ/campaign/C-YKZ2"; $site = 11; }
	elseif (preg_match("/short-term/",$sniff_host)) { $renew_link = "https://secure.myvanco.com/YHAQ/campaign/C-YKZ1"; $site = 2; }

    ?>
    <!-- <p><?php echo "\$sniff_host=$sniff_host"; ?></p>-->
    <?php $u_id = $user['id']; // <p>echo "\$user = $u_id"; </p>
    ?>
    <p><?php echo "Partnership Level: $level"; ?></p>
    <!--<p><?php echo sprintf(__("Partnership Level: %s", \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst($user['subscription']['partnership'])) ?></p>-->
    <?php endif; ?>
    
  	<?php 
  	$link_out = "https://partner.missionnext.org/renew.php?partner=$u_id&site=$site"; ?>
    <a href="<?php echo "$link_out" ?>" class="btn btn-success" target="_blank"><?php echo __("Renew Now", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
	
    <!--<a href="<?php echo "$renew_link" ?>" class="btn btn-success" target="_blank"><?php echo __("Renew Now", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>-->	
    
</div>