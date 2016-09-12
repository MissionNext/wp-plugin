<?php
/**
 * @var $role
 */
?>

<div class="block" id="saved_search">
    <?php renderTemplate("search/_ajax_search_saved", compact('saved', 'role')) ?>
</div>

<script>
    window.onload = function(){
        loadSavedSearches("<?php echo $role ?>", function(data){
            jQuery('#saved_search').html(data);
        }, function(){console.log('error')});
    };
    window.onunload = function(){};

    function loadSavedSearches(role, successCallback, errorCallback){

        jQuery.ajax({
            type: "GET",
            url: "/saved/search/" + role,
            success: successCallback,
            error: errorCallback
        });

    }
</script>