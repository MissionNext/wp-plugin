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

                $this->setMessage('notice', __("Profile saved - Continue at the Dashboard", Constants::TEXT_DOMAIN), 1);
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


	\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/country', 'country.js', array( 'jquery' ));
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

    public function getProfileFile($params) {
        $config = Context::getInstance()->getConfig();

        $filename = $config->get('api_uploads_dir').'/'.$params[0];

        if(file_exists($filename)){

            //Get file type and set it as Content Type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            header('Content-Type: ' . finfo_file($finfo, $filename));
            finfo_close($finfo);

            //Use Content-Disposition: attachment to specify the filename
            header('Content-Disposition: attachment; filename='.basename($filename));

            //No cache
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');

            //Define file size
            header('Content-Length: ' . filesize($filename));

            ob_clean();
            flush();
            readfile($filename);
            exit;
        }

        return false;
    }
}
