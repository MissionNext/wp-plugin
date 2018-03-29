<div class="page-header">
    <h2>Folders</h2>
</div>
<?php
/**
 * @var $role
 * @var $userRole
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
<?php } 
// echo "<br>\$userRole = $userRole; \$folders = $folders; \$user_id = $user_id";
?>


<table class="custom-folders-table" id="folders">
    <thead id="custom-folders-head" style="display: <?php if (count($custom)) { ?>table-header-group<?php } else { ?>none<?php } ?>">
    <tr>
        <th width="150">Custom Folders</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <!--
    <pre>
    <?php print_r($custom); ?>
    </pre>
    -->
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
<?
// link for EN users only
$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 
// app_id is not identified, so it is hardcoded here for use to organize the tools to for agency users to organize the candidates from affiliated organizations. 
if (preg_match("/explorenext/",$sniff_host)) { 
	// to thwart robots 
	$site_id = 3; 
	$factor		 = rand(10,99); // generate random two-digit number
	$factored	 = $factor * $user_id; // factored is the product of the random number and user_id 
	$pass_string = $factor.$factored; // pass this string, then extract user_id as $factored / $factor 

?>
<div id="folder_migrate" title="Migrate FP to EN">
    <p><a href="https://info.missionnext.org/folder_migration.php?uid=<? echo $pass_string ?>" target="blank">Migrate finishers.org folder assignments to ExploreNext ...</a></p>
    
</div>
<?php
 } 
?>

<script>
    var user_id = <?php echo $user_id; ?>;
    var role = '<?php echo $role; ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/folders/index', 'folders/index.js', array( 'jquery', 'jquery-ui-dialog' ), false, true);
?>