<?php


namespace MissionNext\frontend\controllers;

use MissionNext\lib\Constants;

class matchesController extends AbstractLayoutController {

    const RATE = 50;
    const UPDATE_YEAR = 2013;

    public $layout = 'layout.php';

    public function jobForCandidate(){

        if($this->userRole != 'candidate'){
            $this->forward404();
        }

        $page = isset($_GET['page'])?$_GET['page']:1;
        $new_rate = isset($_GET['rate'])?$_GET['rate']:0;

        if($new_rate)
            $rate = $new_rate;
        else {
            $rate = $this->api->getUserConfigsElement('job_can_rate', $this->userId);
            if(!$rate)
                $rate = self::RATE;
            else
                $rate = $rate['value'];
        }

        $jobs = $this->api->getMatchedJobsForCandidate($this->userId, compact('page', 'rate'));
        $jobs = $jobs?$jobs:array();

        if($this->userRole == 'candidate'){

            $favs = $this->api->getFavorites($this->userId, 'job');
            $inquires = $this->api->getInquiredJobs($this->userId);

            $date_limit = new \DateTime('now');
            $date_limit->modify("-6 months");
            $timelimit = $date_limit->getTimestamp();
            foreach($jobs as $key => $job){
                $organization = $this->api->getUserProfile($job['organization']['id']);
                $job['org_name'] = $organization['profileData']['organization_name'];

                $jobs[$key]['favorite'] = null;
                foreach($favs as $fav){
                    if($job['id'] == $fav['target_id']){
                        $jobs[$key]['favorite'] = $fav['id'];
                    }
                }

                $jobs[$key]['inquired'] = null;
                if($inquires){
                    $is_inquired = null;
                    foreach($inquires as $inquire){
                        if($inquire['id'] == $job['id']){
                            $is_inquired = $inquire['id'];
                            break;
                        }
                    }
                    $jobs[$key]['inquired'] = $is_inquired;
                }
                $job_update_time = strtotime($jobs[$key]['job_updated']);
                if (!$jobs[$key]['favorite'] && !$jobs[$key]['inquired'] && $job_update_time < $timelimit) {
                    unset($jobs[$key]);
                }
            }
        }

        uasort($jobs, array($this, 'sortByMatchingPercent' ));

        $this->jobs = $jobs;
        $this->page = $page;
        $this->pages = $jobs?$jobs[0]['totalPages']:1;
        $this->rate = $rate;

    }

    public function organizationForCandidate(){

        if($this->userRole != 'candidate'){
            $this->forward404();
        }

        $page = isset($_GET['page'])?$_GET['page']:1;
        $new_rate = isset($_GET['rate'])?$_GET['rate']:0;

        if($new_rate)
            $rate = $new_rate;
        else {
            $rate = $this->api->getUserConfigsElement('org_rate', $this->userId);
            if(!$rate)
                $rate = self::RATE;
            else
                $rate = $rate['value'];
        }

        $orgs = $this->api->getMatchedOrganizationsForCandidate($this->userId, compact('page', 'rate'));

        $orgs = $orgs?$orgs:array();

        if($this->userRole == 'candidate'){

            $favs = $this->api->getFavorites($this->userId, 'organization');

            foreach($orgs as $key => $org){
                $orgs[$key]['favorite'] = null;
                foreach($favs as $fav){
                    if($org['id'] == $fav['target_id']){
                        $orgs[$key]['favorite'] = $fav['id'];
                    }
                }
            }
        }

        uasort($orgs, array($this, 'sortByMatchingPercent' ));

        $this->organizations =$orgs;
        $this->page = $page;
        $this->pages = $orgs?$orgs[0]['totalPages']:1;
        $this->rate = $rate;
    }

    public function candidateForOrganization(){

        if($this->userRole != 'organization'){
            $this->forward404();
        }

        $page = isset($_GET['page'])?$_GET['page']:1;
        $new_rate = isset($_GET['rate'])?$_GET['rate']:0;
        $new_updates = isset($_GET['updates'])?$_GET['updates']:0;
        $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'matching_percentage';
        $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'desc';

        if($new_rate)
            $rate = $new_rate;
        else {
            $rate = $this->api->getUserConfigsElement('can_rate', $this->userId);
            if(!$rate)
                $rate = self::RATE;
            else
                $rate = $rate['value'];
        }

        if($new_updates)
            $updates = $new_updates;
        else {
            $updates = $this->api->getUserConfigsElement('last_login', $this->userId);

            if(!$updates)
                $updates = self::UPDATE_YEAR;
            else
                $updates = $updates['value'];
        }

        $candidates = $this->api->getMatchedCandidatesForOrganization($this->userId, compact('page', 'rate', 'updates', 'sort_by', 'order_by'));

        if($candidates == 'rematch')
            header('Location: /dashboard');

        $candidates = $candidates?$candidates:array();

        if($this->userRole == 'organization' || $this->userRole == 'agency'){

            $favs = $this->api->getFavorites($this->userId, 'candidate');

            foreach($candidates as $key => $candidate){
                $candidates[$key]['favorite'] = null;
                foreach($favs as $fav){
                    if($candidate['id'] == $fav['target_id']){
                        $candidates[$key]['favorite'] = $fav['id'];
                    }
                }
            }
        }

        //uasort($candidates, array($this, 'sortByMatchingPercent' ));

        $this->candidates =$candidates;
        $this->page = $page;
        $this->pages = $candidates?$candidates[0]['totalPages']:1;
        $this->rate = $rate;
        $this->updates = $updates;
        $this->sort_by = $sort_by;
        $this->order_by = $order_by;
    }

    public function candidateForJob($params){

        $this->job = $this->api->getJobProfile($params[0]);

        if( ($this->userRole != 'organization' && $this->userRole != 'agency') || !isset($params[0])){
            $this->forward404();
        }

        $page = isset($_GET['page'])?$_GET['page']:1;
        $new_rate = isset($_GET['rate'])?$_GET['rate']:0;
        $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'matching_percentage';
        $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'desc';

        if($new_rate)
            $rate = $new_rate;
        else {
            $rate = $this->api->getUserConfigsElement('can_job_rate', $this->userId);
            if(!$rate)
                $rate = self::RATE;
            else
                $rate = $rate['value'];
        }

        $user_id = $this->userId;
        $job_owner = $this->job['organization_id'];

        $candidates = $this->api->getMatchedCandidatesForJob($params[0], compact('page', 'rate', 'user_id', 'job_owner', 'sort_by', 'order_by'));

        $candidates = $candidates?$candidates:array();

        $this->candidates =$candidates;

        $this->page = $page;
        $this->pages = $candidates?$candidates[0]['totalPages']:1;
        $this->rate = $rate;
        $this->sort_by = $sort_by;
        $this->order_by = $order_by;
    }

    private function sortByMatchingPercent($a, $b){

        $a_prior = (isset($a['subscription']) && $a['subscription']['partnership'] == Constants::PARTNERSHIP_PLUS) ||
            (isset($a['organization']['subscription']) && $a['organization']['subscription']['partnership'] == Constants::PARTNERSHIP_PLUS);
        $b_prior = (isset($b['subscription']) && $b['subscription']['partnership'] == Constants::PARTNERSHIP_PLUS) ||
            (isset($b['organization']['subscription']) && $b['organization']['subscription']['partnership'] == Constants::PARTNERSHIP_PLUS);

        if($a_prior && $b_prior || !$a_prior && !$b_prior){
            return $a['matching_percentage'] < $b['matching_percentage'] ? 1 : -1;
        } elseif($a_prior){
            return -1;
        } else {
            return 1;
        }
    }

    public function getFields()
    {
        if($_SERVER['REQUEST_METHOD'] != 'POST'
            || !$this->userId
            || !isset($_POST['for_user_id'])
            || !isset($_POST['user_id'])
            || !isset($_POST['role'])
        ){
            $this->forward404();
        }

        $forUserId = $_POST['for_user_id'];
        $userId = $_POST['user_id'];
        $role = $_POST['role'];

        $matchResult = $this->api->getOneMatchResult($forUserId, $userId, compact('role'));

        if(empty($matchResult))
            $this->forward404();

        $matchResult = $matchResult[0];


        $form = \MissionNext\lib\core\Context::getInstance()->getApiManager()->getApi()->getForm($role, $role == 'job'?'job':'profile');
        $profile = \MissionNext\lib\ProfileLib::prepareDataToShow($matchResult['profileData'], $form);

        $results = [];
        foreach ($matchResult['results'] as $matchKey => $matchItem) {
            foreach ($matchItem[$role."_value"] as &$resultValue) {
                $exclamation_mark = strpos($resultValue, '(!)');
                if ($exclamation_mark !== false) {
                    $resultValue = str_replace("(!) ", "", $resultValue);
                    $matchItem['forcibly'] = true;
                }
            }
            $results[$matchItem['matchingDataKey']] = $matchItem;
        }

        $fields = $this->api->getModelFields($role);
        $checkboxFields = $this->getCheckboxWithGroups($fields);

        $html = '';
        foreach($profile as $group){

            $matchedFields = 0;
            foreach($group['fields'] as $field) {
                if (isset($results[$field['symbol_key']]) && $results[$field['symbol_key']]['matches'])
                    $matchedFields++;

                foreach ($results as $resultItem) {
                    if (($resultItem['matchingDataKey'] == $field['symbol_key'] || $resultItem['mainDataKey'] == $field['symbol_key']) && $resultItem['matches'])
                        $matchedFields++;
                }
            }

            if( (isset( $group['meta']['is_private'] ) && !$group['meta']['is_private'] || !isset($group['meta']['is_private'])) && $matchedFields > 0 ){

                $html .= '<fieldset class="mn-profile-group">';
                $html .= '<legend>' . $group['name'] . '</legend>';

                foreach($group['fields'] as $field){
                    $matchesFlag = false;

                    if (isset($results[$field['symbol_key']]) && $results[$field['symbol_key']]['matches'])
                        $matchesFlag = true;

                    foreach ($results as $resultItem) {
                        if (($resultItem['matchingDataKey'] == $field['symbol_key'] || $resultItem['mainDataKey'] == $field['symbol_key']) && $resultItem['matches'])
                            $matchesFlag = true;
                    }

                    if($matchesFlag){
                        $html .= '<div class="match" >';
                        $html .= '<strong>' . $field['label'] . ':</strong>';
                        $html .= '<div>';

                        if (is_array($field['value'])) {
                            sort($field['value'], SORT_STRING);
                        }
                        if (!isset($checkboxFields[$field['symbol_key']])) {
                            if(is_array($field['value']))
                                if (isset($results[$field['symbol_key']]['forcibly'])) {
                                    $isGeographicField = $this->checkGeographicField($field['symbol_key']);
                                    foreach($results[$field['symbol_key']][$role."_value"] as $value) {
                                        if (!$isGeographicField) {
                                            $html .= '<div>' . ucfirst($value) . '</div>';
                                        } else {
                                            $html .= '<div>' . ucwords($value) . '</div>';
                                        }
                                    }
                                } else {
                                    foreach($field['value'] as $value) {
                                        $html .= '<div>' . $value . '</div>';
                                    }
                                }
                            else
                                $html .= $field['value'];
                        } else {
                            $sectionLabel = '';
                            $sectionItemsCount = 0;
                            foreach ($checkboxFields[$field['symbol_key']] as $key => $value) {
                                if ($sectionLabel != $value) {
                                    $sectionLabel = $value;
                                    $sectionItemsCount = 0;
                                }
                                $searchValue = strtolower($key);
                                $searchKey = array_search($key, $field['value']);
                                if ($searchKey !== FALSE && in_array($searchValue, $results[$field['symbol_key']][$role."_value"])) {
                                    if (0 == $sectionItemsCount) {
                                        $html .= '<h5 class="group-section">'.$sectionLabel.'</h5>';
                                    }
                                    $html .= '<div>'.$key.'</div>';
                                    $sectionItemsCount++;
                                }
                            }
                        }

                        $html .= '</div>';
                        $html .= '</div>';
                    }
                }
                $html .= '</fieldset>';
            }
        }

        echo $html;

        return false;
    }

    private function checkGeographicField($field_key){
        $world = strpos($field_key, 'world');
        $country = strpos($field_key, 'country');

        if ($world === false && $country === false) {
            return false;
        } else {
            return true;
        }
    }

    private function getCheckboxWithGroups($paramFields)
    {
        $fields = $tempFields = [];

        foreach ($paramFields as $fieldItem) {
            if ('checkbox' == $fieldItem['type'] && $fieldItem['choices']) {
                $multiSelectField = false;
                foreach ($fieldItem['choices'] as $choice) {
                    if (isset($choice['dictionary_meta']['group'])) {
                        $multiSelectField = true;
                        continue;
                    }
                }
                if ($multiSelectField) {
                    foreach ($fieldItem['choices'] as $choice) {
                        $tempFields[$fieldItem['symbol_key']][$choice['dictionary_order']] = $choice;
                    }
                }
            }
        }

        foreach ($tempFields as &$field) {
            ksort($field);
        }

        foreach ($tempFields as $key => $value) {
            $groupName = '';
            $tempArray = [];
            foreach ($value as $choiceItem) {
                if (isset($choiceItem['dictionary_meta']) && isset($choiceItem['dictionary_meta']['group'])) {
                    $groupName = $choiceItem['dictionary_meta']['group'][0];
                }
                $fields[$key][$choiceItem['default_value']] = $groupName;
                $tempArray[$choiceItem['default_value']] = $groupName;
            }
        }

        return $fields;
    }

} 