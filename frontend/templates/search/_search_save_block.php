<?php
/**
 * @var $searches Array
 * @var $search String
 * @var $role String
 * @var $userRole String
 */

$search_jsons = array();

if($searches){
    foreach($searches as $item){
        $search_jsons[$item['id']] = json_encode($item['data']);
    }
}

$search_json = json_encode($search);

?>

<div id="save-search-block">

    <?php if(!in_array($search_json, $search_jsons)): ?>

    <form id="save-search" action="/saved/search/add" method="post" rel="form" class="form-horizontal">
        <div class="form-group">
            <input type="hidden" name="data" value='<?php echo htmlentities(json_encode($search), ENT_QUOTES); ?>'/>
            <input type="hidden" name="role_from" value='<?php echo $userRole ?>'/>
            <input type="hidden" name="role_to" value='<?php echo $role ?>'/>
            <div class="col-sm-12">
                <label for="save-search-name"><?php echo __("Save this search. Search Name:", \MissionNext\lib\Constants::TEXT_DOMAIN) ?> </label>
                <input id="save-search-name" type="text" name="name" required="required"/>
                <button class="btn btn-success" type="submit"><?php echo __('Save', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </div>
    </form>

    <?php endif; ?>
</div>

<script>

    jQuery('#save-search').on('submit', function(event){
        var form = jQuery(event.target);

        var data = {
            name: form.find('[name="name"]').val(),
            role_from: form.find('[name="role_from"]').val(),
            role_to: form.find('[name="role_to"]').val(),
            data: JSON.parse(form.find('[name="data"]').val())
        };

        if(!form.find('input[name="name"]').val()){
            return false;
        }

        jQuery.ajax({
            type: "POST",
            url: form.attr('action'),
            data: data,
            success: function(data, textStatus, jqXHR){
                jQuery("#save-search-block")
                    .prepend("<p class='success'><?php echo __('Successfully saved', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></p>")
                    .find('form').hide();
            },
            error: function(jqXHR, textStatus, errorThrown){
                jQuery("#save-search-block").prepend("<p class='error'><?php echo __('Save error', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></p>");
            },
            dataType: "JSON"
        });

        event.preventDefault();
        return false;
    });

</script>

