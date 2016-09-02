<?php


namespace MissionNext\frontend\controllers;


use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;
use MissionNext\lib\form\UserForm;

class homeController extends AbstractLayoutController {

    public $layout = 'sidebarLayout.php';

    public function index(){

        $this->app_key = Context::getInstance()->getApiManager()->publicKey;
        $this->name = Context::getInstance()->getUser()->getName();

        $this->subscriptions = $this->api->getSubscriptionsForUser($this->userId);

        $this->matching = $this->api->checkQueue();

        $configs = $this->api->getSubscriptionConfigs();
        $candidateSubscriptions = [];
        $blockedIndexes = [];
        foreach($configs as $app){
            $app_block = false;
            foreach($app['configs'] as $app_options){
                if ("block_website" == $app_options['key']){
                    $app_block = $app_options['value'];
                }
            }
            if (!$app_block) {
                foreach($app['sub_configs'] as $sub_config){
                    if($sub_config['role'] == Constants::ROLE_CANDIDATE && $sub_config['price_year'] == 0){
                        $candidateSubscriptions[$app['id']] = $sub_config;
                        $candidateSubscriptions[$app['id']]['app_name'] = $app['name'];
                    }
                }
            } else {
                $blockedIndexes[] = $app["id"];
            }
        }

        $subsIndexes = [];
        $counter = 0;
        foreach($this->subscriptions as $sub_item){
            unset($candidateSubscriptions[$sub_item['app_id']]);

            if(in_array($sub_item['app_id'], $blockedIndexes)){
                $subsIndexes[] = $counter;
            }
            $counter++;
        }

        foreach($subsIndexes as $val){
            unset($this->subscriptions[$val]);
        }

        $this->candidateSubs = $candidateSubscriptions;

        $this->apps = [
            1   => 'http://missionfinder.net',
            2   => 'http://finishersproject.missionfinder.net',
            3   => 'http://explorenext.missionfinder.net',
            4   => 'http://journeydeepens.missionfinder.net',
            5   => 'http://bammatch.missionfinder.net',
            6   => 'http://missionteach.missionfinder.net',
        ];
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

        echo json_encode($this->api->checkQueue());

        return false;
    }
}