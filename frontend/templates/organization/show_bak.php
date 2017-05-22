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
                    <a onclick="EmailPopup.open('<?php echo $user['id'] ?>', '<?php echo $organization['id'] ?>')" class="btn btn-primary"><?php echo __('Send message', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
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
                                    <?php else: ?>
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
