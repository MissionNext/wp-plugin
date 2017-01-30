(function($){
    $(function(){
        if(!Modernizr.inputtypes.date) {
            console.log("The 'date' input type is not supported, so using JQueryUI datepicker instead.");
            $("input[data-type='date']").datepicker({
                dateFormat: "yy-mm-dd"
            });
        }

        $('form .field-tooltip').tooltip({
            position: {
                my: "center bottom",
                at: "center top-5"
            }
        });
    });
})(jQuery);


function postData(actionUrl, method, data) {
    var mapForm = $('<form id="mapform" action="' + actionUrl + '" method="' + method.toLowerCase() + '"></form>');
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            mapForm.append('<input type="hidden" name="' + key + '" id="' + key + '" value="' + data[key] + '" />');
        }
    }
    $('body').append(mapForm);
    mapForm.submit();
}

function addFavorite(job_id, role, successCallback, errorCallback){

    jQuery.ajax({
        type: "POST",
        url: "/favorite/add",
        data: {
            id: job_id,
            role: role
        },
        success: function(data){
            if(data){
                successCallback(data)
            } else {
                errorCallback();
            }
        },
        error: errorCallback,
        dataType: "JSON"
    });

}

function removeFavorite(id, successCallback, errorCallback){

    if(!id){
        return;
    }

    jQuery.ajax({
        type: "POST",
        url: "/favorite/remove",
        data: {
            id: id
        },
        success: function(data){
            if(data){
                successCallback(data)
            } else {
                errorCallback();
            }
        },
        error: errorCallback,
        dataType: "JSON"
    });
}

function inquire(job_id, successCallback, errorCallback){

    if(!job_id){
        return;
    }

    jQuery.ajax({
        type: "POST",
        url: "/inquire/add",
        data: {
            id: job_id
        },
        success: function(data){
            if(data){
                successCallback(data)
            } else {
                errorCallback();
            }
        },
        error: errorCallback,
        dataType: "JSON"
    });

}

function cancelInquire(job_id, successCallback, errorCallback){

    if(!job_id){
        return;
    }

    jQuery.ajax({
        type: "POST",
        url: "/inquire/cancel",
        data: {
            job_id: job_id
        },
        success: function(data){
            if(data){
                successCallback(data)
            } else {
                errorCallback();
            }
        },
        error: errorCallback,
        dataType: "JSON"
    });
}

function cancelInquireByAgency(inquire_id, job_id, candidate_id, successCallback, errorCallback){

    if(!inquire_id){
        return;
    }

    jQuery.ajax({
        type: "POST",
        url: "/inquire/cancel/by/agency",
        data: {
            inquire_id: inquire_id,
            job_id: job_id,
            candidate_id: candidate_id
        },
        success: function(data){
            if(data){
                successCallback(data)
            } else {
                errorCallback();
            }
        },
        error: errorCallback,
        dataType: "JSON"
    });
}

function cancelInquireByOrganization(inquire_id, job_id, candidate_id, successCallback, errorCallback){

    if(!inquire_id){
        return;
    }

    jQuery.ajax({
        type: "POST",
        url: "/inquire/cancel/by/organization",
        data: {
            inquire_id: inquire_id,
            job_id: job_id,
            candidate_id: candidate_id
        },
        success: function(data){
            if(data){
                successCallback(data)
            } else {
                errorCallback();
            }
        },
        error: errorCallback,
        dataType: "JSON"
    });
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}