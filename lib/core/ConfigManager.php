<?php


namespace MissionNext\lib\core;


class ConfigManager {

    private $configDir;
    private $rootDir;

    public function __construct(){
        $this->rootDir = MN_ROOT_DIR;
        $this->configDir = $this->rootDir .'/config';
    }

    public function load($name, $app = null, $assoc = true){

        if($app){
            $path = $this->rootDir.'/'. $app .'/config/'.$name.'.json';
        } else {
            $path = $this->configDir.'/'.$name.'.json';
        }

        if(!is_file($path)){
            return false;
        }

        return json_decode(file_get_contents($path), $assoc);

    }

    public function save($name, $app = null, $data){

        if($app){
            $path = $this->rootDir.'/'. $app .'/config/'.$name.'.json';
        } else {
            $path = $this->configDir.'/'.$name.'.json';
        }

        file_put_contents($path, json_encode($data));

    }

    public static function get($name, $app = null, $assoc = true){

        if($app){
            $path = MN_ROOT_DIR.'/'. $app .'/config/'.$name.'.json';
        } else {
            $path = MN_ROOT_DIR.'/config/'.$name.'.json';
        }

        if(!is_file($path)){
            return false;
        }

        return json_decode(file_get_contents($path), $assoc);
    }

} 