<?php


namespace MissionNext\lib\form;


use MissionNext\Api;
use MissionNext\lib\core\Context;
use MissionNext\lib\Constants;
use MissionNext\lib\LocalizationManager;

class RegistrationForm extends Form {

    public $captcha;
    public $captcha_prefix;
    public $captcha_image;

    public function __construct(Api $api, $role){

        $this->api = $api;
        $this->name = 'registration';
        $this->role = $role;

//        $this->setName('registration');

        $form = $this->api->getForm($role, 'registration');

        $groups = $form?$form:array();

        $lang_id = Context::getInstance()->getLocalizationManager()->getCurrentLangId();

        $main_fields              = Context::getInstance()->getSiteConfigManager()->get("{$this->name}_{$this->role}_main_fields", 'Register');
        $main_fields_translations = Context::getInstance()->getSiteConfigManager()->get("{$this->name}_{$this->role}_main_fields_translations", array());

        if(!empty($main_fields_translations))
        {
            $translations = json_decode($main_fields_translations, true);
            foreach($translations as $translation)
            {
                if($lang_id == $translation['id'])
                {
                    $main_fields = $translation['value'];
                }
            }
        }

        $username_tooltip_translations = json_decode(Context::getInstance()->getSiteConfigManager()->get('registration_username_tooltip'), true);
        $password_tooltip_translations = json_decode(Context::getInstance()->getSiteConfigManager()->get('registration_password_tooltip'), true);
        $email_tooltip_translations    = json_decode(Context::getInstance()->getSiteConfigManager()->get('registration_email_tooltip'), true);

        $username_tooltip = isset($username_tooltip_translations[$lang_id]) ? $username_tooltip_translations[$lang_id] : '';
        $password_tooltip = isset($password_tooltip_translations[$lang_id]) ? $password_tooltip_translations[$lang_id] : '';
        $email_tooltip    = isset($email_tooltip_translations[$lang_id])    ? $email_tooltip_translations[$lang_id]    : '';

        $group = array
        (
            'symbol_key'        => 'main_fields',
            'name'              => $main_fields,
            'depends_on'        => null,
            'depends_on_option' => null,
            'order'             => 0,
            'fields'     => array
            (
                array
                (
                    'type'          => 'input',
                    'symbol_key'    => 'username',
                    'name'          => __('Username', Constants::TEXT_DOMAIN),
                    'default_name'  => __('Username', Constants::TEXT_DOMAIN),
                    'default_note'  => $username_tooltip,
                    'note'          => '',
                    'default_value' => null,
                    'order'         => 1,
                    'choices'       => array()
                ),
                array
                (
                    'type'          => 'password',
                    'symbol_key'    => 'password',
                    'name'          => __('Password', Constants::TEXT_DOMAIN),
                    'default_name'  => __('Password', Constants::TEXT_DOMAIN),
                    'default_note'  => $password_tooltip,
                    'note'          => '',
                    'default_value' => null,
                    'order'         => 3,
                    'choices'       => array()
                )
            )
        );

        $has_email = false;

        foreach($groups as $g){
            foreach($g['fields'] as $field){
                if($field['symbol_key'] == Constants::$predefinedFields[$this->role]['email']){
                    $has_email = true;
                    break 2;
                }
            }
        }

        if(!$has_email)
        {
            $group['fields'][] = array
            (
                'type'          => 'input',
                'symbol_key'    => 'email',
                'name'          => __('Email', Constants::TEXT_DOMAIN),
                'default_name'  => __('Email', Constants::TEXT_DOMAIN),
                'default_note'  => $email_tooltip,
                'note'          => '',
                'default_value' => null,
                'order'         => 2,
                'choices'       => array()
            );
        }

        if(class_exists('ReallySimpleCaptcha')){
            $this->captcha = new \ReallySimpleCaptcha();
            $this->captcha->cleanup();
            $word = $this->captcha->generate_random_word();
            $this->captcha_prefix = mt_rand();
            $this->captcha_image = plugins_url($this->captcha->generate_image($this->captcha_prefix, $word), $this->captcha->tmp_dir .'/tpm');
        }

        array_unshift($groups, $group);

        $this->setGroups($groups);


    }

    public function validate(){

        $username = $this->data['main_fields']['username'];

        foreach($this->data as $group){
            foreach($group as $key => $field){
                if($key == Constants::$predefinedFields[$this->role]['email']){
                    $email = $field;
                }
            }
        }

        $result = $this->validateUserSignup($username, $email);

        $errors = $result['errors']->errors;

        if(!empty($errors)){
            if(isset($errors['user_name'])){
                $this->addError( 'username', $errors['user_name']);
            }

            if(isset($errors['user_email'])){
                $this->addError('email', $errors['user_email']);
            }
        }

        if(!isset($this->data['main_fields']['password'])){
            $this->addError('password', __("Please fill in the password", Constants::TEXT_DOMAIN));
        }

        if(class_exists('ReallySimpleCaptcha')){

            if(!isset($_POST['captcha']) || !isset($_POST['captcha']['prefix']) || !isset($_POST['captcha']['value'])){
                $this->addError('captcha', __("Captcha not set", Constants::TEXT_DOMAIN));
            } else {
                if(!$this->captcha->check($_POST['captcha']['prefix'], $_POST['captcha']['value'])){

                    $this->addError('captcha', __("Captcha not matched", Constants::TEXT_DOMAIN));
                }
                $this->captcha->remove($_POST['captcha']['prefix']);
            }

        }
    }

    public function save($role = 'candidate'){

        $this->validate();

        if(!$this->isValid()){
            return false;
        }

        $username = $this->data['main_fields']['username'];
        $email = isset($this->data['main_fields']['email'])?$this->data['main_fields']['email']:'';
        $password = $this->data['main_fields']['password'];

        $data = array(
            Constants::$predefinedFields[$this->role]['email'] => array(
                'value' => $email,
                'type' => 'input',
                'dictionary_id' => 0
            )
        );

        $fdata = $this->getData();
        unset($fdata['main_fields']);

        foreach($fdata as $group){
            foreach($group as $key => $value){
                $r = array(
                    'type' => $this->fields[$key]['type'],
                    'value' => $value,
                    'dictionary_id' => ''
                );

                if($this->fields[$key]['choices']){
                    foreach($this->fields[$key]['choices'] as $choice){
                        if(is_array($value)){
                            foreach($value as $v){
                                if($choice['default_value'] == $v){
                                    $r['dictionary_id'][] = $choice['id'];
                                }
                            }
                        } else {
                            if($choice['default_value'] == $value){
                                $r['dictionary_id'] = $choice['id'];
                            }
                        }
                    }
                }

                $data[$key] = $r;

                if($key == Constants::$predefinedFields[$this->role]['email']){
                    $email = $value;
                }
            }
        }

        $groups = $this->groups;
        unset($groups['main_fields']);

        foreach($groups as $group){
            foreach($group->fields as $field){
                if( $field->field['type'] != 'file' && !in_array($field->field['symbol_key'], array_keys($data))){
                    $data[$field->field['symbol_key']] = array(
                        'type' => $field->field['type'],
                        'value' => '',
                        'dictionary_id' => 0
                    );
                }
            }
        }

        if($this->files){
            foreach($this->files as $key => $file){
                if($file['tmp_name']){
                    $new_name = dirname($file['tmp_name']) . '/' . $file['name'];
                    rename($file['tmp_name'], $new_name);
                    $data[$key] =  '@' . $new_name;
                }
            }
        }

        if (Constants::ROLE_CANDIDATE == $role) {
            $data['marital_status'] = [
                'type'          => 'custom_marital',
                'value'         => 'Single',
                'dictionary_id' => ''
            ];
        }

        $response = $this->api->register($username, $email, mb_strtolower($password), $role, $data);

        // Validation error
        if($this->api->getLastStatus() == 2){

            foreach($response as $name => $errors){

                $this->addError($name, $errors);
            }

        } elseif($this->api->getLastStatus() == 1) {
            $user_id = wp_create_user($username, $password, $email);

            update_user_meta($user_id, Constants::META_KEY, $response['id']);
            update_user_meta($user_id, Constants::META_ROLE, $role);

            wp_set_auth_cookie($user_id, true);

            return $response['id'];
        } else {
            $this->addError( 'username', "Internal error" );
        }

        return false;
    }

    public function validateUserSignup($user_name, $user_email) {
        global $wpdb;

        $errors = new \WP_Error();

        $orig_username = $user_name;
        $user_name = preg_replace( '/\s+/', '', sanitize_user( $user_name, true ) );

        $user_email = sanitize_email( $user_email );

        if ( empty( $user_name ) )
            $errors->add('user_name', __( 'Please enter a username.' ) );

        $illegal_names = get_site_option( 'illegal_names' );
        if ( ! is_array( $illegal_names ) ) {
            $illegal_names = array(  'www', 'web', 'root', 'admin', 'main', 'invite', 'administrator' );
            add_site_option( 'illegal_names', $illegal_names );
        }
        if ( in_array( $user_name, $illegal_names ) ) {
            $errors->add( 'user_name',  __( 'Sorry, that username is not allowed.' ) );
        }

        /** This filter is documented in wp-includes/user.php */
        $illegal_logins = (array) apply_filters( 'illegal_user_logins', array() );

        if ( in_array( strtolower( $user_name ), array_map( 'strtolower', $illegal_logins ) ) ) {
            $errors->add( 'user_name',  __( 'Sorry, that username is not allowed.' ) );
        }

        if ( is_email_address_unsafe( $user_email ) )
            $errors->add('user_email',  __('You cannot use that email address to signup. We are having problems with them blocking some of our email. Please use another email provider.'));

        if ( strlen( $user_name ) < 4 )
            $errors->add('user_name',  __( 'Username must be at least 4 characters.' ) );

        if ( strlen( $user_name ) > 60 ) {
            $errors->add( 'user_name', __( 'Username may not be longer than 60 characters.' ) );
        }

        // all numeric?
        if ( preg_match( '/^[0-9]*$/', $user_name ) )
            $errors->add('user_name', __('Sorry, usernames must have letters too!'));

        if ( !is_email( $user_email ) )
            $errors->add('user_email', __( 'Please enter a valid email address.' ) );

        $limited_email_domains = get_site_option( 'limited_email_domains' );
        if ( is_array( $limited_email_domains ) && ! empty( $limited_email_domains ) ) {
            $emaildomain = substr( $user_email, 1 + strpos( $user_email, '@' ) );
            if ( ! in_array( $emaildomain, $limited_email_domains ) ) {
                $errors->add('user_email', __('Sorry, that email address is not allowed!'));
            }
        }

        // Check if the username has been used already.
        if ( username_exists($user_name) )
            $errors->add( 'user_name', __( 'Sorry, that username already exists!' ) );

        // Check if the email address has been used already.
        if ( email_exists($user_email) )
            $errors->add( 'user_email', __( 'Sorry, that email address is already used!' ) );

        // Has someone already signed up for this username?
        $signup = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->signups WHERE user_login = %s", $user_name) );
        if ( $signup != null ) {
            $registered_at =  mysql2date('U', $signup->registered);
            $now = current_time( 'timestamp', true );
            $diff = $now - $registered_at;
            // If registered more than two days ago, cancel registration and let this signup go through.
            if ( $diff > 2 * DAY_IN_SECONDS )
                $wpdb->delete( $wpdb->signups, array( 'user_login' => $user_name ) );
            else
                $errors->add('user_name', __('That username is currently reserved but may be available in a couple of days.'));
        }

        $signup = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->signups WHERE user_email = %s", $user_email) );
        if ( $signup != null ) {
            $diff = current_time( 'timestamp', true ) - mysql2date('U', $signup->registered);
            // If registered more than two days ago, cancel registration and let this signup go through.
            if ( $diff > 2 * DAY_IN_SECONDS )
                $wpdb->delete( $wpdb->signups, array( 'user_email' => $user_email ) );
            else
                $errors->add('user_email', __('That email address has already been used. Please check your inbox for an activation email. It will become available in a couple of days if you do nothing.'));
        }

        $result = array('user_name' => $user_name, 'orig_username' => $orig_username, 'user_email' => $user_email, 'errors' => $errors);

        /**
         * Filter the validated user registration details.
         *
         * This does not allow you to override the username or email of the user during
         * registration. The values are solely used for validation and error handling.
         *
         * @since MU
         *
         * @param array $result {
         *     The array of user name, email and the error messages.
         *
         *     @type string   $user_name     Sanitized and unique username.
         *     @type string   $orig_username Original username.
         *     @type string   $user_email    User email address.
         *     @type WP_Error $errors        WP_Error object containing any errors found.
         * }
         */
        return apply_filters( 'wpmu_validate_user_signup', $result );
    }
} 