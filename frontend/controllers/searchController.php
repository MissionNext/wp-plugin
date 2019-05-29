<?php


namespace MissionNext\frontend\controllers;


use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;
use MissionNext\lib\form\Form;
use MissionNext\lib\form\SearchForm;
use MissionNext\lib\SiteConfig;

class searchController extends AbstractLayoutController {

    public function getSaved($params){

        if(!isset($params[0]) || !in_array($params[0], array(
            Constants::ROLE_CANDIDATE,
            Constants::ROLE_ORGANIZATION,
            Constants::ROLE_AGENCY,
            Constants::ROLE_JOB
        ))){
            $this->forward404();
        }

        $this->role = $params[0];
        $this->saved = $this->api->getSavedSearches($this->userRole, $this->role, $this->userId);

        $this->layout = false;
        return "search/_ajax_search_saved.php";

    }

    public function candidate(){

        $this->role = 'candidate';

        $this->processSearch();

    }

    public function organization(){

        $this->role = 'organization';

        $this->processSearch();

    }

    public function agency(){

        if(!SiteConfig::isAgencyOn()){
            $this->forward404();
        }

        $this->role = 'agency';

        $this->processSearch();

    }

    public function job(){

        $this->role = 'job';

        $this->processSearch();

        $inquiredJobs = $this->api->getInquiredJobs($this->userId);
        if ($inquiredJobs) {
            $filteredInquiredJobs = array_map(function($item){
                return $item['id'];
            }, $inquiredJobs);
        }

        if (isset($this->result)) {
            foreach ($this->result as $key => $job) {
                if (isset($filteredInquiredJobs) && in_array($job['id'], $filteredInquiredJobs)) {
                    $this->result[$key]['inquired'] = true;
                }
            }
        }
    }

    public function addSaved(){

        if(!isset($_POST['data'])
            || !isset($_POST['name'])
            || !isset($_POST['role_from'])
            || !isset($_POST['role_to'])
        ){
            $this->forward404();
        }

        $response = $this->api->addSavedSearch($_POST['role_from'], $_POST['role_to'], $this->userId, array( 'search_name' => $_POST['name'], 'search_data' => $_POST['data']));

        echo json_encode($response);

        return false;
    }

    public function deleteSaved(){

        if(!isset($_POST['id'])){
            $this->forward404();
        }

        $response = $this->api->deleteSavedSearch($_POST['id'], $this->userId);

        echo json_encode($response);

        return false;
    }

    private function processSearch(){
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $this->page = $page;

        $this->form = new SearchForm($this->api, $this->userRole, $this->userId, $this->role, 'search');
        $this->searches = $this->api->getSavedSearches($this->userRole, $this->role, $this->userId);

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $this->search = array();
            $this->result = array();

            if(isset($_POST['saved'])){

                foreach($this->searches as $item){
                    if($item['id'] == $_POST['saved']){
                        $resultArray = $this->api->search($this->role, $this->userRole, $this->userId , $item['data'], $page);
                        $this->result = $resultArray['results'];
                        $this->pages = $resultArray['count'];

                        $this->search = $item['data'];
                        $this->search_name = $item['search_name'];

                        $this->form->setSearchDefaults($item['data']);
                    }
                }

            } else {

                $this->form->bind(@$_POST[$this->form->getName()]);

                $this->form->validateJobTitle();

                if ($this->form->isValid()) {
                    $resultArray = $this->form->search($page);
                    $this->pages = $resultArray['count'];
                    $this->result = $resultArray['results'];

                    foreach ($this->result as &$item) {
                        if (Constants::ROLE_AGENCY == $item['role']) {
                            if (!$item['org_name']) {
                                $item['org_name'] = $item['profileData']['last_name']." ".$item['profileData']['first_name'];
                            }
                        }
                    }
                    $this->search = $this->form->searchRequest;

                    if(empty($this->search['profileData'])){
                        unset($this->search['profileData']);
                    }
                }

            }

            if (Constants::ROLE_AGENCY == $this->userRole) {
                $default_folder_id = \MissionNext\lib\SiteConfig::getDefaultFolder($this->role);
                $foldersApi = \MissionNext\lib\core\Context::getInstance()->getApiManager()->getApi()->getUserFolders($this->role, $this->userId);
                $default_folder = '';
                foreach($foldersApi as $folder) {
                    if ($folder['id'] == $default_folder_id) {
                        $default_folder = $folder['title'];
                        break;
                    }
                }

                $this->additional_info = $this->api->getMetaInfoForAgency($this->userId, $this->role);
                $this->multipleResults = $multipleResults = [];
                foreach ($this->additional_info['affiliates'] as $org) {
                    $multipleResults[$org['id']] = [];
                }

                foreach ($this->result as &$item) {
                    foreach ($this->additional_info['own_notes'] as $own_note) {
                        if ($own_note['user_id'] == $item['id'] && !empty($own_note['notes'])) {
                            $item['meta']['own_note'] = htmlentities($own_note['notes']);
                        }
                    }

                    foreach ($this->additional_info['notes'] as $note) {
                        if ($note['user_id'] == $item['id'] && !empty($note['notes'])) {
                            $item['meta']['notes'][$note['note_owner']] = [
                                'org_name'  => $this->additional_info['affiliates'][$note['note_owner']]['name'],
                                'note_text' => htmlentities($note['notes'])
                            ];
                        }
                    }

                    foreach ($this->additional_info['favorites'] as $fav) {
                        if ($fav['target_id'] == $item['id']) {
                            $item['meta'][$fav['favorite_owner']]['fav'] = true;
                        }
                    }
                    foreach ($this->additional_info['folders'] as $folder) {
                        if ($folder['user_id'] == $item['id'] && $default_folder != $folder['folder']) {
                            $item['meta'][$folder['folder_owner']]['folder'] = $folder['folder'];
                        }
                    }

                    foreach ($this->additional_info['affiliates'] as $org) {
                        $itemData = $item;
                        $itemData['meta'] = null;
                        if (isset($item['meta'])) {
                            $itemData['folder'] = isset($item['meta'][$org['id']]['folder']) ? $item['meta'][$org['id']]['folder'] : null;
                            $itemData['favorite'] = isset($item['meta'][$org['id']]['fav']) ? $item['meta'][$org['id']]['fav'] : null;
                            $itemData['note'] = isset($item['meta']['own_note']) ? $item['meta']['own_note'] : null;
                            $itemData['notes'] = isset($item['meta']['notes']) ? $item['meta']['notes'] : [];
                        }

                        $multipleResults[$org['id']][] = $itemData;
                    }
                }

                if (count($this->additional_info['affiliates']) > 0 ) {
                    $this->multipleResults = $multipleResults;
                } else {
                    $this->multipleResults = [ 1 => $this->result ];
                    $this->additional_info['affiliates'] = [ 1 => [ 'id' => '1', 'name' => 'Fake' ]];
                }
            }
        }
    }

} 