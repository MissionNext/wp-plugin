<?php


namespace MissionNext\lib\core;


class Config {

    const FILE_NAME = 'app';

    private $conf = array();

    public function __construct(){
        $this->conf = Context::getInstance()->getConfigManager()->load(self::FILE_NAME);
    }

    public function has($key){
        return isset($this->conf[$key]);
    }

    public function get($key, $default = null){
        return $this->has($key)?$this->conf[$key]:$default;
    }

    public function set($key, $value){
        $this->conf[$key] = $value;
    }


} 