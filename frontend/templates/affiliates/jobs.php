<?php

/**
 * @var $jobs Array
 */
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
                <th class="exp_date"><?php echo __("Exp Date", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                <th class="name"><?php echo __("Name", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
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
            <tr>
                <td class="id"><?php echo $key ?></td>
                <td class="exp_date"><?php echo __(\MissionNext\lib\ProfileLib::getProfileField($job, 'expiration_date')) ?></td>
                <td class="name"><a href="/job/<?php echo $job['id'] ?>"><?php echo $job['name'] ?></a></td>
                <td class="location"><?php echo __(\MissionNext\lib\ProfileLib::getProfileField($job, 'location')) ?></td>
                <td class="actions">
                    <a class="btn btn-link" href="/job/matches/candidate/<?php echo $job['id'] ?>">
                        <?php echo __("Matches", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="block">
        <?php echo sprintf(__("No %s yet.", \MissionNext\lib\Constants::TEXT_DOMAIN), getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL) ) ?>
    </div>
    <?php endif; ?>
</div>
