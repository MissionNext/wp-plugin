<div class="page-header">
    <?php if(!empty($name)): ?>
        <h1><?php echo __('Hello', \MissionNext\lib\Constants::TEXT_DOMAIN) . ', ' . $name; ?></h1> <!--Candidate Name-->
    <?php else: ?>
        <h1><?php echo __('Hello', \MissionNext\lib\Constants::TEXT_DOMAIN); ?></h1>
    <?php endif ;?>
</div>
<div class="dashboard-applications">
    <div class="matching-status">
        <p class="matching-inprogress" style="display: none;">
            <img src="<?php echo getResourceUrl('/resources/images/spinner_32x32.gif') ?>" width="16" />
            <span>Matching calculations in progress.</span>
        </p>
        <p class="matching-ready" style="display: none;">
            <img src="<?php echo getResourceUrl('/resources/images/green_circle_icone.png') ?>" width="16" />
            <span>Matching results ready.</span>
        </p>
    </div>
    <div class="col-md-12">
        <?php if ($userRole == \MissionNext\lib\Constants::ROLE_CANDIDATE) { ?>
            <table class="subscriptions-table">
                <tr>
                    <td>
                        <img class="spinner-icon" width="16" src="<?php echo getResourceUrl('/resources/images/spinner_32x32.gif') ?>" />
                    </td>
                </tr>
            </table>
        <?php } else { ?>
            <p class="left subscription-list">
                <img class="spinner-icon" width="16" src="<?php echo getResourceUrl('/resources/images/spinner_32x32.gif') ?>" />
            </p>
        <?php } ?>
    </div>
</div>
<?php
        $sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 
		if (preg_match("/explorenext/",$sniff_host))   { $subdomain = "explorenext"; }
		elseif (preg_match("/teachnext/",$sniff_host)) { $subdomain = "teachnext"; }
		elseif (preg_match("/jg./",$sniff_host)) { $subdomain = "jg"; }
if ($subdomain != "jg") {
?>
<div class="info-icons">
    <ul>
        <li>
            <a href="/inquiries">
                <span class="icon-title inquiries-icon"><?php echo __('Inquiries', \MissionNext\lib\Constants::TEXT_DOMAIN) ?><br>
                    <img class="spinner-icon" width="16" src="<?php echo getResourceUrl('/resources/images/spinner_32x32.gif') ?>" />
                </span>
                <img src="<?php echo getResourceUrl('/resources/images/dash_inquiries.jpg') ?>" />
                
            </a>
        </li>
        <?php if (\MissionNext\lib\Constants::ROLE_AGENCY != $userRole) { ?>
        <li>
            <a href="/favorite">
                <span class="icon-title favorites-icon"><?php echo __('Favorites', \MissionNext\lib\Constants::TEXT_DOMAIN) ?><br>
                    <img class="spinner-icon" width="16" src="<?php echo getResourceUrl('/resources/images/spinner_32x32.gif') ?>" />
                </span>
                <img src="<?php echo getResourceUrl('/resources/images/dash_favorites.jpg') ?>" />
                
            </a>
        </li>
        <?php } ?>
        <?php if (\MissionNext\lib\Constants::ROLE_CANDIDATE != $userRole) { ?>
            <li>
                <a href="/affiliates">
                    <span class="icon-title affiliates-icon"><?php echo __('Affiliates', \MissionNext\lib\Constants::TEXT_DOMAIN) ?><br>
                        <img class="spinner-icon" width="16" src="<?php echo getResourceUrl('/resources/images/spinner_32x32.gif') ?>" />
                    </span>
                    <img src="<?php echo getResourceUrl('/resources/images/dash_affiliates.png') ?>" />
                    
                </a>
            </li>
        <?php } ?>
    </ul>
</div> <!--<div class="info-icons">-->
<? } // if ($subdomain != "jg")
else {
    ?>
	<table>
	<tr><td align="center">JOURNEY GUIDE DASHBOARD</p></td></tr>
	<tr><td align="center"><a href="https://guides.missionnext.org/jg_home.php"><img src="<?php echo getResourceUrl('/resources/images/dash_affiliates.png') ?>" /></a></td></tr>
	<tr><td align="center">Providing information and challege to serve in missions</p></td></tr>
	</table>
	<?php }
?>

<script type="text/javascript">
    var role = '<?php echo $userRole; ?>';
    var appKey = '<?php echo $app_key; ?>';

    jQuery(document).ready(function () {
        jQuery.get('/get/user/favs', { role: role, userid: "<?php echo $userId; ?>"})
            .success( function (data) {
                response = JSON.parse(data);
                if (typeof response.affiliatesCount != 'undefined') {
                    jQuery('.affiliates-icon').html("Affiliates<br />" + response.affiliatesCount);
                }
                if (typeof response.favoritesCount != 'undefined') {
                    jQuery('.favorites-icon').html('Favorites<br />' + response.favoritesCount);
                }
                if (typeof response.inquiriesCount != 'undefined') {
                    jQuery('.inquiries-icon').html('Inquiries<br />' + response.inquiriesCount);
                }
        });

        jQuery.get('/get/user/subscriptions', { userid: "<?php echo $userId; ?>"})
            .success(function (data) {
                response = JSON.parse(data);
                if ("candidate" == role) {
                    var subsTable = jQuery('.subscriptions-table');
                    subsTable.html('');
                    jQuery.each(response.subscriptions, function (index, value) {
                        link = getLinkHtml(appKey, value);
                        subsTable.append('<tr><td>' + link + '</td><td></td></tr>');
                    });

                    jQuery.each(response.candidateSubs, function (index, value) {
                        appUrl = getAppLink(value.app_id);
                        subsTable.append('<tr><td>' +
                            '<a class="btn btn-default" disabled target="_blank" href="' + appUrl + '/dashboard">' + value.app_name + '</a>' +
                            '</td><td>' +
                            '<a class="btn btn-default" href="/subscription/add/' + value.app_id + '">SignUp for Free</a>' +
                            '</td></tr>');
                    });
                } else {
                    var subsList = jQuery('.subscription-list');
                    subsList.html('');
                    jQuery.each(response.subscriptions, function (index, value) {
                        link = getLinkHtml(appKey, value);
                        subsList.append(link);
                    });
                }
            });
    });

    function getLinkHtml(appKey, value) {
        link = '<a class="btn ';
        if (appKey == value.app.public_key) {
            link += 'btn-success" ';
        } else {
            link += 'btn-default" target="_blank" ';
        }
        appUrl = getAppLink(value.app_id);
        link += 'href="' + appUrl + '/dashboard">' + value.app.name + '</a><br><br>';

        return link;
    }

    function getAppLink(id) {
        switch (id) {
            case 2:
                return 'https://finishers.missionnext.org';
                break;
            case 3:
                return 'https://explorenext.missionnext.org';
                break;
            case 4:
                return 'https://jg.missionnext.org';
                break;
            case 5:
                return 'https://bammatch.missionnext.org';
                break;
            case 6:
                return 'https://teachnext.missionnext.org';
                break;
            case 9:
                return 'https://new.missionnext.org';
                break;
            default:
                return '';
        }
    }
</script>
