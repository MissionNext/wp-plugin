<?php
        $sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 
		if (preg_match("/explorenext/",$sniff_host))   { $subdomain = "explorenext"; }
		elseif (preg_match("/teachnext/",$sniff_host)) { $subdomain = "teachnext"; }
		elseif (preg_match("/canada/",$sniff_host)) { $subdomain = "canada"; }
		elseif (preg_match("/jg./",$sniff_host)) { $subdomain = "jg"; }
		else { $subdomain = "Not Identified"; }
?>
<div class="page-header">

	<?php if (\MissionNext\lib\Constants::ROLE_AGENCY == $userRole && $subdomain == "explorenext"): 
		$fullname = $user[profileData][first_name]." ".$user[profileData][last_name]; ?>
        <h1><?php echo __('Hello', \MissionNext\lib\Constants::TEXT_DOMAIN) . ', ' . $fullname; ?></h1> <!--Rep Name-->
   <?php elseif(!empty($name)): ?>
        <h1><?php echo __('Hello', \MissionNext\lib\Constants::TEXT_DOMAIN) . ', ' . $name; ?></h1> <!--Candidate Name-->
    <?php else: ?>
        <h1><?php echo __('Hello', \MissionNext\lib\Constants::TEXT_DOMAIN); ?></h1>
    <?php endif ;?>
</div>
<div class="dashboard-applications">
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
		elseif (preg_match("/canada/",$sniff_host)) { $subdomain = "canada"; }
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
                <img src="<?php echo getResourceUrl('/resources/images/dash_favorites.png') ?>" />
                
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
else { // for Journey Guide Application Only
    ?>
    <p>&nbsp;</p><p>&nbsp;</p>
	<center><table style="width: 600px">
	<tr><td style="text-align:center; width: 200px">CANDIDATE DASHBOARD</td><td style="text-align:center; width: 200px">VIEW INQUIRIES</td><td style="text-align:center; width: 200px">VIEW JOBS</td></tr>
	<tr><td style="text-align:center; width: 200px"><a href="https://guides.missionnext.org/jg_home.php" title="View Selected Candidates"><img src="<?php echo getResourceUrl('/resources/images/dash_affiliates.png') ?>" /></a> </td>
	<td style="text-align:center; width: 200px"> <a href="https://guides.missionnext.org/inq_list.php" title="Inquiry List (Takes a long moment to display)" target="_blank"><img src="<?php echo getResourceUrl('/resources/images/dash_inquiries.jpg') ?>" /></a></td>
	<td style="text-align:center; width: 200px"> <a href="https://guides.missionnext.org/job_list.php" title="Jobs List" target="_blank"><img src="<?php echo getResourceUrl('/resources/images/dash_jobs.png') ?>" /></a></td></tr>
	<tr><td align="center" colspan="3">&nbsp;</p></td></tr>
	<tr><td align="center" colspan="3">MissionNext: Providing information, challenge and pathways for fellow Christ-followers to serve in missions.</p></td></tr>
	</table></center>
	<?php }
?>

<script type="text/javascript">
    var role = '<?php echo $userRole; ?>';
    var appKey = '<?php echo $app_key; ?>';
    var userId = '<?php echo $userId; ?>';

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
                return '<?php echo $links[0]; ?>';
                break;
            case 3:
                return '<?php echo $links[1]; ?>';
                break;
            case 4:
                return '<?php echo $links[2]; ?>';
                break;
            case 5:
                return '<?php echo $links[3]; ?>';
                break;
            case 6:
                return '<?php echo $links[4]; ?>';
                break;
            case 9:
                return '<?php echo $links[5]; ?>';
                break;
            case 10:
                return '<?php echo $links[6]; ?>';
                break;
            default:
                return '';
        }
    }
</script>
<?php 
if ($subdomain == "canada") {
print ("<p>&nbsp; &nbsp; &nbsp; &nbsp;Note: Use <strong>Canada</strong> or <strong>TeachNext</strong>. <strong>ExploreNext</strong> is for US citizens</p>");
} elseif (\MissionNext\lib\Constants::ROLE_CANDIDATE == $userRole) {
print ("<p>&nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp;Note: <strong>Canada</strong> is for Canadian citizens</p>");
}
// echo "<br> \$userRole =  $userRole";
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/home/index', 'home/index.js', array( 'jquery' ), false, true);
?>