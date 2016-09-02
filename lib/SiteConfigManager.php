<?php


namespace MissionNext\lib;


use MissionNext\lib\core\Context;

class SiteConfigManager {

    private $api;
    private $configs = array();

    public function __construct(){
        $this->api = Context::getInstance()->getApiManager()->getApi();
        if($this->api && Context::getInstance()->getApiManager()->isConnected()){
            $this->configs = $this->load();
        }
    }

    public function load(){

        $_data = $this->api->getConfigs();

        if(!$_data){
            return array();
        }

        $configs = array();

        foreach($_data as $item){
            $configs[$item['key']] = $item['value'];
        }

        return $configs;
    }

    public function get($key, $default = null){
        return isset($this->configs[$key])?$this->configs[$key]:$default;
    }

    public function set($key, $value){
        $this->configs[$key] = $value;
    }

    public function save($key, $value){
        $this->set($key, $value);

        return $this->api->saveConfig(array(
            'key' => $key,
            'value' => $value
        ));
    }
} 