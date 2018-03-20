<?php


namespace MissionNext\frontend\controllers;


use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;
use MissionNext\lib\SiteConfig;
use MissionNext\lib\UserLib;

class affiliatesController extends AbstractLayoutController {

    public $layout = 'sidebarLayout.php';

    public function beforeAction(){
        parent::beforeAction();

        if(!SiteConfig::isAgencyOn()){
            $this->forward404();
        }
    }

    public function affiliates(){

        if($this->userRole != 'agency' && $this->userRole != 'organization'){
            $this->forward404();
        }

        $this->role = $this->userRole == 'agency' ? 'organization' : 'agency';

        $affiliates = $this->api->getAffiliates($this->userId, 'any');

        $this->affiliates = array(
            'approved' => array(),
            'pending' => array()
        );

        foreach($affiliates as $aff){
            $this->affiliates[$aff['status']][] = $aff;
        }
    }

    public function jobs(){

        if($this->userRole != 'agency'){
            $this->forward404();
        }

        $this->jobs = $this->api->getAffiliateJobs($this->userId);
    }

    public function requestAffiliate(){
        if($_SERVER['REQUEST_METHOD'] != 'POST'
            || !$this->userId
            || !isset($_POST['id'])
        ){
            $this->forward404();
        }

        $response = $this->api->requestAffiliate($this->userId, $_POST['id']);

        if($response){

            $user_from = $this->user;
            $user_to = $this->api->getUserProfile($_POST['id']);
            $mail_service = Context::getInstance()->getMailService();

            $message = UserLib::replaceTokens(Context::getInstance()->getLocalizationManager()->getLocalizedEmail('affiliate_request.txt'), $user_from, $user_to);

            $mail_service->from = 'no-reply@'.$_SERVER['SERVER_NAME'];
            $mail_service->send($user_to['email'], "Affiliate request", $message);
            $this->logger('email', 'sent', "Affiliate request from $user_from to $user_to");
        }

        echo json_encode($response);

        return false;
    }

    public function approveAffiliate(){
        if($_SERVER['REQUEST_METHOD'] != 'POST'
            || !$this->userId
            || !isset($_POST['requester_id'])
            || !isset($_POST['approver_id'])
        ){
            $this->forward404();
        }

        $response = $this->api->approveAffiliate($_POST['requester_id'], $_POST['approver_id']);

        if($response){
            $user_from = $this->user;
            $user_to = $this->api->getUserProfile($_POST['requester_id']);
            $mail_service = Context::getInstance()->getMailService();

            $message = UserLib::replaceTokens(Context::getInstance()->getLocalizationManager()->getLocalizedEmail('affiliate_approve.txt'), $user_from, $user_to);

            $mail_service->from = 'no-reply@'.$_SERVER['SERVER_NAME'];
            $mail_service->send($user_to['email'], "Affiliate approval", $message);
            $this->logger('email', 'sent', "Affiliate approval. From $user_from to $user_to");
        }

        echo json_encode($response);

        return false;
    }

    public function cancelAffiliate(){

        if($_SERVER['REQUEST_METHOD'] != 'POST'
            || !$this->userId
            || !isset($_POST['requester_id'])
            || !isset($_POST['approver_id'])
        ){
            $this->forward404();
        }

        $response = $this->api->cancelAffiliate($_POST['requester_id'], $_POST['approver_id']);

        if($response){

            $user_from = $this->user;
            $user_to = $this->api->getUserProfile($this->userId == $_POST['requester_id'] ? $_POST['approver_id'] : $_POST['requester_id']);
            $mail_service = Context::getInstance()->getMailService();

            $message = UserLib::replaceTokens(Context::getInstance()->getLocalizationManager()->getLocalizedEmail('affiliate_cancel.txt'), $user_from, $user_to);

            $mail_service->from = 'no-reply@'.$_SERVER['SERVER_NAME'];
            $mail_service->send($user_to['email'], "Affiliate cancellation", $message);
            $this->logger('email', 'sent', "Affiliate cancellation. From $user_from to $user_to");

        }

        echo json_encode($response);

        return false;
    }
}