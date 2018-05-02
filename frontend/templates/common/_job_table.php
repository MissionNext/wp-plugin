<?php
/**
 * @var $userId
 * @var $userRole
 * @var $role String
 * @var $items Array
 * @var $messages Array


 */
// print_r($items);
// to add an item to a column must call the item into the array at MissionNext/lib/Constants.php 
// must distinguish which application is in use for users with more than one subscriptiion, since there is more than one app_id 
$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 

$items = array_values($items);

$foldersApi = \MissionNext\lib\core\Context::getInstance()->getApiManager()->getApi()->getUserFolders($role, $userId);

$default_folder_id = \MissionNext\lib\SiteConfig::getDefaultFolder($role);
$default_folder = '';

$folders = array();

foreach($foldersApi as $folderApi){

    if($folderApi['id'] == $default_folder_id){
        $default_folder = $folderApi['title'];
        $folders = array_merge(array($folderApi['title'] => $folderApi['value']?$folderApi['value']:$folderApi['title']), $folders);
    } else {
        $folders[$folderApi['title']] = $folderApi['value']?$folderApi['value']:$folderApi['title'];
    }
}

$groups = array();

foreach($folders as $key => $folder){
    $groups[$key] = array();
}

reset($folders);



$form = \MissionNext\lib\core\Context::getInstance()->getApiManager()->getApi()->getForm($role, $role == 'job'?'job':'profile');

foreach($items as $item){

    $item['show_name'] = $item['name'];

    $item['profile'] = \MissionNext\lib\ProfileLib::prepareDataToShow($item['profileData'], $form);
    // echo "\$item = <br>"; print_r($item); echo "<br>";
    $groups[$item['folder']?$item['folder']:($default_folder?$default_folder:key($folders))][] = $item;
}

//MATCHING
$matching = isset($items[0]['matching_percentage']);

//FAVORITES

function getProfileField($item, $symbol_key){

    $item =  \MissionNext\lib\ProfileLib::getProfileField($item, $symbol_key);

    return str_replace("(!) ",'',is_array($item)?(current($item)?current($item):key($item)):$item);
}

function getAge($item){
    return \MissionNext\lib\ProfileLib::getAge($item);
}

function getLocation($item){
    return \MissionNext\lib\ProfileLib::getLocation($item);
}

function getLastLogin($item){
    return date("Y-m-d", strtotime($item['last_login']));
}

?>
                
<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">

            <table class="table table-striped result <?php echo $role ?>-matches" data-role="<?php echo $role ?>" >
                <thead>
                <tr>
                    <th>#</th>
                    <th class="sortable"><?php echo __('Job Category', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <th class="sortable"><?php echo ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION)) ?></th>
                    <th class="sortable"><?php echo __("Job Title", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <th class="sortable"><?php echo __("Region", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <!--<th class="sortable"><?php echo __("Category", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>-->
                    <?php if (($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                    <th style='text-align:center'><?php echo __("Candidate", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <?php } ?>

                    <?php if($matching): ?>
                        <th class="sortable asc"><?php echo __("Match (%)", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                        <th><?php echo __("What Matched?", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <?php endif; ?>
                    <?php if (!($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                    <th class="sortable"><a title="Inquired"><?php echo __("Inq", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a></th>
                    <th><a title="Favorite"><?php echo __("Fav", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a></th>
                    <th class="center"><?php echo __("Folder", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <th><?php echo __("Notes", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <?php } ?>
                    
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($groups as $group_name => $folderItems):?>
                    <?php if (!($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                    <tr class="folder-title <?php if(empty($folderItems)) echo 'hide'; ?> header <?php if(isset($folders[$group_name]) && $folders[$group_name] == $default_folder) echo 'default-folder'; ?> open-folder" data-name="<?php echo $group_name ?>">
                        <td colspan="15"><?php echo $group_name; ?> (<span><?php echo count($folderItems) ?></span>)</td>
                    </tr>
                    <?php } ?>
                    <?php foreach($folderItems as $key => $item):
                            $prior = ($role == \MissionNext\lib\Constants::ROLE_JOB && @$item['organization']['subscription']['partnership'] == \MissionNext\lib\Constants::PARTNERSHIP_PLUS);
                            ?>

                            <tr class="item<?php if($prior) echo ' success'; ?>" data-id="<?php echo $item['id'] ?>" data-name="<?php
                            $record_name = '';
                            $record_name = htmlentities($item['name']);

                            echo $record_name;
                            ?>" data-prior="<?php echo $prior ?>" data-updated="<?php echo date("Y", strtotime($item['updated_at'])); ?>">
                                <td><?php echo $key + 1  ?></td>
                                <td class="name">
                                    <?php $job_key = 'job_title_!#'.$item['app_names'][0]; ?>
                                    <a href="javascript:void(0)" onclick="OpenInNewTab('/<?php echo $role ?>/<?php echo $item['id'] ?>')"><?php echo !empty($item['profileData'][$job_key]) ? current($item['profileData'][$job_key]) : $item['show_name'] ?></a>
                                </td>
                                <td class="organization" >
                                    <a href="/organization/<?php echo $item['organization']['id'] ?>" target="_blank">
                                        <?php echo !empty($item['org_name']) ? $item['org_name'] : $item['organization']['username']; ?>
                                    </a>
                                </td>
                                 <td class="second_title"><?php echo getProfileField($item, 'second_title') ?></td>
                                 <td class="region"><?php echo getProfileField($item, 'world_region') ?></td>
                                <!--<td class="categories"><?php echo getProfileField($item, 'job_category') ?></td>-->
                               
                                <?php if($matching): ?>
                                    <td class="matching" ><?php echo $item['matching_percentage'] ?></td>
                                    <td class="match-highlight"  >
                                        <!--                                    <div data-user-role='--><?php //echo str_replace('"','', json_encode($item['role'], JSON_HEX_APOS | JSON_HEX_QUOT)); ?><!--' data-item_profile='--><?php //echo json_encode($item['profile'], JSON_HEX_APOS | JSON_HEX_QUOT)?><!--' data-item_results='--><?php //echo json_encode($item['results'], JSON_HEX_APOS | JSON_HEX_QUOT)?><!--'></div>-->
                                        <div data-name="<?php echo $record_name; ?>" data-for-user-id='<?php echo $userId ?>' data-user-role='<?php echo $item['role'] ?>' data-user-id='<?php echo $item['id'] ?>'></div>
                                        <p class="spinner">
                                            <img src="/wp-includes/images/spinner.gif" width="20" height="20" />
                                        </p>
                                    </td>
                                <?php endif; ?>
                                
                               <?php if (!($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                               <td class="inquired">
                                    <?php if (isset($item['inquired'])) { ?>
                                        <img src="<?php echo getResourceUrl('/resources/images/inquire.png') ?>" height="16" width="16" />
                                    <?php } ?>
                                </td>
                              <td class="favorite" data-id="<?php echo isset($item['favorite']) ? isset($item['favorite']) : ''; ?>">
                                    <div class="favorite-block <?php echo (isset($item['favorite']) && is_integer($item['favorite'])) ? 'favorite' : 'not-favorite'; ?>"></div>
                                </td>

                                    <td class="folder">
                                        <select>
                                            <?php foreach($folders as $value => $folder): ?>
                                                <option <?php if($item['folder'] == $value) echo 'selected="selected"' ?> value="<?php echo $value ?>"><?php echo $folder ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                <?php } ?>
    							<?php if (!($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                                <td class="note" data-note="<?php echo htmlentities($item['notes']) ?>">
                                
                                        <?php if($item['notes']) { ?>
                                            <div></div>
                                        <?php } ?>
                                 </td>  
                                <?php } ?>
                                
                                <?php if (($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                    			<td class="actions">
                    				<a class="btn btn-primary" href="/job/matches/candidate/<?php echo $item['id'] ?>">
                        			<?php echo __("Matches", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                    				</a>
                				</td>
                    			<?php } ?>
   
                            </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
                     <?php if (($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                    <a class="btn btn-default" href="/affiliates"><?php echo __("Affiliates List", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a> 
                    <?php } ?>

 
        </div>
        
        
        <?php if($matching): ?>
            <div id="no_results" style="display: none">
                <?php echo __("No results with the set matching rate.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="note" title="<?php echo __('Note', \MissionNext\lib\Constants::TEXT_DOMAIN); ?>" style="display: none">
    <input type="hidden" name="role" value="<?php echo $role ?>"/>
    <input type="hidden" name="id" value=""/>
    <div class="help">
        <p class="role"><?php echo __("Enter or update a brief note about ", \MissionNext\lib\Constants::TEXT_DOMAIN) ?><span class="name"></span>:</p>
        <p class="folder"><?php echo __("This record is stored in folder:", \MissionNext\lib\Constants::TEXT_DOMAIN) ?> <span></span></p>
    </div>
    <textarea cols="25" rows="5" class="message" maxlength="1000"></textarea>
</div>

<div id="match-highlight" style="display: none;">

</div>

<div id="folder-message" style="display: none;">

</div>

<script>
    var userRole = '<?php echo $userRole; ?>';
    var matching = '<?php echo $matching; ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/common/job_table', 'common/job_table.js', array( 'jquery', 'jquery-ui-dialog' ), false, true);
?>