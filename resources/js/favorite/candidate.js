jQuery(document).on('click', '.favorite-remove', function(e){

    var row = jQuery(e.target).parents('tr');

    removeFavorite(row.attr('data-fav-id'), function(data){
        row.remove();
        resetIndexes();
    });
}).on('click', 'table tr td.note div', function(e){

        var tr = jQuery(e.target).parents('tr');

        openNote(
            tr.data('role'),
            tr.data('id'),
            jQuery(e.target).parents('td').attr('data-note'),
            tr.attr('data-name')
        );
    }
);

jQuery(document).ready(function(){
    jQuery('#note').dialog({
        autoOpen: false,
        height: 'auto',
        width: '500',
        modal: true,
        draggable: false,
        resizable: false,
        buttons: {
            saveButton : function(){

                var modal = jQuery(this);
                var role = modal.find('[name="role"]').val();
                var id = modal.find('[name="id"]').val();
                var message = modal.find('textarea.message').val();

                var data = {
                    role : role,
                    id: id,
                    note: message.trim()
                };

                jQuery.ajax({
                    type: "POST",
                    url: "/note/change",
                    data: data,
                    success: function(data, textStatus, jqXHR){

                        var tr = jQuery('table.result tr[data-id="'+data.user_id+'"]');

                        tr.find('td.note').attr('data-note', data.notes);
                        tr.find('td.note div').attr( 'class', data.notes ? '' : 'no-note');

                        modal.dialog('close');
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        modal.dialog('close');
                    },
                    dataType: "JSON"
                });

            },
            cancelButton : function(){
                jQuery(this).dialog('close');
            }
        },
        close: function() {
            var modal = jQuery(this);
            modal.find('[name="id"]').val('');
            modal.find('textarea.message').val('');
        }
    });
});

function openNote(role, id, text, name){

    var modal = jQuery('#note');

    modal.find('[name="role"]').val(role);
    modal.find('[name="id"]').val(id);
    modal.find('textarea.message').val(text?text:' ');

    modal.find('.help .name').html(name);

    modal.dialog('open');
}

function resetIndexes(){
    var index = 1;
    var rows = jQuery('table tbody tr td.id');
    jQuery.each(rows, function(key, value){
        jQuery(value).text(index);
        index++;
    });
}