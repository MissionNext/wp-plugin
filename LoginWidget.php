<?php

namespace MissionNext;

use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;

class LoginWidget extends \WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        parent::__construct(
            'mn_login_widget', // Base ID
            __('MissionNext Login Widget', Constants::TEXT_DOMAIN), // Name
            array( 'description' => __( 'A Login widget', Constants::TEXT_DOMAIN ),
                   'classname' => 'custom-login-block') // Args

        );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {

        echo $args['before_widget'];

        if(is_user_logged_in()){

            $fullname = Context::getInstance()->getUser()->getName();
            $user = Context::getInstance()->getUser()->getUser();

            ?>
                <?php if(!empty($fullname)): ?>
                <?php echo $args['before_title'] . __('Hello', Constants::TEXT_DOMAIN) . ', ' . $fullname . $args['after_title']; ?>
                <?php else: ?>
                <?php echo $args['before_title'] . __('Hello', Constants::TEXT_DOMAIN) . $args['after_title']; ?>
                <?php endif ;?>
                <ul>
                    <?php if($user): ?>
                    <li class="mn-home-link"><a href="<?php echo site_url("/dashboard")?>"><?php echo __('My Dashboard', Constants::TEXT_DOMAIN) ?></a></li>
                    <?php endif; ?>
                    <li class="mn-logout-link"><a href="<?php echo wp_logout_url($_SERVER['REQUEST_URI']) ?>"><?php echo __('Logout', Constants::TEXT_DOMAIN) ?></a></li>
                </ul>

        <?php

        } else {

            ?>
                <h4><?php echo __("Registered Users", Constants::TEXT_DOMAIN) ?></h4>
                <p><?php echo __("If you have previously registered, login to access your information.", Constants::TEXT_DOMAIN) ?></p>

                <?php $login_form = wp_login_form(array('echo' => false)); ?>
                <?php $login_placeholder = __("Username"); ?>
                <?php $pass_placeholder = __("Password"); ?>
                <?php $login_form = preg_replace("/<label(.*?)\/label>/is", "", $login_form); ?>
                <?php $login_form = str_replace("name=\"log\"","name=\"log\" placeholder=\"{$login_placeholder}\"",$login_form); ?>
                <?php $login_form = str_replace("name=\"pwd\"","name=\"pwd\" placeholder=\"{$pass_placeholder}\"",$login_form); ?>
                <?php $login_form = str_replace('wp-login.php', 'login', $login_form); ?>

                <?php echo $login_form; ?>

                <div>
                    <p><a href="<?php echo wp_lostpassword_url("/dashboard") ?>"><?php echo __("Can't Sign In?", Constants::TEXT_DOMAIN) ?></a></p>
                    <p><?php echo __("Register as", Constants::TEXT_DOMAIN) ?></p>
                    <a href="/signup/candidate"><?php echo ucfirst(getCustomTranslation(Constants::ROLE_CANDIDATE)) ?></a>&comma;
                    <?php if(isAgencyOn()): ?>
                    <a href="/signup/agency"><?php echo ucfirst(getCustomTranslation(Constants::ROLE_AGENCY)) ?></a>&comma;
                    <?php endif; ?>
                    <a href="/signup/organization"><?php echo ucfirst(getCustomTranslation(Constants::ROLE_ORGANIZATION)) ?></a>
                </div>

        <?php
        }

        echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
        // outputs the options form on admin
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
    }
}