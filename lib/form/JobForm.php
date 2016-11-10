<?php


namespace MissionNext\lib\form;


use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;

class JobForm extends Form {

    public $job;
    public $force_new;

    public function __construct(Api $api, $org_id, $job_id = null, $force_new = false){

        $this->api = $api;
        $this->role = 'job';
        $this->name = 'job';
        $this->force_new = $force_new;

//        $this->setName('job');

        $form = $this->api->getForm('job', 'job');

        $groups = $form?$form:array();
        $groups = $this->clearJobTitle($groups);
        $name = null;

        if($job_id){
            $this->job = $this->api->getJobProfile($job_id);

            $defaults = $this->job['profileData'];
            $name = $this->job['name'];

            $publicKey = Context::getInstance()->getApiManager()->publicKey;

            if($force_new){
                unset($defaults['job_title_!#'.$publicKey]);
                unset($defaults['job_title']);
            }
        }

        $group = array(
                array(
                    'type' => 'hidden',
                    'symbol_key' => 'id',
                    'name' => 'id',
                    'default_value' => $job_id,
                    'order' => -2,
                    'choices' => array()
                ),
                array(
                    'type' => 'hidden',
                    'symbol_key' => 'organization_id',
                    'name' => 'id',
                    'default_value' => $org_id,
                    'order' => -1,
                    'choices' => array()
                )

        );

        if(!empty($groups)){
            uasort($groups, array($this, 'sortGroups'));

            foreach($group as $field){
                array_unshift($groups[key($groups)]['fields'], $field);
            }
        } else {
            $groups = array(
                array(
                    'symbol_key'        => 'main_fields',
                    'name'              => 'Mandatory Fields',
                    'depends_on'        => null,
                    'depends_on_option' => null,
                    'order'             => 0,
                    'fields'            => $group
                )
            );
        }

        $this->setGroups($groups);

        if(isset($defaults)){
            $this->setDefaults($defaults);
        }
    }

    public function save(){

        $org_id = $this->data[key($this->data)]['organization_id'];
        $id = isset($this->data[key($this->data)]['id'])?$this->data[key($this->data)]['id']:null;

        $profile = $this->data;

        $pdata = array();

        foreach($profile as $group){
            foreach($group as $key => $value){
                $r = array(
                    'type' => $this->fields[$key]['type'],
                    'value' => is_array($value)?stripslashes_deep($value):stripslashes($value),
                    'dictionary_id' => ''
                );

                if($this->fields[$key]['choices']){
                    foreach($this->fields[$key]['choices'] as $choice){
                        if(is_array($value)){
                            foreach($value as $v){
                                if($choice['default_value'] == $v){
                                    $r['dictionary_id'][] = $choice['id'];
                                }
                            }
                        } else {
                            if($choice['default_value'] == $value){
                                $r['dictionary_id'] = $choice['id'];
                            }
                        }
                    }
                }

                $pdata[$key] = $r;
            }
        }

        $publicKey = Context::getInstance()->getApiManager()->publicKey;
        if (isset($pdata['job_title_!#'.$publicKey]) && $pdata['job_title_!#'.$publicKey]['value']) {
            $name = $pdata['job_title_!#'.$publicKey]['value'];
        } elseif (isset($pdata['job_title']) && $pdata['job_title']['value']) {
            $name = $pdata['job_title']['value'];
        } else {
            $name = 'undefined';
        }
        $symbol_key = str_replace(' ', '_', strtolower($name)) . time();

        $groups = $this->groups;
        unset($groups['main_fields']);

        foreach($this->groups as $group){
            foreach($group->fields as $field){
                if( $field->field['type'] != 'file' && !in_array($field->field['symbol_key'], array_keys($pdata))){
                    $pdata[$field->field['symbol_key']] = array(
                        'type' => $field->field['type'],
                        'value' => '',
                        'dictionary_id' => ''
                    );
                }
            }
        }

        if($this->files){
            foreach($this->files as $key => $file){
                if($file['tmp_name']){
                    $new_name = dirname($file['tmp_name']) . '/' . $file['name'];
                    rename($file['tmp_name'], $new_name);
                    $pdata[$key] =  '@' . $new_name;
                }
            }
        }

        unset($pdata['id'], $pdata['organization_id'], $pdata['name']);

        if($id && !$this->force_new){
            $this->api->updateJob($id, $symbol_key, $name, $org_id);
            $response = $this->api->updateJobProfile($id, $pdata, $this->changedFields);
        } else {
            $response = $this->api->createJob($symbol_key, $name, $org_id, $pdata);
        }

        // Validation error
        if($this->api->getLastStatus() == 2){

            foreach($response as $name => $errors){

                $this->addError($name, $errors);
            }

        } elseif($this->api->getLastStatus() == 1) {

            return true;
        }

        return false;
    }

} 