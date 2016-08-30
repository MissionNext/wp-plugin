<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $jobs
 */

//echo "<pre>";
//print_r($jobs);
//echo "</pre>";

?>

<div class="page-header">
    <h1><?php echo ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL)) ?></h1>
</div>
<div class="page-content">
    <?php if($jobs): ?> 
    <table class="table table-bordered">
        <thead>
            <tr>
                <td class="name"><?php echo __("Name", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></td>
                <td class="alt_title"><?php echo __("Alternate Title", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></td>
                <td class="country"><?php echo __("Country", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></td>
                <td class="actions"><?php echo __("Actions", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></td>
            </tr>
        </thead>
        <tbody>
        <?php foreach($jobs as $job): ?>
            <tr>
                <td class="name"><a href="/job/<?php echo $job['id'] ?>"><?php echo $job['name'] ?></a> </td>
                <td class="alt_title"><?php echo $job['profileData']['second_title'] ?></td>
                <td class="country"><?php echo $job['profileData']['country'] ?></td>
                <td class="actions">
                    <a class="btn btn-primary" href="/job/matches/candidate/<?php echo $job['id'] ?>"><?php echo __("Matches", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                    <a class="btn btn-warning" href="/job/<?php echo $job['id'] ?>/edit"><?php echo __("Edit", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                    <a class="btn btn-danger" href="/job/<?php echo $job['id'] ?>/delete"><?php echo __("Delete", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="block">
            <?php echo __("No jobs yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </div>
    <?php endif; ?>
    <a class="btn btn-success" href="/job/new"><?php echo __("New", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
</div>
