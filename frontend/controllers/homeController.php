<?php


namespace MissionNext\frontend\controllers;


use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;
use MissionNext\lib\form\Form;
use MissionNext\lib\form\UserForm;

class homeController extends AbstractLayoutController {

    public $layout = 'sidebarLayout.php';

    public function index(){

        if (!$this->profileCompleted) {
            $this->api->deactivateUserApp($this->userId);
            $this->redirect('/profile');
        }

        $this->app_key = Context::getInstance()->getApiManager()->publicKey;
        $this->name = Context::getInstance()->getUser()->getName();

        $this->matching = $this->api->checkQueue($this->userId);

        $this->links = Context::getInstance()->getConfig()->get('links');
    }

    public function wpProfile(){

        $this->form = new UserForm($this->api, wp_get_current_user(), $this->user, $this->userId);

        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'profile'){

            $this->form->changedFields = $this->getChangedFields($this->form->groups, @$_POST[$this->form->getName()]);

            $this->form->bind($_POST[$this->form->getName()]);

            $this->form->save();

            if($this->form->isValid()){
                $this->setMessage('notice' , __('Your account info saved successfully!', Constants::TEXT_DOMAIN));
                $this->redirect($_SERVER['REQUEST_URI']);
            }
        }
    }

    public function updateAvatar(){

        if(!isset($_FILES['image'])){
            $this->forward404();
        }

        $error = Context::getInstance()->getAvatarManager()->updateAvatar(
            Context::getInstance()->getUser()->getWPUser()->ID,
            $_FILES['image']
        );

        $this->redirect(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/dashboard');
    }

    public function deleteAvatar(){

        Context::getInstance()->getAvatarManager()->avatar_delete(Context::getInstance()->getUser()->getWPUser()->ID);

        $this->redirect(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/dashboard');
    }

    public function checkQueue(){

        echo json_encode($this->api->checkQueue($this->userId));

        return false;
    }
}
