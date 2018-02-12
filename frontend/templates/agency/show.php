<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $agency
 * @var Array $fields
 * @var array $presentation
 */
$config = $context->getConfig();
function fieldEmpty($field){
    return empty($field) || $field == array(''=>'');
}
function groupEmpty($group){
    foreach($group as $field){

        if(!fieldEmpty($field['value'])){
            return false;
        }
    }
    return true;
}
?>

<div class="page-header">
    <h1><?php echo !empty($agency['profileData']['agency_full_name']) ? $agency['profileData']['agency_full_name'] : $agency['username']; ?></h1>
</div>
<div class="page-content">
    <div class=" sidebar-container">
        <div class="sidebar">
            <div class="info">

                <?php echo get_avatar($agency['email'], 203) ?>
            </div>
            <?php if($agency['email'] != $user['email']): ?>
                <div class="buttons">
                    <a id="sendEmail" class="btn btn-primary"><?php echo __('Send message', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </div>
            <?php endif; ?>

            <?php if(!empty($presentation['value'])): ?>
                <div class="buttons">
                    <a href="/agency/<?php echo $agency['id'] ?>/presentation" class="btn btn-default"><?php echo __('View presentation', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="content">

        <!--<p> <strong><?php echo __('Username', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong> : <?php echo $agency['username'] ?></p>-->
        <p> <strong><?php echo __('Email', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong> : <?php echo $agency['email'] ?></p>

        <?php foreach($agency['profile'] as $group): ?>
            <?php if(!groupEmpty($group['fields']) && ( isset($group['meta']['is_private']) && !$group['meta']['is_private'] || !isset($group['meta']['is_private']) ) ): ?>
                <fieldset class="mn-profile-group">
                    <legend><?php echo $group['name'] ?></legend>

                    <?php foreach($group['fields'] as $field): ?>
                        <?php if(!fieldEmpty($field['value'])): ?>
                            <div>
                                <strong><?php echo $field['label'] ?>:</strong>
                                <div>
                                    <?php if(is_array($field['value'])): ?>

                                        <?php foreach($field['value'] as $value): ?>
                                            <div><?php echo $value ?></div>
                                        <?php endforeach; ?>

                                    <?php elseif($field['type'] == 'file' && $field['value']): ?>
                                        <a href="<?php echo '/profile/file/' . $field['value'] ?>" class="mn-input-file-data"></a>
                                    <?php elseif('boolean' == $field['type'] && $field['value']): ?>
                                        <?php echo "&nbsp;"; ?>
                                        <?php echo (1 == $field['value']) ? "Yes" : "No" ; ?>
                                    <?php else: ?>
                                        &nbsp; <?php echo $field['value'] ?> 
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </fieldset>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="control-buttons">
            <div class="left">
                <a class="btn btn-default" href="/dashboard"><?php echo __('Dashboard', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>
        </div>
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
    \MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/email_candidate_popup', 'email_candidate_popup.js', array( 'jquery', 'jquery-ui-dialog' ));
    \MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/organization/presentation', 'organization/presentation.js', array( 'jquery', 'mn/email_candidate_popup' ));
?>