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
                        <td><a class="btn <?php echo ($app_key == $subscription['app']['public_key']) ? "btn-success" : "btn-default"; ?>" target="_blank" href="<?php echo $apps[$subscription['app_id']]; ?>/dashboard"><?php echo $subscription['app']['name']; ?></a></td>
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
                    <a class="btn <?php echo ($app_key == $subscription['app']['public_key']) ? "btn-success" : "btn-default"; ?>" target="_blank" href="<?php echo $apps[$subscription['app_id']]; ?>/dashboard"><?php echo $subscription['app']['name']; ?></a>
                    <br><br><!-- line breaks added by Nelson to have the buttons stack vertically;  -->
                <?php } ?>
            </p>
        <?php } ?>
    </div>
</div>

<div class="info-icons">
    <ul>
        <li>
            <a href="/inquiries">
                <span class="icon-title"><?php echo __('Inquiries', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></span>
                <img src="<?php echo getResourceUrl('/resources/images/dash_inquiries.jpg') ?>" />
                <span class="icon-views"><?php echo __('Views', \MissionNext\lib\Constants::TEXT_DOMAIN) ?>: <?php echo $inquiriesCount; ?></span>
            </a>
        </li>
        <li>
            <a href="/favorite">
                <span class="icon-title"><?php echo __('Favorites', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></span>
                <img src="<?php echo getResourceUrl('/resources/images/dash_favorites.jpg') ?>" />
                <span class="icon-views"><?php echo __('Views', \MissionNext\lib\Constants::TEXT_DOMAIN) ?>: <?php echo $favoritesCount; ?></span>
            </a>
        </li>
        <?php if (\MissionNext\lib\Constants::ROLE_CANDIDATE != $userRole) { ?>
            <li>
                <a href="/affiliates">
                    <span class="icon-title"><?php echo __('Affiliates', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></span>
                    <img src="<?php echo getResourceUrl('/resources/images/dash_affiliates.png') ?>" />
                    <span class="icon-views"><?php echo __('Views', \MissionNext\lib\Constants::TEXT_DOMAIN) ?>: <?php echo $affiliatesCount; ?></span>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>