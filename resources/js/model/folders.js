jQuery(document).on('click', '#folders tr td.actions .edit', function(e){
    var tr = jQuery(e.target).parents('tr');

    editFolder(tr.attr('data-id'), tr.find('td.name').text().trim());
}).on('click', '#folders tr td.actions .delete', function(e){
    var tr = jQuery(e.target).parents('tr');

    deleteFolder(tr.attr('data-id'), function(data){
        tr.remove();
    });
}).on('click', '#folders tr td.actions .translate', function(e){
    var tr = jQuery(e.target).parents('tr');

    loadTranslations(tr.attr('data-id'), function(data){

        data.push({
            id: 0,
            value: tr.find('td.name').text()
        });

        FolderTranslationModal.open(data, function(saveData){
            saveTranslations(tr.attr('data-id'), saveData);
        });
    });

}).on('click', '#new_folder_button', function(e){
    var folder = jQuery('#new_folder_input').val();

    addFolder(folder, function(data){

        jQuery('#new_folder_input').val('');

        var tr = '<tr data-id="'+data['id']+'"><td class="name">' + data['title'] + '</td><td class="actions"><button type="button" class="edit button">Edit</button><button type="button" class="translate button">Translate</button><button type="button" class="delete button">Delete</button></td></tr>';

        jQuery('#folders').append(tr);
    });
}).on('change', '#folders td.default input[type="radio"]', function(e){

    saveDefaultFolder(jQuery(e.target).parents('tr').attr('data-id'), function(data){
        console.log(data);
    });

});

jQuery(function(){

    jQuery( "#folder_update_dialog" ).dialog({
        dialogClass : 'wp-dialog',
        closeOnEscape : true,
        autoOpen: false,
        height: 'auto',
        width: '250px',
        modal: true,
        buttons: {
            Save: function() {

                var dialog = jQuery(this);

                var id = dialog.find('input[name=id]').val();
                var folder = dialog.find('input[name=folder]').val();

                if(id && folder){
                    updateFolder(id, folder, function(data){

                        jQuery('#folders').find('tr[data-id='+id+'] td.name').text(data['title']);
                        dialog.dialog( "close" );
                    });
                }

            },
            Cancel: function() {
                jQuery( this ).dialog( "close" );
            }
        }
    });
});

function editFolder(id, folder){

    var dialog = jQuery('#folder_update_dialog');

    dialog.find('input[name=id]').val(id);
    dialog.find('input[name=folder]').val(folder);

    dialog.dialog("open");
}

function addFolder(folder, successCallback){

    folder = folder.trim();

    if(!folder){
        return;
    }

    var data = {
        action: 'mn',
        route: 'folder/add',
        role: role,
        folder: folder
    };

    jQuery.ajax({
        url : ajaxurl,
        type: "POST",
        dataType: 'json',
        data: data,
        success: successCallback
    });

}

function deleteFolder(id, successCallback){

    var data = {
        action: 'mn',
        route: 'folder/delete',
        id: id
    };

    jQuery.ajax({
        url : ajaxurl,
        type: "POST",
        dataType: 'json',
        data: data,
        success: successCallback
    });

}

function updateFolder(id, folder, successCallback){

    folder = folder.trim();

    if(!folder){
        return;
    }

    var data = {
        action: 'mn',
        route: 'folder/update',
        id: id,
        folder: folder
    };

    jQuery.ajax({
        url : ajaxurl,
        type: "POST",
        dataType: 'json',
        data: data,
        success: successCallback
    });

}

function loadTranslations(id, successCallback){

    var data = {
        action: 'mn',
        route: '/folder/translation/load',
        id: id,
        role: role
    };

    jQuery.ajax({
        url : ajaxurl,
        type: "POST",
        dataType: 'json',
        data: data,
        success: successCallback
    });

}

function saveTranslations(id, translations, successCallback){

    var data = {
        action: 'mn',
        route: '/folder/translation/save',
        id: id,
        translations: translations
    };

    jQuery.ajax({
        url : ajaxurl,
        type: "POST",
        dataType: 'json',
        data: data,
        success: successCallback
    });

}

function saveDefaultFolder(id, successCallback){

    var data = {
        action: 'mn',
        route: '/folder/default/save',
        id: id,
        role: role
    };

    console.log(data);

    jQuery.ajax({
        url : ajaxurl,
        type: "POST",
        dataType: 'json',
        data: data,
        success: successCallback
    });

}