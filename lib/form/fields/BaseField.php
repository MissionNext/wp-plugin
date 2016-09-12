<?php


namespace MissionNext\lib\form\fields;


use MissionNext\lib\core\Context;
use MissionNext\lib\form\FieldGroup;

abstract class BaseField {

    public static $types = array(
        'date' => array(
            'id' => 1,
            'key' => 'date',
            'label' => 'Date'
        ),
        'select' => array(
            'id' => 2,
            'key' => 'select',
            'label' => 'Select'
        ),
        'input' => array(
            'id' => 3,
            'key' => 'input',
            'label' => 'Input'
        ),
        'select_multiple'=> array(
            'id' => 4,
            'key' => 'select_multiple',
            'label' => 'Multiple Select'
        ),
        'text' => array(
            'id' => 5,
            'key' => 'text',
            'label' => 'Textarea'
        ),
        'radio' => array(
            'id' => 6,
            'key' => 'radio',
            'label' => 'Radio'
        ),
        'boolean' => array(
            'id' => 7,
            'key' => 'boolean',
            'label' => 'Checkbox'
        ),
        'checkbox' => array(
            'id' => 8,
            'key' => 'checkbox',
            'label' => 'Multiple Checkboxes'
        ),
        'file' => array(
            'id' => 9,
            'key' => 'file',
            'label' => 'File'
        ),
        'radio_yes_no' => array(
            'id'    => 10,
            'key'   => 'radio_yes_no',
            'label' => 'Yes/No'
        ),
        'custom_marital' => array(
            'id'    => 11,
            'key'   => 'custom_marital',
            'label' => 'Marital status'
        )
    );

    public $field;
    public $constraints;
    /**
     * @var FieldGroup
     */
    public $dependentGroup;
    public $default;
    public $notes = array( 'before' => '', 'after' => '' );
    public $tooltip;

    protected $id;
    protected $name;
    protected $data;
    protected $error;

    public function __construct($formName, $field, $default = null){

        $this->name = $formName."[".$field['symbol_key']."]";
        $this->id = str_replace('[', '_',str_replace(']', '', $this->name));
        $this->field = $field;
        $this->default = $default;
        $this->constraints = isset($field['constraints'])?explode('|', $field['constraints']):array();

        if(array_key_exists('note', $field) && isset($field['default_note'])){
            $this->tooltip = $field['note']?$field['note']:$field['default_note'];
        }

        $this->setup();
        $this->prepareNotes();
    }

    public function setDefault($default){
        $this->default = $default;
    }

    public function getDefault(){
        return $this->default;
    }

    public function hasError(){
        return $this->error !== null;
    }

    public function setError($error){
        $this->error = $error;
    }

    public function getError(){
        return $this->error;
    }

    public function isRequired(){
        return in_array('required', $this->constraints);
    }

    public function printLabel ($options = null)
    {
        $key = $this->id;
        $label = $this->field['name']?$this->field['name']:$this->field['default_name'];

        $optionsString = '';
        $required = $this->isRequired()?'*':'';

        if($options && is_array($options)){
            foreach($options as $key => $option){
                $optionsString .= $key."='$option' ";
            }
        }

        return "<label $optionsString for='$key'>$label$required</label>";
    }

    public function addDependentGroup($group){
        $this->dependentGroup[] = $group;
    }

    public function setDependentGroup($group){
        $this->dependentGroup = $group;
    }

    public function hasDependentGroup(){
        return $this->dependentGroup != null;
    }

    protected function prepareNotes(){

        if(isset($this->field['meta']) && isset($this->field['meta']['before_notes']) && isset($this->field['meta']['after_notes'])){
            $before_notes = $this->field['meta']['before_notes'];
            $after_notes = $this->field['meta']['after_notes'];

            $current_lang_id = Context::getInstance()->getLocalizationManager()->getCurrentLangId();

            foreach($before_notes as $note){
                if(!$this->notes['before'] && $note['lang_id'] == 0){
                    $this->notes['before'] = $note['value'];
                }

                if($note['lang_id'] == $current_lang_id && $note['value']){
                    $this->notes['before'] = $note['value'];
                }
            }

            foreach($after_notes as $note){
                if(!$this->notes['after'] && $note['lang_id'] == 0){
                    $this->notes['after'] = $note['value'];
                }

                if($note['lang_id'] == $current_lang_id && $note['value']){
                    $this->notes['after'] = $note['value'];
                }
            }
        }


    }

    protected function getDefaultOptions(){

        $options = array();

        if(isset($this->field['symbol_key']) and isset($this->field['id'])){
            $options['data-key'] = $this->field['symbol_key'];
            $options['data-id'] = $this->field['id'];
        }

        return $options;
    }

    protected function getDefaultClasses(){
        $classes = '';

        if(isset($this->field['model_meta']['size']))
        {
            if($this->field['type'] == 'input' or $this->field['type'] == 'text')
            {
                $classes = 'mn-' . $this->field['model_meta']['size'] . '-field';
            }
        }

        return $classes;
    }

    protected function setup(){}

    abstract public function printField($options = null);
} 