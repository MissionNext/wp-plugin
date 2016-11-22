<?php
/**
 * @var $organization
 * @var $jobs
 */
?>
<div class="page-header">
    <h1>
        <?php echo !empty($organization['profileData']['organization_name']) ? $organization['profileData']['organization_name'] : $organization['username']; ?>
        <?php echo ' ' . ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL)) ?>
    </h1>
</div>

<?php if($jobs):?>
    <?php renderTemplate("common/_job_table", array('role' => 'job', 'items' => $jobs, 'messages' => $messages, 'userRole' => $userRole, 'userId' => $userId)); ?>
<?php else: ?>
    <div class="block">
        <?php echo __("No jobs yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    </div>
<?php endif; ?>