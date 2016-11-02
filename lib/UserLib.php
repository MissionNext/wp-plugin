<?php


namespace MissionNext\lib;


class UserLib extends ProfileLib {

    public static function getUserFullName($user){
        if(isset($user['profileData'][Constants::$predefinedFields[$user['role']]['first_name']])
            || isset($user['profileData'][Constants::$predefinedFields[$user['role']]['last_name']])
        ){
            return $user['profileData'][Constants::$predefinedFields[$user['role']]['first_name']] . ' ' . $user['profileData'][Constants::$predefinedFields[$user['role']]['last_name']];
        } else {
            return $user['username'];
        }
    }

    public static function getUserOrganizationName($user){
        if(isset($user['profileData'][Constants::$predefinedFields[$user['role']]['organization_name']])
        ){
            return $user['profileData'][Constants::$predefinedFields[$user['role']]['organization_name']];
        } else {
            return $user['username'];
        }
    }

    public static function replaceTokens($string, $user_from, $user_to, $job = null){

        $from = array();
        $to = array();

        $profile = array();
        $profile['username'] = $user_from['username'];
        $profile['email'] = $user_from['email'];
        $profile['full_name'] = self::getUserFullName($user_from);
        $profile = array_merge($profile, $user_from['profileData']);

        foreach($profile as $key => $value){
            if(!is_array($value)){
                $from[] = "%from_$key%";
                $to[] = $value;
            }
        }

        $profile = array();
        $profile['username'] = $user_to['username'];
        $profile['email'] = $user_to['email'];
        $profile['full_name'] = self::getUserFullName($user_to);
        $profile = array_merge($profile, $user_to['profileData']);

        foreach($profile as $key => $value){
            if(!is_array($value)){
                $from[] = "%to_$key%";
                $to[] = $value;
            }
        }

        if($job){

            $profile = array();
            $profile['name'] = $job['name'];
            $profile = array_merge($profile, $job['profileData']);

            foreach($profile as $key => $value){
                if(!is_array($value)){
                    $from[] = "%job_$key%";
                    $to[] = $value;
                }
            }
        }

        return str_replace($from, $to, $string);
    }

} 