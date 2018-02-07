<?php
/**
 * @var $user
 * @var $size
 */

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/avatar', 'avatar.js', array( 'jquery' ));
?>

<style>
    #avatar{
        position: relative;
        width: <?php echo $size ?>px;
        height: <?php echo $size ?>px;
    }
    #avatar img{
        z-index: 2;
        position: absolute;
        top: 0;
        bottom: 0;
        margin: auto;
    }
    #avatar .spinner32{
        position: absolute;
        left:0;
        right:0;
        margin-left:auto;
        margin-right:auto;
        top: <?php echo $size/2 - 16 ?>px;
        visibility: hidden;
        z-index: 10;
    }
    #avatar .action{
        position: absolute;
        left: 50%;
        height: 70px;
        margin: auto;
        top: 0; bottom: 0;
        z-index: 10;
    }
    #avatar button{
        display: block;
        position: relative;
        height: 30px;
        padding: 0 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: #ccc;
        visibility: hidden;
        left: -50%;
    }
    #avatar .background{
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: #000000;
        position: absolute;
        opacity: 0.7;
        visibility: hidden;
        z-index: 10;
    }
    #avatar:hover:not(.loading) button{
        visibility: visible;
    }
    #avatar:hover:not(.loading) .background{
        visibility: visible;
    }
    #avatar form{
        visibility: hidden;
    }
</style>

<div id="avatar">
    <div class="spinner32"></div>
    <label for="avatar_upload"><?php echo get_avatar($user['email'], $size) ?></label>
    <div class="background"></div>
    <div class="action">
        <button class="upload"><?php echo __("Upload", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
        <?php if(\MissionNext\lib\core\Context::getInstance()->getAvatarManager()->hasAvatar(\MissionNext\lib\core\Context::getInstance()->getUser()->getWPUser()->ID)): ?>
        <button class="delete"><?php echo __("Delete", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
        <?php endif; ?>
    </div>
    <form action="/avatar/update" method="POST" enctype="multipart/form-data">
        <input id="avatar_upload" type="file" name="image"/>
    </form>
</div>