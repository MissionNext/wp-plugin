<?php


namespace MissionNext\frontend\controllers;


use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;

class loginController extends AbstractLayoutController {

    public $secured = false;

    public function beforeAction(){

        $this->user = Context::getInstance()->getUser()->getUser();

        if(get_current_user_id() && $this->user){
            $this->redirectAfterLogin();
        } elseif(get_current_user_id()) {
            wp_logout();
        }
    }

    public function login(){

        $this->errors = array();

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            mn_process_login();

            $user = wp_authenticate($_POST['log'], $_POST['pwd']);

            if(is_wp_error($user)){

                foreach($user->errors as $key => $error){
                    if(in_array($key, array('empty_username', 'invalid_username'))){
                        $this->errors['username'] = current($error);
                    } else {
                        $this->errors['password'] = current($error);
                    }
                }
            } else {
                wp_set_auth_cookie($user->ID, @$_POST['rememberme'], is_ssl());
                wp_set_current_user($user->ID);
                $this->redirectAfterLogin();
            }
        }
    }

    private function redirectAfterLogin(){

        $redirect_url = site_url('/');

        if(current_user_can('manage_options')){
            $redirect_url = site_url('/wp-admin/');
        } else if(get_user_meta(get_current_user_id(), Constants::META_KEY, true)){
            $redirect_url = "/dashboard";
        }

        $this->redirect($redirect_url);
    }

}