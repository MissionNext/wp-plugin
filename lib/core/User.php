<?php


namespace MissionNext\lib\core;


use MissionNext\lib\Constants;

class User {

    /**
     * @var \WP_User
     */
    private $wp_user;
    private $user;
    private $cache_manager;

    public function __construct(){
        $this->cache_manager = Context::getInstance()->getCacheManager();
        $this->initData();
    }

    public function getWPUser(){
        return $this->wp_user;
    }

    public function updateWPUser($data){
        $data['ID'] = isset($data['ID'])?$data['ID']:$this->wp_user->ID;
        return wp_update_user($data);
    }

    public function getUser(){
        return $this->user;
    }

    public function getName(){

        if($this->user)
        {
            if($this->user['role'] == 'candidate')
            {
                if(isset($this->user['profileData'][Constants::$predefinedFields[$this->user['role']]['first_name']]) || isset($this->user['profileData'][Constants::$predefinedFields[$this->user['role']]['last_name']]))
                {
                    return $this->user['profileData'][Constants::$predefinedFields[$this->user['role']]['first_name']] . ' ' . $this->user['profileData'][Constants::$predefinedFields[$this->user['role']]['last_name']];
                }
                else
                {
                    return $this->user['username'];
                }
            }

            if($this->user['role'] == 'agency')
            {
                if(isset($this->user['profileData'][Constants::$predefinedFields[$this->user['role']]['agency_full_name']]))
                {
                    return $this->user['profileData'][Constants::$predefinedFields[$this->user['role']]['agency_full_name']];
                }
            }

            if($this->user['role'] == 'organization')
            {
                if(isset($this->user['profileData'][Constants::$predefinedFields[$this->user['role']]['organization_name']]))
                {
                    return $this->user['profileData'][Constants::$predefinedFields[$this->user['role']]['organization_name']];
                }
            }
        }
        else
        {
            return $this->wp_user->user_nicename;
        }
    }

    public function getWPMeta($key = '', $single = false){
        return get_user_meta($this->wp_user->ID, $key, $single);
    }

    private function initData(){
        $this->wp_user = wp_get_current_user();

        if($this->wp_user->exists() && Context::getInstance()->getApiManager()->getApi()){

            $this->user = Context::getInstance()->getApiManager()->getApi()->getUserProfile($this->getWPMeta(Constants::META_KEY, true));

            if($this->user){
                Context::getInstance()->getApiManager()->getApi()->setUserId($this->user['id']);
            }
        }
    }

} 