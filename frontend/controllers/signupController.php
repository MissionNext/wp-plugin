<?php

namespace MissionNext\frontend\controllers;

use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;
use MissionNext\lib\form\Form;
use MissionNext\lib\form\RegistrationForm;
use MissionNext\lib\SiteConfig;

class signupController extends AbstractLayoutController {

    /**
     * @var Form
     */
    public $profileForm;
    /**
     * @var Form
     */
    public $registrationForm;
    public $secured = false;
    public $role;

    public function beforeAction(){

        $this->api = Context::getInstance()->getApiManager()->getApi();
        \MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/country', 'country.js', array( 'jquery' ), false, true);

    }

    public function candidate(){

        $this->role = Constants::ROLE_CANDIDATE;
        $this->registrationForm = new RegistrationForm($this->api, 'candidate');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $this->registrationForm->bind($_POST[$this->registrationForm->getName()]);

            $this->registrationForm->save('candidate');

            if($this->registrationForm->isValid()){
                $this->redirect(home_url('/payment/first'));
            }

        }

        return 'signup/registration.php';

    }

    public function agency(){

        if(!SiteConfig::isAgencyOn()){
            $this->forward404();
        }

        $this->role = Constants::ROLE_AGENCY;
        $this->registrationForm = new RegistrationForm($this->api, 'agency');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $this->registrationForm->bind($_POST[$this->registrationForm->getName()]);

            $user_id = $this->registrationForm->save('agency');

            if($user_id){
                $this->sendMail($this->registrationForm, $user_id);
            }

            if($this->registrationForm->isValid()){
                $this->redirect(home_url('/payment/first'));
            }

        }

        return 'signup/registration.php';

    }

    public function organization(){

        $this->role = Constants::ROLE_ORGANIZATION;
        $this->registrationForm = new RegistrationForm($this->api, 'organization');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $this->registrationForm->bind($_POST[$this->registrationForm->getName()]);

            $user_id = $this->registrationForm->save('organization');

            if($user_id){
                $this->sendMail($this->registrationForm, $user_id);
            }

            if($this->registrationForm->isValid()){
                $this->redirect(home_url('/payment/first'));
            }

        }

        return 'signup/registration.php';
    }

    private function sendMail($form, $user_id){

        ob_start();
        Context::getInstance()->getTemplateService()->render("common/_approval_email", compact('form', 'user_id'));

        $mail = ob_get_clean();

        $mail_service = Context::getInstance()->getAdminMailService();

        $mail_service->contentType = "text/html";
        $mail_service->from = 'no-reply@'.$_SERVER['SERVER_NAME'];

        $mail_service->sendToAll('Approval request', $mail);

    }

} 
