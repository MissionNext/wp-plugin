<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $candidates
 */

$percentages = [10, 20, 30, 40, 50, 60, 70, 80, 90];
$years = [2001, 2010, 2011, 2012, 2013, 2014, 2015];

?>
<div class="page-header">
    <h1><?php echo sprintf(__("%s Matches", \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_CANDIDATE))) ?></h1>
</div>
<div class="page-content">
	 <? print_r($_COOKIE) ?> 
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
        <?php echo __("not shown. <br>Folder icons clickable to expand/collapse. Refresh screen to update favorites and note icons. C for O", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    </div>

    <div class="block">
        <?php echo __("Results with profile updates before", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>
        <select class="update-year" id="update_year" name="update_year">
            <?php foreach($years as $year){
                if($updates == $year)
                    echo '<option value="' . $year . '" selected="true">' . $year . '</option>';
                else
                    echo '<option value="' . $year . '">' . $year . '</option>';
            }?>
        </select>
        <?php echo __("not shown.", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>
    </div>

    <?php if($candidates): ?>
        <?php renderTemplate("common/_table", array('role' => 'candidate', 'items' => $candidates, 'messages' => $messages, 'userRole' => $userRole, 'userId' => $userId)) ?>
        <?php renderTemplate("common/_pager", compact('page', 'pages')) ?>
    <?php else: ?>
        <div class="block">
            <?php echo __("Sorry, no matches yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </div>
    <?php endif; ?>

    <div class="control-buttons">
        <div class="left">
            <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN); ?></a>
        </div>
    </div>
</div>

<script>
    jQuery(document).on('change', '#percentage_filter', function() {
        window.location.href = "<?php echo $_SERVER['REDIRECT_URL']?>?rate=" + jQuery(this).val();

    }).on('change', '#update_year', function () {
        window.location.href = "<?php echo $_SERVER['REDIRECT_URL']?>?updates=" + jQuery(this).val();
    });
</script>

