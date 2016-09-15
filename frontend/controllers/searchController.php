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

        /*if ($this->userRole == Constants::ROLE_AGENCY) {
            $newResults = [];
            foreach ($this->result as $item) {
                if ($newResults[$item['id']]) {
                    $newResults[$item['id']]['notes'] .= '<br />'.$item['notes'];
                } else {
                    $newResults[$item['id']] = $item;
                }
            }

            $this->result = $newResults;
        }*/
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

        $this->form = new SearchForm($this->api, $this->userRole, $this->userId, $this->role, 'search');
        $this->searches = $this->api->getSavedSearches($this->userRole, $this->role, $this->userId);

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $this->search = array();
            $this->result = array();

            if(isset($_POST['saved'])){

                foreach($this->searches as $item){
                    if($item['id'] == $_POST['saved']){
                        $this->result = $this->api->search($this->role, $this->userRole, $this->userId , $item['data']);
                        $this->search = $item['data'];

                        $this->form->setSearchDefaults($item['data']);
                    }
                }

            } else {

                $this->form->bind(@$_POST[$this->form->getName()]);

                $this->form->validateJobTitle();

                if ($this->form->isValid()) {
                    $this->result = $this->form->search();

                    foreach ($this->result as &$item) {
                        if (Constants::ROLE_AGENCY == $item['role']) {
                            if (!$item['org_name']) {
                                $item['org_name'] = $item['org_name'] = $item['profileData']['agency_full_name'];
                            }
                        }
                    }
                    $this->search = $this->form->searchRequest;

                    if(empty($this->search['profileData'])){
                        unset($this->search['profileData']);
                    }
                }

            }
        }
    }

} 