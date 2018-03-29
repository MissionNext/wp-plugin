<?php
/**
 * @var $userId Int
 * @var $role String
 * @var $result Array
 * @var $messages Array
 * @var $userRole String
 */

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/search/search_result', 'search/search_result.js', array( 'jquery' ), false, true);
?>
<div id="result_table">
    <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('common/_'.$role.'_table', array('role' => $role, 'items' => $result, 'messages' => $messages, 'userRole' => $userRole, 'userId' => $userId, 'organization_id' => $userId, 'pagename' => 'search')) ?>
    <?php renderTemplate("common/_pager", compact('page', 'pages')) ?>
</div>