<?php
/**
 * @var $userId
 * @var $userRole
 * @var $role String
 * @var $items Array
 * @var $messages Array

 */
// print_r($items);
// echo "<br>\$role = $role";
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
    $item['show_name'] = \MissionNext\lib\UserLib::getUserFullName($item);

    $item['profile'] = \MissionNext\lib\ProfileLib::prepareDataToShow($item['profileData'], $form);
    // echo "\$item = <br>"; print_r($item); echo "<br>";
    $groups[$item['folder']?$item['folder']:($default_folder?$default_folder:key($folders))][] = $item;
}

//MATCHING
$matching = isset($items[0]['matching_percentage']);

//AFFILIATE
$affiliate = ($userRole == 'agency' && $role == 'organization') || ($userRole == 'organization' && $role == 'agency');

if($affiliate){
    $aff_tmp = \MissionNext\lib\core\Context::getInstance()->getApiManager()->getApi()->getAffiliates($userId, 'any');

    $affiliates = array();

    foreach($aff_tmp as $aff_key => $aff){
        $affiliates[$aff[$role.'_profile']['id']] = $aff;
    }
}

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
                    <th class="sortable"><?php echo __('Organization Name', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>

                    <?php if($matching): ?>
                        <th class="sortable asc"><?php echo __("Match (%)", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                        <th><?php echo __("What Matched?", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <?php endif; ?>

                    <th><?php echo __("Favorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>

                    <?php if (!($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                        <th class="center"><?php echo __("Folder", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <?php } ?>

                    <th><?php echo __("Notes", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>

                    <?php if($affiliate): ?>
                        <th><font color="blue"><?php echo __("Affiliate", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></font></th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($groups as $group_name => $folderItems):?>
                    <tr class="folder-title <?php if(empty($folderItems)) echo 'hide'; ?> header <?php if(isset($folders[$group_name]) && $folders[$group_name] == $default_folder) echo 'default-folder'; ?> open-folder" data-name="<?php echo $group_name ?>">
                        <td colspan="15"><?php echo $group_name; ?> (<span><?php echo count($folderItems) ?></span>)</td>
                    </tr>
                    <?php foreach($folderItems as $key => $item):
                        if ($item['is_active'] == 1): // endif at line 277
                            $prior = ($role == \MissionNext\lib\Constants::ROLE_ORGANIZATION && @$item['subscription']['partnership'] == \MissionNext\lib\Constants::PARTNERSHIP_PLUS);
                            ?>

                            <tr class="item<?php if($prior) echo ' success'; ?>" data-id="<?php echo $item['id'] ?>" data-name="<?php
                            $record_name = htmlentities($item['profileData']['organization_name']);
                            echo $record_name;
                            ?>" data-prior="<?php echo $prior ?>" data-updated="<?php echo date("Y", strtotime($item['updated_at'])); ?>">
                                <td><?php echo $key + 1  ?></td>
                                <td class="name">
                                    <a href="javascript:void(0)" onclick="OpenInNewTab('/<?php echo $role ?>/<?php echo $item['id'] ?>')"><?php echo $item['profileData']['organization_name']; ?></a>
                                </td>

                                <?php if($role == \MissionNext\lib\Constants::ROLE_AGENCY): ?>
                                    <td class="name">
                                        <?php if (preg_match("/explorenext/",$sniff_host))   { ?>
                                            <a href="javascript:void(0)" onclick="OpenInNewTab('/<?php echo $role ?>/<?php echo $item['id'] ?>')"><?php echo $item['profileData']['last_name']." ".$item['profileData']['first_name']." (".$item['profileData']['abbreviation'].")"; ?></a>
                                        <?php } else { ?>
                                            <a href="javascript:void(0)" onclick="OpenInNewTab('/<?php echo $role ?>/<?php echo $item['id'] ?>')"><?php echo $item['profileData']['agency_full_name']; ?></a>
                                        <?php } ?>
                                    </td>
                                <?php endif; ?>

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

                                <td class="favorite" data-id="<?php echo $item['favorite'] ?>">
                                    <div class="favorite-block <?php echo is_integer($item['favorite'])?'favorite':'not-favorite' ?>"></div>
                                </td>

                                <?php if (!($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                                    <td class="folder">
                                        <select>
                                            <?php foreach($folders as $value => $folder): ?>
                                                <option <?php if($item['folder'] == $value) echo 'selected="selected"' ?> value="<?php echo $value ?>"><?php echo $folder ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                <?php } ?>

                                <td class="note" data-note="<?php echo htmlentities($item['notes']) ?>">
                                    <?php if (!($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                                        <div <?php if(!$item['notes']) echo 'class="no-note"' ?>></div>
                                    <?php } else { ?>
                                        <?php if($item['notes']) { ?>
                                            <div></div>
                                        <?php } ?>
                                    <?php } ?>
                                </td>

                                <?php if($affiliate) : ?>
                                    <td class="affiliate" data-status="<?php echo isset($affiliates[$item['id']])?$affiliates[$item['id']]['status']:'' ?>">
                                        <div class="<?php echo isset($affiliates[$item['id']])?'mn-'.$affiliates[$item['id']]['status']:'btn btn-link request' ?>">
                                            <?php echo isset($affiliates[$item['id']])?
                                                ucfirst(__($affiliates[$item['id']]['status'], \MissionNext\lib\Constants::TEXT_DOMAIN)):
                                                __('Request', \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endif; ?> <!--From line 164 -->
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
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
    var affiliate = '<?php echo $affiliate; ?>';
    var saveButton = '<?php echo __("Save", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>';
    var cancelButton = '<?php echo __("Cancel", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>';
    var closeButton = '<?php echo __("Close", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/common/organization_table', 'common/organization_table.js', array( 'jquery', 'jquery-ui-dialog' ));
?>