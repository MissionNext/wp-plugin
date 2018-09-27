<?php


namespace MissionNext\lib;


use MissionNext\Api;
use MissionNext\ClientInterface;
use MissionNext\lib\core\Context;

class CacheApi extends Api {

    private $cache_manager;

    public function __construct(ClientInterface $client, $publicKey, $privateKey, $basePath){
        parent::__construct($client, $publicKey, $privateKey, $basePath);

        $this->cache_manager = Context::getInstance()->getCacheManager();
    }

    public function getAdministratorEmails(){
        $data = $this->cache_manager->get("administrator_emails");

        if(!$data){
            $data = parent::getAdministratorEmails();
            $this->cache_manager->set("administrator_emails", $data, 60*60);
        }

        return $data;
    }

    public function saveConfig($config){
        $this->cache_manager->remove('api_config');
        return parent::saveConfig($config);
    }

    public function getGlobalConfigs(){
        $data = $this->cache_manager->get("api_global_config");

        if(!$data){
            $data = parent::getGlobalConfigs();
            $this->cache_manager->set("api_global_config", $data, 10*60);
        }

        return $data;
    }

    public function getConfigs(){

        $data = $this->cache_manager->get("api_config");

        if(!$data){
            $data = parent::getConfigs();
            $this->cache_manager->set("api_config", $data, 10*60);
        }

        return $data;
    }

    public function saveSiteLanguage($languages){
        $this->cache_manager->remove("api_site_languages");
        return parent::saveSiteLanguage($languages);
    }

    public function getSiteLanguages(){

        $data = $this->cache_manager->get("api_site_languages");

        if(!$data){
            $data = parent::getSiteLanguages();
            $this->cache_manager->set("api_site_languages", $data, 10*60);
        }

        return $data;
    }

    public function getCustomTranslations(){
        $data = $this->cache_manager->get("api_custom_translation");

        if(!$data){
            $data = parent::getCustomTranslations();
            $this->cache_manager->set("api_custom_translation", $data, 10*60);
        }

        return $data;
    }

    public function saveCustomTranslations($data){
        $this->cache_manager->remove("api_custom_translation");
        return parent::saveCustomTranslations($data);
    }

    public function updateUserProfile($user_id, $profile, $changedData = null, $saveLater = null){
        $this->cache_manager->remove("api_user_profile_$user_id");
        return parent::updateUserProfile($user_id, $profile, $changedData, $saveLater);
    }

//    public function getUserProfile($user_id){
//        $data = $this->cache_manager->get("api_user_profile_$user_id");
//
//        if(!$data){
//            $data = parent::getUserProfile($user_id);
//            $this->cache_manager->set("api_user_profile_$user_id", $data, 10*60);
//        }
//
//        return $data;
//    }

} 
