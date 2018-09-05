<?php


namespace MissionNext\lib;

use MissionNext\Api;
use MissionNext\CurlClient;
use MissionNext\lib\core\Context;
use MissionNext\UnirestClient;

class ApiManager {

    public $publicKey;

    public $basePath;
    private $privateKey;
    /**
     * @var Api
     */
    private $api;
    private $is_connected = false;
    private $cache_manager;

    public function __construct(){

        $this->publicKey = get_option(Constants::PUBLIC_KEY_TOKEN);
        $this->privateKey = get_option(Constants::PRIVATE_KEY_TOKEN);
        $this->basePath = Context::getInstance()->getConfig()->get('api_base_path', 'https://api.missionfinder.net');

        $this->cache_manager = Context::getInstance()->getCacheManager();

        //Api init
        if($this->publicKey && $this->privateKey){
            $client = new CurlClient();
            $this->api = new ApiLogger($client, $this->publicKey, $this->privateKey, $this->basePath);
            $this->testConnection();
        }

    }

    public function isConnected(){
        return $this->is_connected;
    }

    public function getApi(){
        return $this->api;
    }

    public function testConnection($force = false){
        $this->is_connected = $this->tryConnection($force);
    }

    private function tryConnection($force = false){

        $data = $this->cache_manager->get("api_status");

        if( (!$data || $force) && $this->api ){
            $data = $this->api->testConnection();
            $this->cache_manager->set("api_status", $data, 10*60);
        }
	
        return $data;
    }

} 
