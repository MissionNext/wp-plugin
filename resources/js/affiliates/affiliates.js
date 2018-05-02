jQuery(document).on("click", "table tr td div.approve", function(e){
    var div = jQuery(e.target);
    var tr = div.parents('tr');

    approveAffiliate(tr.attr('data-requester'), tr.attr('data-approver'), function(data){
        if(data['status'] == 'approved'){
            tr.find('td.actions').html("<div class='btn btn-link cancel'>Cancel</div>");
            var header = tr.siblings('.pending-header');
            tr.detach();
            header.before(tr);
        }
    });
}).on("click", "table tr td div.cancel", function(e){
    var div = jQuery(e.target);
    var tr = div.parents('tr');

    cancelAffiliate(tr.attr('data-requester'), tr.attr('data-approver'), function(data){
        if(data){
            tr.remove();
        }
    });
});

function approveAffiliate(requester, approver, successCallback, errorCallback){

    jQuery.ajax({
        type: "POST",
        url: "/affiliate/approve",
        data: {
            requester_id: requester,
            approver_id: approver
        },
        success: successCallback,
        error: errorCallback,
        dataType: "JSON"
    });
}

function cancelAffiliate(requester, approver, successCallback, errorCallback){

    jQuery.ajax({
        type: "POST",
        url: "/affiliate/cancel",
        data: {
            requester_id: requester,
            approver_id: approver
        },
        success: successCallback,
        error: errorCallback,
        dataType: "JSON"
    });
}