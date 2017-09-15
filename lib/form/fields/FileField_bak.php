<?php


namespace MissionNext\lib\form\fields;


use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;

class FileField extends BaseField {

    public function __construct($formName, $field, $default = null){

        parent::__construct($formName, $field, $default);

        $this->name = $field['symbol_key'];
    }

    public function printField ($options = array())
    {
        $default = $this->default?$this->default:$this->field['default_value'];
        $type = $this->field['type'];

        $all_types = [];
        foreach ($this->constraints as $single_constraint) {
            $mime = strpos($single_constraint, 'mimes');

            if ($mime !== false) {
                $mime_array = explode(":", $single_constraint);
                $types_array = explode(",", $mime_array[1]);

                foreach ($types_array as $type) {
                    $all_types[] = ".".$type;
                }
            }
        }
        $accept_str = implode(',', $all_types);

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

        $viewShowClass = $uploadShowClass = 'display: none;';
        if ($default) {
            $viewShowClass = '';
        } else {
            $uploadShowClass = '';
        }
        $path = Context::getInstance()->getConfig()->get('api_base_path') . '/' . Context::getInstance()->getConfig()->get('api_uploads_dir').'/'.$default;
        $field = '<div id="view-'.$key.'" style="'.$viewShowClass.'"><a class="file-link" href="'.$path.'" target="_blank">'.$default.'</a>';
        $field .= '<a class="file-delete-icon" href="javascript:void(0);" alt="Delete file" title="Delete file" data-fieldkey="'.$key.'">&nbsp;&nbsp;<img src="'.getResourceUrl('/resources/images/delete.png').'" width="14"/></a></div>';

        $field .= "<div id='uploaded-$key' style='".$uploadShowClass."'><input $optionsString class=\"mn-input-file\" id=\"$id\" name=\"$key\" type=\"file\" data-type=\"$type\" value=\"$default\" accept='$accept_str'/>";
        $field .= "<span>".sprintf(__("Max file size is %s", Constants::TEXT_DOMAIN), ini_get("upload_max_filesize"))."</span></div>";

        return $field;

    }

} 