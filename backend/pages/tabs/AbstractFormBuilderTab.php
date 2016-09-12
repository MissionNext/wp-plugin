<?php


namespace MissionNext\backend\pages\tabs;

use MissionNext\Api;
use MissionNext\lib\core\Context;

abstract class AbstractFormBuilderTab extends AbstractSettingsTab {

    /**
     * @var Api
     */
    protected $api;
    protected $fields = array();
    protected $restFields = array();
    protected $defaults;
    protected $languages;
    protected $translations;

    /**
     * You can overwrite this in child class with array of strings
     */
    protected $predefinedFields;
    protected $canHaveInnerDependencies = true;
    protected $canHaveOuterDependencies = true;

    protected $canHaveExpandedFields = false;
    protected $canHavePrivateGroups = true;

    public function initTab()
    {
        $this->api = Context::getInstance()->getApiManager()->getApi();

        $this->languages = $this->getSiteLanguages();

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $this->submitForm($_POST[$this->getFormName()]);
        }

        $fields = $this->getFields();

        $this->defaults = $this->getDefaults();

        $this->translations = $this->getTranslations();

        //Indexing
        foreach($this->defaults as $group){
            $this->fields[$group['symbol_key']] = $group;

            foreach($group['fields'] as $fieldKey => $field){

                if(isset($this->fields[$group['symbol_key']]['fields'][$fieldKey]['group'])){
                    foreach ($this->fields[$group['symbol_key']]['fields'][$fieldKey]['group'] as $subgroup) {
                        $fields[$field['symbol_key']]['group'][] = $subgroup;

                        if (count($subgroup['fields']) > 0) {
                            foreach($subgroup['fields'] as $innerField){
                                unset($fields[$innerField['symbol_key']]);
                            }
                        }
                    }
                }

                unset($this->fields[$group['symbol_key']]['fields'][$fieldKey]);

                $this->fields[$group['symbol_key']]['fields'][$field['symbol_key']] = $fields[$field['symbol_key']];
                unset($fields[$field['symbol_key']]);
            }
        }

        uasort($this->fields, array($this, 'sortGroups'));

        $this->restFields = $fields;

        $this->form_intro               = Context::getInstance()->getSiteConfigManager()->get($this->getFormName() . '_' . $this->getRole() . '_form_intro');
        $this->form_outro               = Context::getInstance()->getSiteConfigManager()->get($this->getFormName() . '_' . $this->getRole() . '_form_outro');
        $this->main_fields              = Context::getInstance()->getSiteConfigManager()->get($this->getFormName() . '_' . $this->getRole() . '_main_fields', 'Main Fields');
        $this->main_fields_translations = Context::getInstance()->getSiteConfigManager()->get($this->getFormName() . '_' . $this->getRole() . '_main_fields_translations');

        $this->predefinedFields = $this->predefinedMainFields();

        Context::getInstance()->getResourceManager()->addJSResource('jquery-ui-draggable');
        Context::getInstance()->getResourceManager()->addJSResource('jquery-ui-droppable');
    }

    /**
     * Функция сохранения данных.
     *
     * @param array $data
     */
    public function submitForm($data)
    {
        if(isset($data['form_intro']))
        {
            Context::getInstance()->getSiteConfigManager()->save($this->getFormName() . '_' . $this->getRole() . '_form_intro', stripslashes($data['form_intro']));

            unset($data['form_intro']);
        }

        if(isset($data['form_outro']))
        {
            Context::getInstance()->getSiteConfigManager()->save($this->getFormName() . '_' . $this->getRole() . '_form_outro', stripslashes($data['form_outro']));

            unset($data['form_outro']);
        }

        if(isset($data['main_fields']))
        {
            Context::getInstance()->getSiteConfigManager()->save($this->getFormName() . '_' . $this->getRole() . '_main_fields', $data['main_fields']['group_name']);
            Context::getInstance()->getSiteConfigManager()->save($this->getFormName() . '_' . $this->getRole() . '_main_fields_translations', stripslashes($data['main_fields']['translations']));

            unset($data['main_fields']);
        }

        if(isset($data['username'], $data['password'], $data['email']))
        {
            Context::getInstance()->getSiteConfigManager()->save('registration_username_tooltip', stripslashes($data['username']['tooltip']));
            Context::getInstance()->getSiteConfigManager()->save('registration_password_tooltip', stripslashes($data['password']['tooltip']));
            Context::getInstance()->getSiteConfigManager()->save('registration_email_tooltip', stripslashes($data['email']['tooltip']));

            unset($data['username'], $data['password'], $data['email']);
        }

        $res = array();

        $groupOrder = 1;

        $translations = array();

        foreach($data as $groupKey => $groupData){
            if (isset($groupData['translations'])) {
                $_translations = json_decode(stripslashes($groupData['translations']), true);
            }

            $group = array(
                'name'                  => $groupData['group_name'],
                'symbol_key'            => $groupKey,
                'fields'                => array(),
                'order'                 => $groupOrder,
                'depends_on'            => isset($groupData['depends_on'])?$groupData['depends_on']:'',
                'depends_on_option'     => isset($groupData['depends_on_option'])?$groupData['depends_on_option']:'',
                'is_outer_dependent'    => isset($groupData['is_outer_dependent'])?$groupData['is_outer_dependent']?"true":"false":'true',
                'meta' => array(
                    'is_private' => isset($groupData['is_private'])
                )
            );

            if(!isset($groupData['fields'])){
                continue;
            }
            $fields = $groupData['fields'];

            if(empty($fields)){
                continue;
            }

            $fieldOrder = 1;
            foreach($fields as $field){

                $notes = json_decode(stripslashes($field['notes']), true);

                $group['fields'][] = array(
                    'symbol_key' => $field['symbol_key'],
                    'order' => $fieldOrder,
                    'is_expanded' => isset($field['is_expanded'])?$field['is_expanded']:0,
                    'before_notes' => isset($notes['before_notes'])?$notes['before_notes']:array(),
                    'after_notes' => isset($notes['after_notes'])?$notes['after_notes']:array()
                );
                $fieldOrder++;
            }

            $groupOrder++;

            $res[] = $group;

            if($_translations){
                foreach($_translations as $_translation){

                    $translations[] = array(
                        'lang_id' => $_translation['id'],
                        'group_id' => 0,
                        'symbol_key' => $groupKey,
                        'value' => $_translation['value'],
                    );
                }
            }
        }

        $ret = $this->api->saveForm($this->getRole(), $this->getFormName(), $res);

        if($ret){

            foreach($translations as &$translation){
                foreach($ret as $group){
                    if($group['symbol_key'] == $translation['symbol_key']){
                        unset($translation['symbol_key']);
                        $translation['group_id'] = $group['id'];
                        continue 2;
                    }
                }
            }

            $this->api->saveFormGroupTranslations($translations);

            $this->addNotice('form', ucfirst($this->getRole()) . ' ' . $this->getFormName() . ' form saved');
        } else {
            $error = $this->api->getLastError();

            $this->addError(
                $error['type'],
                $error['message'] ? $error['message'] : $error['type']
            );
        }
    }

    /**
     * Функция получения зарезервированных полей.
     *
     * @return array
     */
    private function predefinedMainFields()
    {
        $fields = array();

        if(is_array($this->predefinedFields))
        {
            foreach($this->predefinedFields as $field)
            {
                $key = strtolower($field);

                $tooltip = Context::getInstance()->getSiteConfigManager()->get("registration_{$key}_tooltip");

                $fields[] = compact('field', 'key', 'tooltip');
            }
        }

        return $fields;
    }

    private function getFields(){

        $fields = $this->api->getModelFields($this->getRole());

        if($fields === false){
            $error = $this->api->getLastError();

            $this->addError(
                $error['type'],
                $error['message'] ? $error['message'] : $error['type']
            );
        }

        foreach($fields as $key => $field){
            $choices = [];

            if ($field['choices']) {
                foreach ($field['choices'] as $choice) {
                    if (!empty($choice['default_value'])) {
                        $choices[] = $choice['default_value'];
                    }
                }
                $field['filtered_choices'] = $choices;
            }

            $fields[$field['symbol_key']] = $field;
            unset($fields[$key]);
        }

        return $fields;
    }

    private function getDefaults(){

        $model = $this->api->getForm($this->getRole(), $this->getFormName());

        if($model === false){
            $error = $this->api->getLastError();

            $this->addError(
                $error['type'],
                $error['message'] ? $error['message'] : $error['type']
            );
        }

        $defaults = $model?$model:array();

        $dependant = array();
        $groups = array();

        foreach($defaults as $groupKey => $group){

            uasort($group['fields'], array( $this, 'sortFields') );

            foreach($group['fields'] as $fieldKey => $field){
                $field['value'] = 1;
                $group['fields'][$field['symbol_key']] = $field;
                unset($group['fields'][$fieldKey]);
            }

            if($group['depends_on'] && !$group['is_outer_dependent']){
                $dependant[$group['depends_on']][] = $group;
            } else {
                $groups[$group['symbol_key']] = $group;
            }
        }

        //Dependant filter

        if(!empty($dependant)){
            foreach($groups as $groupKey => $group){
                foreach($group['fields'] as $symbolKey => $field){
                    if(isset($dependant[$symbolKey])){
                        foreach ($dependant[$symbolKey] as $dependantGroup) {
                            $groups[$groupKey]['fields'][$symbolKey]['group'][] = $dependantGroup;
                        }
                    }
                }
            }
        }

        return $groups;
    }

    private function getTranslations(){
        $data = $this->api->getFormTranslations($this->getRole(), $this->getFormName());

        if(!$data){
            return array();
        }

        $translations = array();

        foreach($data as $obj){
            if(!isset($translations[$obj['group_id']])){
                $translations[$obj['group_id']] = array();
            }

            $translations[$obj['group_id']][] = array(
                'id' => $obj['lang_id'],
                'value' => $obj['value']
            );
        }

        return $translations;
    }

    private function sortGroups($a, $b){
        return $a['order'] < $b['order'] ? -1 : 1;
    }

    private function sortFields($a, $b){
        return $a['order'] < $b['order'] ? -1 : 1;
    }

    private function getSiteLanguages(){

        $langs = $this->api->getSiteLanguages();

        $res = array();

        foreach($langs as $lang){
            $res[$lang['key']] = $lang;
        }

        return $res;
    }

    public function printContent(){

        Context::getInstance()->getTemplateService()->render('forms/base_building', array
        (
            'fields'                   => $this->fields,
            'defaults'                 => $this->defaults,
            'restFields'               => $this->restFields,
            'formName'                 => $this->getFormName(),
            'predefinedFields'         => $this->predefinedFields,
            'canHaveInnerDependencies' => $this->canHaveInnerDependencies,
            'canHaveOuterDependencies' => $this->canHaveOuterDependencies,
            'canHaveExpandedFields'    => $this->canHaveExpandedFields,
            'canHavePrivateGroups'     => $this->canHavePrivateGroups,
            'languages'                => $this->languages,
            'translations'             => $this->translations,
            'form_intro'               => $this->form_intro,
            'form_outro'               => $this->form_outro,
            'main_fields'              => $this->main_fields,
            'main_fields_translations' => $this->main_fields_translations
        ));

    }

    abstract function getFormName();

    abstract function getRole();

}