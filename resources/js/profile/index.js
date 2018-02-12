var formHasChanged = false;
var submitted = false;

jQuery(document).ready(function () {

    jQuery(document).on('change', 'form.form-horizontal input, form.form-horizontal select, form.form-horizontal textarea', function (e) {
        formHasChanged = true;
    });

    jQuery(document).on('click', '.file-delete-icon', function (e) {
        var link = jQuery(this);
        var route = '/delete/file';
        var fieldkey = link.data('fieldkey');

        jQuery.ajax
        ({
            url: route,
            type: "POST",
            dataType: "JSON",
            data:
                {
                    fieldname: fieldkey,
                    userid: userId
                },
            success: function(data)
            {
                if ("success" == data.status) {
                    jQuery('#view-' + fieldkey).hide();
                    jQuery('#uploaded-' + fieldkey).show();
                }

            }
        });
    });

    window.onbeforeunload = function (e) {
        if (formHasChanged && !submitted) {
            var message = "You have not saved your changes.", e = e || window.event;
            if (e) {
                e.returnValue = message;
            }
            return message;
        }
    }
    jQuery("form").submit(function() {
        submitted = true;
    });
});