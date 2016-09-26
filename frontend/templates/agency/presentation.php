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
            </div>
            <?php if($agency['email'] != $user['email']): ?>
                <div class="buttons">
                    <a onclick="EmailPopup.open('<?php echo $user['email'] ?>', '<?php echo $agency['email'] ?>')" class="btn btn-primary"><?php echo __('Send message', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </div>
            <?php endif; ?>

            <?php if(!empty($presentation['value'])): ?>
                <div class="buttons">
                    <a href="/agency/<?php echo $agency['id'] ?>" class="btn btn-default"><?php echo __('View profile', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="content">

        <h2><?php echo __('Presentation', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h2>

        <?php if(!empty($presentation['value'])): ?>
            <?php echo $presentation['value']; ?>
        <?php endif; ?>
    </div>
</div>
