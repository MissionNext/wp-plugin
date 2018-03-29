<?php

/**
 * @var array $organization
 * @var array $presentation
 */

?>

<div class="page-header">
    <h1><?php echo !empty($organization['profileData']['organization_name']) ? $organization['profileData']['organization_name'] : $organization['username']; ?></h1>
</div>
<div class="page-content">
    <div class=" sidebar-container">
        <div class="sidebar">
            <div class="info">

                <?php echo get_avatar($organization['email'], 203) ?>
            </div>

            <?php if($organization['email'] != $user['email']): ?>
                <div class="buttons">
                    <a id="sendEmail" class="btn btn-primary"><?php echo __('Send message', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </div>
            <?php endif; ?>

            <div class="buttons">
                <a href="/organization/<?php echo $organization['id'] ?>/jobs" class="btn btn-default"><?php echo __('View positions', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>

            <?php if(!empty($presentation['value'])): ?>
                <div class="buttons">
                    <a href="/organization/<?php echo $organization['id'] ?>" class="btn btn-default"><?php echo __('View profile', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </div>
            <?php endif; ?>
 
            <?php if( $userRole != \MissionNext\lib\Constants::ROLE_AGENCY) { ?>
                <div class="buttons">
                    <button id="make_favorite" class="btn btn-success <?php echo $organization['favorite']?'hide':'' ?>"><?php echo __("Make favorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                    <button data-id="<?php echo $organization['favorite'] ?>"  id="remove_from_favorites" class="btn btn-danger <?php echo $organization['favorite']?'':'hide' ?>"><?php echo __("Unfavorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                </div>
            <?php } ?>

            <div class="control-buttons">
                <div class="left">
                <a class="btn btn-default" href="/dashboard"><?php echo __('Dashboard', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </div>
            </div>
        </div> <!--<div class="sidebar">-->
    </div>

    <div class="content presentation-content">
        <h2><?php echo __('Presentation', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h2>

        <?php if(!empty($presentation['value'])): ?>
            <?php echo $presentation['value']; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    var from = '<?php echo $user['id'] ?>';
    var to = '<?php echo $organization['id'] ?>';
    var from_name = '<?php echo isset($user['profileData']['agency_full_name']) ?
        str_replace("'", "`", $user['profileData']['agency_full_name']) :
        str_replace("'", "`", $user['profileData']['first_name']) . ' ' . str_replace("'", "`", $user['profileData']['last_name']) ?>';
    var to_name = '<?php echo str_replace("'", "`", $organization['profileData']['organization_name']) ?>';
</script>

<?php
    renderTemplate('_email_candidate_popup');
    \MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/email_candidate_popup', 'email_candidate_popup.js', array( 'jquery', 'jquery-ui-dialog' ), false, true);
    \MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/organization/presentation', 'organization/presentation.js', array( 'jquery', 'mn/email_candidate_popup' ), false, true);
?>
