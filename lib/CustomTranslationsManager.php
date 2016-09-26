<?php


namespace MissionNext\lib;


use MissionNext\Api;
use MissionNext\lib\core\Context;

class CustomTranslationsManager {

    /**
     * @var Api
     */
    private $api;
    private $translations;

    public function __construct(){
        $this->init();
    }

    public function init(){
        $this->api = Context::getInstance()->getApiManager()->getApi();

        if(Context::getInstance()->getApiManager()->isConnected()){
            $this->translations = $this->loadTranslations();
        }
    }

    public function loadTranslations(){
        $data = $this->api->getCustomTranslations();

        $translations = array();

        foreach($data as $row){

            if(!isset($translations[$row['key']])){
                $translations[$row['key']] = array();
            }

            $translations[$row['key']][$row['lang_id']?$row['lang_id']:0] = $row['value'];
        }

        return $translations;
    }

    public function get($key, $default = ''){

        $lang_id = Context::getInstance()->getLocalizationManager()->getCurrentLangId();

        if(isset($this->translations[$key][$lang_id]) && $this->translations[$key][$lang_id]){
            return $this->translations[$key][$lang_id];
        }

        return $default?$default:$key;
    }

} 