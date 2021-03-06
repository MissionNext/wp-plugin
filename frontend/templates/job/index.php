<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $jobs
 * @var string $domain
 * @var $site
   */

$warn = '';
// there is nothing in $user, $userRole to identify which subscription is active for organizations with multiple subscriptions 
$date_today = date("Y-m-d");
$number_jobs = count($jobs);
$once = "No";
// echo "Line 15 \$domain = $domain; \$site = $site; \$jobs =<br>"; print_r($jobs); echo "<br>\$user =<br>"; print_r($user); 
?>

<div class="page-header">
    <h1><?php echo ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL)) ?></h1>
</div>
<div class="page-content">
    <?php if($jobs): 

    if ($number_jobs > 9):  
    	if ($site == 2): ?>
    	<a class="btn btn-success" href="/job/new"><?php echo __("New Job or Trip", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a> 
		<?php else: ?>
    	<a class="btn btn-success" href="/job/new"><?php echo __("New Job", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
    	<?php endif;   
     endif; ?> 
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
        <?php if($site == $job['app_id']): ?>
        <?php if ($date_today > $job['profileData']['listing_expiration']) { $font = "red"; $warn = "Yes"; $jobs_expired[] = $job[id]; } else { $font = "black"; } 
        if ($warn == "Yes" && $once == "No") { 
        	echo "<font color='red'>NOTICE: One or more jobs has expired. Edit / Save job spec to extend the expiration date for another 6 months.</font><br>";     
			$once = "Yes"; 
		}
		?>
            <tr>
                <td class="expiration" width="80"><font color='<?php echo $font ?>'><?php echo $job['profileData']['listing_expiration'] ?></font></td>
                <td class="name"><a href="/job/<?php echo $job['id'] ?>" target="_blank"><?php echo $job['name'] ?></a> </td>
                <td class="alt_title"><?php echo $job['profileData']['second_title'] ?></td>
                <td class="country"><?php echo $job['profileData']['country'] ?></td>
                <td class="actions" width="320">
                    <a class="btn btn-primary" href="/job/matches/candidate/<?php echo $job['id'] ?>"><?php echo __("Matches", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                    <a class="btn btn-warning" href="/job/<?php echo $job['id'] ?>/edit"><?php echo __("Edit", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                    <a class="btn btn-danger" onclick="javascript:if (confirm('Are you sure you want to delete?')) { return true; } else { return false; };" href="/job/<?php echo $job['id'] ?>/delete"><?php echo __("Delete", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </td>
            </tr>
        <?php endif; ?>   
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="block">
        	<?php if ($userRole == "organization" && $site == 2) {
            	echo __("Post your first short-term opportunity to find appropriate candidates.", \MissionNext\lib\Constants::TEXT_DOMAIN); 
            }
        	elseif ($userRole == "organization") {
            	echo __("Post your first job/assigment to find appropriate candidates.", \MissionNext\lib\Constants::TEXT_DOMAIN); 
            }
            elseif ($site == 2) {
            	echo __("Available short-term opportunities to be posted soon. Please check back.", \MissionNext\lib\Constants::TEXT_DOMAIN); 
            }
            else {
            	echo __("Available jobs to be posted soon. Please check back.", \MissionNext\lib\Constants::TEXT_DOMAIN); 
            }
            ?>
        </div>
    <?php endif; 
    if ($warn == "Yes") { 
    	echo "<font color='red'>NOTICE: One or more jobs has expired. Edit / Save job spec to extend the expiration date for another 6 months.</font><br>"; 
    	if ($site != 6 && $userRole == "organization") { // allow updating jobs in bulk if an org that is not "Education", $site = 6.
    		$implode_expired = implode(",", $jobs_expired); // echo "$implode_expired<br>"; // produces comma separated string, e.g. 1855,1539,1540,660
    		// print_r($jobs_expired); echo "<br>"; echo "Line 86 \$site = $site; \$user[id] = $user[id]"; 
    		print ("<a href='https://info.missionnext.org/jobs_bulk_update.php?exp_list=$implode_expired&uid=$user[id]&site=$site' target='_blank'>Update expired jobs in bulk.</a><br><br>");
    	}
    }      
    if ($site == 2): ?>
    <a class="btn btn-success" href="/job/new"><?php echo __("New Job or Trip", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a> 
	<?php else: ?>
    <a class="btn btn-success" href="/job/new"><?php echo __("New Job", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
    <?php endif; ?>   
</div>
