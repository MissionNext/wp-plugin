jQuery(document).ready(function() {
    jQuery(document).on('click', '#sendEmail', function (e) {
        EmailPopup.init();
        EmailPopup.open(from, to, from_name, to_name);
    });
});