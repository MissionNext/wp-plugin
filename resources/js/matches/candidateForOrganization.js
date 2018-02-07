jQuery(document).on('change', '#percentage_filter', function() {
    window.location.href = redirect_url + "?rate=" + jQuery(this).val();

}).on('change', '#update_year', function () {
    window.location.href = redirect_url + "?updates=" + jQuery(this).val();
});