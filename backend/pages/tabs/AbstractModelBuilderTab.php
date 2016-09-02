<?php


namespace MissionNext\backend\pages\tabs;

use MissionNext\Api;
use MissionNext\lib\core\Context;
use MissionNext\lib\form\FieldGroup;
use MissionNext\lib\form\fields\BaseField;

abstract class AbstractModelBuilderTab extends AbstractSettingsTab {

    private $fields;
    private $defaults;
    private $fieldsGroup;

    protected $validators = array(
        'required' => array(
            'key' => 'required',
            'label' => 'Required'
        ),
        'email' => array(
            'key' => 'email',
            'label' => 'Email',
            'types' => array(
                'input'
            )
        ),
        'url' => array(
            'key' => 'url',
            'label' => 'Url',
            'types' => array(
                'input'
            )
        ),
        'alpha' => array(
            'key' => 'alpha',
            'label' => 'Apha',
            'types' => array(
                'input'
            )
        ),
        'alpha_dash' => array(
            'key' => 'alpha_dash',
            'label' => 'Alpha-numeric with dashes',
            'types' => array(
                'input'
            )
        ),
        'alpha_num' => array(
            'key' => 'alpha_num',
            'label' => 'Alpha-numeric',
            'types' => array(
                'input'
            )
        ),
        'numeric' => array(
            'key' => 'numeric',
            'label' => 'Numeric',
            'types' => array(
                'input'
            )
        ),
        'size' => array(
            'key' => 'size',
            'label' => 'Size',
            'options' => array(
                array(
                    'type' => 'text',
                    'name' => 'Size',
                    'required' => true
                )
            ),
            'types' => array(
                'input'
            )
        ),
        'between' => array(
            'key' => 'between',
            'label' => 'Between',
            'options' => array(
                array(
                    'type' => 'text',
                    'name' => 'Min',
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'name' => 'Max',
                    'required' => false
                )
            ),
            'types' => array(
                'input'
            )
        ),
        'max' => array(
            'key' => 'max',
            'label' => 'Max',
            'options' => array(
                array(
                    'type' => 'text',
                    'name' => 'Max',
                    'required' => true
                )
            ),
            'types' => array(
                'input',
                'text'
            )
        ),
        'min' => array(
            'key' => 'min',
            'label' => 'Min',
            'options' => array(
                array(
                    'type' => 'text',
                    'name' => 'Min',
                    'required' => true
                )
            ),
            'types' => array(
                'input',
                'text'
            )
        ),
        'after' => array(
            'key' => 'after',
            'label' => 'After',
            'options' => array(
                array(
                    'type' => 'date',
                    'name' => 'Date after',
                    'required' => true
                )
            ),
            'types' => array(
                'date'
            )
        ),
        'before' => array(
            'key' => 'before',
            'label' => 'Before',
            'options' => array(
                array(
                    'type' => 'date',
                    'name' => 'Date before',
                    'required' => true
                )
            ),
            'types' => array(
                'date'
            )
        ),
        'ymd_more_than' => array(
            'key' => 'ymd_more_than',
            'label' => 'YMD more than',
            'options' => array(
                array(
                    'type' => 'number',
                    'name' => 'Year',
                    'required' => false
                ),
                array(
                    'type' => 'number',
                    'name' => 'Month',
                    'required' => false
                ),
                array(
                    'type' => 'number',
                    'name' => 'Day',
                    'required' => false
                )
            ),
            'types' => array(
                'date'
            )
        ),
        'ymd_less_than' => array(
            'key' => 'ymd_less_than',
            'label' => 'YMD less than',
            'options' => array(
                array(
                    'type' => 'number',
                    'name' => 'Year',
                    'required' => false
                ),
                array(
                    'type' => 'number',
                    'name' => 'Month',
                    'required' => false
                ),
                array(
                    'type' => 'number',
                    'name' => 'Day',
                    'required' => false
                )
            ),
            'types' => array(
                'date'
            )
        ),
        'mimes' => array(
            'key' => 'mimes',
            'label' => 'Custom MIME type',
            'options' => array(
                array(
                    'type' => 'text',
                    'name' => 'MIME Type',
                    'required' => true
                )
            ),
            'types' => array(
                'file'
            )
        ),
        'mimes_pdf' => array(
            'key' => 'mimes:pdf',
            'label' => 'Only PDF',
            'types' => array(
                'file'
            )
        )

    );

    /**
     * @var Api
     */
    private $api;

    public function initTab(){
        $this->api = Context::getInstance()->getApiManager()->getApi();
        $this->language = Context::getInstance()->getLocalizationManager()->getCurrentLangId();

        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form'])){

            if($_POST['form'] == 'model'){
                $this->submitForm($_POST['model']);
            } elseif($_POST['form'] == 'field'){
                $this->createField($_POST);
            }
        }

        $this->fields = $this->getFields();

        $this->defaults = $this->getDefaults();
        $this->fieldsGroup = new FieldGroup(array(
            'name'              => 'tooltip',
            'symbol_key'        => 'tooltip',
            'depends_on'        => null,
            'depends_on_option' => null,
            'fields'            => $this->fields
        ), 'tooltip');
    }

    public function createField($data){

        if(!$data['label'] || !$data['symbol_key'] || !$data['type']){
            return;
        }

        if(!isset($data['default_value'])){
            $data['default_value'] = false;
        }

        $field = array();

        $fields = $this->getFields();
        if(!empty($fields))
        {
            $field_id = element('id', $data);

            foreach($fields as $field)
            {
                if($field['id'] == $field_id)
                {
                    break;
                }
            }
        }
        unset($fields);

        if(isset($data['choices'])){

            $choices = array
            (
                'new' => array()
            );
            $_choices = $data['choices'];

            $meta = array();

            foreach($_choices as $order => $choice)
            {
                $key = key($choice);

                if($choice[$key] !== '')
                {
                    if($key == 'group')
                    {
                        if(!empty($field['choices'][$order]['dictionary_meta']['group']))
                        {
                            $group = $field['choices'][$order]['dictionary_meta']['group'];
                        }

                        $group[0] = str_replace('|', ' ', $choice[$key]);

                        $meta = compact('group');

                        continue;
                    }

                    if($key == 'new')
                    {
                        $choices['new'][] = array
                        (
                            'value' => str_replace('|', ' ', trim($choice[$key])),
                            'order' => $order,
                            'meta' => $meta
                        );
                    }
                    else
                    {
                        $choices[] = array
                        (
                            'id' => $key,
                            'value' => str_replace('|', ' ', trim($choice[$key])),
                            'order' => $order,
                            'meta' => $meta
                        );
                    }
                }

                $meta = array();
            }

            if(isset($data['add_empty']) && $data['add_empty']){
                array_unshift($choices['new'], array(
                    'value' => '',
                    'order' => -1
                ));
            }

        } else {
            $choices = '';
        }

        $default_value = $data['default_value'];

        if(is_array($default_value))
        {
            foreach($default_value as $key => $value)
            {
                $default_value[$key] = str_replace('|', ' ', $value);
            }

            $default_value = implode('|', array_map('trim', $default_value));
        } else {
            $default_value = trim($default_value);
        }

        $field = array(
            'name' => $data['label'],
            'symbol_key' =>$data['symbol_key'],
            'type' => BaseField::$types[$data['type']]['id'],
            'default_value' => $default_value,
            'choices' => $choices,
            'note' => $data['tooltip'],
            'meta' => array(
              'size' => isset($data['size'])?$data['size']:'medium'
            )
        );

        //Update or created
        if(isset($data['id']) && $data['id']){
            $field['id'] = $data['id'];
            $this->api->saveRoleField($this->getModelName(), $field);

        } else {
            $field['choices'] = isset($field['choices']['new'])?$field['choices']['new']:array();
            $this->api->addRoleField($this->getModelName(), $field);
        }
    }

    public function submitForm($data){
        $res = array();

        foreach($data as $key => $field){

            if(!isset($field['id'])){
                unset($data[$key]);
                continue;
            }

            $constraints = array();


            if(isset($field['constraints'])){
                foreach($field['constraints'] as $value){
                    $constraints[] = $value;
                }
            }

            $res[] = array(
                'id' => $field['id'],
                'constraints' => implode('|',$constraints)
            );
        }

        $ret = $this->api->saveModel($this->getModelName(), $res);

        if($ret){
            $this->addNotice('model', 'Model saved');
        } else {
            $error = $this->api->getLastError();

            $this->addError(
                $error['type'],
                $error['message'] ? $error['message'] : $error['type']
            );
        }
    }

    private function getFields(){

        $fields = $this->api->getRoleFields($this->getModelName());

        if($fields === false){
            $error = $this->api->getLastError();

            $this->addError(
                $error['type'],
                $error['message'] ? $error['message'] : $error['type']
            );
        }

        return $fields;
    }

    private function getDefaults(){

        $model = $this->api->getModelFields($this->getModelName());

        if($model === false){
            $error = $this->api->getLastError();

            $this->addError(
                $error['type'],
                $error['message'] ? $error['message'] : $error['type']
            );
        }

        $defaults = array();

        foreach($model as $field){

            $constraints = $field['constraints']?explode('|', $field['constraints']):array();

            $field['constraints'] = array();

            foreach($constraints as $constraint){

                $parts = explode(':', $constraint);

                $key = $parts[0];

                $field['constraints'][$key] = compact('key');
                $field['constraints'][$key]['orig'] = $constraint;

                if(isset($parts[1])){
                    $field['constraints'][$key]['params'] = explode(',',$parts[1]);
                }

            }

            $defaults[$field['symbol_key']] = $field;
        }

        return $defaults;
    }

    public function printContent(){

        Context::getInstance()->getTemplateService()->render('_model_builder', array(
            'fields' => $this->fields,
            'defaults' => $this->defaults,
            'fieldsGroup' => $this->fieldsGroup,
            'validators' => $this->validators,
            'role' => $this->getModelName(),
            'language' => $this->language
        ));

    }

    public function sortChoices($a, $b){
        return $a['order'] > $b['order'] ? 1:-1;
    }

    abstract function getModelName();

} 