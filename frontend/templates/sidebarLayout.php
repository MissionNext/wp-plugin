<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var String $content
 * @var String $domain
 * @var $site
 */
// for page https://aubdomain.missionnext.org/user/account the value for $site is incorrect. 
$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 
if (preg_match("/urbana/",$sniff_host)) { $subdomain = "urbana"; $site = 13; }
if (preg_match("/jg./",$sniff_host))    { $subdomain = "jg"; $site = 4; }

// echo "<br>\$userRole=$userRole; <br>\$userId=$userId; <br>\$domain=$domain; <br>\$site=$site; <br>"; 
// print_r($user);
get_header();
?>
    <div id="main" role="main" >
        <div class="container clearfix">
            <div class="row">
                <div class="page-content">
                    <?php renderTemplate("common/_messages", array('messages' => $messages)) ?>
                    <div class=" sidebar-container">

                        <div class="sidebar">
                            <div class="info">
                                <?php renderTemplate("_avatar", array('user' => $user, 'size' => 160)) ?>
                            </div>
                            <div class="links mn-sidebar-links">
                                <a href="/dashboard"><?php echo __('My Dashboard', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                <a href="/profile"><?php echo __('My Profile', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                <a href="/user/account"><?php echo __('Change Password', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                <?php if($userRole == 'candidate' && $site != 13): // urbana 
                                // generate $_GET value
                                $factor   = rand(10,99); // generate random two-digit number
								$factored = $factor * $user['id'];  // factored is the product of the random number and user_id 
								$pass_string = $factor.$factored; // pass this string, then extract user_id as $factored / $factor 
                                ?>
                                    <a href="/candidate/matches/job" class="matches"><?php echo sprintf(__('View %s Matches', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB))) ?></a>
                                    <a href="/job/search"><?php echo sprintf(__('Search %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL))) ?></a>
                                    <a href="/candidate/matches/organization" class="matches"><?php echo sprintf(__('View %s Matches', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION))) ?></a>
                                    <a href="/organization/search"><?php echo sprintf(__('Search %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION_PLURAL))) ?></a>
                                    <a href="/inquiries"><?php echo __('Job Inquiry List', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <a href="/favorite"><?php echo __('My Favorites', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <?php if ($site == 3 || $site == 6): ?> <!-- explorenext and teachnext only -->
                                    <a target="_blank" href="https://info.<?php echo $domain ?>/qcs.php?uid=<?php echo $pass_string ?> "><?php echo __('Your QCS', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if($userRole == 'agency'): 
     							$rep_id		 = $user['id'];
    							$factor		 = rand(10,99); // generate random two-digit number
								$factored	 = $factor * $rep_id; // factored is the product of the random number and user_id 
								$pass_string = $factor.$factored; // pass this string, then extract user_id as $factored / $factor 
			                    ?>
                                    <a href="/affiliates"><?php echo __('Affiliates', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <?php echo "<a href='https://info.$domain/recruit_candidates.php?appid=$site' target='_blank'>Affiliate Candidates</a>"; ?>
                                    <?php echo "<a href='https://info.$domain/recruit_account.php?aid=$pass_string&s=$site' target='_blank'>Job Candidates</a>"; ?>
                                    <a href="/candidate/search"><?php echo sprintf(__('Search %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_CANDIDATE_PLURAL))) ?></a>
                                    <a href="/organization/search"><?php echo sprintf(__('Search %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION_PLURAL))) ?></a>
                                    <a href="/inquiries"><?php echo __('Job Inquiry List', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                	<!--<?php if($site == 6 && $rep_id = 9775): ?>
                                	<a href="/presentation"><?php echo __('My Presentation', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <?php endif; ?>-->
                                   <?php echo "<a href='https://info.$domain/create_folders.php?appid=$site' target='_blank'>Manage Folders</a>"; ?>
                                <?php endif; ?>

                                <?php if($userRole == 'organization' && $site != 4): ?>
                                    <a href="/presentation"><?php echo __('My Presentation', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <a href="https://info.<?php echo $domain ?>/org_matches.php?s=<?php echo $site ?>" title='Matches to your organization profile' target='_blank' class="matches"><?php echo sprintf(__('%s Matches', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_CANDIDATE))) ?></a>
                                    <a href="/candidate/search"><?php echo sprintf(__('Search %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_CANDIDATE_PLURAL))) ?></a>
                                    <a href="/favorite"><?php echo __('My Favorites', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <a href="/job">My <?php echo ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL)) ?></a>  
                                    <a href="/inquiries"><?php echo __('Job Inquiry List', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <a href="/folders"><?php echo __('Manage Folders', \MissionNext\lib\Constants::TEXT_DOMAIN); ?></a>
                                   <?php if(isAgencyOn()): ?>
                                    <a href="/affiliates"><?php echo __('Affiliates', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <?php endif; ?>
                                    <?php // echo "\$user['id'] = $user[id]"; 
                                    	$this_id = $user['id'];
                                    	date_default_timezone_set('America/New_York');
                                    	$datetime = date("Y-m-d H:i:s"); 
                                    	include("job/connect.inc.php");
                                    	$sql_date = "UPDATE users SET last_login = '$datetime' WHERE id = $this_id"; // echo "$sql_date";
                                    	$res_date = pg_query($db_link,$sql_date) or die("\$sql_date query failed: <br>$sql_date");
	                                    pg_close($db_link);
                                    ?>                                    
                                <?php endif; ?>

                                <a href="<?php echo wp_logout_url(home_url()); ?>" title="Logout"><?php echo __("Logout", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>


                                <?php if($user['subscription']['price'] != 0): ?>
                                <hr/>
                                <?php renderTemplate("payment/_info_block", compact('user')) ?>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <?php echo $content ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="loader" style="display: none; position: absolute; left: 50%; top: 200px; transform: translate(-50%);">
        <img src="<?php echo getResourceUrl('/resources/images/spinner_big.gif') ?>" />
    </div>
<?php
get_footer();
?>
