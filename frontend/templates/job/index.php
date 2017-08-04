<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $jobs
 */
// echo "<br>\$userRole = $userRole";
// echo "<pre>";
// print_r($jobs);
// print_r($user);
// echo "</pre>";
// there is nothing in $user, $userRole to identify which subscription is active for organizations with multiple subscriptions 
$date_today = date("Y-m-d");
$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 
// echo "<br>$sniff_host";
if (preg_match("/explorenext/",$sniff_host))   { $this_app = 3; }
elseif (preg_match("/teachnext/",$sniff_host)) { $this_app = 6; }
$number_jobs = count($jobs);
// echo "<br>\$this_app = $this_app; \$number_jobs = $number_jobs";
?>

<div class="page-header">
    <h1><?php echo ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL)) ?></h1>
</div>
<div class="page-content">
    <?php if($jobs): 
    if ($number_jobs > 9): ?> 
    <a class="btn btn-success" href="/job/new"><?php echo __("New Job", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a><br><br>
     <?php endif; ?> 
     <table class="table table-bordered">
        <thead>
            <tr>
                <td class="expiration" width="80"><strong><?php echo __("Expires", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong></td>
                <td class="name"><strong><?php echo __("Category", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong></td>
                <td class="alt_title"><strong><?php echo __("Job Title", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong></td>
                <td class="country"><strong><?php echo __("Location", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong></td>
                <td class="actions" style="text-align:center" width="320"><strong><?php echo __("Actions", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong></td>
            </tr>
        </thead>
        <tbody>
        <?php foreach($jobs as $job): ?>
        <?php if(3 == $job['app_id']): ?>
        <?php if ($date_today > $job['profileData']['listing_expiration']) { $font = "red"; $warn = "Yes"; } else { $font = "black"; } ?>
            <tr>
                <td class="expiration" width="80"><font color='<?php echo $font ?>'><?php echo $job['profileData']['listing_expiration'] ?></font></td>
                <td class="name"><a href="/job/<?php echo $job['id'] ?>" target="_blank"><?php echo $job['name'] ?></a> </td>
                <td class="alt_title"><?php echo $job['profileData']['second_title'] ?></td>
                <td class="country"><?php echo $job['profileData']['country'] ?></td>
                <td class="actions" width="320">
                    <a class="btn btn-primary" href="/job/matches/candidate/<?php echo $job['id'] ?>"><?php echo __("Matches", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                    <a class="btn btn-warning" href="/job/<?php echo $job['id'] ?>/edit"><?php echo __("Edit", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                    <a class="btn btn-danger" href="/job/<?php echo $job['id'] ?>/delete"><?php echo __("Delete", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </td>
            </tr>
        <?php endif; ?>   
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="block">
        	<?php if ($userRole == "organization") {
            	echo __("Post your first job/assigment to find appropriate candidates.", \MissionNext\lib\Constants::TEXT_DOMAIN); 
            }
            else {
            	echo __("Available jobs to be posted soon. Please check back.", \MissionNext\lib\Constants::TEXT_DOMAIN); 
            }
            ?>
        </div>
    <?php endif; 
    if ($warn == "Yes") { echo "<font color='red'>NOTICE: One or more jobs has expired. Edit / Save job spec to extend the expiration date for another 6 months.</font><br>"; }      
    ?>
    <a class="btn btn-success" href="/job/new"><?php echo __("New Job", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
</div>
