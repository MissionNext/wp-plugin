<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $jobs
 */

// echo "<pre>";
// print_r($jobs);
// print_r($user);
// echo "</pre>";
// there is nothing in $user, $userRole to identify which subscription is active for organizations with multiple subscriptions 
$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 
// echo "<br>$sniff_host";
if (preg_match("/explorenext/",$sniff_host))   { $this_app = 3; }
elseif (preg_match("/teachnext/",$sniff_host)) { $this_app = 6; }
// echo "<br>\$this_app = $this_app";
?>

<div class="page-header">
    <h1><?php echo ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL)) ?></h1>
</div>
<div class="page-content">
    <?php if($jobs): ?> 
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <td class="name"><strong><?php echo __("Category", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong></td>
                <td class="alt_title"><strong><?php echo __("Job Title", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong></td>
                <td class="country"><strong><?php echo __("Country", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong></td>
                <td class="actions" style="text-align:center"><strong><?php echo __("Actions", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong></td>
            </tr>
        </thead>
        <tbody>
        <?php foreach($jobs as $job): ?>
        <?php if($this_app == $job['app_id']): ?>
        
            <tr>
                <td class="name"><a href="/job/<?php echo $job['id'] ?>" target="_blank"><?php echo $job['name'] ?></a> </td>
                <td class="alt_title"><?php echo $job['profileData']['second_title'] ?></td>
                <td class="country"><?php echo $job['profileData']['country'] ?></td>
                <td class="actions">
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
            <?php echo __("Available jobs to be posted soon. Please check back.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </div>
    <?php endif; ?>
    <a class="btn btn-success" href="/job/new"><?php echo __("New", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
</div>
