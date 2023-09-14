<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var String $content
 */
get_header();

?>
    <div id="main" role="main" class="mn-main-container">
        <div class="container">
            <div class="row">
                    <?php if (isset($subscriptions) && is_array($subscriptions)) { ?>
                        <div class="block bg-success notice">
                            <?php echo __("<p style='font-size: 15px; font-weight: bold; color='#ffffff'>You have started or completed a profile on these MissionNext pathways: | ", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
                            <?php if ($userRole == \MissionNext\lib\Constants::ROLE_CANDIDATE) {
                                foreach ($subscriptions as $sub) { 
                                	if ($site == $sub['app_id']) {
                                	?>
                                    <?php echo $sub['app']['name']; ?> | 
									<?php } else { ?>
                                    <a href="<?php echo $apps[$sub['app_id']] ?>/profile" target="_blank"><font color="#ffffff"><u><?php echo $sub['app']['name']; ?></u></font></a> | 
                                <?php }
                                }
                            } ?>
                        </div>
                    <?php } ?>
                    <?php renderTemplate("common/_messages", array('messages' => $messages)) ?>
                    <?php echo $content ?>
            </div>
        </div>
    </div>
    <div id="loader" style="display: none; position: absolute; left: 50%; top: 200px; transform: translate(-50%);">
        <img src="<?php echo getResourceUrl('/resources/images/spinner_big.gif') ?>" />
    </div>
<?php
//if (\MissionNext\lib\Constants::ROLE_CANDIDATE == $userRole) {
//    renderTemplate('_email_popup');
//} else {
    //renderTemplate('_email_candidate_popup');
//}

get_footer();
?>