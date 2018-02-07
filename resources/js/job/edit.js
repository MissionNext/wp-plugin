jQuery(document).ready(function () {

    jQuery(document).on('click', '.file-delete-icon', function (e) {
        var link = jQuery(this);
        var route = '/delete/job/file';
        var fieldkey = link.data('fieldkey');

        jQuery.ajax
        ({
            url: route,
            type: "POST",
            dataType: "JSON",
            data:
                {
                    fieldname: fieldkey,
                    jobid: job_id
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

    var job_title_value = jQuery('select[data-key="' + job_title_field + '"]').val();
    jQuery('select[data-key="' + job_title_field + '"]')
        .find('option')
        .remove()
        .end()
        .append('<option value="' + job_title_value + '">' + job_title_value + '</option>');
});