<?php


namespace MissionNext\lib\form\fields;


use MissionNext\lib\Constants;

class RadioField extends BaseField {

    public function printField ($options = array())
    {
        $default = $this->default?$this->default:array();
        if(!is_array($default)){
            $default = array( $default => $default );
        }

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

        $field = '';

        if($this->field['choices']){

            usort($this->field['choices'], array($this, 'sortChoices'));

            foreach($this->field['choices'] as $c){

                $choice = trim($c['default_value']);
                $name = $c['value']?$c['value']:$c['default_value'];
                $label = strpos($name, Constants::NO_PREFERENCE_SYMBOL) === 0?substr($name, 3):$name;

                $selected = in_array($choice, $default) ? 'checked="checked"' : '' ;

                $field  .= "<div>";
                $field  .= "<input type='radio' $optionsString name=\"{$key}\" $selected value=\"$choice\"/>\n";
                $field  .= "<label class='radio-label'>$label</label>\n";
                $field  .= "</div>";
            }
        }

        return $field;

    }

    private function sortChoices($a, $b){
        return ($a['dictionary_order'] < $b['dictionary_order']) ? -1 : 1;
    }


} 