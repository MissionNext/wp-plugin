<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var String $content
 */
get_header();

?>
    <div id="main" role="main" >
        <div class="container">
            <div class="row">
                    <?php if ($subscriptions) { ?>
                        <div class="block bg-success notice">
                            <?php echo __('You have a profile at the sites: ', \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                            <?php if ($userRole == \MissionNext\lib\Constants::ROLE_CANDIDATE) {
                                foreach ($subscriptions as $sub) { ?>
                                    <a href="<?php echo $apps[$sub['app_id']]; ?>" target="_blank"><?php echo $sub['app']['name']; ?></a>
                                <?php }
                            } ?>
                        </div>
                    <?php } ?>
                    <?php renderTemplate("common/_messages", array('messages' => $messages)) ?>
                    <?php echo $content ?>
            </div>
        </div>
    </div>
<?php
//if (\MissionNext\lib\Constants::ROLE_CANDIDATE == $userRole) {
//    renderTemplate('_email_popup');
//} else {
    renderTemplate('_email_candidate_popup');
//}

get_footer();
?>