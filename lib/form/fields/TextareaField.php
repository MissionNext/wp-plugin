<?php


namespace MissionNext\lib\form\fields;


class TextareaField extends BaseField {

    public function printField ($options = array())
    {
        $default = $this->default?$this->default:'';
        $placeholder = is_array($this->field['default_value'])?current($this->field['default_value']):$this->field['default_value'];

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
		$field = "<textarea $optionsString name=\"$key\" id=\"$id\" placeholder='$placeholder'>$default</textarea>";
		// Change by Nelson Nov 13 2015; added maxlength July 14, 2016 
        // $field = "<textarea rows='3' cols'40' $optionsString name=\"$key\" id=\"$id\" placeholder='$placeholder'>$default</textarea>";

        return $field;

    }


} 