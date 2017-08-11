<?php
/**
 * @var $saved Array
 * @var $role
 */
?>

<?php foreach($saved as $item): ?>
    <div class="search" data-id="<?php echo $item['id'] ?>">
        <form action="/<?php echo $role ?>/search" method="POST">
            <input type="hidden" name="saved" value="<?php echo $item['id'] ?>"/>
            <div class="name">
                <span><?php echo $item['search_name'] ?></span>
            </div>
            <div>
                <button class="btn btn-success"><?php echo __("Search", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                <button class="btn btn-danger delete" type="button" onclick="deleteJob(this)"><?php echo __("Delete", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </form>
    </div>
<?php endforeach; ?>


<script>

    function deleteJob(button){

        var div = jQuery(button).parents('.search');

        jQuery.ajax({
            type: "POST",
            url: "/saved/search/delete",
            data: {
                id: div.attr('data-id')
            },
            success: function(data, textStatus, jqXHR){
                jQuery(div).empty();
            },
            error: function(jqXHR, textStatus, errorThrown){
            },
            dataType: "JSON"
        });
    }
</script>