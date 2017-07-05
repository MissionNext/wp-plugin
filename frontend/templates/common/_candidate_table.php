<?php
/**
 * @var $userId
 * @var $userRole
 * @var $role String
 * @var $items Array
 * @var $messages Array

 */

$data = parse_url($_SERVER['REQUEST_URI']);
parse_str($data['query'], $url_args);
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
if ($loggedRole) { 
	if ($loggedRole == "agency") { 
		$agency_user = $Cookie_Values[$this_key]; 
		$pipe_pos    = strpos($agency_user,"|");
		// the username is before the pipe character. Usernames can contain a space, so these are replaced with an underline 
		$agency_un   = str_replace(" ","_",trim(substr($agency_user, 0, $pipe_pos)));
		$factor		 = rand(10,99); // generate random two-digit number
		// echo "<br>\$factor = $factor; \$agency_un = $agency_un";
	}
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
                    <th><?php echo __("Gender", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <th><?php echo __("Marital status", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <th><?php echo __("Location", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <th class="sortable <?php echo ('last_login' == $sort_by) ? $order_by : ''; ?>">
                        <a href="<?php echo $data['path'] . '?' . http_build_query([
                                'page'      => $page,
                                'sort_by'   => 'last_login',
                                'order_by'  => (isset($sort_by) && 'last_login' == $sort_by && $order_by == 'desc') ? 'asc' : 'desc',
                            ]); ?>">
                            <?php echo __("Last login", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                        </a>
                    </th>

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
                    <?php if (!($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                    	<th><?php echo __("Notes", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <?php } elseif (trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY) { ?>
                    	<th><?php echo __("Notes", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                    <?php } ?>
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

                                <td class="note" data-note="<?php echo htmlentities($item['notes']) ?>">
                                    <?php if (!($userRole == \MissionNext\lib\Constants::ROLE_AGENCY || isset($loggedRole) && trim($loggedRole) ==\MissionNext\lib\Constants::ROLE_AGENCY)) { ?>
                                        <div <?php if(!$item['notes']) echo 'class="no-note"' ?>></div>
                                    <?php } else { ?>
                                        <?php if($item['notes']) { ?>
                                            <div></div>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
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
    function OpenInNewTab(url) {
        var win = window.open(url, '_blank');
        win.focus();
    }

    jQuery(document).on('click', 'table.result tr td.note div', function(e){

            var tr = jQuery(e.target).parents('tr');

            openNote(
                tr.data('id'),
                jQuery(e.target).parents('td').attr('data-note'),
                tr.attr('data-name'),
                tr.find('.folder select').val()
            );
        }
    ).on('change', 'table.result tr td.folder select', function(e){
        changeFolder(jQuery(e.target).parents('tr'), countFolderItems);
    }).ready(function(){
        if ('agency' == userRole) {
            jQuery('#note').dialog({
                autoOpen: false,
                height: 'auto',
                width: '500',
                modal: true,
                draggable: false,
                resizable: false,
                buttons: {},
                close: function() {
                    var modal = jQuery(this);
                    modal.find('[name="id"]').val('');
                    modal.find('textarea.message').val('');
                }
            });
        } else {
            jQuery('#note').dialog({
                autoOpen: false,
                height: 'auto',
                width: '500',
                modal: true,
                draggable: false,
                resizable: false,
                buttons: {
                    "<?php echo __("Save", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>" : function(){

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
                    "<?php echo __("Cancel", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>" : function(){
                        jQuery(this).dialog('close');
                    }
                },
                close: function() {
                    var modal = jQuery(this);
                    modal.find('[name="id"]').val('');
                    modal.find('textarea.message').val('');
                }
            });
        }
    }).on('click', 'table.result tr.folder-title', function(e){
        triggerFolder(this);
    });

    function openNote(id, text, name, folder){

        var modal = jQuery('#note');

        modal.find('[name="id"]').val(id);
        modal.find('textarea.message').val(text?text:' ');

        modal.find('.help .name').html(name);
        modal.find('.help .folder span').html(folder);

        modal.dialog('open');
    }

    function changeFolder(row, callback){

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
                            "<?php echo __('Close', \MissionNext\lib\Constants::TEXT_DOMAIN); ?>" : function(){
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
                    var group = row.siblings("tr.folder-title[data-name='"+data.folder+"']");

                    row.detach();

                    group.after(row);

                    if(!group.hasClass('open-folder')){
                        row.hide();
                    }

                    resetGroups();
                    resetIndexes();

                    if(callback){
                        callback()
                    }
                }

            },
            error: function(jqXHR, textStatus, errorThrown){

            },
            dataType: "JSON"
        });

    }

    function resetGroups(){
        var rows = jQuery('table.result tr.folder-title');

        jQuery.each(rows, function(key, value){
            value = jQuery(value);
            var next = value.next();

            if(next.length > 0 && !next.hasClass('folder-title')){
                value.removeClass('hide');
            } else {
                value.addClass('hide');
            }

        });

    }

    function resetIndexes(){

        var index = 1;

        var rows = jQuery('table.result tr:visible');

        jQuery.each(rows, function(key, value){

            value = jQuery(value);

            if(value.hasClass('folder-title')){
                index = 1;
            } else {
                value.find('td:first').html(index);
                index++;
            }

        });
    }

    function triggerFolder(folder){

        folder = jQuery(folder);

        if(folder.hasClass('open-folder')){
            folder.nextUntil('.folder-title').hide();
        } else {
            folder.nextUntil('.folder-title').show();
        }

        folder.toggleClass('open-folder');

    }

    function countFolderItems(){

        var folders = jQuery('table.result tr.folder-title');

        jQuery.each(folders, function(k, v){
            var folder = jQuery(v);
            var length = folder.nextUntil('.folder-title').length;
            folder.find('td span').text(length);
        });

    }

    <?php if($matching): ?>

    jQuery(document).on('click', 'table.result tr td.match-highlight div', function(){
        var spinner = jQuery(this).siblings('.spinner');
        spinner.show();
        var matchName = jQuery(this).attr('data-name');
        showMatchHighlight(jQuery(this).attr('data-for-user-id'), jQuery(this).attr('data-user-id'), jQuery(this).attr('data-user-role'),  function(data){
            var dialog = jQuery('#match-highlight');

            dialog.dialog('option', 'title', matchName);
            dialog.html(data);
            dialog.dialog('open');
            spinner.hide();
        }, function(data){
            spinner.hide();
        });
    }).ready(function(){
        jQuery('#match-highlight').dialog({
            autoOpen: false,
            height: jQuery(window).height() * 0.75,
            width: '50%',
            modal: true,
            buttons: {
                "<?php echo __('Close', \MissionNext\lib\Constants::TEXT_DOMAIN); ?>" : function(){
                    jQuery(this).dialog('close');
                }
            },
            close: function() {
                jQuery(this).empty();
            }
        });
    });

    function showMatchHighlight(for_user_id, user_id, user_role, success, error){

        jQuery.ajax({
            type: "POST",
            url: "/matches/get_fields",
            data: {
                for_user_id: for_user_id,
                user_id: user_id,
                role: user_role,
            },
            success: success,
            error: error,
            dataType: "HTML"
        });
    }

    <?php endif; ?>

</script>