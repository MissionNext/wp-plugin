<?php

namespace MissionNext\lib\form;

use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;

class Form {

    /**
     * @var FieldGroup[]
     */
    public $groups = array();
    public $errors = array();
    public $apiError;
    public $data;
    public $files;
    public $user_id;

    public $fields;
    public $changedFields = null;
    public $saveLater = null;

    /**
     * @var Api
     */
    protected $api;
    protected $role;
    protected $name = 'mn';
    protected $prefix = 'mn';

    public function __construct(Api $api, $role, $name, $user_id = null){

        $this->api = $api;
        $this->role = $role;
        $this->name = $name;
        $this->user_id = $user_id;

//        $this->setName($name);

        $groups = $this->api->getForm($role, $name);

        $this->setGroups($groups);

        if($user_id){

            $data = stripslashes_deep($this->api->getUserProfile($user_id));

            $this->setDefaults($data['profileData']);
        }
    }

    /**
     * Функция получения introduction формы.
     */
    public function getIntro()
    {
        $intro = '';
        $role = (isset($this->searchRole)) ? $this->searchRole : $this->role;
        $translations = Context::getInstance()->getSiteConfigManager()->get("{$this->name}_{$role}_form_intro");
        $translations = str_replace('&#92;', '\\', $translations);

        if(!empty($translations))
        {
            $lang_id = Context::getInstance()->getLocalizationManager()->getCurrentLangId();

            $translations = json_decode($translations, true);
            foreach($translations as $translation)
            {
                if($lang_id == $translation['id'])
                {
                    $intro = $translation['value'];
                }
            }
        }
        unset($translations);

        return $intro;
    }

    /**
     * Функция получения outro формы.
     */
    public function getOutro()
    {
        $outro = '';
        $role = (isset($this->searchRole)) ? $this->searchRole : $this->role;
        $translations = Context::getInstance()->getSiteConfigManager()->get("{$this->name}_{$role}_form_outro");
        if(!empty($translations))
        {
            $lang_id = Context::getInstance()->getLocalizationManager()->getCurrentLangId();

            $translations = json_decode($translations, true);
            foreach($translations as $translation)
            {
                if($lang_id == $translation['id'])
                {
                    $outro = $translation['value'];
                }
            }
        }
        unset($translations);

        return $outro;
    }

    public function setName($form_name)
    {
        $this->name = $this->prefix . "_" . $form_name;
    }

    public function bind($data, $files = array()){

        $fields = array();

        foreach($this->groups as $_group => $group)
        {
            if(isset($data[$_group]))
            {
                foreach($group->fields as $_field => $field)
                {
                    if(isset($data[$_group][$_field]))
                    {
                        $values = $data[$_group][$_field];

                        if(is_array($field->default))
                        {
                            if(is_array($values))
                            {
                                foreach($values as $value)
                                {
                                    $fields[$_field][$value] = stripslashes(trim($value));
                                }
                            }
                            else
                            {
                                $fields[$_field] = array($values => stripslashes(trim($values)));
                            }
                        }
                        else
                        {
                            if (is_array($values)) {
                                foreach ($values as &$item) {
                                    $item = stripslashes(trim($item));
                                }
                                $fields[$_field] = $values;
                            } else {
                                $fields[$_field] = stripslashes(trim($values));
                            }

                        }
                    }
                }
            }
        }

        $this->setDefaults($fields);
        $this->data = $data;
        $this->files = $files;
    }

    public function prepareForValidation() {
        $preparedData = [];

        foreach($this->groups as $_group => $group)
        {
            $groupData = $group->data;
            foreach($group->fields as $_field => $field) {
                if ('checkbox' === $field->field['type'] || 'select_multiple' === $field->field['type']) {
                    $preparedData[$_group][$_field] = array_values($groupData[$_field]);
                } elseif ('file' === $field->field['type']) {
                    $config = Context::getInstance()->getConfig();

                    $fileName = $groupData[$_field];

                    if(file_exists($config->get('api_uploads_dir') . '/' . $fileName)){
                        $tmpName = '/tmp/php' . substr(md5(rand()), 0, 7);
                        copy($config->get('api_uploads_dir') . '/' . $fileName, $tmpName);

                        $this->files = [$_field => [
                            'name' => $fileName,
                            'type' => mime_content_type($tmpName),
                            'tmp_name' => $tmpName,
                            'error' => 0,
                            'size' => filesize($tmpName),
                        ]];
                    }
                } else {
                    if (is_array($groupData[$_field])) {
                        $preparedData[$_group][$_field] = array_values($groupData[$_field])[0];
                    } else {
                        $preparedData[$_group][$_field] = $groupData[$_field];
                    }
                }
            }
        }

        $this->data = $preparedData;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getData(){
        return $this->data;
    }

    public function hasErrors(){
        return !$this->isValid();
    }

    public function isValid(){
        return empty($this->errors);
    }

    public function setGroups($groups){

        uasort($groups, array($this, 'sortGroups'));

        foreach($groups as $group){

            uasort($group['fields'], array($this, 'sortFields'));

            if($group['depends_on']){
                $group['dependant'] = $this->fields[$group['depends_on']];
            }

            $this->groups[$group['symbol_key']] = new FieldGroup($group, $this->name);
        }

        $this->setFields($groups);

        $this->updateDependantFields($groups);
    }

    public function setDefaults($data){

        foreach($this->groups as $group){
            $group->setDefault($data);
        }
    }

    public function setFields($groups){

        foreach($groups as $group){
            foreach($group['fields'] as $field){
                $this->fields[$field['symbol_key']] = $field;
            }
        }
    }

    public function addError($key, $value){

        if(!isset($this->errors[$key])){
            $this->errors[$key] = array();
        }

        $this->errors[$key][] = $value;

        foreach($this->groups as $group){
            $group->setErrors(array( $key => is_array($value)?$value:array($value) ));
        }
    }

    public function setErrors($errors){

        $this->errors = $errors;

        foreach($this->groups as $group){
            $group->setErrors($errors);
        }

    }

    public function save(){

        $data = array();

        foreach($this->getData() as $group){
            foreach($group as $key => $value){
                $r = array(
                    'type' => $this->fields[$key]['type'],
                    'value' => is_array($value)?stripslashes_deep($value):stripslashes(trim($value)),
                    'dictionary_id' => ''
                );

                if($this->fields[$key]['choices']){
                    foreach($this->fields[$key]['choices'] as $choice){
                        if(is_array($value)){
                            foreach($value as $selected_key => $v){
                                if(trim($choice['default_value']) == trim(stripslashes($v))){
                                    $r['dictionary_id'][$selected_key] = $choice['id'];
                                }
                            }
                        } else {
                            if(trim($choice['default_value']) == trim(stripslashes($value))){
                                $r['dictionary_id'] = $choice['id'];
                            }
                        }
                    }
                }

                $data[$key] = $r;
            }
        }

        foreach($this->groups as $group){
            foreach($group->fields as $field){
                if( $field->field['type'] != 'file' && !in_array($field->field['symbol_key'], array_keys($data))){
                    $data[$field->field['symbol_key']] = array(
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
                    $data[$key] =  '@' . $new_name;
                }
            }
        }

        if(isset($data[Constants::$predefinedFields[$this->role]['email']])){
            $response = $this->api->updateUser($this->user_id, array('email' => $data[Constants::$predefinedFields[$this->role]['email']]['value']));

            if($this->api->getLastStatus() == 2){

                $this->setErrors($response);
                $this->apiError = $this->api->getLastError();
                return;
            } else {

                Context::getInstance()->getUser()->updateWPUser(array('user_email' => $data[Constants::$predefinedFields[$this->role]['email']]['value']));
            }
        }

        $response = $this->api->updateUserProfile($this->user_id, $data, $this->changedFields, $this->saveLater);

        if($this->api->getLastStatus() == 2){

            $this->setErrors($response);
            $this->apiError = $this->api->getLastError();
        } elseif(isset($response['profileData'])) {
            $this->setDefaults($response['profileData']);
        }

    }

    protected function updateDependantFields($groups){
        foreach($groups as $localGroup){
            if($localGroup['depends_on']){
                foreach($this->groups as $group){
                    if(isset($group->fields[$localGroup['depends_on']]) && !$localGroup['is_outer_dependent']){
                        $group->fields[$localGroup['depends_on']]->addDependentGroup($this->groups[$localGroup['symbol_key']]);
                    }
                    if (isset($group->fields[$localGroup['depends_on']]) && $localGroup['is_outer_dependent'] && !$group->fields[$localGroup['depends_on']]->hasDependentGroup()){
                        $group->fields[$localGroup['depends_on']]->setDependentGroup($this->groups[$localGroup['symbol_key']]);
                    }
                }
            }
        }
    }

    protected function sortGroups($a, $b){
        return $a['order'] > $b['order'] ? 1 : -1;
    }

    protected function sortFields($a, $b){
        return $a['order'] > $b['order'] ? 1 : -1;
    }

    protected function clearJobTitle($groups)
    {
        foreach($groups as $key => $value){
            foreach($value['fields'] as $keyItem => $valueItem){
                $specChars = strpos($valueItem['default_name'], Constants::JOB_TITLE_LIMITER);
                if ($specChars !== false) {
                    $groups[$key]['fields'][$keyItem]['default_name'] = substr($valueItem['default_name'], 0, $specChars);
                }
            }
        }

        return $groups;
    }
} 