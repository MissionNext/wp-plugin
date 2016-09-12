<?php

namespace MissionNext\lib\form\fields;


use MissionNext\lib\Constants;

class MultipleSelectField extends BaseField {

    public function printField ($options = array())
    {
        $default = $this->default?array_map('trim', $this->default):array();

        if(!$default && $this->field['default_value']){
            $default = $this->field['default_value'];
        }

        $optionsString = '';

        $options = array_merge($options, $this->getDefaultOptions());
        $options['class'] = isset($options['class'])?$options['class'] . ' ' . $this->getDefaultClasses():$this->getDefaultClasses();

        if($options && is_array($options)){
            foreach($options as $key => $option){
                $optionsString .= $key."='$option' ";
            }
        }

        $key = $this->name;
        $id = $this->id;

        $field = "<select $optionsString multiple='multiple' name=\"{$key}[]\" id=\"$id\">\n";
        if($this->field['choices']){

            usort($this->field['choices'], array($this, 'sortChoices'));

            foreach($this->field['choices'] as $choice){

                $name = $choice['value']?$choice['value']:$choice['default_value'];
                $label = strpos($name, Constants::NO_PREFERENCE_SYMBOL) === 0?substr($name, 3):$name;

                $selected = in_array(trim($choice['default_value']), $default) ? 'selected="selected"' : '' ;

                $field  .= "<option $selected value=\"{$choice['default_value']}\">$label</option>\n";
            }
        }
        $field .= "</select>";

        return $field;

    }

    private function sortChoices($a, $b){
        return ($a['dictionary_order'] < $b['dictionary_order']) ? -1 : 1;
    }


} 