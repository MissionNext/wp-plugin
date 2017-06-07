<div class="page-header">
    <?php if(!empty($name)): ?>
        <h1><?php echo __('Hello', \MissionNext\lib\Constants::TEXT_DOMAIN) . ', ' . $name; ?></h1> <!--Candidate Name-->
    <?php else: ?>
        <h1><?php echo __('Hello', \MissionNext\lib\Constants::TEXT_DOMAIN); ?></h1>
    <?php endif ;?>
</div>
<div class="dashboard-applications">
    <div class="matching-status">
        <p class="matching-inprogress" style="display: none;">
            <img src="<?php echo getResourceUrl('/resources/images/spinner_32x32.gif') ?>" width="16" />
            <span>Matching calculations in progress.</span>
        </p>
        <p class="matching-ready" style="display: none;">
            <img src="<?php echo getResourceUrl('/resources/images/green_circle_icone.png') ?>" width="16" />
            <span>Matching results ready.</span>
        </p>
    </div>
    <div class="col-md-12">
        <?php if ($userRole == \MissionNext\lib\Constants::ROLE_CANDIDATE) { ?>
            <table class="subscriptions-table">
                <?php foreach($subscriptions as $subscription) { ?>
                    <tr>
                        <td><a class="btn <?php echo ($app_key == $subscription['app']['public_key']) ? "btn-success" : "btn-default"; ?>" <?php if ($app_key != $subscription['app']['public_key']) { ?> target="_blank" <?php } ?> href="<?php echo $apps[$subscription['app_id']]; ?>/dashboard"><?php echo $subscription['app']['name']; ?></a></td>
                        <td></td>
                    </tr>
                <?php } ?>
                <?php foreach($candidateSubs as $sub) { ?>
                    <tr>
                        <td><a class="btn btn-default" disabled target="_blank" href="<?php echo $apps[$sub['app_id']]; ?>/dashboard"><?php echo $sub['app_name']; ?></a></td>
                        <td><a class="btn btn-default" href="/subscription/add/<?php echo $sub['app_id']; ?>">SignUp for Free</a></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p class="left">
                <?php foreach($subscriptions as $subscription) { ?>
                    <a class="btn <?php echo ($app_key == $subscription['app']['public_key']) ? "btn-success" : "btn-default"; ?>" <?php if ($app_key != $subscription['app']['public_key']) { ?> target="_blank" <?php } ?> href="<?php echo $apps[$subscription['app_id']]; ?>/dashboard"><?php echo $subscription['app']['name']; ?></a>
                    <br><br><!-- line breaks added by Nelson to have the buttons stack vertically;  -->
                <?php } ?>
            </p>
        <?php } ?>
    </div>
</div>
<?php
        $sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 
		if (preg_match("/explorenext/",$sniff_host))   { $subdomain = "explorenext"; }
		elseif (preg_match("/teachnext/",$sniff_host)) { $subdomain = "teachnext"; }
		elseif (preg_match("/jg./",$sniff_host)) { $subdomain = "jg"; }
if ($subdomain != "jg") {
?>
<div class="info-icons">
    <ul>
        <li>
            <a href="/inquiries">
                <span class="icon-title"><?php echo __('Inquiries', \MissionNext\lib\Constants::TEXT_DOMAIN) ?><br><?php echo $inquiriesCount; ?></span>
                <img src="<?php echo getResourceUrl('/resources/images/dash_inquiries.jpg') ?>" />
                
            </a>
        </li>
        <?php if (\MissionNext\lib\Constants::ROLE_AGENCY != $userRole) { ?>
        <li>
            <a href="/favorite">
                <span class="icon-title"><?php echo __('Favorites', \MissionNext\lib\Constants::TEXT_DOMAIN) ?><br><?php echo $favoritesCount; ?></span>
                <img src="<?php echo getResourceUrl('/resources/images/dash_favorites.jpg') ?>" />
                
            </a>
        </li>
        <?php } ?>
        <?php if (\MissionNext\lib\Constants::ROLE_CANDIDATE != $userRole) { ?>
            <li>
                <a href="/affiliates">
                    <span class="icon-title"><?php echo __('Affiliates', \MissionNext\lib\Constants::TEXT_DOMAIN) ?><br><?php echo $affiliatesCount; ?></span>
                    <img src="<?php echo getResourceUrl('/resources/images/dash_affiliates.png') ?>" />
                    
                </a>
            </li>
        <?php } ?>
    </ul>
</div> <!--<div class="info-icons">-->
<? } // if ($subdomain != "jg") 
	else { ?>
	<table>
	<tr><td align="center">JOURNEY GUIDE DASHBOARD</p></td></tr>
	<tr><td align="center"><a href="https://guides.missionnext.org/jg_home.php"><img src="<?php echo getResourceUrl('/resources/images/dash_affiliates.png') ?>" /></a></td></tr>
	<tr><td align="center">Under Construction</p></td></tr>
	</table>
	<?php }
?>