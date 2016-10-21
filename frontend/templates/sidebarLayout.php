<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var String $content
 */

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
                                <a href="/user/account"><?php echo __('My Account', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                <?php if($userRole == 'candidate'): ?>
                                    <a href="/candidate/matches/job" class="matches"><?php echo sprintf(__('View %s Matches', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB))) ?></a>
                                    <a href="/candidate/matches/organization" class="matches"><?php echo sprintf(__('View %s Matches', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION))) ?></a>
                                    <a href="/job/search"><?php echo sprintf(__('Search %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL))) ?></a>
                                    <a href="/inquiries"><?php echo __('Job Inquiry List', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <a href="/favorite"><?php echo __('My Favorites', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                <?php endif; ?>

                                <?php if($userRole == 'agency'): ?>
                                    <a href="/presentation"><?php echo __('My Presentation', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <a href="/affiliates"><?php echo __('Affiliates', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <a href="/affiliates/jobs"><?php echo ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL)) ?></a>
                                    <a href="/job/search"><?php echo sprintf(__('Search %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL))) ?></a>
                                    <a href="/candidate/search"><?php echo sprintf(__('Search %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_CANDIDATE_PLURAL))) ?></a>
                                    <a href="/organization/search"><?php echo sprintf(__('Search %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION_PLURAL))) ?></a>
                                    <a href="/inquiries"><?php echo __('Job Inquiry List', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                <?php endif; ?>

                                <?php if($userRole == 'organization'): ?>
                                    <a href="/presentation"><?php echo __('My Presentation', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <a href="/job"><?php echo ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB_PLURAL)) ?></a>
                                    <a href="/organization/matches/candidate" class="matches"><?php echo sprintf(__('View %s Match', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_CANDIDATE_PLURAL))) ?></a>
                                    <?php if(isAgencyOn()): ?>
                                    <a href="/affiliates"><?php echo __('Affiliates', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <a href="/agency/search"><?php echo sprintf(__('Search %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_AGENCY_PLURAL))) ?></a>
                                    <?php endif; ?>
                                    <a href="/candidate/search"><?php echo sprintf(__('Search %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_CANDIDATE_PLURAL))) ?></a>
                                    <a href="/favorite"><?php echo __('My Favorites', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <a href="/inquiries"><?php echo __('Job Inquiry List', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                                    <a href="/folders"><?php echo __('Folders', \MissionNext\lib\Constants::TEXT_DOMAIN); ?></a>
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

<script type="text/javascript">
        var inprogress = jQuery('.matching-inprogress');
        var ready = jQuery('.matching-ready');
        var matches = jQuery('.matches');
        jQuery(document).ready(function(){
                inprogress.show();
                matches.hide();
                checkQueueStatus();
                setInterval(checkQueueStatus, 10000);
            });
        function checkQueueStatus() {
                jQuery.get('/check/queue', function( response ){
                        parsedResponse = JSON.parse(response);
                        if (parsedResponse.data == 0) {
                                inprogress.hide();
                                ready.show();
                                matches.show();
                            } else {
                                ready.hide();
                                matches.hide();
                                inprogress.show();
                            }
                    });
            }
    </script>
<?php
renderTemplate('_email_popup');
get_footer();
?>
