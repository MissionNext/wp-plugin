<?php


namespace MissionNext\frontend\controllers;

use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;
use MissionNext\lib\form\Form;
use MissionNext\lib\UserLib;

class profileController extends AbstractLayoutController {

    public $layout = 'sidebarLayout.php';

    public function index(){

        $this->form = new Form($this->api, $this->userRole, 'profile', $this->userId);

        $this->form->saveLater = @$_POST['savelater'];

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $this->form->changedFields = $this->getChangedFields($this->form->groups, @$_POST[$this->form->getName()]);

            $this->form->bind(@$_POST[$this->form->getName()], $_FILES);

            $this->form->save();

            if($this->form->isValid()){

                $this->setMessage('notice', __("Profile saved", Constants::TEXT_DOMAIN), 1);
                $this->redirect($_SERVER['REQUEST_URI']);
            }
        }

        if( $this->secured && !in_array($this->context->getApiManager()->publicKey, $this->user['app_names']) &&
            $this->route['controller'] == 'profile' && $this->route['action'] == 'index' && !current_user_can('manage_options')
        ){
            $this->profileCompleted = false;
        } else {
            $this->profileCompleted = true;
        }


	\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/country', 'country.js');
    }

    /**
     * Страница редактирования presentation контента.
     */
    public function presentation()
    {
        if($this->userRole == Constants::ROLE_CANDIDATE)
        {
            $this->forward404();
        }

        Context::getInstance()->getUser()->getWPUser()->add_cap('upload_files');

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if(isset($_POST['presentation']))
            {
                $presentation = nl2br($_POST['presentation']);
                $response = Context::getInstance()->getUserConfigManager()->save('presentation', $presentation);

                if ($response) {
                    $this->setMessage('notice', 'Presentation saved', Constants::TEXT_DOMAIN);
                }
            }
        }

        $this->presentation = Context::getInstance()->getUserConfigManager()->get('presentation');
    }


}
