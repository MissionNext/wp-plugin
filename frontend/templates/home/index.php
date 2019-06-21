<?php
/**
 * @var $userRole
 * @var $user
 * @var $app_key
 * @var $userId
 * @var $domain
 * @var $site
 * @var $links
 */
// echo "<br>\$userRole=$userRole; \$userId=$userId; \$domain=$domain; \$site=$site; \$user= <br>"; print_r($user);
// print_r($_COOKIE);
while(list($key,$cookie) = each($_COOKIE)) {
	if (preg_match("/wordpress_logged_in/",$key)) {
		// echo "<br>$key => $cookie";
		$pipe_pos  = strpos($cookie, "|");
		$visit_un = substr($cookie, 0, $pipe_pos);
	} 
	elseif (preg_match("/wordpress_sec/",$key)) {
		// echo "<br>$key => $cookie";
		$pipe_pos  = strpos($cookie, "|");
		$visit_un = substr($cookie, 0, $pipe_pos);
	} // wordpress_sec
}

?>

<div class="page-header">

	<?php if (\MissionNext\lib\Constants::ROLE_AGENCY == $userRole && $site == 3):
		$fullname = $user['profileData']['first_name']." ".$user['profileData']['last_name']; ?>
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
// specify if affilations are possible.
if (3 === $site || 6 === $site)   { $affilations = "Yes"; }

if ($site != 4 && $site != 13) {
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
       <?php } elseif (\MissionNext\lib\Constants::ROLE_AGENCY == $userRole) { ?> 
        <li>
            <a href="https://info.<?php echo $domain ?>/favorites.php?appid=<?php echo "$site"; ?>" target="blank">
                <span><?php echo __('Favorites', \MissionNext\lib\Constants::TEXT_DOMAIN) ?><br>
                    <?php include("agency_favs.inc.php"); ?>
                </span>
                <img src="<?php echo getResourceUrl('/resources/images/dash_favorites.png') ?>" />
            </a>
        </li>
       <?php } ?>
        <?php if (\MissionNext\lib\Constants::ROLE_CANDIDATE != $userRole && $affilations == "Yes") { ?>
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
 
<?php if (\MissionNext\lib\Constants::ROLE_CANDIDATE == $userRole) { ?>
<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p align="center">Be sure to set your email options to accept messages from missionnext.org</p>
<?php }
} // if ($site != 4)
elseif ($site == 4) { // for Journey Guide Application Only
    ?>
    <p>&nbsp;</p><p>&nbsp;</p>
	<center><table style="width: 600px">
	<tr><td style="text-align:center; width: 200px">CANDIDATE DASHBOARD</td><td style="text-align:center; width: 200px">VIEW INQUIRIES</td><td style="text-align:center; width: 200px">VIEW JOBS</td></tr>
	<tr><td style="text-align:center; width: 200px"><a href="https://guides.<?php echo $domain ?>/jg_home.php" title="View Selected Candidates"><img src="<?php echo getResourceUrl('/resources/images/dash_affiliates.png') ?>" /></a> </td>
	<td style="text-align:center; width: 200px"> <a href="https://guides.<?php echo $domain ?>/inq_list.php" title="Inquiry List (Takes a long moment to display)" target="_blank"><img src="<?php echo getResourceUrl('/resources/images/dash_inquiries.jpg') ?>" /></a></td>
	<td style="text-align:center; width: 200px"> <a href="https://guides.<?php echo $domain ?>/job_list.php" title="Jobs List" target="_blank"><img src="<?php echo getResourceUrl('/resources/images/dash_jobs.png') ?>" /></a></td></tr>
	<tr><td align="center" colspan="3">&nbsp;</p></td></tr>
	<tr><td align="center" colspan="3">MissionNext: Providing information, challenge and pathways for fellow Christ-followers to serve in missions.</p></td></tr>
	</table></center>
	<?php }
elseif ($site == 13) { // for Urbana Application Only
?>
 
 <p>Thank you for registering. You are now eligible to receive a one-hour coaching session with a MissionNext Journey Guide regarding possible next steps including options in missions. We'll be in touch with you.</p>
 <p>Join any of the pathways into missions at the left to connect with organizations that will be a surprisingly good fit for you. 
 <strong>ExploreNext</strong> for any field | <strong>TeachNext</strong> is for educators | <strong>Short-Term</strong> is new.</p>
 <br><p>MissionNext has partnered with <i>Perspectives on the World Christian Movement</i> to offer Spring Scholarships for Perspective classes. 
 Register today at Perspectives.org and select MissionNext. [<a href="https://info.missionnext.org/perspectives.php" target="_blank">Instructions ...</a>] 
 <p align="center"><img src="<?php echo getResourceUrl('/resources/images/psp_logos_gray.png') ?>" /></p>

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
                return '<?php echo $links[0]; ?>'; // short-term
                break;
            case 3:
                return '<?php echo $links[1]; ?>'; // explorenext
                break;
            case 4:
                return '<?php echo $links[2]; ?>'; // jg
                break;
            case 5:
                return '<?php echo $links[3]; ?>'; // bammatch
                break;
            case 6:
                return '<?php echo $links[4]; ?>'; // teachnext
                break;
            case 9:
                return '<?php echo $links[5]; ?>'; // new
                break;
            case 10:
                return '<?php echo $links[6]; ?>'; // canada
                break;
            case 11:
                return '<?php echo $links[7]; ?>'; // it-technology 
                break;
            case 12:
                return '<?php echo $links[8]; ?>'; // urbana
                break;
            default:
                return '';
        }
    }
</script>
<?php 
if ($site == 10) { // Canada
print ("<p>&nbsp; &nbsp; &nbsp; &nbsp;Note: Use <strong>Canada</strong> or <strong>TeachNext</strong>. <strong>ExploreNext</strong> is for US citizens</p>");
} elseif (\MissionNext\lib\Constants::ROLE_CANDIDATE == $userRole) {
// print ("<p>&nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp;Note: <strong>Canada</strong> is for Canadian citizens</p>");
}
// echo "<br> \$userRole =  $userRole";
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/home/index', 'home/index.js', array( 'jquery' ), false, true);
?>