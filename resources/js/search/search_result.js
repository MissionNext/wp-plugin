jQuery(document).ready(function () {
    jQuery('.pagination a').on('click', function (e) {
        e.preventDefault();
        var pageNumber = jQuery(this).data('page');
        jQuery('#page_number').val(pageNumber);
        jQuery('#search-form').submit();
    })
});