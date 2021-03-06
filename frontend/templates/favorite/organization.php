<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $favorites
 * @var String $role
 This page is seen by organizations
 */
// print_r($favorites); // echo "organization.php <br>\$role = $role";
if (!isset($role)) { $role = "candidate"; }
?>
<div class="page-header">
    <h1><?php echo __("Favorites", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>
<div class="page-content">
    <?php if($favorites): ?>
    <table class="table result" data-role="<?php echo $role; ?>">
        <thead>
        <tr>
            <th>#</th>
            <th><?php echo __('Full name', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            <th><?php echo __('Notes', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            <th><?php echo __('Actions', \MissionNext\lib\Constants::TEXT_DOMAIN)?></th>
            <th><?php echo __('Folder', \MissionNext\lib\Constants::TEXT_DOMAIN); ?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($favorites as $key => $favorite): ?>
        <tr data-id="<?php echo $favorite['data']['id'] ?>" data-fav-id="<?php echo $favorite['id'] ?>" data-name="<?php echo \MissionNext\lib\UserLib::getUserFullName($favorite['data']) ?>">
            <td class="id"><?php echo $key+1 ?></td>
            <td class="name"><a href="/<?php echo $role ?>/<?php echo $favorite['data']['id'] ?>" target="_blank"><?php echo \MissionNext\lib\UserLib::getUserFullName($favorite['data']) ?></a></td>
            <td class="note" data-note="<?php echo htmlentities($favorite['notes']) ?>">
                <div <?php if(!$favorite['notes']) echo 'class="no-note"' ?>></div>
            </td>
            <td>
                <a class="btn btn-danger favorite-remove"><?php echo __('Unfavorite', \MissionNext\lib\Constants::TEXT_DOMAIN ) ?></a>
            </td>
            <td class="folder" width="130">
                <select>
                    <?php foreach($folders as $value => $folder): ?>
                        <option <?php if($favorite['folder'] == $folder) echo 'selected="selected"' ?> value="<?php echo $value ?>"><?php echo $folder ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <?php endforeach; ?>

        </tbody>

    </table>
    <?php else: ?>
    <div class="block">
        <?php echo __("No favorites yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    </div>
    <?php endif; ?>

</div>

<div id="note" title="<?php echo __('Note', \MissionNext\lib\Constants::TEXT_DOMAIN); ?>" style="display: none">
    <input type="hidden" name="role" value="<?php echo $role ?>"/>
    <input type="hidden" name="id" value=""/>
    <div class="help">
        <p class="role"><?php echo __("Enter or update a brief note about ", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        <span class="name"></span>:</p>
    </div>
    <textarea cols="25" rows="5" class="message"></textarea>
</div>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/favorite/organization', 'favorite/organization.js', array( 'jquery', 'jquery-ui-dialog' ), false, true);
?>