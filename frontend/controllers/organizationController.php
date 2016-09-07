<?php


namespace MissionNext\frontend\controllers;


use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;

class organizationController extends AbstractLayoutController {

    /**
     * @param array
     */
    public function show($params)
    {
        $user_id = array_shift($params);

        $organization = $this->api->getUserProfile($user_id);

        if(!$organization || $organization['role'] != 'organization'){
            $this->forward404();
        }

        $profile = $organization['profileData'];

        $groups = $this->api->getForm('organization', 'profile');

        $organization['profile'] = $this->prepareDataToShow($profile, $groups);

        $favorites = $this->api->getFavorites($this->userId, 'organization');

        $is_favorite = false;

        if($favorites){
            foreach($favorites as $favorite){
                if($favorite['target_id'] == $user_id){
                    $is_favorite = $favorite['id'];
                    break;
                }
            }
        }

        $organization['favorite'] = $is_favorite;


        $this->organization = $organization;

        $fields = $this->api->getModelFields('organization');
        $this->fields = array();

        foreach($fields as $field){
            $this->fields[$field['symbol_key']] = $field;
        }

        $this->presentation = $this->api->getUserConfigsElement('presentation', $user_id);
    }

    public function jobs($params){

        $this->layout = 'sidebarLayout.php';

        $id = $params[0];

        $this->organization = $this->api->getUserProfile($id);

        if(!$this->organization || $this->organization['role'] != 'organization'){
            $this->forward404();
        }

        $candidateJobCategories = isset($this->user['profileData']) ? $this->user['profileData']['job_categories'] : null;
        $favorites = $this->api->getFavorites($this->userId, 'job');

        foreach ($favorites as $key => $value) {
            $favoritedJobs[$value['id']] = $value['target_id'];
        }

        $inquiredJobs = $this->api->getInquiredJobs($this->userId);
        if ($inquiredJobs) {
            $filteredInquiredJobs = array_map(function($item){
                return $item['name'];
            }, $inquiredJobs);
        }

        if ($candidateJobCategories) {
            $tmpJobArray = $this->api->getOrganizationPositions($id, $this->userId);
            foreach ($tmpJobArray as $job) {
                $job['org_name'] = $this->organization['profileData']['organization_name'];
                if ($favoritedJobs && in_array($job['id'], $favoritedJobs)) {
                    $favKey = array_search($job['id'], $favoritedJobs);
                    $job['favorite'] = $favKey;
                }
                if ($filteredInquiredJobs && in_array($job['name'], $filteredInquiredJobs)) {
                    $job['inquired'] = true;
                }
                if (in_array($job['name'], $candidateJobCategories)) {
                    $this->jobs[] = $job;
                }
            }
        } else {
            $this->jobs = [];
        }
    }

    /**
     * Страница вывода presentation контента организации.
     *
     * @param array
     */
    public function presentation($params)
    {
        $user_id = array_shift($params);

        $this->organization = $this->api->getUserProfile($user_id);

        if(empty($this->organization))
        {
            $this->forward404();
        }

        if($this->organization['role'] != Constants::ROLE_ORGANIZATION)
        {
            $this->forward404();
        }

        $this->presentation = $this->api->getUserConfigsElement('presentation', $user_id);
        $this->presentation['value'] = do_shortcode($this->presentation['value']);

        if(empty($this->presentation['value']))
        {
            $this->redirect("/organization/$user_id");
        }
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
} 
