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
 * @var $domain
 * @var $site
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
<p><a class="btn btn-default" href="https://info.<?php echo $domain ?>/assign_folders.php?appid=<?php echo $site ?>" target="_blank"><?php echo __("Assign Folders to Affiliates", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>

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
    
    <tr><td colspan="2"><strong><font color="red">DELETE CAUTION</font>:</strong> Empty a folder before deleting. &nbsp; &nbsp; &nbsp; &nbsp; 
    <button type="display" class="delete button btn btn-danger" title="Choose Archived or Not a Fit if you no longer have need of the profiles. To move the profiles, Select Candidate Matches and scroll below New Listing to find/open your folder and move the profiles." >Mouseover Tip</button>
    <br>
    Candidate listings remaining in a deleted folder will re-appear in &quot;New Listing&quot;. 
    </td></tr>
    
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
<!--
<?
// link for EN users only
if (3 === $site) {
	// to thwart robots
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
-->
<script>
    var user_id = <?php echo $user_id; ?>;
    var role = '<?php echo $role; ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/folders/index', 'folders/index.js', array( 'jquery', 'jquery-ui-dialog' ), false, true);
?>