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
                <a onclick="EmailPopup.open('<?php echo $user['email'] ?>', '<?php echo $job['organization']['email'] ?>')" class="btn btn-primary"><?php echo __("Send message", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
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
                                        <a href="<?php echo $config->get('api_base_path') . '/' . $config->get('api_uploads_dir') . '/' . $field['value'] ?>" class="mn-input-file-data"></a>
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
                                        <a href="<?php echo $config->get('api_base_path') . '/' . $config->get('api_uploads_dir') . '/' . $field['value'] ?>" class="mn-input-file-data"></a>
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

    jQuery(document).on('click', '#make_favorite', function(e){
        addFavorite( <?php echo $job['id'] ?>, 'job',function(data){
            jQuery('#remove_from_favorites').attr('data-id', data['id']).removeClass('hide');
            jQuery('#make_favorite').addClass('hide');
        });
    }).on('click', '#remove_from_favorites', function(e){
        removeFavorite(jQuery(e.target).attr('data-id'),function(data){
            jQuery('#remove_from_favorites').attr('data-id', false).addClass('hide');
            jQuery('#make_favorite').removeClass('hide');
        });
    }).on('click', '#make_inquire', function(e){
        inquire( <?php echo $job['id'] ?>, function(data){
            jQuery('#cancel_inquire').removeClass('hide');
            jQuery('#make_inquire').addClass('hide');
        });
    }).on('click', '#cancel_inquire', function(e){
        cancelInquire( <?php echo $job['id'] ?>, function(data){
            jQuery('#cancel_inquire').addClass('hide');
            jQuery('#make_inquire').removeClass('hide');
        });
    });

</script>