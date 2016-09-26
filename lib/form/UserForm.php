<?php

namespace MissionNext\lib\form;

use MissionNext\Api;
use MissionNext\lib\Constants;

class UserForm extends Form{

    private $user;
    private $wp_user;

    public function __construct(Api $api, \WP_User $wp_user, $user, $user_id){

        $this->api = $api;
        $this->user = $user;
        $this->name = 'profile';
        $this->wp_user = $wp_user;
        $this->user_id = $user_id;

//        $this->setName('profile');

        $group = array(
            array(
                'symbol_key'        => 'main_fields',
                'name'              => __('Profile fields', Constants::TEXT_DOMAIN),
                'depends_on'        => null,
                'depends_on_option' => null,
                'order'             => 0,
                'fields' => array(
                    array(
                        'type' => 'input',
                        'symbol_key' => 'username',
                        'name' => __('Username', Constants::TEXT_DOMAIN),
                        'order' => 1,
                        'default_value' => $wp_user->user_login
                    ),
                    array(
                        'type' => 'input',
                        'symbol_key' => 'email',
                        'name' => __('Email', Constants::TEXT_DOMAIN),
                        'order' => 2,
                        'default_value' => $wp_user->user_email
                    ),
                    array(
                        'type' => 'password',
                        'symbol_key' => 'old_password',
                        'name' => __('Old Password', Constants::TEXT_DOMAIN),
                        'order' => 3,
                        'default_value' => null
                    ),
                    array(
                        'type' => 'password',
                        'symbol_key' => 'password',
                        'name' => __('New Password', Constants::TEXT_DOMAIN),
                        'order' => 3,
                        'default_value' => null
                    )
                )

            ));

        $this->setGroups($group);
    }

    public function validate(){

        if(!$this->data['main_fields']['email']){
            $this->addError('email', __("Fill in the email", Constants::TEXT_DOMAIN));
        }

        if($this->data['main_fields']['old_password'] && !$this->data['main_fields']['password']){
            $this->addError('password', __("Fill in new password", Constants::TEXT_DOMAIN));
        }

        if(!$this->data['main_fields']['old_password'] && $this->data['main_fields']['password']){
            $this->addError('old_password', __("Fill in old password", Constants::TEXT_DOMAIN));
        }

        if($this->data['main_fields']['old_password'] && $this->data['main_fields']['password']){

            if(!$this->api->checkAuth($this->user['username'], mb_strtolower($this->data['main_fields']['old_password']))){
                $this->addError('old_password', __("Password doesn't match", Constants::TEXT_DOMAIN));
            }
        }

    }

    public function save($role = 'candidate'){

        $this->validate();

        if(!$this->isValid()){
            return false;
        }

        $email = $this->data['main_fields']['email'];
        $password = $this->data['main_fields']['password'];

        $params = array();

        if($email != $this->user['email']){
            $params['email'] = $email;
        }

        if($password){
            $params['password'] = $password;
        }

        $response = $this->api->updateUser($this->user_id, $params);

        if($this->api->getLastStatus() == 2){

            foreach($response as $name => $errors){

                $this->addError($name, $errors);
            }

        } elseif($this->api->getLastStatus() == 1) {

            $this->api->updateUserProfile($this->user_id, array( 'email' => $email ), $this->changedFields);

            $wp_params = array(
                'ID' => $this->wp_user->ID
            );

            $wp_params['user_email'] = $email;

            if($password){
                $wp_params['user_pass'] = $password;
            }

            wp_update_user($wp_params);
        }

        return false;
    }
}