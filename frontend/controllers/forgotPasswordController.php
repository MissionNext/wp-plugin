<?php

namespace MissionNext\frontend\controllers;

use MissionNext\lib\Constants;
use MissionNext\lib\form\PasswordRequestForm;
use MissionNext\lib\form\PasswordResetForm;

class forgotPasswordController extends AbstractLayoutController {

    public $secured = false;

    public function beforeAction(){
        parent::beforeAction();
    }

    public function request(){

        $form = new PasswordRequestForm();

        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $form->bind($_POST[$form->getName()]);
            $form->validate();

            if($form->isValid()){
                if($form->save()){
                    $this->setMessage("notice", __("Check your e-mail for the confirmation link.", Constants::TEXT_DOMAIN));
                }
            }

        }

        $this->form = $form;
    }

    public function reset(){

        $user = check_password_reset_key($_GET['key'], $_GET['login']);

        if ( is_wp_error($user) ) {
            if ( $user->get_error_code() === 'expired_key' ){
                $this->setMessage("error", __('Sorry, that key has expired. Please try again.', Constants::TEXT_DOMAIN), 0);
            } else {
                $this->setMessage("error", __( 'Sorry, that key does not appear to be valid.', Constants::TEXT_DOMAIN), 0);
            }
        } else {

            $form = new PasswordResetForm($user);

            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $form->bind($_POST[$form->getName()]);

                $form->validate();

                if($form->isValid()){

                    $form->save();
                    $this->redirect('/');
                }

            }

            $this->form = $form;
        }
    }

}