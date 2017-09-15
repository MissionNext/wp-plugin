/**
 * Created by wizard on 22.03.17.
 */
jQuery(document).ready(function(){
    jQuery('#wp-submit').on('click', function() {
        jQuery('#wp-submit').val('Loading...');
        jQuery('.login-spinner').show();
    });
});