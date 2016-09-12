<?php


namespace MissionNext\lib\core;


class HelperManager {

    private $dir;

    public function __construct(){

        $this->dir = MN_ROOT_DIR . '/' . Context::getInstance()->getConfig()->get('helpers_dir');
        $this->loadHelpers(Context::getInstance()->getConfig()->get('helpers'));
    }

    public function loadHelper($name){
        $path = $this->dir.'/'.ucfirst($name).'.php';

        if(is_file($path)){
            require $path;
            return true;
        }

        return false;
    }

    public function loadHelpers($helpers){

        foreach($helpers as $helper){
            $this->loadHelper($helper);
        }
    }


} 