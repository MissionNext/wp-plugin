<?php


namespace MissionNext\lib\form\fields;


class InputField extends BaseField {

    public function printField ($options = array())
    {
        $default = ($this->default && $this->field['type'] != 'password') ? $this->default : null;

        $placeholder = is_array($this->field['default_value']) ? current($this->field['default_value']) : $this->field['default_value'];

        if (is_array($default)) {
            $default = current($default);
        }

        $type = $this->field['type'];
        $optionsString = '';

        if ('hidden' == $type && empty($default)) {
            $default = $placeholder;
        }

        $options = array_merge($options, $this->getDefaultOptions());
        $options['class'] = isset($options['class'])?$options['class'] . ' ' . $this->getDefaultClasses():$this->getDefaultClasses();

        if($options && is_array($options)){
            foreach($options as $label => $option){

                $optionsString .= $label."='$option' ";
            }
        }
        $key = $this->name;
        $id = $this->id;

		$field = "<input $optionsString id=\"$id\" name=\"$key\" type=\"$type\" data-type=\"$type\" value=\"$default\" placeholder='$placeholder'/>";

        return $field;

    }


} 