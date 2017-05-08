<?php


namespace MissionNext\frontend\controllers;


use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;

class commonAjaxController extends AbstractLayoutController {

    public function beforeAction(){
        parent::beforeAction();

        if(!$this->user){
            $this->redirect(wp_login_url(home_url($_SERVER['REQUEST_URI'])));
        }

    }

    public function folderChange(){
        if($_SERVER['REQUEST_METHOD'] != 'POST'
          || !$this->userId
          || !isset($_POST['role'])
          || !isset($_POST['id'])
          || !isset($_POST['folder'])
        ){
            $this->forward404();
        }

        $response = $this->api->changeFolder($_POST['id'], $_POST['role'], $this->userId, $_POST['folder']);

        echo json_encode($response);

        return false;
    }

    public function noteChange(){
        if($_SERVER['REQUEST_METHOD'] != 'POST'
            || !$this->userId
            || !isset($_POST['role'])
            || !isset($_POST['id'])
            || !isset($_POST['note'])
        ){
            $this->forward404();
        }

        $response = $this->api->changeNote($_POST['id'], $_POST['role'], $this->userId, $_POST['note']);

        echo json_encode($response);

        return false;
    }

    public function sendEmail(){

        if($_SERVER['REQUEST_METHOD'] != 'POST'
            || !$this->userId
            || !isset($_POST['from'])
            || !isset($_POST['to'])
            || !isset($_POST['subject'])
            || !isset($_POST['body'])
        ){
            $this->forward404();
        }

        $manager = Context::getInstance()->getMailService();

        $options = get_option('sp_settings');
        $manager->from = (isset($options['from_email']) && !empty($options['from_email'])) ? $options['from_email'] : $_POST['from'];
        $response = $manager->send($_POST['to'], $_POST['subject'], $_POST['body']);

        echo json_encode($response);

        return false;
    }

    /**
     * Функция выбора страны.
     */
    public function selectCountry()
    {
        if(is_post())
        {
            $name = element('name', $_POST);
            if(!empty($name))
            {
                $file = MN_ROOT_DIR . '/resources/json/countries.json';
                if(is_file($file))
                {
                    $countries = json_decode(file_get_contents($file), true);
                    if(!empty($countries))
                    {
                        $lang_id = Context::getInstance()->getLocalizationManager()->getCurrentLangId();

                        $states  = array();
                        $choices = array();

                        $languages = $this->api->getFieldsLanguages($this->userRole);
                        if(!empty($languages))
                        {
                            foreach($languages as $language)
                            {
                                foreach($language['fields'] as $field)
                                {
                                    if($language['lang_id'] == 0)
                                    {
                                        if($field['symbol_key'] == 'state')
                                        {
                                            $states = array_combine($field['dictionary_id'], $field['choices']);
                                        }
                                    }

                                    if($language['lang_id'] == $lang_id)
                                    {
                                        if($field['symbol_key'] == 'state')
                                        {
                                            $choices = array_combine($field['dictionary_id'], $field['choices']);
                                        }
                                    }
                                }
                            }
                        }
                        unset($languages);

                        foreach($countries as $country)
                        {
                            if($country['name'] == $name)
                            {
                                if(!empty($country['states']))
                                {
                                    foreach($country['states'] as $s => $state)
                                    {
                                        if(in_array($state['name'], $states))
                                        {
                                            $value = element(array_search($state['name'], $states), $choices);

                                            if(!empty($value))
                                            {
                                                $country['states'][$s]['name'] = $value;
                                            }

                                            $country['states'][$s]['value'] = $state['name'];
                                        }
                                    }

                                    echo json_encode($country['states'], TRUE);
                                }
                            }
                        }
                    }
                    unset($countries);
                }
                unset($file);
            }
            unset($name);
        }

        return false;
    }
}

/* End of file commonAjaxController.php */
/* Location: ./frontend/controllers/commonAjaxController.php */