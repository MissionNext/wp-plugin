<?php


namespace MissionNext\frontend\controllers;


use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;
use MissionNext\lib\form\Form;
use MissionNext\lib\form\JobForm;

class jobController extends AbstractLayoutController {

    public function index(){

        $this->layout = 'sidebarLayout.php';

        if($this->userRole != 'organization'){
            $this->redirect(wp_login_url(home_url($_SERVER['REQUEST_URI'])));
        }

        $jobs = $this->api->findJobsByOrgId($this->userId);
        uasort($jobs, array($this, 'sortJobs'));

        $this->jobs = $jobs;
    }

    public function show($params){

        $id = $params[0];

        $job = $this->api->getJob($id);

        if(!$job){
            $this->forward404();
        }

        $profile = $this->api->getJobProfile($id);
        $profile = $profile['profileData'];

        $groups = $this->api->getForm('job', 'job');

        $job['profile'] = $this->prepareDataToShow($profile, $groups);

        if($this->userRole == 'candidate'){
            //favorites
            $favorites = $this->api->getFavorites($this->userId, 'job');

            $is_favorite = false;

            if($favorites){
                foreach($favorites as $favorite){
                    if($favorite['target_id'] == $id){
                        $is_favorite = $favorite['id'];
                        break;
                    }
                }
            }

            $job['favorite'] = $is_favorite;

            //Inquires
            $inquires = $this->api->getInquiredJobs($this->userId);
            $is_inquired = false;

            if($inquires){
                foreach($inquires as $inquire){
                    if($inquire['id'] == $id){
                        $is_inquired = $inquire['id'];
                        break;
                    }
                }
            }

            $job['inquire'] = $is_inquired;
        }

        $this->job = $job;

        $fields = $this->api->getModelFields('job');
        $this->fields = array();

        $organizationProfile = $this->api->getUserProfile($job['organization']['id']);
        $this->job['org_name'] = $organizationProfile['profileData']['organization_name'];

        foreach($fields as $field){
            $this->fields[$field['symbol_key']] = $field;
        }
    }

    public function newJob(){

        if($this->userRole != 'organization'){
            $this->redirect(wp_login_url(home_url($_SERVER['REQUEST_URI'])));
        }

        $this->jobs = $this->api->findJobs(array('organization_id' => $this->userId));

        uasort($this->jobs, array($this, 'sortJobs'));

        $this->form = new JobForm($this->api, $this->userId, isset($_GET['from']) ? $_GET['from'] : null, true);

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $this->form->bind(@$_POST[$this->form->getName()], $_FILES);

            $this->form->save();

            if($this->form->isValid()){
                $this->redirect(home_url('/job'));
            }

        }
    }

    public function edit($params){

        $this->form = new JobForm($this->api, $this->userId, $params[0]);

        if(!$this->form->job && $this->userRole != 'organization' || $this->form->job['organization_id'] != $this->userId ){
            $this->forward404();
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->form->changedFields = $this->getChangedFields($this->form->groups, @$_POST[$this->form->getName()]);

            $this->form->bind(@$_POST[$this->form->getName()], $_FILES);

            $this->form->save();

            if($this->form->isValid()){
                $this->redirect(home_url('/job'));
            }

        }
    }

    public function delete($params){

        if($this->userRole != 'organization'){
            $this->forward404();
        }

        $this->api->deleteJob($params[0], $this->userId);

        $this->redirect(home_url('/job'));
    }

    private function prepareDataToShow($profile, $groups){
        $result = array();

        uasort($groups, array($this, 'sortGroups'));

        foreach($groups as $group){

            uasort($group['fields'], array($this, 'sortFields'));

            $fields = $group['fields'];
            $group['fields'] = array();

            foreach($fields as $field){

                $value = isset($profile[$field['symbol_key']])?$profile[$field['symbol_key']]:null;

                if($value){
                    if(is_array($value)){
                        foreach($value as $key => $item){
                            if(strpos($item, Constants::NO_PREFERENCE_SYMBOL) === 0){
                                $value[$key] = substr($item, 3);
                            }
                        }
                    } else {
                        if(strpos($value, Constants::NO_PREFERENCE_SYMBOL) === 0){
                            $value = substr($value, 3);
                        }
                    }
                }

                $specChars = strpos($field['default_name'], Constants::JOB_TITLE_LIMITER);
                if ($specChars !== false) {
                    $field['default_name'] = substr($field['default_name'], 0, $specChars);
                }

                $group['fields'][$field['symbol_key']] = array(
                    'value' => $value,
                    'symbol_key' => $field['symbol_key'],
                    'label' => $field['name']?$field['name']:$field['default_name'],
                    'type' => $field['type']
                );
            }

            $result[$group['symbol_key']] = $group;

        }

        return $result;
    }

    private function sortGroups($a, $b){
        return $a['order'] < $b['order'] ? -1 : 1;
    }

    private function sortFields($a, $b){
        return $a['order'] < $b['order'] ? -1 : 1;
    }

    private function sortJobs($a, $b){
        return $a['id'] < $b['id'] ? -1 : 1;
    }
} 