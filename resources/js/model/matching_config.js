jQuery(document).ready(function(){
    jQuery(document).on('click', '#relationAdd', function(e){
        var mainFieldOption = jQuery('#mainFields option:selected');
        var secondaryFieldOption = jQuery('#secondaryFields option:selected');
        var weightOption = jQuery('#weights option:selected');
        var relationTypeOption = jQuery('#relationType option:selected');

        addRelation(
            mainFieldOption.val(),
            mainFieldOption.text(),
            secondaryFieldOption.val(),
            secondaryFieldOption.text(),
            weightOption.val(),
            weightOption.text(),
            relationTypeOption.val(),
            relationTypeOption.text()
        );
    }).on('click', 'table tr td button.delete', function(e){
        jQuery(e.target).parents('tr').remove();
    });
});

function addRelation(mainId, mainLabel, matchId, matchLabel, weight, weightLabel, type, typeLabel){

    if(!mainId || !matchId){
        return;
    }

    var container = jQuery('.relations tbody');

    var key = new Date().getTime();

    var row = jQuery(document.createElement('tr'));
    row.append('<td><input type="hidden" name="mn_rels['+key+'][main_field_id]" value="' + mainId + '"><span>' + mainLabel + '</span></td>');
    row.append('<td><input type="hidden" name="mn_rels['+key+'][matching_field_id]" value="' + matchId + '"><span>' + matchLabel + '</span></td>');
    row.append('<td><input type="hidden" name="mn_rels['+key+'][weight]" value="' + weight + '"><span>' + weightLabel + '</span></td>');
    row.append('<td><input type="hidden" name="mn_rels['+key+'][matching_type]" value="' + type + '"><span>' + typeLabel+ '</span></td>');
    row.append('<td><button type="button" class="button delete">Delete</button></td>');

    container.append(row);
}