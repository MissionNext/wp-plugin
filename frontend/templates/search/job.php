<?php
/**
 * @var $form \MissionNext\lib\form\Form
 * @var $role String
 * @var $userRole String
 * @var $result Array
 * @var $searches Array
 * @var $search Array
 */
?>

<div class="page-header">
    <h1><?php echo sprintf(__('Search %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL))) ?></h1>
</div>
<div class="page-content">

    <?php if($_SERVER['REQUEST_METHOD'] == 'POST' && !$form->hasErrors()): ?>

        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('search/_search_block', compact('form')) ?>

        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('search/_search_save_block', array('searches' => $searches, 'search' => $search, 'role' => $role, 'userRole' => $userRole)) ?>

        <?php if($result): ?>
            <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('search/_search_result', compact('result', 'role', 'messages', 'userRole', 'userId', 'page', 'pages')) ?>
        <?php else: ?>
            <div class="block">
                <?php echo sprintf(__("No %s found", \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL))) ?>
            </div>
        <?php endif; ?>

        <div class="control-buttons">
            <div class="left">
<!--                <button class="btn btn-default" type="button" onclick="history.back()">--><?php //echo __("Back", \MissionNext\lib\Constants::TEXT_DOMAIN) ?><!--</button>-->
                <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>
            <div class="right">
                <a class="btn btn-success" href="/job/search"><?php echo __("Start over", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>
        </div>
    <?php else: ?>

        <div class="block search-help">
            <p class="welcome"> <?php echo __("Welcome!", \MissionNext\lib\Constants::TEXT_DOMAIN) ?> </p>
            <ul class="list-of-tips">
                <li><?php echo __("Selection fields available for a search.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></li>
                <li><?php echo __("Many selections in a group increases the count. More groups selected will narrow the search.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></li>
                <li><?php echo __("Play with it. Useful searches can be saved after assigning a search name.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></li>
            </ul>

        </div>

        <?php if($searches): ?>
            <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('search/_search_saved', array('saved' => $searches, 'role' => $role)) ?>
        <?php endif; ?>
    <?php endif; ?>

    <form id="search-form" role="form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST" class="form-horizontal search-form <?php echo ($_SERVER['REQUEST_METHOD'] == 'POST') ? "hide" : ""; ?>">
        <input type="hidden" name="page" value="<?php echo $page; ?>" id="page_number" />

        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('search/_search_form', compact('form')) ?>

        <div class="control-buttons">
            <div class="left">
                <a href="/dashboard" class="btn btn-default"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>
            <div class="right">
                <button type="submit" class="btn btn-success"><?php echo __("Search", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </div>
    </form>

</div>
