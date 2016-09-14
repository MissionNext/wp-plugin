<?php


namespace MissionNext\lib\form;


use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;

class PasswordRequestForm extends Form {

    private $user_data;

    public function __construct(){

        $this->name = 'password_request';

//        $this->setName('password_request');

        $group =array(
            array(
                'symbol_key'        => 'main_fields',
                'name'              => '',
                'depends_on'        => null,
                'depends_on_option' => null,
                'order'             => 0,
                'fields' => array(
                    array(
                        'type' => 'input',
                        'symbol_key' => 'login',
                        'name' => __('Username or E-mail:', Constants::TEXT_DOMAIN),
                        'order' => 1,
                        'default_value' => ''
                    )
                )

            ));

        $this->setGroups($group);

    }

    public function validate(){

        if(!$this->data['main_fields']['login']){
            $this->addError('login', __('<strong>ERROR</strong>: Enter a username or e-mail address.', Constants::TEXT_DOMAIN));
        } else if ( strpos( $this->data['main_fields']['login'], '@' ) ) {
            $this->user_data = get_user_by( 'email', trim( $this->data['main_fields']['login'] ) );
            if ( empty( $this->user_data ) )
                $this->addError('login', __('<strong>ERROR</strong>: There is no user registered with that email address.', Constants::TEXT_DOMAIN));
        } else {
            $login = trim($this->data['main_fields']['login']);
            $this->user_data = get_user_by('login', $login);

            if(!$this->user_data){
                $this->addError('login', __('<strong>ERROR</strong>: Invalid username or e-mail.', Constants::TEXT_DOMAIN));
            }
        }

    }

    public function save(){

        global $wpdb, $wp_hasher;

        $user_login = $this->user_data->user_login;

        $user = Context::getInstance()->getApiManager()->getApi()->getUserProfile(get_user_meta($this->user_data->ID, Constants::META_KEY, true));

        $key = wp_generate_password( 20, false );

        if ( empty( $wp_hasher ) ) {
            require_once ABSPATH . 'wp-includes/class-phpass.php';
            $wp_hasher = new \PasswordHash( 8, true );
        }

        $hashed = $wp_hasher->HashPassword( $key );

        $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );

        $message = __('Someone requested that the password be reset for the following account:', Constants::TEXT_DOMAIN) . "\r\n\r\n";
        $message .= home_url( '/' ) . "\r\n\r\n";
        $message .= sprintf(__('Username: %s', Constants::TEXT_DOMAIN), $user_login) . "\r\n\r\n";
        $message .= __('If this was a mistake, just ignore this email and nothing will happen.', Constants::TEXT_DOMAIN) . "\r\n\r\n";
        $message .= __('To reset your password, visit the following address:', Constants::TEXT_DOMAIN) . "\r\n\r\n";
        $message .= '<a href="' . site_url("password/reset?key=$key&login=" . rawurlencode($user_login), 'login') . '">' . site_url("password/reset?key=$key&login=" . rawurlencode($user_login), 'login') . '</a>';

        $ms = Context::getInstance()->getMailService();

        $ms->from = "no-reply@".$_SERVER['SERVER_NAME'];
        $ms->fromName = "MissionNext";

        return $ms->send($user['email'], __("Password reset", Constants::TEXT_DOMAIN), $message);
    }

} 