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