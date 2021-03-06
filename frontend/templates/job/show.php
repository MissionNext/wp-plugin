<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $job
 * @var Array $fields
 */
// echo "\$userRole = $userRole";
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
    <h1><?php echo $job['name'] ?></h1>
</div>
<div class="page-content">

    <div class=" sidebar-container">
        <div class="sidebar">
            <div class="info">

                <?php echo get_avatar($job['organization']['email'], 203) ?>
            </div>
        <?php if($job['organization']['email'] != $user['email']): ?>
            <div class="buttons">
                <a id="sendEmail" class="btn btn-primary"><?php echo __("Send message", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>
        <?php endif; ?>

        <?php if($userRole == \MissionNext\lib\Constants::ROLE_CANDIDATE): ?>
        <div class="buttons">
            <button id="make_inquire" class="btn btn-success <?php echo $job['inquire']?'hide':'' ?>"><?php echo __("Inquire Now", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            <button id="cancel_inquire" class="btn btn-danger <?php echo $job['inquire']?'':'hide' ?>"><?php echo __("Cancel Inquire", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
        </div>

        <div class="buttons">
            <button id="make_favorite" title="Click once. Wait a few seconds for update" class="btn btn-success <?php echo $job['favorite']?'hide':'' ?>"><?php echo __("Make favorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            <button data-id="<?php echo $job['favorite'] ?>" id="remove_from_favorites" title="Click once. Wait a few seconds for update" class="btn btn-danger <?php echo $job['favorite']?'':'hide' ?>"><?php echo __("Unfavorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
        </div>

        <?php endif; ?>
        </div>

    </div>
    <div class="content">
        <button class="btn btn-default position-right" onclick="javascript:window.close();">Close</button>
        <p> <strong><?php echo __("Name", \MissionNext\lib\Constants::TEXT_DOMAIN) ?> : <?php echo $job['name'] ?></strong></p>
        <p> <strong><?php echo ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION)) ?></strong> :
            <a href="/organization/<?php echo $job['organization']['id'] ?>">
                <?php echo $job['org_name']; ?>
            </a>
        </p>
	<?php if($userRole == \MissionNext\lib\Constants::ROLE_AGENCY): ?> 
        <?php foreach($job['profile'] as $group): ?>
            <?php if(!groupEmpty($group['fields'])): ?>
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
                                    <?php else: echo "&nbsp;"; ?> <!--space added by Nelson Apr 23, 2016-->
                                        <?php echo $field['value'] ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </fieldset>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
         <?php foreach($job['profile'] as $group): ?>
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
                                    <?php else: echo "&nbsp;"; ?> <!--space added by Nelson Apr 23, 2016-->
                                        <?php echo $field['value'] ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </fieldset>
            <?php endif; ?>
        <?php endforeach; ?>
   
	<?php endif; ?>
        <div class="control-buttons">
            <div class="left">
                <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                <?php if($userRole == \MissionNext\lib\Constants::ROLE_ORGANIZATION): ?> <!--Only Organizations can enter/edit/delete jobs. Edit logic added by Nelson Oct 21, 2016-->
                <a class="btn btn-default" href="/job/<?php echo $job['id'] ?>/edit"><?php echo __("Edit Job", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                <a class="btn btn-default" href="/job"><?php echo __("Jobs", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a> <!--job button added by Nelson Apr 23, 2016-->
            	<?php endif; ?>
                <button class="btn btn-default" onclick="javascript:window.close();"><strong>Close</strong></button> <!--Close button added by Nelson Oct 21, 2016-->
            </div>
        </div>
    </div>

</div>

<script>
    var job_id = '<?php echo $job['id'] ?>';
    var from = '<?php echo $user['id'] ?>';
    var to = '<?php echo $job['organization']['id'] ?>';
    var from_name = '<?php echo str_replace("'", "`", $user['profileData']['first_name']) . ' ' . str_replace("'", "`", $user['profileData']['last_name']) ?>';
    var to_name = '<?php echo str_replace("'", "`", $job['org_name']) ?>';
</script>

<?php
    renderTemplate('_email_candidate_popup');
    \MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/email_candidate_popup', 'email_candidate_popup.js', array( 'jquery', 'jquery-ui-dialog' ), false, true);
    \MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/job/show', 'job/show.js', array( 'jquery', 'mn/email_candidate_popup' ), false, true);
?>