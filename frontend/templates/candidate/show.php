<?php
/**
 * @var String $name
 * @var Array $user
 * @var String $userRole
 * @var Array $candidate
 * @var Array $fields
 * @var \MissionNext\lib\core\Context $context
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
    <h1><?php echo $name ?></h1>
</div>
<div class="page-content">
    <button class="btn btn-default position-right" onclick="javascript:window.close();">Close</button>
    <div class=" sidebar-container">
        <div class="sidebar">
            <div class="info">

                <?php echo get_avatar($candidate['email'], 160) ?>
            </div>
            <?php if($candidate['email'] != $user['email']): ?>
            <div class="buttons">
                <a onclick="EmailPopup.open('<?php echo $user['email'] ?>', '<?php echo $candidate['email'] ?>')" class="btn btn-primary"><?php echo __("Send message", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>
            <?php endif; ?>

            <?php if( $userRole == 'organization' || $userRole == 'agency' ): ?>
            <div class="buttons">
                <button id="make_favorite" class="btn btn-success <?php echo $candidate['favorite']?'hide':'' ?>"><?php echo __("Make favorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                <button data-id="<?php echo $candidate['favorite'] ?>"  id="remove_from_favorites" class="btn btn-danger <?php echo $candidate['favorite']?'':'hide' ?>"><?php echo __("Unfavorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="content">
        <p> <strong><?php echo __("Username", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong> : <span><?php echo $candidate['username'] ?></span></p>
        <p> <strong><?php echo __("Email", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong> : <span><?php echo $candidate['email'] ?></span></p>

        <?php foreach($candidate['profile'] as $group): ?>
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
                <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>
        </div>
        <button class="btn btn-default position-right" onclick="javascript:window.close();">Close</button>
    </div>

</div>


<script>

    jQuery(document).on('click', '#make_favorite', function(e){
        addFavorite( <?php echo $candidate['id'] ?>, 'candidate',function(data){
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