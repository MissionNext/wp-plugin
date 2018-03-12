<?php
/**
 * @var $userId
 * @var $userRole
 * @var $role String
 * @var $items Array
 * @var $messages Array

 */

$data = parse_url($_SERVER['REQUEST_URI']);
$url_args = [];
if (isset($data['query'])) {
    parse_str($data['query'], $url_args);
}

$sort_by = isset($url_args['sort_by']) ? $url_args['sort_by']: 'matching_percentage';
$order_by = isset($url_args['order_by']) ? $url_args['order_by']: 'desc';
$page = isset($url_args['page']) ? $url_args['page'] : 1;

// print_r($items); // print_r($messages); 
// 3/3/2017 is no way to identify an agency user_id if this table is called by an agency user. So grab the username from the COOKIE 
$Cookie_Values = array_values($_COOKIE); // an array with indexed keys 
$Cookie_Keys   = array_keys($_COOKIE);   // an array of just the cookie keys 
while (list($key, $val) = each($Cookie_Keys)) {
	// This is the cookie key. (Following wordpress_logged_in there is a long random string that has no meaning. 
	// capture the index of $Cookie_Values for a cookie with key containing "/wordpress_logged_in/"
	if (preg_match("/wordpress_logged_in/",$val)) { $this_key = $key; }
}
// echo "<br>\$userRole = $userRole; \$role = $role; \$userId = $userId; \$loggedRole = $loggedRole ";
// for org search of candidate: $userRole = organization; $role = candidate
if (isset($loggedRole) && \MissionNext\lib\Constants::ROLE_AGENCY == $loggedRole) {
    $agency_user = $Cookie_Values[$this_key];
    $pipe_pos    = strpos($agency_user,"|");
    // the username is before the pipe character. Usernames can contain a space, so these are replaced with an underline
    $agency_un   = str_replace(" ","_",trim(substr($agency_user, 0, $pipe_pos)));
    $factor		 = rand(10,99); // generate random two-digit number
    // echo "<br>\$factor = $factor; \$agency_un = $agency_un";
}

// must distinguish which application is in use for users with more than one subscriptiion, since there is more than one app_id 
$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 
// app_id is not identified, so it is hardcoded here for use to organize the tools to for agency users to organize the candidates from affiliated organizations. 
if (preg_match("/explorenext/",$sniff_host)) { 
$site_id = 3; 
}
elseif (preg_match("/teachnext/",$sniff_host)) { 
$site_id = 6; 
}

$items = array_values($items);

$foldersApi = \MissionNext\lib\core\Context::getInstance()->getApiManager()->getApi()->getUserFolders($role, $organization_id);

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
                    <?php if (isset($pagename) && 'search' == $pagename) { ?>
                        <th>
                            <?php echo __('Candidate Name', \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                        </th>
                        <th>
                            <?php echo __("Age", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                        </th>
                    <?php } else { ?>
                        <th class="sortable <?php echo ('name' == $sort_by) ? $order_by : ''; ?>">
                            <a href="<?php echo $data['path'] . '?' . http_build_query([
                                    'page'      => $page,
                                    'sort_by'   => 'name',
                                    'order_by'  => (isset($sort_by) && 'name' == $sort_by && $order_by == 'asc') ? 'desc' : 'asc',
                                ]); ?>">
                                <?php echo __('Candidate Name', \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                            </a>
                        </th>
                        <th class="sortable <?php echo ('birth_date' == $sort_by) ? $order_by : ''; ?>">
                            <a href="<?php echo $data['path'] . '?' . http_build_query([
                                    'page'      => $page,
                                    'sort_by'   => 'birth_date',
                                    'order_by'  => (isset($sort_by) && 'birth_date' == $sort_by && $order_by == 'asc') ? 'desc' : 'asc',
                                ]); ?>">
                                <?php echo __("Age", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                            </a>
                        </th>
                    <?php } ?>

                    <th><?php echo __("Gender", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <th><?php echo __("Marital status", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <th><?php echo __("Location", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <?php if (isset($pagename) && 'search' == $pagename) { ?>
                        <th>
                            <?php echo __("Last login", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                        </th>
                    <?php } else { ?>
                        <th class="sortable <?php echo ('last_login' == $sort_by) ? $order_by : ''; ?>">
                            <a href="<?php echo $data['path'] . '?' . http_build_query([
                                    'page'      => $page,
                                    'sort_by'   => 'last_login',
                                    'order_by'  => (isset($sort_by) && 'last_login' == $sort_by && $order_by == 'desc') ? 'asc' : 'desc',
                                ]); ?>">
                                <?php echo __("Last login", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                            </a>
                        </th>
                    <?php } ?>


                    <?php if($matching): ?>
                        <th class="sortable <?php echo ('matching_percentage' == $sort_by) ? $order_by : ''; ?>">
                            <a href="<?php echo $data['path'] . '?' . http_build_query([
                                    'page'      => $page,
                                    'sort_by'   => 'matching_percentage',
                                    'order_by'  => (isset($sort_by) && 'matching_percentage' == $sort_by && $order_by == 'asc') ? 'desc' : 'asc',
                                ]); ?>">
                                <?php echo __("Match (%)", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                            </a>
                        </th>
                        <th><?php echo __("What Matched?", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <?php endif; ?>

                    <th><?php echo __("Favorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>

                    <?php if (!($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                        <th class="center"><?php echo __("Folder", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <?php } ?>
                    <th><?php echo __("Notes", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
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
                            ?>

                            <tr class="item" data-id="<?php echo $item['id'] ?>" data-name="<?php
                            $record_name = htmlentities($item['show_name']);
                            echo $record_name;
                            ?>" data-prior="" data-updated="<?php echo date("Y", strtotime($item['updated_at'])); ?>">
                                <td><?php echo $key + 1  ?></td>
                                <td class="name">
                                    <a href="javascript:void(0)" onclick="OpenInNewTab('/<?php echo $role ?>/<?php echo $item['id'] ?>')">
                                        <?php echo $item['show_name'] ?>
                                    </a>
                                </td>
                                <td class="age"><?php echo getAge($item) ?></td>
                                <td class="gender"><?php echo getProfileField($item, 'gender') ?></td>
                                <td class="marital-status"><?php echo getProfileField($item, 'marital_status') ?></td>
                                <td class="location"><?php echo getLocation($item) ?></td>
                                <td class="last-login"><?php echo getLastLogin($item) ?></td>

                                <?php if($matching): ?>
                                    <td class="matching" ><?php echo $item['matching_percentage'] ?></td>
                                    <td class="match-highlight"  >
                                        <!--   <div data-user-role='--><?php //echo str_replace('"','', json_encode($item['role'], JSON_HEX_APOS | JSON_HEX_QUOT)); ?><!--' data-item_profile='--><?php //echo json_encode($item['profile'], JSON_HEX_APOS | JSON_HEX_QUOT)?><!--' data-item_results='--><?php //echo json_encode($item['results'], JSON_HEX_APOS | JSON_HEX_QUOT)?><!--'></div>-->
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

                                <?php if (!($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                                    <td class="note" data-note="<?php echo htmlentities($item['notes']) ?>">
                                        <div <?php if(!$item['notes']) echo 'class="no-note"' ?>></div>
                                    </td>
                                <?php } else { ?>
                                    <td class="note" data-note="<?php echo $item['meta']['own_note']; ?>" data-notes='<?php echo json_encode($item['meta']['notes']); ?>' data-group="<?php echo $group_name; ?>">
                                        <div <?php if(!$item['meta']['own_note'] && count($item['meta']['notes']) == 0) echo 'class="no-note"' ?>></div>
                                    </td>
                                <?php }?>

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
    <p id="other_notes"></p>
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
    var loggedUser = '<?php echo isset($loggedRole) ? $loggedRole : ''; ?>';
    var matching = '<?php echo $matching; ?>';
    var saveButton = '<?php echo __("Save", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>';
    var cancelButton = '<?php echo __("Cancel", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>';
    var closeButton = '<?php echo __("Close", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/common/candidate_table', 'common/candidate_table.js', array( 'jquery', 'jquery-ui-dialog' ), false, true);
?>