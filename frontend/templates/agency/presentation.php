<?php

/**
 * @var array $agency
 * @var array $presentation
 */

?>

<div class="page-header">
    <h1><?php echo $agency['username'] ?></h1>
</div>
<div class="page-content">
    <div class=" sidebar-container">
        <div class="sidebar">
            <div class="info">

                <?php echo get_avatar($agency['email'], 203) ?>
            </div> <!--class=info -->
            <?php if($agency['email'] != $user['email']): ?>
                <div class="buttons">
                    <a id="sendEmail" class="btn btn-primary"><?php echo __('Send message', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </div>
            <?php endif; ?>

            <?php if(!empty($presentation['value'])): ?>
                <div class="buttons">
                    <a href="/agency/<?php echo $agency['id'] ?>" class="btn btn-default"><?php echo __('View profile', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </div>
            <?php endif; ?>

            <div class="control-buttons">
                <div class="left">
                <a class="btn btn-default" href="/dashboard"><?php echo __('Dashboard', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </div>
            </div>

        </div> <!-- class=sidebar -->
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
    var to = '<?php echo $agency['id'] ?>';
    var from_name = '<?php echo isset($user['profileData']['organization_name']) ? 
        str_replace("'", "`", $user['profileData']['organization_name']) : 
        str_replace("'", "`", $user['profileData']['first_name']) . ' ' . str_replace("'", "`", $user['profileData']['last_name']) ?>';
    var to_name = '<?php echo str_replace("'", "`", $agency['profileData']['agency_full_name'])?>';
</script>

<?php
    renderTemplate('_email_candidate_popup');
    \MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/email_candidate_popup', 'email_candidate_popup.js', array( 'jquery', 'jquery-ui-dialog' ), false, true);
    \MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/organization/presentation', 'organization/presentation.js', array( 'jquery', 'mn/email_candidate_popup' ), false, true);
?>
