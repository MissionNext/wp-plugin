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

<table class="relations wp-list-table widefat" id="folders" >
    <thead>
    <tr>
        <th>Default</th>
        <th>Folder</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($folders as $folder): ?>
        <tr data-id="<?php echo $folder['id'] ?>">
            <td class="default">
                <input type="radio" name="folder_default" <?php if($default == $folder['id']) echo 'checked="checked"' ?>/>
            </td>
            <td class="name">
                <?php echo $folder['title'] ?>
            </td>
            <td class="actions">
                <button type="button" class="edit button">Edit</button>
                <button type="button" class="translate button">Translate</button>
                <button type="button" class="delete button">Delete</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td>
                <input id="new_folder_input" type="text"/>
                <button id="new_folder_button" type="button" class="button">Add</button>
            </td>
        </tr>
    </tfoot>
</table>

<div id="folder_update_dialog" title="Folder update">
    <p>Enter folder name:</p>
    <input type="hidden" name="id"/>
    <input type="text" name="folder"/>
</div>

<?php renderTemplate('_folder_translation_modal', compact('languages')) ?>

<script>
    var role = '<?php echo $role ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/model/folders', 'model/folders.js', array( 'jquery', 'jquery-ui-dialog' ), false, true);
?>