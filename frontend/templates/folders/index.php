<div class="page-header">
    <h2>Utilities</h2>
</div>
<?php
/**
 * @var $role
 * @var $folders
 * @var $languages
 * @var $default
 */
?>
<style>
    td.default{
        width: 25px;
        text-align: center;
    }
    #folder_update_dialog{
        padding: 15px;
    }
    #folder_update_dialog p {
        text-align: center;
        font-size: 14px;
    }
</style>

<?php if (count($default)) { ?>
    <table class="default-folders-table">
        <thead>
        <tr>
            <th>Default Folder</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($default as $folder): ?>
            <tr data-id="<?php echo $folder['id'] ?>">
                <td class="name">
                    <?php echo $folder['title'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php } ?>


<table class="custom-folders-table" id="folders">
    <thead id="custom-folders-head" style="display: <?php if (count($custom)) { ?>table-header-group<?php } else { ?>none<?php } ?>">
    <tr>
        <th width="150">Custom Folders</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($custom as $folder): ?>
        <tr data-id="<?php echo $folder['id'] ?>">
            <td class="name">
                <?php echo $folder['title'] ?>
            </td>
            <td class="actions">
                <button type="button" class="edit button btn btn-default">Edit</button>
                <button type="button" class="delete button btn btn-danger">Delete</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    <tr>
        <td>
            <strong>Add New Folder</strong>
        </td>
        <td class="frontend-controls">
            <input id="new_folder_input" type="text"/>
            <button id="new_folder_button" type="button" class="btn btn-success">Add</button>
        </td>
    </tr>
    </tfoot>
</table>

<div id="folder_update_dialog" title="Folder update">
    <p>Enter folder name:</p>
    <input type="hidden" name="id"/>
    <input type="text" name="folder"/>
</div>

<script>

    jQuery(document).on('click', '#folders tr td.actions .edit', function(e){
        var tr = jQuery(e.target).parents('tr');

        editFolder(tr.attr('data-id'), tr.find('td.name').text().trim());
    }).on('click', '#folders tr td.actions .delete', function(e){
        var tr = jQuery(e.target).parents('tr');

        deleteFolder(tr.attr('data-id'), function(data){
            tr.remove();
            rows = jQuery('#folders tbody tr').length;
            if (rows == 0) {
                jQuery('#custom-folders-head').hide();
            }
        });
    }).on('click', '#new_folder_button', function(e){
        var folder = jQuery('#new_folder_input').val();
        var user_id = <?php echo $user_id; ?>;

        addFolder(folder, user_id, function(data){

            jQuery('#new_folder_input').val('');

            var tr = '<tr data-id="'+data['id']+'"><td class="name">' + data['title'] + '</td><td class="actions"><button type="button" class="edit button btn btn-default">Edit</button><button type="button" class="delete button btn btn-danger">Delete</button></td></tr>';

            jQuery('#folders').append(tr);
            jQuery('#custom-folders-head').show();
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

    function addFolder(folder, user_id, successCallback){

        folder = folder.trim();

        if(!folder){
            return;
        }

        var data = {
            role: '<?php echo $role ?>',
            folder: folder,
            user_id: user_id
        };

        jQuery.ajax({
            url : "/folders/add",
            type: "POST",
            dataType: 'json',
            data: data,
            success: successCallback
        });

    }

    function deleteFolder(id, successCallback){

        var data = {
            id: id
        };

        jQuery.ajax({
            url : "/folders/delete",
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
            id: id,
            folder: folder
        };

        jQuery.ajax({
            url : "/folders/update",
            type: "POST",
            dataType: 'json',
            data: data,
            success: successCallback
        });

    }

</script>