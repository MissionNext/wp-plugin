<?php


namespace MissionNext\frontend\controllers;


use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;
use MissionNext\lib\SiteConfig;

class agencyController extends AbstractLayoutController {

    public function beforeAction(){
        parent::beforeAction();

        if(!SiteConfig::isAgencyOn()){
            $this->forward404();
        }
    }

    /**
     * @param $params
     */
    public function show($params)
    {
        $user_id = array_shift($params);

        $agency = $this->api->getUserProfile($user_id);

        if(!$agency || $agency['role'] != 'agency'){
            $this->forward404();
        }

        $profile = $agency['profileData'];

        $groups = $this->api->getForm('agency', 'profile');

        $agency['profile'] = $this->prepareDataToShow($profile, $groups);

        $this->agency = $agency;

        $fields = $this->api->getModelFields('candidate');

        $this->fields = array();

        foreach($fields as $field){
            $this->fields[$field['symbol_key']] = $field;
        }

        $this->presentation = $this->api->getUserConfigsElement('presentation', $user_id);
    }

    /**
     * Страница вывода presentation контента агенства.
     *
     * @param array
     */
    public function presentation($params)
    {
        $user_id = array_shift($params);

        $this->agency = $this->api->getUserProfile($user_id);

        if(empty($this->agency))
        {
            $this->forward404();
        }

        if($this->agency['role'] != Constants::ROLE_AGENCY)
        {
            $this->forward404();
        }

        $this->presentation = $this->api->getUserConfigsElement('presentation', $user_id);
        $this->presentation['value'] = do_shortcode($this->presentation['value']);
        $this->presentation['value'] = str_replace("\r\n\r\n", "<br />", $this->presentation['value']);

        if(empty($this->presentation['value']))
        {
            $this->redirect("/agency/$user_id");
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