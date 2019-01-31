<?php
/**
 * @var $saved Array
 * @var $role
 */

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/search/ajax_search_saved', 'search/ajax_search_saved.js', array( 'jquery' ), false, true);
?>

<?php foreach($saved as $item): ?>
    <div class="search" data-id="<?php echo $item['id'] ?>">
        <form action="/<?php echo $role ?>/search#top" method="POST">
            <input type="hidden" name="saved" value="<?php echo $item['id'] ?>"/>
            <div>
                <button class="btn btn-success"><?php echo __("Search", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                <button class="btn btn-danger delete" type="button" onclick="deleteJob(this)"><?php echo __("Delete", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
            <div class="name">
                <span><?php echo $item['search_name'] ?></span>
            </div>
        </form>
    </div>
<?php endforeach; ?>