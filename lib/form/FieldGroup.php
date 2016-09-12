<?php


namespace MissionNext\lib\form;


use MissionNext\lib\form\fields\BaseField;
use MissionNext\lib\form\fields\CheckboxField;
use MissionNext\lib\form\fields\CheckboxMultipleField;
use MissionNext\lib\form\fields\CustomMaritalField;
use MissionNext\lib\form\fields\DateField;
use MissionNext\lib\form\fields\FileField;
use MissionNext\lib\form\fields\InputField;
use MissionNext\lib\form\fields\MultipleSelectField;
use MissionNext\lib\form\fields\RadioField;
use MissionNext\lib\form\fields\RadioYesnoField;
use MissionNext\lib\form\fields\SelectField;
use MissionNext\lib\form\fields\TextareaField;

class FieldGroup {

    /**
     * @var BaseField[]
     */
    public $fields;
    public $name;
    public $key;
    public $group;
    public $dependant;

    public $data;

    public function __construct($group, $formName){

        $this->group = $group;

        $this->name = $group['name'];

        $this->key = $formName . "[" . $group['symbol_key'] . "]";

        $this->fields = $this->createdFieldsFromArray($group['fields']);

        if($this->isDependent() && isset($group['dependant'])){
            $this->dependant = $group['dependant'];
        }

    }

    public function isPrivate(){
        return isset($this->group['meta']['is_private']) && $this->group['meta']['is_private'];
    }

    public function isDependent(){
        return (boolean)$this->group['depends_on'];
    }

    public function isInnerDependent(){
        return $this->group['depends_on'] && !$this->group['is_outer_dependent'];
    }

    public function isOuterDependent(){
        return $this->group['depends_on']  && $this->group['is_outer_dependent'];
    }

    public function isDependentWithOption(){
        return $this->group['depends_on'] && $this->group['depends_on_option'];
    }

    public function setDefault($data){

        if(!is_array($data)){
            return;
        }

        $this->data = array();

        foreach($this->fields as $field){
            if(in_array($field->field['symbol_key'], array_keys($data))){
                $field->setDefault($data[$field->field['symbol_key']]);
                $this->data[$field->field['symbol_key']] = $data[$field->field['symbol_key']];
            } else {
                $field->setDefault(null);
            }
        }
    }

    public function getDefault(){
        return $this->data;
    }

    public function hasErrors(){
        $res = false;

        foreach($this->fields as $field){
            $res |= $field->hasError();
            if ($res){
                continue;
            }

            if ($field->hasDependentGroup() && is_array($field->dependentGroup)){
                foreach ($field->dependentGroup as $innerGroup) {
                    if ($field->hasDependentGroup() && $innerGroup->isInnerDependent()){
                        $res |= $this->hasErrorsDependentGroup($innerGroup);
                    }
                }
            } elseif ($field->hasDependentGroup() && $field->dependentGroup->isInnerDependent()){
                $res |= $this->hasErrorsDependentGroup($field->dependentGroup);
            }
        }

        return $res;
    }

    private function hasErrorsDependentGroup($dependentGroup){
        $res = false;

        foreach($dependentGroup->fields as $dependentField){
            if ($dependentField->hasError()){
                return true;
            }

            if ($dependentField->hasDependentGroup() && $dependentField->dependentGroup->isInnerDependent()){
                $res = $this->hasErrorsDependentGroup($dependentField->dependentGroup);
            }
        }

        return $res;
    }

    public function setErrors($errors){

        if(!$errors){
            return;
        }

        foreach($this->fields as $field){
            if(in_array($field->field['symbol_key'], array_keys($errors))){
                $field->setError($errors[$field->field['symbol_key']]);
            }
        }

    }

    private function createdFieldsFromArray($fields){

        $res = array();

        foreach($fields as $field){

            switch($field['type']){
                case 'select' : {
                    $res[$field['symbol_key']] = new SelectField($this->key ,$field);
                    break;
                }
                case 'boolean' : {
                    $res[$field['symbol_key']] = new CheckboxField($this->key, $field);
                    break;
                }
                case 'text' : {
                    $res[$field['symbol_key']] = new TextareaField($this->key, $field);
                    break;
                }
                case 'checkbox' : {
                    $res[$field['symbol_key']] = new CheckboxMultipleField($this->key, $field);
                    break;
                }
                case 'radio' : {
                    $res[$field['symbol_key']] = new RadioField($this->key, $field);
                    break;
                }
                case 'select_multiple' : {
                    $res[$field['symbol_key']] = new MultipleSelectField($this->key, $field);
                    break;
                }
                case 'file' : {
                    $res[$field['symbol_key']] = new FileField('', $field);
                    break;
                }
                case 'date' : {
                    $res[$field['symbol_key']] = new DateField($this->key, $field);
                    break;
                }
                case 'radio_yes_no' : {
                    $res[$field['symbol_key']] = new RadioYesnoField($this->key, $field);
                    break;
                }
                case 'custom_marital': {
                    $res[$field['symbol_key']] = new CustomMaritalField($this->key, $field);
                    break;
                }
                default : {
                    $res[$field['symbol_key']] = new InputField($this->key, $field);
                }
            }
        }

        return $res;

    }

} 