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
                .prepend("<p class='success'>" + success + "</p>")
                .find('form').hide();
        },
        error: function(jqXHR, textStatus, errorThrown){
            jQuery("#save-search-block").prepend("<p class='error'>" + error + "</p>");
        },
        dataType: "JSON"
    });

    event.preventDefault();
    return false;
});
