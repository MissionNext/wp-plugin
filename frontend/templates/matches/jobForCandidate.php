<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $jobs
 page URL /candidate/matches/job
 */
// print_r($user);
date_default_timezone_set('America/New_York');		
$datetime   = date("Y-m-d H:i:s"); 
$updated_at = $user['updated_at'];
$str_now    = strtotime($datetime);   // The time now becomes a timestamp 
$str_update = strtotime($updated_at); // updated time becomes a timestamp 
$interval   = $str_now - $str_update; // subtract the selected number of seconds
if ($interval < 64800) {
	$hours = floor($interval / 3600);
	$minutes = sprintf('%0.0f', ($interval - $hours * 3600)/60);
}
// echo "\$interval = $interval";

$percentages = [10, 20, 30, 40, 50, 60, 70, 80, 90];

?>

<div class="page-header">
    <h1><?php echo sprintf(__("%s Matches", \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB))) ?></h1>
</div>
<div class="page-content">
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
        <?php echo __("not shown. <br>Folder icons clickable to expand/collapse. Refresh screen to update favorites and note icons. <font color=white>jobforCandidate.php</font>", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    </div>
	
    <?php 
		// echo "<br>"; print_r($jobs); echo "<br>"; 
    ?>
    <?php if($jobs): ?>

        <?php renderTemplate("common/_job_table", array('role' => 'job', 'items' => $jobs, 'messages' => $messages, 'userRole' => $userRole, 'userId' => $userId)) ?>
        <?php renderTemplate("common/_pager", compact('page', 'pages')) ?>
    <?php else: ?>
        <div class="block">
        	<?php if ($userRole != "agency") {
            	echo __("Your profile was updated $hours hours $minutes minutes ago. Job matching calculations can take several hours.", \MissionNext\lib\Constants::TEXT_DOMAIN); 
            }
            else {
            	echo __("Sorry, no matches yet. ", \MissionNext\lib\Constants::TEXT_DOMAIN);
            } ?>
        </div>
    <?php endif; 
     	$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 
    ?>

    <div class="control-buttons">
        <div class="left">
            <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN); ?></a>
            <?php		
            if (preg_match("/teachnext/",$sniff_host)): ?>
            <a class="btn btn-default" href="/candidate/matches/organization"><?php echo __("Schools", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a> <!-- Added By Nelson Apr 23, 2016 -->
            <?php else: ?>
            <a class="btn btn-default" href="/candidate/matches/organization"><?php echo __("Agencies", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a> <!-- Added By Nelson Apr 23, 2016 -->
           <?php endif; ?>
           
           </div>
    </div>
</div>

<script>
    jQuery(document).on('change', '#percentage_filter', function() {
        window.location.href = "<?php echo $_SERVER['REDIRECT_URL']?>?rate=" + jQuery(this).val();
    });
</script>
