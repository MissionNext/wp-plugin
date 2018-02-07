var matches = jQuery('.matches');
jQuery(document).ready(function(){
    matches.hide();
    checkQueueStatus();
    setInterval(checkQueueStatus, 10000);
});
function checkQueueStatus() {
    jQuery.get('/check/queue', function( response ){
        parsedResponse = JSON.parse(response);
        if (parsedResponse.data == 0) {
            matches.show();
        } else {
            matches.hide();
        }
    });
}