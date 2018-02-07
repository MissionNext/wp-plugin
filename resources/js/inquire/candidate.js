jQuery(document).on("click", "table tr td .inquire-cancel", function(e){

    var tr = jQuery(e.target).parents("tr");
    cancelInquire(tr.attr('data-id'), function(data){
        tr.remove();
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