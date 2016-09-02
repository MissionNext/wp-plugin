<?php

namespace MissionNext\lib\form\fields;

use MissionNext\lib\core\Context;
use MissionNext\lib\Constants;

class CheckboxMultipleField extends BaseField
{
    public function printField ($options = array())
    {
        $default = $this->default ? $this->default : array();

        if(!$default && $this->field['default_value'])
        {
            $default = $this->field['default_value'];
        }

        $choicesNumber = count($this->field['choices']);

        $multiplier = 5;
        $columnsCount = 0;
        if($choicesNumber < $multiplier)
        {
            $size = 'mn-small-checkbox';
            $columnsCount = 1;
        }
        elseif($choicesNumber < $multiplier * 2)
        {
            $size = 'mn-medium-checkbox';
            $columnsCount = 2;
        }
        else
        {
            $size = 'mn-large-checkbox';
            $columnsCount = 3;
        }

        $options['class'] = ( isset($options['class'])?$options['class']:'' ) . ' mn-checkbox ' . $size;
        $options['class'] = isset($options['class'])?$options['class'] . ' ' . $this->getDefaultClasses():$this->getDefaultClasses();

        $optionsString = '';

        $options = array_merge($options, $this->getDefaultOptions());

        if($options){
            foreach($options as $key => $option){
                $optionsString .= $key."='$option' ";
            }
        }

        $key = $this->name;
        $id = $this->id;

        $field = '';

        if($this->field['choices'])
        {
            usort($this->field['choices'], array($this, 'sortChoices'));

            $groups = $this->prepareGroups($this->field['choices'], $columnsCount);
            foreach($groups as $group)
            {
                if(isset($group['group']))
                {
                    $field .= '<p class="mn-group-choices">' . $group['group'] . '</p>';
                }

                if(!empty($group['choices']))
                {
                    foreach($group['choices'] as $choices)
                    {
                        $field .= "<div $optionsString>";
                        foreach($choices as $choice)
                        {
                            $name = $choice['value']?$choice['value']:$choice['default_value'];
                            $label = strpos($name, Constants::NO_PREFERENCE_SYMBOL) === 0?substr($name, 3):$name;

                            $selected = in_array(trim($choice['default_value']), $default) ? 'checked="checked"' : '' ;

                            if($choice['default_value']){

                                $value = esc_html($choice['default_value']);

                                $field  .= "<div>";
                                $field  .= "<input id='$id' type='checkbox' name=\"{$key}[]\" $selected value=\"$value\"/>\n";
                                $field  .= "<label>$label</label>\n";
                                $field  .= "</div>";
                            }
                        }
                        $field .= '</div>';
                    }
                }
            }
        }

        return $field;
    }

    private function sortChoices($a, $b)
    {
        return ($a['dictionary_order'] < $b['dictionary_order']) ? -1 : 1;
    }


    /**
     * Функция подготовки групп.
     *
     * @param array
     *
     * @return array
     */
    private function prepareGroups($choices, $columnsCount)
    {
        if(empty($choices))
        {
            return array();
        }

        $language = Context::getInstance()->getLocalizationManager()->getCurrentLangId();

        $i = 0;
        $group  = array();
        $groups = array();


        $groupElementCount = [];
        $groupChoiceId = null;
        $localGroupCount = 0;
        foreach($choices as $choice){
            if(!empty($choice['dictionary_meta']['group']))
            {
                if ($groupChoiceId){
                    $groupElementCount[$groupChoiceId] = $localGroupCount;
                }
                $groupChoiceId = $choice['id'];
                $localGroupCount = 0;
            } else {
                $localGroupCount++;
            }
        }
        if ($groupChoiceId)
        {
            $groupElementCount[$groupChoiceId] = $localGroupCount;
        }

        $column = 0;
        $choicesCount = count($choices);
        $columnMax = $choicesCount / $columnsCount;
        $columnMax = ceil($columnMax);
        $elementsCount = 0;

        foreach($choices as $choice)
        {
            if(!empty($choice['dictionary_meta']['group']))
            {
                $column = 0;
                $choicesCount = $groupElementCount[$choice['id']];
                $columnMax = $choicesCount / $columnsCount;
                $columnMax = ceil($columnMax);
                $elementsCount = 0;

                $i++;
                $group = array();

                $languages = $choice['dictionary_meta']['group'];

                $groups[$i]['group'] = element($language, $languages);
            }

            $group[$column][] = $choice;
            $elementsCount++;
            $groups[$i]['choices'] = $group;

            if ($elementsCount == $columnMax) {
                $column++;
                $elementsCount = 0;
            }
        }

        return $groups;
    }
}