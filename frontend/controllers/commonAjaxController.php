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
            || !isset($_POST['to_name'])
            || !isset($_POST['subject'])
            || !isset($_POST['body'])
        ){
            $this->forward404();
        }

        $fromValue = $_POST['from'];
        $toValue = $_POST['to'];

        $subject = stripslashes($_POST['subject']);
        $body = stripslashes($_POST['body']);

        $cc_me = $_POST['cc_me'];
        $to_name = stripslashes($_POST['to_name']);

        $fromUser = $this->api->getUserProfile($fromValue);
        $from = $fromUser['email'];

        $toUser = $this->api->getUserProfile($toValue);
        $to = $toUser['email'];

        $manager = Context::getInstance()->getMailService();
        $manager->reset();
        $array_state   = $fromUser['profileData']['state'];   // simplify the 3 dimensional array
        $array_country = $fromUser['profileData']['country'];
        // these are select fields with only one value 
        if (is_array($array_state)) {
        	foreach ($array_state AS $This_Value) {
        		$user_state = $This_Value;
        	}
        }
        if (is_array($array_country)) {
        	foreach ($array_country AS $This_Value) {
        		$user_country = $This_Value;
        	}
        }
		// Candidate to Organization 
        if (Constants::ROLE_CANDIDATE == $fromUser['role']) {
        	unset($response);
            // $body = "Message sent from: ".$fromUser['profileData']['first_name'].' '.$fromUser['profileData']['last_name']."\n".$body;
            $body1 = "Notice from MissionNext: \n";
            $body1 .= "This is a message sent from Candidate: ".$fromUser['profileData']['first_name'].' '.$fromUser['profileData']['last_name']."\n";
            $body1 .= "Email Address: ".$fromUser['profileData']['email']."\n";
            $body1 .= "Location: ".$user_state.' '.$user_country."\n";
        	if ($fromUser['profileData']['cell_phone']) {
        		$body1 .= "Best Phone: ".$fromUser['profileData']['cell_phone']."\n";
        	}
        	// $body1 .= json_encode($fromUser)."\n"; // if used, captures entire user JSON profile; unscramble at http://freeonlinetools24.com/json-decode
        	$body1 = $body1."\n".$body;
        	$response = $manager->send($to, $subject, $body1);
        }
        // CC to Candidate if Copy Me box is checked 
        if ('copy' == $cc_me && Constants::ROLE_CANDIDATE == $fromUser['role']) {
            unset($response);
            $body2 = "Notice: \n";
            $body2 .= "You sent a message to: ".$toUser['profileData']['organization_name']."\n";
            $body2 .= "Key Contact Name: ".$toUser['profileData']['first_name'].' '.$toUser['profileData']['last_name']."\n";
            $body2 .= "Key Contact Phone: ".$toUser['profileData']['key_contact_phone']."\n";
            $body2 .= "Email Address: ".$toUser['profileData']['email']."\n";
            $message = $body2."\n - - - - - - - - - - - - - - \n".$body;

            $response = $manager->send($from, $subject, $message);
        }
        // Organization to Candidate 
        if (Constants::ROLE_ORGANIZATION == $fromUser['role']) {
            $body3 = "Notice from MissionNext: \n";
            $body3 .= "You have a message from partner organization: ".$fromUser['profileData']['organization_name']."\n";
            $body3 .= "Key Contact Name: ".$fromUser['profileData']['first_name'].' '.$fromUser['profileData']['last_name']."\n";
            $body3 .= "Key Contact Phone: ".$fromUser['profileData']['key_contact_phone']."\n";
            $body3 .= "Email Address: ".$fromUser['profileData']['email']."\n";
            // $body3 .= json_encode($fromUser)."\n"; // if used, captures entire user JSON profile; unscramble at http://freeonlinetools24.com/json-decode
        	$body3 .= "Message: " . "\n - - - - - - - - - - - - - - \n".$body;
        	$body = $body3. "\n - - - - - - - - - - - - - - \n"."(Do not reply directly to this note. Respond to ".$fromUser['profileData']['organization_name']." using the email address shown above.)\n";
        	$response = $manager->send($to, $subject, $body);
       	}
        // CC to Organization if Copy Me box is checked 
        if ('copy' == $cc_me && Constants::ROLE_ORGANIZATION == $fromUser['role']) {
        	unset($response);
            $body4 = "Notice: \n";
            $body4 .= "You sent a message to Candidate: ".$toUser['profileData']['first_name'].' '.$toUser['profileData']['last_name']."\n";
            $body4 .= "Email Address: ".$toUser['profileData']['email']."\n";
            $body4 .= "Location: ".$user_state.' '.$user_country."\n\n";
        	$body4 .= "That message reads: " . "\n - - - - - - - - - - - - - - \n";
        	$message = $body4."\n".$body;
            $response = $manager->send($from, $subject, $message);
        }

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