<?php


namespace MissionNext\lib;


use MissionNext\lib\core\Context;

class SiteConfig {

    public static function get($key, $default){
        return Context::getInstance()->getSiteConfigManager()->get($key, $default);
    }

    public static function isAgencyOn(){
        return self::get(Constants::CONFIG_AGENCY_TRIGGER, true);
    }

    public static function getDefaultFolder($role){
        return self::get($role . "_default_folder", 0);
    }

}