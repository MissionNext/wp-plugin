jQuery(document).ready(function () {
    jQuery('.options-link').on('click', function (e) {
        e.preventDefault();
        jQuery('#search_options').toggle();
    });
});