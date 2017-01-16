<?php


namespace MissionNext\lib\form;

use MissionNext\Api;
use MissionNext\lib\Constants;

class SearchForm extends Form {

    public $searchRequest;
    public $searchRole;

    public function __construct(Api $api, $userRole, $user_id , $searchRole, $name){

        $this->api = $api;
        $this->name = $name;
        $this->role = $userRole;
        $this->user_id = $user_id;
        $this->searchRole = $searchRole;

//        $this->setName($name);

        $groups = $this->api->getForm($searchRole, $name);

        $groups = $this->setEmptyOption($groups);

        $groups = $this->prepareExpandedFields($groups);

        $groups = $this->clearJobTitle($groups);

        $groups = $this->removeConstraints($groups);

        if($searchRole != Constants::ROLE_JOB && $searchRole != Constants::ROLE_CANDIDATE){

            $extra = array(
                'symbol_key'        => 'main_fields',
                'name'              => __('Main Fields', Constants::TEXT_DOMAIN),
                'depends_on'        => null,
                'depends_on_option' => null,
                'order'             => 0,
                'fields' => array(
                    array(
                        'type' => 'input',
                        'symbol_key' => 'username',
                        'name' => __('Username', Constants::TEXT_DOMAIN),
                        'order' => 1,
                        'default_value' => null
                    )
                )
            );

            array_unshift($groups, $extra);
        }

        $this->setGroups($groups);
    }

    public function search(){

        $data = array();

        $groups = $this->getData();

        $main_group = isset($groups['main_fields'])?array_filter($groups['main_fields']):array();
        unset($groups['main_fields']);

        foreach($groups as $group){
            $data = array_merge($data, array_filter($group));
        }

        $request = $main_group;
        $request['profileData'] = $data;

        $this->searchRequest = $request;

        $result = $this->api->search($this->searchRole, $this->role, $this->user_id, $request);

        if(!$result){
            $result = array();
        }

        return $result;

    }

    public function setSearchDefaults($data){

        $pData = isset($data['profileData'])?$data['profileData']:array();
        unset($data['profileData']);

        $data = array_merge($data, $pData);

        parent::setDefaults($data);
    }

    private function prepareExpandedFields($groups){

        foreach($groups as &$group){
            foreach($group['fields'] as &$field){

                $field['default_value'] = null;

                if($field['meta']['search_options']['is_expanded']){
                    if($field['type'] == 'select' || "custom_marital" == $field['type']){
                        $field['choices'] = array_filter($field['choices']);
                    }
                    $field['type'] = 'checkbox';
                }
            }
        }

        return $groups;
    }

    private function removeConstraints($groups){
        foreach($groups as &$group){
            foreach($group['fields'] as &$field){
                $pos = strpos($field['symbol_key'], 'job_title_!#');
                if ($pos === false) {
                    $field['constraints'] = null;
                }
            }
        }

        return $groups;
    }

    public function validateJobTitle()
    {
        $isValid = true;
        $searchData = $this->getData();

        foreach ($this->groups as $groupKey => $groupValue) {
            foreach ($groupValue->fields as $key => $value) {
                $titlePos = strpos($key, 'job_title_!#');
                if ($titlePos !== false && $value->isRequired() && !isset($searchData[$groupKey][$key])) {
                    $this->addError($key, 'Field is required');
                    return false;
                }
            }
        }

        return $isValid;
    }

    private function setEmptyOption($groups)
    {
        foreach($groups as &$group){
            foreach($group['fields'] as &$field){

                if($field['type'] == 'select' || "custom_marital" == $field['type']){
                    if (!empty($field['choices'][count($field['choices']) - 1]['default_value'])) {
                        $field['choices'][] = [
                            'value'             => null,
                            'default_value'     => '',
                            'id'                => '',
                            'dictionary_order'  => -1,
                            'dictionary_meta'   => null
                        ];
                    }
                }
            }
        }
        return $groups;
    }
} 