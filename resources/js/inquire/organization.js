jQuery(document).on('click', '.inquire-cancel', function(e){

    var row = jQuery(e.target).parents('tr');

    cancelInquireByOrganization(row.attr('data-id'), row.attr('data-job-id'), row.attr('data-candidate-id'), function(data){
        row.remove();
        removeHeaders();
        resetIndexes();
    });
});

function resetIndexes(){
    var index = 1;
    var rows = jQuery('table tbody tr td.id');
    jQuery.each(rows, function(key, value){
        jQuery(value).text(index);
        index++;
    });
}

function removeHeaders(){
    var rows = jQuery('table.table.result tbody tr');

    jQuery.each(rows, function(key, value){

        value = jQuery(value);

        if(!value.hasClass('header')){
            return;
        }

        var next = value.next();

        if(!next.length || next.hasClass('header')){
            value.remove();
        }
    });
}