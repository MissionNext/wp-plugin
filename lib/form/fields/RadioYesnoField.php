<?php


namespace MissionNext\lib\form\fields;


use MissionNext\lib\Constants;

class RadioYesnoField extends BaseField {

    public function printField ($options = array())
    {
        $default = $this->default ? $this->default : null;

        if(!$default && $this->field['default_value']){
            $default = $this->field['default_value'][0];
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

        $disabled = (isset($options['disabled']) && $options['disabled'] == 'disabled') ? 'disabled="disabled"' : '';
        $field = '';

        $selected = ($default == 'Yes') ? ' checked="checked"' : '';
        $field .= "<div>";
        $field .= "<input type='radio' custom-field='1' $optionsString $disabled name=\"{$key}\" value=\"Yes\" $selected/>\n";
        $field .= "<label class='radio-label'>Yes</label>\n";

        $selected = ($default == 'No') ? ' checked="checked"': '';
        $field .= "<input type='radio' custom-field='1' $optionsString $disabled name=\"{$key}\" value=\"No\" $selected/>\n";
        $field .= "<label class='radio-label'>No</label>\n";
        $field .= "</div>";

        return $field;

    }


} 