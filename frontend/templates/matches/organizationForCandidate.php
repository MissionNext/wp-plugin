<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $organizations
   shows organization list for candidates
 */

$percentages = [10, 20, 30, 40, 50, 60, 70, 80, 90];

?>

<div class="page-header">
    <h1><?php echo sprintf(__("%s Matches", \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION))) ?></h1>
</div>
<div class="page-content">

<div class="control-buttons">
        <div class="left">
            <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN); ?></a>
        </div>
</div>
        <div class="col-sm-12 block">
        <?php echo __("Results with match rate below", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        <select class="update-year" id="percentage_filter">
            <?php foreach($percentages as $percentage){
                if($rate == $percentage)
                    echo '<option value="' . $percentage . '" selected="true">' . $percentage . '</option>';
                else
                    echo '<option value="' . $percentage . '">' . $percentage . '</option>';
            }?>
        </select>
        <?php echo __("not shown. <br>Folder icons clickable to expand/collapse. Refresh screen to update favorites and note icons. ", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    </div>

    <?php if($organizations): ?>
        <?php renderTemplate("common/_organization_table", array('role' => 'organization', 'items' => $organizations, 'messages' => $messages, 'userRole' => $userRole, 'userId' => $userId)) ?>
        <?php renderTemplate("common/_pager", compact('page', 'pages')) ?>
    <?php else: ?>
        <div class="block">
            <?php echo __("Sorry, no matches yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </div>
    <?php endif; ?>

    <div class="control-buttons">
        <div class="left">
            <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN); ?></a>
            <a class="btn btn-default" href="/candidate/matches/job"><?php echo __("Jobs", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
           </div>
    </div>
</div>

<script>
    jQuery(document).on('change', '#percentage_filter', function() {
        window.location.href = "<?php echo $_SERVER['REDIRECT_URL']?>?rate=" + jQuery(this).val();
    });
</script>
