function deleteJob(button){
    jQuery("#loader").show();
    var div = jQuery(button).parents('.search');

    jQuery.ajax({
        type: "POST",
        url: "/saved/search/delete",
        data: {
            id: div.attr('data-id')
        },
        success: function(data, textStatus, jqXHR){
            jQuery(div).empty();
            jQuery("#loader").hide();
        },
        error: function(jqXHR, textStatus, errorThrown){
            jQuery("#loader").hide();
        },
        dataType: "JSON"
    });
}