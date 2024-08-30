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

                $wp_user_id = wp_get_current_user()->ID;
                $meta_value = get_user_meta($wp_user_id, 'thank_you_page', true);
        
                echo "<!-- ";
                if (!$meta_value) {
                    echo "<pre>";
                    print_r('No thank you page showed');
                    echo "</pre>";
                    update_user_meta($wp_user_id, 'thank_you_page', 1);
                    echo " -->";
                } else {
                    echo "<pre>";
                    print_r('Thank you page showed');
                    echo "</pre>"; 
                    echo " -->";
                    $this->setMessage('notice', __("<p style='font-size: 15px; font-weight: bold; color='#ffffff'>Thank you for completing your Profile. Select <a href='/dashboard'>My Dashboard</a> to continue.</p>", Constants::TEXT_DOMAIN), 1);
                    $this->redirect($_SERVER['REQUEST_URI']);
                }
            }
        } else {
            $this->form->prepareForValidation();

            $this->form->changedFields = $this->getChangedFields($this->form->groups, $this->form->getData());

            $this->form->save();

            if($this->form->isValid() && isset($_GET['requestUri'])) {

                $this->redirect($_GET['requestUri']);
            }
        }

    	\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/country', 'country.js', [], false, true);
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
