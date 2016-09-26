<?php


namespace MissionNext\backend\controllers;

use MissionNext\Api;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;

class modelController extends Controller {

    /**
     * @var Api
     */
    private $api;

    public function beforeAction(){
        $this->api = Context::getInstance()->getApiManager()->getApi();
    }

    public function deleteField(){

        if( isset($_POST['role']) && isset($_POST['id'])){
            $response = $this->api->deleteRoleField($_POST['role'], $_POST['id']);

            echo json_encode($response?$response:0);
        }
        exit;
    }

    public function getTranslation(){
        if(!isset($_POST['id']) || !isset($_POST['role'])){
            $this->forward404();
        }

        $_fields = $this->api->getFieldsLanguages($_POST['role']);
        $languages = Context::getInstance()->getLocalizationManager()->getSiteLanguages();

        $response = array(
            'id' => $_POST['id'],
            'name' => array(),
            'default_name' => '',
            'choices' => array(),
            'default_choices' => array(),
            'default_note' => '',
            'note' => array()
        );

        foreach($_fields as $language){
            foreach($language['fields'] as $field){
                if($field['id'] == $_POST['id']){
                    $response['id'] = $field['id'];

                    if($language['lang_id'] == 0){
                        $response['default_name'] = $field['name'];
                        $response['default_note'] = $field['note'];
                        if($field['dictionary_id'] && $field['choices']){
                            $response['default_choices'] = array_combine($field['dictionary_id'], $field['choices']);
                        }
                        if($field['dictionary_id'] && $field['dictionary_meta']){
                            $response['meta'] = array_combine($field['dictionary_id'], $field['dictionary_meta']);
                        }
                    } else {
                        $response['name'][$language['lang_id']] = $field['name'];
                        $response['choices'][$language['lang_id']] = ($field['dictionary_id'] && $field['choices'])?array_combine($field['dictionary_id'], $field['choices']):array();
                        $response['note'][$language['lang_id']] = $field['note'];
                    }
                }
            }
        }

        $this->field = $response;
        $this->languages = $languages;
    }

    public function saveTranslation(){

        if(!isset($_POST['field']) || !isset($_POST['role'])){
            $this->forward404();
        }

        parse_str($_POST['field'], $field);

        $groups = element('groups', $field);
        if(!empty($groups))
        {
            $role     = element('role', $_POST);
            $field_id = element('id', $field);
            $data     = array();

            foreach($groups as $choice_id => $group)
            {
                $data[] = array
                (
                    'id'   => $choice_id,
                    'meta' => compact('group')
                );
            }

            $this->api->saveRoleFieldChoices($role, $field_id, $data);
        }
        unset($groups);

        $data = array();

        foreach($field['name'] as $language_id => $name){
            $data[] = array(
                'field_id' => $field['id'],
                'lang_id' => $language_id,
                'label' => $name,
                'choices' => isset($field['choices'])?$field['choices'][$language_id]:'',
                'note' => isset($field['note'])?$field['note'][$language_id]:''
            );
        }

        $response = $this->api->saveFieldsLanguages($_POST['role'], $data);

        echo json_encode($response);

        return false;
    }

} 