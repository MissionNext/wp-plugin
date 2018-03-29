<?php
/**
 * @var $userId Int
 * @var $role String
 * @var $result Array
 * @var $messages Array
 * @var $userRole String
 */
?>
<div id="result_table">
    <?php
    /**
     * @var $userId
     * @var $userRole
     * @var $role String
     * @var $items Array
     * @var $messages Array

    // attempt to fake script into thinking this is an organization so the folders and notes for an agency are the same as the organization
    // but this approach does not work. Maybe the cookies are taking over.
    if ($userRole == "agency") {
    $userId = $receiving_org;
    $userRole = "organization";
    }
     */

    $foldersApi = \MissionNext\lib\core\Context::getInstance()->getApiManager()->getApi()->getUserFolders($role, $userId);

    $default_folder_id = \MissionNext\lib\SiteConfig::getDefaultFolder($role);
    $default_folder = '';
    foreach($foldersApi as $folderApi){
        if($folderApi['id'] == $default_folder_id){
            $default_folder = $folderApi['title'];
            break;
        }
    }

    $default_affiliate = null;
    if (count($additional_info['affiliates']) > 0) {
        reset($additional_info['affiliates']);
        $default_affiliate = key($additional_info['affiliates']);
    }

    $folders = array();
    $folders[$default_folder] = $default_folder;
    foreach ($additional_info['folders'] as $folder) {
        $folders[$folder['folder']] = $folder['folder'];
    }

    $groups = array();

    foreach($folders as $key => $folder){
        foreach ($additional_info['affiliates'] as $orgItem) {
            $groups[$orgItem['id']][$key] = array();
        }

    }

    reset($folders);

    $form = \MissionNext\lib\core\Context::getInstance()->getApiManager()->getApi()->getForm($role, $role == 'job'?'job':'profile');

    foreach ($multipleResults as $key => $items) {
        foreach($items as $item){

            $item['show_name'] = \MissionNext\lib\UserLib::getUserFullName($item);

            $item['profile'] = \MissionNext\lib\ProfileLib::prepareDataToShow($item['profileData'], $form);

            if (!empty($item['folder'])) {
                $groups[$key][$item['folder']][] = $item;
            } else {
                $groups[$key][$default_folder?$default_folder:key($folders)][] = $item;
            }
        }
    }

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

                <?php foreach ($groups as $orgId => $orgGroups) { ?>
                    <table class="table table-striped result <?php echo $role ?>-matches" data-role="<?php echo $role ?>" id="orgid-<?php echo $orgId; ?>">
                        <thead>
                        <tr>
                            <th>#</th>

                            <th class="sortable"><?php echo __('Candidate Name', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                            <th class="sortable"><?php echo __("Age", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                            <th><?php echo __("Gender", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                            <th><?php echo __("Marital status", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                            <th class="sortable"><?php echo __("Location", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                            <th><?php echo __("Last login", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>

                            <th><?php echo __("Favorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                            <th><?php echo __("Notes", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($orgGroups as $group_name => $folderItems):?>
                            <tr class="folder-title <?php if(empty($folderItems)) echo 'hide'; ?> header <?php if(isset($folders[$group_name]) && $folders[$group_name] == $default_folder) echo 'default-folder open-folder'; ?>" data-name="<?php echo $group_name ?>">
                                <td colspan="15"><?php echo $folders[$group_name] ?> (<span><?php echo count($folderItems) ?></span>)</td>
                            </tr>
                            <?php foreach($folderItems as $key => $item):
                                $prior = ($role == \MissionNext\lib\Constants::ROLE_JOB && @$item['organization']['subscription']['partnership'] == \MissionNext\lib\Constants::PARTNERSHIP_PLUS) ||
                                    ($role == \MissionNext\lib\Constants::ROLE_ORGANIZATION && @$item['subscription']['partnership'] == \MissionNext\lib\Constants::PARTNERSHIP_PLUS);
                                ?>

                                        <tr class="item<?php if($prior) echo ' success'; ?>"
                                            data-id="<?php echo $item['id'] ?>"
                                            data-name="<?php $record_name = htmlentities($item['show_name']); echo $record_name; ?>"
                                            data-prior="<?php echo $prior ?>"
                                            data-updated="<?php echo date("Y", strtotime($item['updated_at'])); ?>">
                                            <td><?php echo $key + 1  ?></td>

                                            <td class="name">
                                                <a href="#" onclick="OpenInNewTab('/<?php echo $role ?>/<?php echo $item['id'] ?>')">
                                                    <?php echo $item['show_name'] ?>
                                                </a>
                                            </td>
                                            <td class="age"><?php echo getAge($item) ?></td>
                                            <td class="gender"><?php echo getProfileField($item, 'gender') ?></td>
                                            <td class="marital-status"><?php echo getProfileField($item, 'marital_status') ?></td>
                                            <td class="location"><?php echo getLocation($item) ?></td>
                                            <td class="last-login"><?php echo getLastLogin($item) ?></td>


                                            <td class="favorite" data-id="<?php echo $item['favorite'] ?>">
                                                <div class="favorite-block <?php echo ($item['favorite'])?'favorite':'not-favorite' ?>"></div>
                                            </td>

                                            <td class="note" data-note="<?php echo isset($item['note']) ? $item['note'] : ''; ?>" data-notes='<?php echo json_encode($item['notes'][$orgId]); ?>' data-group="<?php echo $group_name; ?>">
                                                <div <?php if((!isset($item['note']) || !$item['note']) && count($item['notes'][$orgId]) == 0) echo 'class="no-note"' ?>></div>
                                            </td>

                                        </tr>


                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>

    <div id="note" title="<?php echo __('Note', \MissionNext\lib\Constants::TEXT_DOMAIN); ?>" style="display: none">
        <input type="hidden" name="role" value="<?php echo $role ?>"/>
        <input type="hidden" name="id" value=""/>
        <p id="other_notes"></p>
        <div class="help">
            <p class="role"><?php echo __("Enter or update a brief note about ", \MissionNext\lib\Constants::TEXT_DOMAIN) ?><span class="name"></span>:</p>
            <p class="folder"><?php echo __("This record is stored in folder:", \MissionNext\lib\Constants::TEXT_DOMAIN) ?> <span></span></p>
        </div>
        <textarea cols="25" rows="5" class="message" maxlength="1000"></textarea>
    </div>

    <script>
        var current_org = '<?php echo $default_affiliate; ?>';
    </script>
</div>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/search/search_result_agency', 'search/search_result_agency.js', array( 'jquery', 'jquery-ui-dialog' ), false, true);
?>
