<?php


namespace MissionNext\lib\form;


use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;

class PasswordResetForm extends Form {

    private $wp_user;

    public function __construct($user){

        $this->name = 'password_reset';
        $this->wp_user = $user;

//        $this->setName('password_reset');

        $group =array(
            array(
                'symbol_key'        => 'main_fields',
                'name'              => '',
                'depends_on'        => null,
                'depends_on_option' => null,
                'order'             => 0,
                'fields' => array(
                    array(
                        'type' => 'password',
                        'symbol_key' => 'pass1',
                        'name' => __('New password', \MissionNext\lib\Constants::TEXT_DOMAIN),
                        'order' => 1,
                        'default_value' => ''
                    ),
                    array(
                        'type' => 'password',
                        'symbol_key' => 'pass2',
                        'name' => __('Confirm new password', \MissionNext\lib\Constants::TEXT_DOMAIN),
                        'order' => 2,
                        'default_value' => ''
                    )
                )

            ));

        $this->setGroups($group);

    }

    public function validate(){

        if(!$this->data['main_fields']['pass1']){
            $this->addError('pass1', __("Please fill in the new password field.", Constants::TEXT_DOMAIN));
        }

        if(!$this->data['main_fields']['pass2']){
            $this->addError('pass2', __("Please fill in the new password field.", Constants::TEXT_DOMAIN));
        }

        if($this->data['main_fields']['pass1']
            && $this->data['main_fields']['pass2']
            && $this->data['main_fields']['pass1'] != $this->data['main_fields']['pass2']
        ){
            $this->addError('pass1', __("The passwords do not match.", Constants::TEXT_DOMAIN));
        }

    }

    public function save(){
        reset_password($this->wp_user, $this->data['main_fields']['pass1']);
    }

} 