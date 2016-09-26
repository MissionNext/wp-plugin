<?php


namespace MissionNext\lib;


use MissionNext\lib\core\Context;

class GlobalConfig {

    public static function get($key, $default){
        return Context::getInstance()->getGlobalConfigManager()->get($key, $default);
    }

    public static function getSubscriptionDiscount(){
        return intval(self::get(Constants::GLOBAL_CONFIG_DISCOUNT, 10));
    }

    public static function getSubscriptionFee(){
        return intval(self::get(Constants::GLOBAL_CONFIG_FEE, 10));
    }

} 