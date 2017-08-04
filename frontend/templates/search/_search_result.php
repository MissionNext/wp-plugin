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
    <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('common/_'.$role.'_table', array('role' => $role, 'items' => $result, 'messages' => $messages, 'userRole' => $userRole, 'userId' => $userId, 'organization_id' => $userId, 'pagename' => 'search')) ?>
    <?php renderTemplate("common/_pager", compact('page', 'pages')) ?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.pagination a').on('click', function (e) {
            e.preventDefault();
            var pageNumber = jQuery(this).data('page');
            jQuery('#page_number').val(pageNumber);
            jQuery('#search-form').submit();
        })
    });
</script>