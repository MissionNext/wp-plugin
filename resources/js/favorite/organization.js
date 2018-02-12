jQuery(document).on('click', '.favorite-remove', function(e){

    var row = jQuery(e.target).parents('tr');

    removeFavorite(row.attr('data-fav-id'), function(data){
        row.remove();
        resetIndexes();
    });
}).on('click', 'table tr td.note div', function(e){

        var tr = jQuery(e.target).parents('tr');

        openNote(
            tr.data('id'),
            jQuery(e.target).parents('td').attr('data-note'),
            tr.attr('data-name')
        );
    }
).on('change', '.folder select', function (e) {
    changeFolder(jQuery(e.target).parents('tr'));
});

jQuery(document).ready(function(){
    jQuery('#note').dialog({
        autoOpen: false,
        height: 'auto',
        width: '500',
        modal: true,
        draggable: false,
        resizable: false,
        buttons: {
            Save : function(){

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
            Cancel : function(){
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

function openNote(id, text, name){

    var modal = jQuery('#note');

    modal.find('[name="id"]').val(id);
    var role = modal.find('[name="role"]').val();

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

function changeFolder(row){

    row = jQuery(row);

    var folder = row.find('td.folder select').val();

    jQuery.ajax({
        type: "POST",
        url: "/folder/change",
        data: {
            role: row.parents('table').attr('data-role'),
            id: row.attr('data-id'),
            folder: folder
        },
        success: function(data, textStatus, jqXHR){
            if (typeof data.error != "undefined" && data.error.length > 0) {
                jQuery('#folder-message').dialog({
                    autoOpen: false,
                    height: '300',
                    width: '300',
                    modal: true,
                    buttons: {
                        Close : function(){
                            jQuery(this).dialog('close');
                        }
                    },
                    close: function() {
                        jQuery(this).empty();
                    }
                });
                var dialog = jQuery('#folder-message');

                dialog.html("<p>" + data.error + "</p>");

                dialog.dialog('open');

            } else {
                resetIndexes();
            }

        },
        error: function(jqXHR, textStatus, errorThrown){

        },
        dataType: "JSON"
    });
}