window.onload = function(){
    loadSavedSearches(role , function(data){
        jQuery('#saved_search').html(data);
    }, function(){console.log('error')});
};
window.onunload = function(){};

function loadSavedSearches(role, successCallback, errorCallback){

    jQuery.ajax({
        type: "GET",
        url: "/saved/search/" + role,
        success: successCallback,
        error: errorCallback
    });

}