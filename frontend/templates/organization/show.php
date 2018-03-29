<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $organization
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

//echo "<pre>";
//print_r($organization);
//echo "</pre>";

?>
<div class="page-header">
    <h1><?php echo !empty($organization['profileData']['organization_name']) ? $organization['profileData']['organization_name'] : $organization['username']; ?></h1>
</div>
<div class="page-content">
    <button class="btn btn-default position-right" onclick="javascript:window.close();">Close</button>
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
                    <a href="/organization/<?php echo $organization['id'] ?>/presentation" class="btn btn-default"><?php echo __('View presentation', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </div>
            <?php endif; ?>

            <?php if( $userRole != \MissionNext\lib\Constants::ROLE_AGENCY) { ?>
                <div class="buttons">
                    <button id="make_favorite" title="Click once. Wait a few seconds for update" class="btn btn-success <?php echo $organization['favorite']?'hide':'' ?>"><?php echo __("Make favorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                    <button data-id="<?php echo $organization['favorite'] ?>"  id="remove_from_favorites" title="Click once. Wait a few seconds for update" class="btn btn-danger <?php echo $organization['favorite']?'':'hide' ?>"><?php echo __("Unfavorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                </div>
            <?php } ?>
        </div> <!--<div class="sidebar">-->
    </div>

    <div class="content">
        <?php foreach($organization['profile'] as $group): ?>
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
                                   <?php else: echo "&nbsp;"; ?> <!--space added by Nelson Apr 20, 2016-->
                                        <?php echo $field['value'] ?>
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
        <button class="btn btn-default position-right" onclick="javascript:window.close();">Close</button>
    </div>
</div>

<script>
    var organization_id = '<?php echo $organization['id'] ?>';
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
    \MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/organization/show', 'organization/show.js', array( 'jquery', 'mn/email_candidate_popup' ), false, true);
?>
