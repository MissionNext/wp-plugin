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

        /* Simulation of the form save to get validation information */
        $this->form = new Form($this->api, $this->userRole, 'profile', $this->userId);
        $this->form->saveLater = null;
        $this->form->changedFields = [
            'status' => 'checked',
            'changedFields' => []
        ];
        $data = [];
        foreach ($this->form->groups as $key => $value) {
            $groupData = $value->data;
            foreach ($value->fields as $fieldKey => $fieldValue) {
                if ($fieldValue->field['type'] == 'file') {
                    unset($groupData[$fieldKey]);
                }
            }
            $data[$key] = $groupData;
        }
        $this->form->data = $data;
        $this->form->save();

        if ($this->form->hasErrors()) {
            $this->api->deactivateUserApp($this->userId);
            $this->redirect('/profile');
        }

        $this->app_key = Context::getInstance()->getApiManager()->publicKey;
        $this->name = Context::getInstance()->getUser()->getName();

        $this->subscriptions = $this->api->getSubscriptionsForUser($this->userId);

        $this->matching = $this->api->checkQueue($this->userId);

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

        if($this->userRole == Constants::ROLE_CANDIDATE){
            $orgFavorites = $this->api->getFavorites($this->userId, 'organization');
            $jobFavorites = $this->api->getFavorites($this->userId, 'job');
            $this->favoritesCount = count($orgFavorites) + count($jobFavorites);

            $this->inquiriesCount = $this->getInquiriesViews();
        } else {
            $favorites = $this->api->getFavorites($this->userId, 'candidate');
            $this->favoritesCount = count($favorites);

            $this->inquiriesCount = $this->getInquiriesViews();

            $affiliates = $this->api->getAffiliates($this->userId, 'any');
            $this->affiliatesCount = count($affiliates);
        }

        $config = Context::getInstance()->getConfig();
        $links = $config->get('links');

        $this->apps = [
            2   => $links[0],
            3   => $links[1],
            4   => $links[2],
            5   => $links[3],
            6   => $links[4],
            9   => $links[5],
            10  => $links[6],
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

        echo json_encode($this->api->checkQueue($this->userId));

        return false;
    }

    private function getInquiriesViews(){
        $inquiries = array();

        switch ($this->userRole) {
            case "candidate" : {

                $tmpInquiries = $this->api->getInquiredJobs($this->userId);
                $inquiries = [];
                foreach ($tmpInquiries as $jobItem) {
                    $inquiries[] = $jobItem;
                }
                break;
            }
            case "organization" : {
                $inquiries = $this->api->getInquiredCandidatesForOrganization($this->userId);
                break;
            }
            case "agency" : {
                $inquiries = $this->api->getInquiredCandidatesForAgency($this->userId);
                break;
            }
        }

        return count($inquiries);
    }
}
