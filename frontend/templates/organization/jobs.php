<?php
/**
 * @var $organization
 * @var $jobs
 */
// print_r($organization);
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
        <!--<?php echo __("No jobs yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>-->
         Please call or email the key contact for this organization for mission opportunities or educational positions. <br><br>
        <?php echo $organization['profileData']['organization_name'] ?> <br>
        <?php echo $organization['profileData']['first_name'] ?> <?php echo $organization['profileData']['last_name'] ?> <br>
        Phone: <?php echo $organization['profileData']['key_contact_phone'] ?> <br>
        Email: <?php echo $organization['profileData']['email'] ?> <br>
    </div>
<?php endif; ?>