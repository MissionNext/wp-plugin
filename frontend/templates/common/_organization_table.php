<?php
/**
 * @var $userId
 * @var $userRole
 * @var $role String
 * @var $items Array
 * @var $messages Array

// attempt to fake script into thinking this is an organization so the folders and notes for an agency are the same as the organization 
// but this approach does not work. Maybe the cookies are taking over. Nelson 
if ($userRole == "agency") {
$userId = $receiving_org;
$userRole = "organization";
}
 */
// print_r($items);

// must distinguish which application is in use for users with more than one subscriptiion, since there is more than one app_id 
$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 

$items = array_values($items);

$foldersApi = \MissionNext\lib\core\Context::getInstance()->getApiManager()->getApi()->getUserFolders($role, $userId);

$default_folder_id = \MissionNext\lib\SiteConfig::getDefaultFolder($role);
$default_folder = '';

uasort($foldersApi, 'sortFolders');

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

function sortFolders($a, $b){
    return $a['id'] < $b['id']? -1: 1;
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
                    <tr class="folder-title <?php if(empty($folderItems)) echo 'hide'; ?> header <?php if(isset($folders[$group_name]) && $folders[$group_name] == $default_folder) echo 'default-folder open-folder'; ?>" data-name="<?php echo $group_name ?>">
                        <td colspan="15"><?php echo $folders[$group_name] ?> (<span><?php echo count($folderItems) ?></span>)</td>
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
                                    <a href="#" onclick="OpenInNewTab('/<?php echo $role ?>/<?php echo $item['id'] ?>')"><?php echo $item['profileData']['organization_name']; ?></a>
                                </td>

                                <?php if($role == \MissionNext\lib\Constants::ROLE_AGENCY): ?>
                                    <td class="name">
                                        <?php if (preg_match("/explorenext/",$sniff_host))   { ?>
                                            <a href="#" onclick="OpenInNewTab('/<?php echo $role ?>/<?php echo $item['id'] ?>')"><?php echo $item['profileData']['last_name']." ".$item['profileData']['first_name']." (".$item['profileData']['abbreviation'].")"; ?></a>
                                        <?php } else { ?>
                                            <a href="#" onclick="OpenInNewTab('/<?php echo $role ?>/<?php echo $item['id'] ?>')"><?php echo $item['profileData']['agency_full_name']; ?></a>
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

<div id="match-highlight">

</div>

<div id="folder-message">

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


        var table = jQuery('table.result');

        table.find('th.sortable')
            .each(function(){

                var th = jQuery(this),
                    thIndex = th.index(),
                    inverse = false;

                th.click(function(){

                    table.find('tr.header').each(function(){
                        jQuery(this).nextUntil('.header').find('td').filter(function(){
                            return jQuery(this).index() === thIndex;

                        }).sortElements(function(a, b){

                            var a_obj = jQuery(a);
                            a = a_obj.text();
                            var parent_a = a_obj.parents('tr');
                            var prior_a = parent_a.attr('data-prior');
                            var b_obj = jQuery(b);
                            b = b_obj.text();
                            var parent_b = b_obj.parents('tr');
                            var prior_b = parent_b.attr('data-prior');

                            if( !isNaN(parseInt(a)) && !isNaN(parseInt(b)) ){
                                a = parseInt(a);
                                b = parseInt(b);
                            } else if( !isNaN(parseInt(a)) && !isNaN(parseInt(b)) ){
                                a = parseInt(a);
                                b = parseInt(b);
                            }


                            if( (prior_a && prior_b) || (!prior_a && !prior_b) ){
                                return a > b ?
                                    inverse ? -1 : 1
                                    : inverse ? 1 : -1;
                            } else if(prior_a){
                                return -1;
                            } else {
                                return 1;
                            }

                        }, function(){
                            // parentNode is the element we want to move
                            return this.parentNode;

                        })
                    });

                    table.find('th.asc').removeClass('asc');
                    table.find('th.desc').removeClass('desc');
                    th.addClass(inverse?'asc':'desc');
                    resetIndexes();

                    inverse = !inverse;
                });

            });
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


    function hideAllFolders(){
        var folders = jQuery('table.result tr.folder-title:not(.default-folder)');

        jQuery.each(folders, function(k, v){
            var folder = jQuery(v);
            folder.nextUntil('.folder-title').hide();
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

    <?php if($affiliate): ?>

    jQuery(document).on('click', 'table.result tr td.affiliate[data-status=""] div', function(e){

        var div = jQuery(e.target);
        var td = div.parents('td');
        var tr = div.parents('tr');

        requestAffiliate(tr.attr('data-id'), function(data){
            div.attr('class', 'mn-'+data['status']);
            div.text(data['status'].charAt(0).toUpperCase() + data['status'].substr(1));
            td.attr('data-status', data['status']);
        });
    });

    function requestAffiliate(approver_id, success, error){

        jQuery.ajax({
            type: "POST",
            url: "/affiliate/request",
            data: {
                id: approver_id
            },
            success: success,
            error: error,
            dataType: "JSON"
        });

    }

    <?php endif; ?>

</script>