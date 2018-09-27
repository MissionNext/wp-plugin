<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $inquiries
 * @var String $domain
 * @var $site
 */
$key = 0;
// echo "Organization $user[id]<br>"; print_r($inquiries);
        
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/inquire/organization', 'inquire/organization.js', array( 'jquery' ), false, true);
?>
<div class="page-header">
    <h1><?php echo __("Inquiry list", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>
<div class="page-content">
    <?php if($inquiries): ?>
    <table class="table result">
        <thead>
        <tr>
            <th>#</th>
            <th><?php echo __('Full name', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            <th><?php echo __('Date of inquiry', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            <th><?php echo __('Favorite', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            <th><?php echo __('Actions', \MissionNext\lib\Constants::TEXT_DOMAIN)?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($inquiries as $job): ?>

            <tr class="header">
                <td class="inquery-colspan" colspan="5"><a href="/job/<?php echo $job['id']?>"><?php echo $job['name'] ?></a> &#151; <?php echo $job['profileData']['second_title'] ?></td>
            </tr>

            <?php foreach($job['inquiries'] as $inquirie): ?>

            <tr data-id="<?php echo $inquirie['id'] ?>" data-job-id="<?php echo $job['id'] ?>" data-candidate-id="<?php echo $inquirie['candidate']['id'] ?>">
                <td class="id"><?php echo ++$key ?></td>
                <td class="name"><a href="/candidate/<?php echo $inquirie['candidate']['id'] ?> " target="_blank"><?php echo \MissionNext\lib\UserLib::getUserFullName($inquirie['candidate']) ?></a></td>
                <td><?php echo date('Y-m-d', strtotime($inquirie['updated_at'])) ?></td>
                <td class="favorite" >
                    <div class="favorite-block <?php echo is_integer($inquirie['favorite'])?'favorite':'not-favorite' ?>"></div>
                </td>
                <td>
                    <a class="btn btn-danger inquire-cancel" title="Click once. Screen takes a moment to refresh"><?php echo __('Remove', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </td>
            </tr>
            <?php endforeach; ?>

        <?php endforeach; ?>

        </tbody>

    </table>
    <?php else: ?>
    <div class="block">
        <?php echo __("No inquiries yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    </div>
    <?php endif; ?>
    <div class="block">
	<a href="https://info.<?php echo $domain ?>/inquiries.php?appid=<?php echo $site ?>" target="_blank">View deleted inquiries</a>
	</div>
</div>