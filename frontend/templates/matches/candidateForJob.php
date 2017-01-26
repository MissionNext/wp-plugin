<?php
/**
 * @var Array $job
 * @var Int $userId
 * @var Array $user
 * @var String $userRole
 * @var Array $candidates
 */

$percentages = [10, 20, 30, 40, 50, 60, 70, 80, 90];
$From_URL = $_SERVER['HTTP_REFERER'];
$receiving_org = $job['organization']['id'];


?>

<div class="page-header">
    <h1><?php echo sprintf(__("%s Matches", \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_CANDIDATE))) ?></h1>
</div>
<div class="page-content">
    <div class="col-sm-12 block">
        <!--<?php print_r($job) ?> <br> Display Job category and title on this page -->
        JOB:&nbsp;<strong> <?php echo $job['name']; ?> &gt; <?php echo $job['profileData']['second_title']; ?></strong> 
        <br>
        <?php echo __("Results with match rate below", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        <select class="update-year" id="percentage_filter">
            <?php foreach($percentages as $percentage){
                if($rate == $percentage)
                    echo '<option value="' . $percentage . '" selected="true">' . $percentage . '</option>';
                else
                    echo '<option value="' . $percentage . '">' . $percentage . '</option>';
            }?>
        </select>
        <?php echo __("not shown. <br>Folder icons clickable to expand/collapse. Refresh screen to update favorites and note icons. <font color=white>candidateForJob.php</font>", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    </div>

    <?php if($candidates):
        ?>
        <?php renderTemplate("common/_candidate_table", array('role' => 'candidate', 'items' => $candidates, 'messages' => $messages, 'userRole' => 'job', 'userId' => $job['id'], 'receiving_org' => $receiving_org, 'loggedRole' => $userRole)) ?>
        <?php renderTemplate("common/_pager", compact('page', 'pages')) ?>
    <?php else: ?>
        <div class="block">
            <?php echo __("Sorry, no matches yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </div>
    <?php endif; ?>

    <div class="control-buttons">
        <div class="left">
            <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN); ?></a>
            <?php if(preg_match("/affiliates/", $From_URL)): ?> <!--$_SERVER['HTTP_REFERER'] is used to discern if this page view is from a receiving org or a sending (affiliate) organization, then redirect as appropriate. -->
            	<a class="btn btn-default" href="/affiliates/jobs"><?php echo __("Jobs", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            <?php else: ?>
            	<a class="btn btn-default" href="/job"><?php echo __("Jobs", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            <?php endif; ?>
           </div>
    </div>
</div>

<script>
    jQuery(document).on('change', '#percentage_filter', function() {
        window.location.href = "<?php echo $_SERVER['REDIRECT_URL']?>?rate=" + jQuery(this).val();
    });
</script>
