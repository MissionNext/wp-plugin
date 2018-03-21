jQuery(document).ready(function() {
    jQuery(document).on('click', '#sendEmail', function (e) {
        EmailPopup.init();
        jQuery.ajax({
            type: "GET",
            url: "/get/captcha",
            dataType: "JSON"
        }).done(function (data) {
            EmailPopup.open(from, to, from_name, to_name, data.image_path, data.prefix);
        });
    });
});