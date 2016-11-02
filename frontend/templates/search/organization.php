<?php
/**
 * @var $form \MissionNext\lib\form\Form
 * @var $role String
 * @var $userRole String
 * @var $result Array
 * @var $searches Array
 * @var $search Array
 */
$FROM = $_SERVER['HTTP_REFERER'];
// echo "<br>\$role = $role; \$userRole = $userRole";
$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 
// echo "<br>$sniff_host";
if (preg_match("/explorenext/",$sniff_host))   { $this_app = 3; }
elseif (preg_match("/teachnext/",$sniff_host)) { $this_app = 6; }

?>

<div class="page-header">
    <h1><?php echo sprintf(__('Interactive %s Search', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION_PLURAL))) ?></h1>
</div>
<div class="page-content">

    <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>

        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('search/_search_block', compact('form')) ?>

        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('search/_search_save_block', array('searches' => $searches, 'search' => $search, 'role' => $role, 'userRole' => $userRole)) ?>

        <?php if($result): ?>
            <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('search/_search_result', compact('result', 'role', 'messages', 'userRole', 'userId')) ?>
        <?php else: ?>
            <div class="block">
                <?php echo sprintf(__("No %s found", \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION_PLURAL))) ?>
            </div>
        <?php endif; ?>

        <div class="control-buttonss">
            <div class="left">
<!--                <button class="btn btn-default" type="button" onclick="history.back()">--><?php //echo __("Back", \MissionNext\lib\Constants::TEXT_DOMAIN) ?><!--</button>-->
                <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>
            <div class="right">
                <a class="btn btn-success" href="/organization/search"><?php echo __("Start over", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
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
	<!-- custom instructions depending on $role (entity that is target of the search) and $app -->
    <?php     if($role == "organization" && $this_app == 6): ?>
        <?php echo __("Search/Find a mission agency. Select to affiliate with your school to assist in recruiting candidates.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    <?php elseif($role == "organization" && $this_app == 3): ?>
        <?php echo __("Search/Find a mission agency representative, typically working with your organization. Select to &quot;affiliate&quot; with your agency to assist in recruiting candidates.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    <?php elseif($role == "agency" && $this_app == 6): ?>
        <?php echo __("Search/Find a school. Select to affiliate with you or your agency to assist in recruiting candidates.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    <?php elseif($role == "agency" && $this_app == 3): ?>
        <?php echo __("Search/Find a mission agency, typically the one you are serving with. Select to &quot;affiliate&quot; to assist in recruiting candidates fitting your agency assignments.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    <?php elseif(preg_match("/affiliates/",$FROM)): ?>
        <?php echo __("Search/Find a partner, then request to affiliate.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    <?php endif; ?>

    <?php if($searches): ?>
        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('search/_search_saved', array('saved' => $searches, 'role' => $role)) ?>
    <?php endif; ?>

    <form role="form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST" class="form-horizontal search-form">

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
    <?php endif; ?>

</div>
