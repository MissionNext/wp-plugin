jQuery(document).on('change', '#percentage_filter', function() {
    window.location.href = redirect_url + "?rate=" + jQuery(this).val();
});