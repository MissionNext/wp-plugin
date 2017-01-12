<?php


namespace MissionNext\frontend\controllers;


use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;

class candidateController extends AbstractLayoutController {

    public function show($params){

        $id = $params[0];

        $candidate = $this->api->getUserProfile($id);

        if(!$candidate || $candidate['role'] != 'candidate'){
            $this->forward404();
        }

        $profile = $candidate['profileData'];

        $groups = $this->api->getForm('candidate', 'profile');

        $candidate['profile'] = $this->prepareDataToShow($profile, $groups);

        if($this->userRole == 'organization' || $this->userRole == 'agency'){
            $favorites = $this->api->getFavorites($this->userId, 'candidate');

            $is_favorite = false;

            if($favorites){
                foreach($favorites as $favorite){
                    if($favorite['target_id'] == $id){
                        $is_favorite = $favorite['id'];
                        break;
                    }
                }
            }

            $candidate['favorite'] = $is_favorite;
        }

        if(isset($profile[Constants::$predefinedFields[Constants::ROLE_CANDIDATE]['first_name']]) && isset($profile[Constants::$predefinedFields[Constants::ROLE_CANDIDATE]['last_name']])){
            $this->name = $profile[Constants::$predefinedFields[Constants::ROLE_CANDIDATE]['first_name']] . ' ' . $profile[Constants::$predefinedFields[Constants::ROLE_CANDIDATE]['last_name']];
        } else {
            $this->name = $candidate['username'];
        }

        $this->candidate = $candidate;

        $fields = $this->api->getModelFields('candidate');

        $this->fields = array();

        foreach($fields as $field){
            $this->fields[$field['symbol_key']] = $field;
        }
    }

    private function prepareDataToShow($profile, $groups){
        $show_spouse_fields = (isset($profile['marital_status']) && "Married" == $profile['marital_status']) ? true : false;
        $result = array();

        uasort($groups, array($this, 'sortGroups'));

        foreach($groups as $group){

            uasort($group['fields'], array($this, 'sortFields'));

            $fields = $group['fields'];
            $group['fields'] = array();

            foreach($fields as $field){
                $spouse_pos = strpos($field['symbol_key'], 'spouse');
                if ($spouse_pos === false) {
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
                } elseif ($show_spouse_fields) {
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
