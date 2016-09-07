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
                    <a onclick="EmailPopup.open('<?php echo $user['email'] ?>', '<?php echo $organization['email'] ?>')" class="btn btn-primary"><?php echo __('Send message', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
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
                    <button id="make_favorite" class="btn btn-success <?php echo $organization['favorite']?'hide':'' ?>"><?php echo __("Make favorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                    <button data-id="<?php echo $organization['favorite'] ?>"  id="remove_from_favorites" class="btn btn-danger <?php echo $organization['favorite']?'':'hide' ?>"><?php echo __("Unfavorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                </div>
            <?php } ?>
        </div>
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
                                        <a href="<?php echo $config->get('api_base_path') . '/' . $config->get('api_uploads_dir') . '/' . $field['value'] ?>" class="mn-input-file-data"></a>
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

    jQuery(document).on('click', '#make_favorite', function(e){
        addFavorite( <?php echo $organization['id'] ?>, 'organization',function(data){
            jQuery('#remove_from_favorites').attr('data-id', data['id']).removeClass('hide');
            jQuery('#make_favorite').addClass('hide');
        });
    }).on('click', '#remove_from_favorites', function(e){
        removeFavorite(jQuery(e.target).attr('data-id'),function(data){
            jQuery('#remove_from_favorites').attr('data-id', false).addClass('hide');
            jQuery('#make_favorite').removeClass('hide');
        });
    });

</script>
