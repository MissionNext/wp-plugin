<?php

namespace MissionNext\lib\form\fields;


use MissionNext\lib\Constants;

class CustomMaritalField extends BaseField {

    public function printField ($options = array())
    {
        $default = $this->default?$this->default:$this->field['default_value'];
        
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

        $field = "<select $optionsString name=\"$key\" id=\"$id\" custom-field=\"2\">\n";

        foreach($this->field['choices'] as $key => $value){

            $selected = ( $default == $value['default_value'] ) ? 'selected="selected"' : '' ;

            $field  .= "<option $selected value='{$value['default_value']}'>{$value['default_value']}</option>\n";
        }

        $field .= "</select>";

        return $field;

    }

} 