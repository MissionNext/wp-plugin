<?php

namespace MissionNext\lib\form\fields;


use MissionNext\lib\Constants;

class SelectField extends BaseField {

    public function printField ($options = array())
    {
        $default = $this->default?$this->default:$this->field['default_value'];
        if(!is_array($default)){
            $default = array( $default => $default );
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

        $field = "<select $optionsString name=\"$key\" id=\"$id\">\n";

        if($this->field['choices']){

            usort($this->field['choices'], array($this, 'sortChoices'));

            foreach($this->field['choices'] as $choice){

                $key = trim($choice['default_value']);

                $name = $choice['value']?$choice['value']:$choice['default_value'];
                $label = strpos($name, Constants::NO_PREFERENCE_SYMBOL) === 0?substr($name, 3):$name;

                $selected = in_array( trim($choice['default_value']), $default ) ? 'selected="selected"' : '' ;

                $key = esc_html($key);

                $field  .= "<option $selected value=\"$key\">$label</option>\n";
            }
        }

        $field .= "</select>";

        return $field;

    }

    private function sortChoices($a, $b){
        return ($a['dictionary_order'] < $b['dictionary_order']) ? -1 : 1;
    }

} 