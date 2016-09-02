<?php


namespace MissionNext\lib;


use MissionNext\lib\core\Context;

class AdminMailService extends MailService {

    private $emails;

    public function __construct(){

        $api = Context::getInstance()->getApiManager()->getApi();

        if($api) {
            $this->emails = Context::getInstance()->getApiManager()->getApi()->getAdministratorEmails();
        }

    }

    public function sendToAll($subject, $message, $headers = '', $attachments = array(), $reset = true){

        foreach($this->emails as $email){
            $this->send($email, $subject, $message, $headers, $attachments, $reset);
        }

    }

} 