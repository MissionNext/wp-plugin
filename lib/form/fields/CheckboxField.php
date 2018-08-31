<?php


namespace MissionNext\lib\form\fields;


class CheckboxField extends BaseField {

    public function printField ($options = array())
    {
        $default = !is_array($this->default)?$this->default:$this->field['default_value'];

        $checked = $default ? "checked='checked'": '';

        $optionsString = '';

        $options = array_merge($options, $this->getDefaultOptions());
        $options['class'] = isset($options['class'])?$options['class'] . ' ' . $this->getDefaultClasses():$this->getDefaultClasses();

        if($options && is_array($options)){
            foreach($options as $key => $option){
                $optionsString .= $key."='$option' ";
            }
        }

        $key = $this->id;
        $name = $this->name;

        if(isset($this->constraints[0]) && $this->constraints[0] == 'required')
            $field = "<input $optionsString name=\"$name\" id=\"$key\" type=\"checkbox\" value=\"1\" $checked />";
        else
            $field = "<input name=\"$name\" id=\"$key\" type=\"hidden\" value=\"0\"><input $optionsString name=\"$name\" id=\"$key\" type=\"checkbox\" value=\"1\" $checked />";

        return $field;

    }


} 