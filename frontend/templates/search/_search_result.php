<?php
/**
 * @var $userId Int
 * @var $role String
 * @var $result Array
 * @var $messages Array
 * @var $userRole String
 */
?>
<div id="result_table">
    <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('common/_'.$role.'_table', array('role' => $role, 'items' => $result, 'messages' => $messages, 'userRole' => $userRole, 'userId' => $userId)) ?>
</div>
