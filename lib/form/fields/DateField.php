<?php


namespace MissionNext\lib\form\fields;


use MissionNext\lib\Constants;

class DateField extends BaseField {

    public function printField ($options = array())
    {
        $default = ($this->default && $this->field['type'] != 'password')?$this->default:$this->field['default_value'];

        if(is_array($default)){
            $default = current($default);
        }

        $default = date('Y-m-d', strtotime($default));

        $checkConstraints = $this->checkLessMoreConstraint($this->constraints);
        if ($checkConstraints && !$this->default && !$this->field['default_value']) {
            $default = date('Y-m-d', mktime(0, 0, 0, date('m') + $checkConstraints['month'], date('d') + $checkConstraints['day'], date('Y') + $checkConstraints['year']));
        }
        $type = $this->field['type'];
        $optionsString = '';

        $options = array_merge($options, $this->getDefaultOptions());
        $options['class'] = isset($options['class'])?$options['class'] . ' ' . $this->getDefaultClasses():$this->getDefaultClasses();

        if($options && is_array($options)){
            foreach($options as $label => $option){
                $optionsString .= $label."='$option' ";
            }
        }
        $key = $this->name;
        $id = $this->id;

        $field = "<input $optionsString id=\"$id\" name=\"$key\" type=\"$type\" data-type=\"$type\" value=\"$default\"/>";

        return $field;

    }

    private function checkLessMoreConstraint($constraints)
    {
        foreach($constraints as $condition) {
            if (strpos($condition, Constants::CONSTRAINT_LESS_THAN) !== false || strpos($condition, Constants::CONSTRAINT_MORE_THAN) !== false) {
                $conditionArray = explode(":", $condition);
                $dateParams = explode(",", $conditionArray[1]);

                return [
                    'year'  => $dateParams[0],
                    'month' => $dateParams[1],
                    'day'   => $dateParams[2]
                ];
            }
        }

        return false;
    }

} 