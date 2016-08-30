<?php


namespace MissionNext\lib\core;


class TemplateService {

    private $context = '';

    public function __construct(){
        $this->context = is_admin()?'backend':'frontend';
    }

    public function render($templateName, $vars = array()){

        extract($vars);

        include(MN_ROOT_DIR.'/'.$this->context.'/templates/'.$templateName.'.php');

    }

} 