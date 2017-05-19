<?php
/**
 * @var String $name
 * @var Array $user
 * @var String $userRole
 * @var Array $candidate
 * @var Array $fields
 * @var \MissionNext\lib\core\Context $context
 */

// echo "\$name = $name; \$userRole = $userRole";
// print_r($candidate);
$config = $context->getConfig();
function fieldEmpty($field){
    return empty($field) || $field == array(''=>'');
}
function groupEmpty($group){
    foreach($group as $field){
        if(!fieldEmpty($field['value'])){
            return false;
        }
    }
    return true;
}
// print_r($user); echo "<br>\$userRole = $userRole";
$organization_id = $user[id];
// echo "\$organization_id = $user[id] ";
$factor		 = rand(10,99); // generate random two-digit number
$factored	 = $factor * $candidate['id'];  // factored is the product of the random number and user_id
$pass_string = $factor.$factored; // pass this string, then extract user_id as $factored / $factor
$factor_org  = $factor * $organization_id;  // factored is the product of the random number and user_id
$org_string  = $factor.$factor_org; // pass this string, then extract organization_id as $factored / $factor

$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash
if (preg_match("/explorenext/",$sniff_host)) {
    $site = 3 * $factor;
}
elseif (preg_match("/teachnext/",$sniff_host)) {
    $site = 6 * $factor;
}

?>
<!-- javascript functions are needed to open a new window using Firefox running on Windows and Macs -->
<script>
function function_qcs() {
    window.open("https://info.missionnext.org/qcs_view.php?uid=<?php echo $pass_string ?>");
}
function function_print() {
    window.open("https://info.missionnext.org/print_view.php?uid=<?php echo $pass_string ?>&oid=<?php echo $org_string ?>&site=<?php echo $site ?>");
}
function function_jobs() {
    window.open("https://info.missionnext.org/jobs_view.php?uid=<?php echo $pass_string ?>&oid=<?php echo $org_string ?>&site=<?php echo $site ?>");
}
</script>

<div class="page-header">
    <h1><?php echo $name ?></h1>
</div>
<div class="page-content">
    <button class="btn btn-default position-right" onclick="javascript:window.close();">Close</button>
    <div class=" sidebar-container">
        <div class="sidebar">
            <div class="info">

                <?php echo get_avatar($candidate['email'], 160) ?>
            </div>
            <?php if($candidate['email'] != $user['email']): ?>
                <div class="buttons">
                    <a onclick="EmailPopup.open('<?php echo $user['email'] ?>', '<?php echo $candidate['email'] ?>')" class="btn btn-primary"><?php echo __("Send message", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </div>
            <?php endif; ?>

            <?php if( $userRole == \MissionNext\lib\Constants::ROLE_ORGANIZATION ): ?>
                <div class="buttons">
                    <button id="make_favorite" title="Click once. Wait a few seconds for update" class="btn btn-success <?php echo $candidate['favorite']?'hide':'' ?>"><?php echo __("Make favorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                    <button data-id="<?php echo $candidate['favorite'] ?>"  id="remove_from_favorites" title="Click once. Wait a few seconds for update" class="btn btn-danger <?php echo $candidate['favorite']?'':'hide' ?>"><?php echo __("Unfavorite", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                </div>
            <?php endif; ?>
            <?php if( $userRole == \MissionNext\lib\Constants::ROLE_ORGANIZATION || $userRole == \MissionNext\lib\Constants::ROLE_AGENCY): ?>
                <div class="buttons">
                    <button class="btn btn-default" title="Qualified Candidate Scale" onclick="function_qcs()"><a>View QCS Scale</a></button>
                </div>
                <div class="buttons">
                    <button class="btn btn-default" title="Printer Friendly Display with What Matched" onclick="function_print()"><a>Print/Forward Profile</a></button>
                </div>
				<?php if($userRole != "agency") { ?>
                <div class="buttons">
                    <button class="btn btn-default" title="Matches to Your Jobs" onclick="function_jobs()"><a>Job Matches</a></button>
                </div>
                <?php } ?>
                <!--<br> Test For IE:
                <div class="buttons">
                    <button class="btn btn-default"><a href="https://info.missionnext.org/qcs_view.php?uid=<?php echo $pass_string ?>" title="Qualified Candidate Scale" target="_blank">View QCS Scale</a></button>
                </div>
                <div class="buttons">
                    <button class="btn btn-default"><a href="https://info.missionnext.org/print_view.php?uid=<?php echo $pass_string ?>&oid=<?php echo $org_string ?>&site=<?php echo $site ?>" title="Printer Friendly Display with What Matched" target="_blank">Print/Forward Profile</a></button>
                </div>
                <div class="buttons">
                    <button class="btn btn-default"><a href="https://info.missionnext.org/jobs_view.php?uid=<?php echo $pass_string ?>&oid=<?php echo $org_string ?>&site=<?php echo $site ?>" title="Matches to Your Jobs" target="_blank">Job Matches</a></button>
                </div>-->
               <?php if( $userRole != \MissionNext\lib\Constants::ROLE_CANDIDATE ):
               $last_login = substr($candidate['last_login'],0,10);
               if ($last_login == "2010-01-01") { $last_login = substr($candidate['updated_at'],0,10); }
               ?>
                <div>
                    <hr>
                    <table>
                    <tr><td style='text-align:left'>Last Login&nbsp;</td><td><?php echo $last_login ?></td></tr>
                    <tr><td style='text-align:left'>Last Update&nbsp;</td><td><?php echo substr($candidate['updated_at'],0,10) ?></td></tr>
                    <tr><td style='text-align:left'>Date Created&nbsp;</td><td><?php echo substr($candidate['created_at'],0,10) ?></td></tr>
                    </table>

                </div>
            <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
    <div class="content">
        <!--<p> <strong><?php echo __("Username", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong> : <span><?php echo $candidate['username'] ?></span></p>-->
        <p> <strong><?php echo __("Email", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></strong> : <span><?php echo $candidate['email'] ?></span></p>

        <?php foreach($candidate['profile'] as $group): ?>
            <?php if(!groupEmpty($group['fields']) && ( isset($group['meta']['is_private']) && !$group['meta']['is_private'] || !isset($group['meta']['is_private']) ) ): ?>
                <fieldset class="mn-profile-group">
                    <legend><?php echo $group['name'] ?></legend>

                    <?php foreach($group['fields'] as $field): ?>
                        <?php if(!fieldEmpty($field['value'])): ?>
                            <div>
                                <strong><?php echo $field['label'] ?>:</strong>
                                <div>
                                    <?php if(is_array($field['value'])): ?>

                                        <?php foreach($field['value'] as $value): ?>
                                            <div><?php echo $value ?></div>
                                        <?php endforeach; ?>

                                    <?php elseif($field['type'] == 'file' && $field['value']): ?>
                                        <a href="<?php echo $config->get('api_base_path') . '/' . $config->get('api_uploads_dir') . '/' . $field['value'] ?>" class="mn-input-file-data"></a>
                                    <?php elseif('boolean' == $field['type'] && $field['value']): ?>
                                        <?php echo "&nbsp;"; ?>
                                        <?php echo (1 == $field['value']) ? "Yes" : "No" ; ?>
                                    <?php else: echo "&nbsp;"; ?> <!--space added by Nelson Apr 20, 2016-->
                                        <?php echo $field['value'] ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </fieldset>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="control-buttons">
            <div class="left">
                <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>
        </div>
        <button class="btn btn-default position-right" onclick="javascript:window.close();">Close</button>
    </div>

</div>


<script>

    jQuery(document).on('click', '#make_favorite', function(e){
        addFavorite( <?php echo $candidate['id'] ?>, 'candidate',function(data){
            jQuery('#remove_from_favorites').attr('data-id', data['id']).removeClass('hide');
            jQuery('#make_favorite').addClass('hide');
        });
    }).on('click', '#remove_from_favorites', function(e){
        removeFavorite(jQuery(e.target).attr('data-id'),function(data){
            jQuery('#remove_from_favorites').attr('data-id', false).addClass('hide');
            jQuery('#make_favorite').removeClass('hide');
        });
    });

</script>