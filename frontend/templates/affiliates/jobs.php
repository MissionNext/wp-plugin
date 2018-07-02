<?php
$date_today = date("Y-m-d");
/**
 * @var $userRole
 * @var $jobs Array
 * @var $user Array
 */
// echo "\$userRole = $userRole "; echo "\$user = <br>"; print_r($user);
// print_r($jobs);
?>
<div class="page-header">
    <h1><?php echo ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL))?></h1>
</div>
<div class="page-content">
    <?php if($jobs): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="id">#</th>
                <th class="listing_expiration"><?php echo __("Exp Date", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                <th class="name"><?php echo __("Name", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                <th class="title"><?php echo __("Alternate Title", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                <th class="location"><?php echo __("Location", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                <th class="actions"><?php echo __("Actions", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($jobs as $organization):?>

            <tr class="header">
                <td colspan="5"><a href="/organization/<?php echo $organization['org_data']['id'] ?>"><?php echo $organization['org_data']['profileData']['organization_name']; ?></a></td>
            </tr>

            <?php $key = 0; foreach($organization['jobs'] as $job): $key++;?>
            <?php if ($date_today > $job['profileData']['listing_expiration']) { $font = "red"; $warn = "Yes"; } else { $font = "black"; } ?>
            <tr>
                <td class="id"><?php echo $key ?></td>
                <td class="listing_expiration" style='text-align:center'><font color='<?php echo $font ?>'><?php echo $job['profileData']['listing_expiration'] ?></font></td>
                <td class="name"><a href="/job/<?php echo $job['id'] ?>" target="blank"><?php echo $job['name'] ?></a></td>
                <td class="title"><?php echo $job['profileData']['second_title'] ?></font></td>
                <td class="location"><?php echo __(\MissionNext\lib\ProfileLib::getProfileField($job, 'country')) ?></td>
                <td class="actions" style='text-align:center'>
                    <a class="btn btn-primary" href="/job/matches/candidate/<?php echo $job['id'] ?>">
                        <?php echo __("Matches", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php 
        if ($warn == "Yes") { echo "<font color='red'>NOTICE: One or more jobs has expired. </font>"; }
        if ($userRole == "agency") {
    		$rep_id		 = $user['id'];
    		$factor		 = rand(10,99); // generate random two-digit number
			$factored	 = $factor * $rep_id;  // factored is the product of the random number and user_id 
			$pass_string = $factor.$factored; // pass this string, then extract user_id as $factored / $factor 

			if (3 === $site) {
    			$entity = "AGENCY";
			}
			elseif (6 === $site) {
		    	$entity = "SCHOOL";
			}
			elseif (10 === $site) {
		    	$entity = "AGENCY";
			}
			print ("<p align='center'><a href='https://info.missionnext.org/recruit_account.php?aid=$pass_string&s=$site' target='_blank'>MANAGE $entity JOB MATCHES FOR AFFILIATED ACCOUNTS</a></p>");
    	}	

    ?>
    <?php else: ?>
    <div class="block">
        <?php echo sprintf(__("No %s yet.", \MissionNext\lib\Constants::TEXT_DOMAIN), getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL) ) ?>
    </div>
    <?php endif; ?>
</div>
