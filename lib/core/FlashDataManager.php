<?php


namespace MissionNext\lib\core;


class FlashDataManager {

    const SLUG = "mn_flash";
    const LIFE_TIME = 600;
    const DEFAULT_NAMESPACE = 'flash';

    private $data = array();
    private $user;
    private $cache_manager;

    public function __construct(){

        $this->user = Context::getInstance()->getUser()->getUser();
        $this->cache_manager = Context::getInstance()->getCacheManager();

        if($this->user){
            $this->data = $this->load();
            register_shutdown_function(array($this, 'save'));
        }
    }

    public function save(){

        if($this->data){
            $this->clearOld();
            $this->cache_manager->set($this->getKey(), $this->data, self::LIFE_TIME);
        } else {
            $this->cache_manager->remove($this->getKey());
        }
    }

    public function set($key, $value, $jumps = 1, $namespace = self::DEFAULT_NAMESPACE){

        if(!isset($this->data[$namespace])){
            $this->data[$namespace] = array();
        }

        $this->data[$namespace][$key] = compact('value', 'jumps');
    }

    public function get($key, $default = null, $namespace = self::DEFAULT_NAMESPACE){
        return (isset($this->data[$namespace]) && isset($this->data[$namespace][$key]))?
            $this->data[$namespace][$key]['value']:
            $default;
    }

    public function has($key, $namespace = self::DEFAULT_NAMESPACE){
        return isset($this->data[$namespace]) && isset($this->data[$namespace][$key]);
    }

    public function getNamespace($namespace){
        $_data = isset($this->data[$namespace])?$this->data[$namespace]:array();

        $data = array();

        foreach($_data as $key => $obj){
            $data[$key] = $obj['value'];
        }

        return $data;
    }

    public function hasNamespace($namespace){
        return isset($this->data[$namespace]);
    }

    private function load(){
        return $this->cache_manager->get($this->getKey());
    }

    private function getKey(){
        return $this->user?self::SLUG . "_" . $this->user['id']:false;
    }

    private function clearOld(){

        foreach($this->data as $namespace => $data){
            foreach($data as $key => $obj){

                $limit = --$this->data[$namespace][$key]['jumps'];

                if($limit < 0){
                    unset($this->data[$namespace][$key]);
                }
            }

            if(!$this->data[$namespace]){
                unset($this->data[$namespace]);
            }
        }
    }

} 