<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $favorites
 This page is seen by candidates 
 */
?>
<div class="page-header">
    <h1><?php echo __("Favorites", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>
<div class="page-content">
    <?php if($job_favorites || $org_favorites):?>

        <?php if($org_favorites):?>
        <table class="table result">
            <thead>
            <tr>
                <th>#</th>
                <th><?php echo sprintf(__('Full %s Name', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION))) ?></th>
                <th><?php echo __('Notes', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                <th><?php echo __('Actions', \MissionNext\lib\Constants::TEXT_DOMAIN)?></th>
            </tr>
            </thead>
            <tbody>

            <?php foreach($org_favorites as $key => $favorite): ?>
                <tr data-role="organization" data-id="<?php echo $favorite['data']['id'] ?>" data-fav-id="<?php echo $favorite['id'] ?>" data-name="<?php echo \MissionNext\lib\UserLib::getUserOrganizationName($favorite['data']) ?>">
                    <td class="id"><?php echo $key+1 ?></td>
                    <td class="name"><a href="/organization/<?php echo $favorite['data']['id'] ?>" target="_blank"><?php echo \MissionNext\lib\UserLib::getUserOrganizationName($favorite['data']) ?></a></td>
                    <td class="note" data-note="<?php echo htmlentities($favorite['notes']) ?>">
                        <div <?php if(!$favorite['notes']) echo 'class="no-note"' ?>></div>
                    </td>
                    <td>
                        <a class="btn btn-danger favorite-remove"><?php echo __('Unfavorite', \MissionNext\lib\Constants::TEXT_DOMAIN ) ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
        <?php endif;?>

        <?php if($job_favorites):?>
        <table class="table result" style="margin-top: 70px">
            <thead>
            <tr>
                <th>#</th>
                <th class="name"><?php echo sprintf(__('%s Title', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB))) ?></th>
                <th><?php echo __('Additional Title', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                <th><?php echo ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION)) ?></th>
                <th><?php echo __('Notes', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                <th><?php echo __('Actions', \MissionNext\lib\Constants::TEXT_DOMAIN)?></th>
            </tr>
            </thead>
            <tbody>

            <?php foreach($job_favorites as $key => $favorite):?>
                <tr data-role="job" data-id="<?php echo $favorite['data']['id'] ?>" data-fav-id="<?php echo $favorite['id'] ?>" data-name="<?php echo $favorite['data']['name'] ?>">
                    <td class="id"><?php echo $key+1 ?></td>
                    <td class="name"><a target="_blank" href="/job/<?php echo $favorite['data']['id'] ?>"><?php echo $favorite['data']['name'] ?></a> </td>
                    <td class="alt_name"><?php echo $favorite['data']['profileData']['second_title'] ?></a> </td>
                    <td class="organization"><a href="/organization/<?php echo $favorite['data']['organization']['id'] ?>"><?php echo $favorite['data']['organization']['profileData']['organization_name'] ?></a> </td>
                    <td class="note" data-note="<?php echo htmlentities($favorite['notes']) ?>">
                        <div <?php if(!$favorite['notes']) echo 'class="no-note"' ?>></div>
                    </td>
                    <td>
                        <a class="btn btn-danger favorite-remove"><?php echo __('Unfavorite', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>

        </table>
        <?php endif; ?>

    <?php else: ?>
    <div class="block">
        <?php echo __("No favorites yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    </div>
    <?php endif; ?>

</div>

<div id="note" title="<?php echo __('Note', \MissionNext\lib\Constants::TEXT_DOMAIN); ?>" style="display: none">
    <input type="hidden" name="role" value=""/>
    <input type="hidden" name="id" value=""/>
    <div class="help">
        <p class="role"><?php echo __("Enter or update a brief note about ", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
            <span class="name">:</p>
    </div>
    <textarea cols="25" rows="5" class="message"></textarea>
</div>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/favorite/candidate', 'favorite/candidate.js', array( 'jquery', 'jquery-ui-dialog' ), false, true);
?>